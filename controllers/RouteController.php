<?php
class RouteController {
    private $pdo;
    private $cities;
    private $routes;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->cities = [
            'New York' => ['London', 'Paris', 'Amsterdam', 'Frankfurt'],
            'London' => ['New York', 'Paris', 'Amsterdam', 'Frankfurt', 'Rome'],
            'Paris' => ['New York', 'London', 'Amsterdam', 'Frankfurt', 'Rome', 'Madrid'],
            'Amsterdam' => ['New York', 'London', 'Paris', 'Frankfurt'],
            'Frankfurt' => ['New York', 'London', 'Paris', 'Amsterdam', 'Rome'],
            'Rome' => ['London', 'Paris', 'Frankfurt', 'Madrid'],
            'Madrid' => ['Paris', 'Rome'],
            'Dubai' => ['London', 'Paris', 'Frankfurt'],
            'Los Angeles' => ['New York', 'Chicago', 'London'],
            'Chicago' => ['New York', 'Los Angeles']
        ];
    }

    public function index() {
        $selectedCities = isset($_POST['cities']) ? $_POST['cities'] : [];
        $routes = [];

        if (!empty($selectedCities) && count($selectedCities) >= 2) {
            $routes = $this->findAllRoutes($selectedCities);
        }

        require 'views/routes.php';
    }

    private function findAllRoutes($selectedCities) {
        $routes = [];
        $start = reset($selectedCities); // Перше місто
        $destinations = array_slice($selectedCities, 1); // Всі інші міста

        foreach ($destinations as $destination) {
            $path = $this->findShortestPath($start, $destination);
            if ($path) {
                $routes[] = [
                    'from' => $start,
                    'to' => $destination,
                    'path' => $path,
                    'stops' => count($path) - 2, // Без початкового і кінцевого міста
                    'estimated_time' => $this->calculateEstimatedTime($path)
                ];
            }
            $start = $destination; // Наступне місто стає початковим
        }

        return $routes;
    }

    private function findShortestPath($start, $end) {
        $distances = [];
        $previous = [];
        $nodes = [];
        $path = [];

        foreach (array_keys($this->cities) as $city) {
            $distances[$city] = PHP_FLOAT_MAX;
            $previous[$city] = null;
            $nodes[] = $city;
        }

        $distances[$start] = 0;

        while (!empty($nodes)) {
            $min = PHP_FLOAT_MAX;
            $closest = null;

            foreach ($nodes as $node) {
                if ($distances[$node] < $min) {
                    $min = $distances[$node];
                    $closest = $node;
                }
            }

            if ($closest === null) {
                break;
            }

            if ($closest === $end) {
                while ($previous[$closest] !== null) {
                    array_unshift($path, $closest);
                    $closest = $previous[$closest];
                }
                array_unshift($path, $start);
                return $path;
            }

            $nodes = array_diff($nodes, [$closest]);

            foreach ($this->cities[$closest] as $neighbor) {
                $alt = $distances[$closest] + 1;
                if ($alt < $distances[$neighbor]) {
                    $distances[$neighbor] = $alt;
                    $previous[$neighbor] = $closest;
                }
            }
        }

        return null;
    }

    private function calculateEstimatedTime($path) {
        // Приблизний час перельоту між містами (в годинах)
        $flightTimes = [
            'New York-London' => 7,
            'New York-Paris' => 8,
            'London-Paris' => 1.5,
            'Paris-Frankfurt' => 1.5,
            'Frankfurt-Rome' => 2,
            'Paris-Rome' => 2,
            'London-Dubai' => 7,
            'New York-Chicago' => 2.5,
            'Chicago-Los Angeles' => 4
        ];

        $totalTime = 0;
        for ($i = 0; $i < count($path) - 1; $i++) {
            $route = $path[$i] . '-' . $path[$i + 1];
            $reverseRoute = $path[$i + 1] . '-' . $path[$i];
            
            if (isset($flightTimes[$route])) {
                $totalTime += $flightTimes[$route];
            } elseif (isset($flightTimes[$reverseRoute])) {
                $totalTime += $flightTimes[$reverseRoute];
            } else {
                // Якщо немає точного часу, використовуємо приблизний розрахунок
                $totalTime += 3; // Середній час перельоту
            }

            // Додаємо час на пересадку, якщо це не остання зупинка
            if ($i < count($path) - 2) {
                $totalTime += 2; // 2 години на пересадку
            }
        }

        return $totalTime;
    }
} 