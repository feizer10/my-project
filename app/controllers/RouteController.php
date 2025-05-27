<?php
namespace app\controllers;

use app\core\BaseController;
use app\models\Route as RouteModel;
use app\models\Flight as FlightModel;

class RouteController extends BaseController {
    private $routeModel;
    private $flightModel;
    private $cities = [
        "Amsterdam" => [52.3676, 4.9041],
        "Athens" => [37.9838, 23.7275],
        "Barcelona" => [41.3851, 2.1734],
        "Berlin" => [52.5200, 13.4050],
        "Brussels" => [50.8503, 4.3517],
        "Budapest" => [47.4979, 19.0402],
        "Copenhagen" => [55.6761, 12.5683],
        "Dublin" => [53.3498, -6.2603],
        "Edinburgh" => [55.9533, -3.1883],
        "Helsinki" => [60.1695, 24.9354],
        "Lisbon" => [38.7169, -9.1399],
        "London" => [51.5072, -0.1276],
        "Madrid" => [40.4168, -3.7038],
        "Milan" => [45.4642, 9.1900],
        "Munich" => [48.1351, 11.5820],
        "Oslo" => [59.9139, 10.7522],
        "Paris" => [48.8566, 2.3522],
        "Prague" => [50.0755, 14.4378],
        "Rome" => [41.9028, 12.4964],
        "Stockholm" => [59.3293, 18.0686],
        "Warsaw" => [52.2379, 21.0175],
        "Kyiv" => [50.4501, 30.5234]
    ];

    public function __construct() {
        parent::__construct();
        $this->routeModel = new RouteModel();
        $this->flightModel = new FlightModel();
        
        // Ініціалізуємо сесійний масив для вибраних рейсів
        if (!isset($_SESSION['selected_flights'])) {
            $_SESSION['selected_flights'] = [];
        }
    }

    public function index() {
        // Обробка додавання/видалення рейсу
        if (isset($_POST['add_flight'])) {
            $flightId = $_POST['flight_id'];
            $flight = $this->flightModel->getFlightById($flightId);
            if ($flight) {
                $_SESSION['selected_flights'][$flightId] = $flight;
            }
        }

        if (isset($_POST['remove_flight'])) {
            $flightId = $_POST['flight_id'];
            unset($_SESSION['selected_flights'][$flightId]);
        }

        // Підрахунок загальної вартості
        $totalPrice = 0;
        foreach ($_SESSION['selected_flights'] as $flight) {
            $totalPrice += $flight['price'];
        }

        $selectedCities = $_POST['cities'] ?? [];
        $selectedCities = array_filter(array_unique($selectedCities));

        $route = [];
        $totalDistance = 0;
        $savedRoutes = [];
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($selectedCities)) {
            if (count($selectedCities) < 2) {
                $error = 'Будь ласка, оберіть принаймні два міста';
            } else {
                try {
                    list($routeSegments, $totalDistance) = $this->findShortestRoute($selectedCities);
                    $route = []; // Очищаем массив перед заполнением

                    // Преобразуем сегменты маршрута в нужный формат
                    foreach ($routeSegments as $segment) {
                        if (!isset($segment[0]) || !isset($segment[1]) || !isset($segment[2])) {
                            continue; // Пропускаем некорректные сегменты
                        }

                        $routeData = [
                            'from_city' => $segment[0],
                            'to_city' => $segment[1],
                            'distance' => (float)$segment[2],
                            'flights' => [],
                            'route_id' => null
                        ];

                        // Проверяем, существует ли уже такой маршрут
                        $existingRoute = $this->routeModel->findRoute($routeData['from_city'], $routeData['to_city']);
                        
                        if ($existingRoute) {
                            $routeData['route_id'] = $existingRoute['id'];
                        } else {
                            try {
                                $routeData['route_id'] = $this->routeModel->saveRoute(
                                    $routeData['from_city'],
                                    $routeData['to_city'],
                                    $routeData['distance']
                                );
                                // Создаем рейсы только для нового маршрута
                                $this->createFlightsForRoute($routeData['route_id'], $routeData['distance']);
                            } catch (\Exception $e) {
                                error_log("Error saving route: " . $e->getMessage());
                                continue; // Пропускаем проблемный маршрут
                            }
                        }
                        
                        // Получаем доступные рейсы для этого маршрута
                        try {
                            $routeData['flights'] = $this->flightModel->getFlightsForRoute($routeData['route_id']) ?? [];
                        } catch (\Exception $e) {
                            error_log("Error getting flights: " . $e->getMessage());
                            $routeData['flights'] = [];
                        }
                        
                        $route[] = $routeData;
                    }
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    $error = 'Помилка при розрахунку маршруту. Спробуйте пізніше.';
                }
            }
        }

        // Получаем сохраненные маршруты
        try {
            $savedRoutes = $this->routeModel->getRoutes();
            // Добавляем информацию о рейсах к каждому сохраненному маршруту
            foreach ($savedRoutes as &$savedRoute) {
                try {
                    $savedRoute['flights'] = $this->flightModel->getFlightsForRoute($savedRoute['id']) ?? [];
                } catch (\Exception $e) {
                    error_log("Error getting flights for saved route: " . $e->getMessage());
                    $savedRoute['flights'] = [];
                }
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $error = 'Помилка при отриманні збережених маршрутів';
        }

        $this->view('route/index', [
            'title' => 'Пошук маршрутів',
            'cities' => $this->cities,
            'selectedCities' => $selectedCities,
            'route' => $route,
            'totalDistance' => $totalDistance,
            'savedRoutes' => $savedRoutes,
            'error' => $error,
            'selectedFlights' => $_SESSION['selected_flights'],
            'totalPrice' => $totalPrice
        ]);
    }

    private function createFlightsForRoute($routeId, $distance) {
        try {
            // Проверяем, есть ли уже рейсы для этого маршрута
            $existingFlights = $this->flightModel->getFlightsForRoute($routeId);
            if (!empty($existingFlights)) {
                return; // Если рейсы уже есть, не создаем новые
            }

            // Базовая цена зависит от расстояния
            $basePrice = $distance * 2; // 2 грн за километр
            
            // Создаем рейсы на следующие 7 дней
            for ($i = 1; $i <= 7; $i++) {
                // Создаем 3 рейса в день в разное время
                foreach ([8, 14, 20] as $hour) {
                    $departureTime = date('Y-m-d H:i:s', strtotime("+$i days +$hour hours"));
                    // Случайная вариация цены ±20%
                    $price = $basePrice * (1 + (rand(-20, 20) / 100));
                    $this->flightModel->createFlight($routeId, $departureTime, $price);
                }
            }
        } catch (\Exception $e) {
            error_log("Error creating flights for route $routeId: " . $e->getMessage());
            throw $e;
        }
    }

    private function findShortestRoute($selectedCities) {
        $visited = [];
        $route = [];
        $totalDistance = 0;

        $current = $selectedCities[0];
        $visited[] = $current;

        while (count($visited) < count($selectedCities)) {
            $minDist = PHP_FLOAT_MAX;
            $nextCity = null;

            foreach ($selectedCities as $city) {
                if (in_array($city, $visited)) continue;

                $dist = $this->haversine(
                    $this->cities[$current][0], $this->cities[$current][1],
                    $this->cities[$city][0], $this->cities[$city][1]
                );

                if ($dist < $minDist) {
                    $minDist = $dist;
                    $nextCity = $city;
                }
            }

            if ($nextCity === null) {
                throw new \Exception('Не вдалося знайти наступне місто');
            }

            $route[] = [$current, $nextCity, $minDist];
            $totalDistance += $minDist;
            $current = $nextCity;
            $visited[] = $current;
        }

        return [$route, $totalDistance];
    }

    private function haversine($lat1, $lon1, $lat2, $lon2) {
        $R = 6371; // Радиус Земли в километрах
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLon/2) * sin($dLon/2);
        return $R * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}
