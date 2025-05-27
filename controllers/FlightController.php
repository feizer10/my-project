<?php
class FlightController {
    private $pdo;
    private $apiKey = '2842fe1bc719e553c2dc273b89aa749b';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Отримуємо параметри фільтрації
        $departure = isset($_GET['departure']) ? $_GET['departure'] : 'JFK';
        $arrival = isset($_GET['arrival']) ? $_GET['arrival'] : '';
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

        // Логуємо параметри пошуку
        error_log("Search parameters: Departure: $departure, Arrival: $arrival, Date: $date");

        // Конвертуємо назву міста в код аеропорту
        $departureCode = $this->getCityCode($departure);
        error_log("Converted departure city to code: $departure -> $departureCode");

        // Отримуємо рейси з API
        $flights = $this->getFlightsFromAPI($departureCode, $date);
        error_log("Retrieved " . count($flights) . " flights from API");

        // Фільтруємо рейси за містом прибуття, якщо воно вказане
        if (!empty($arrival)) {
            $arrivalCode = $this->getCityCode($arrival);
            $flights = array_filter($flights, function($flight) use ($arrival) {
                return stripos($flight['arrival_city'], $arrival) !== false;
            });
            error_log("Filtered flights by arrival city ($arrival). Remaining: " . count($flights));
        }

        // Отримуємо список аеропортів
        $cities = $this->getAirportsFromAPI();

        // Підключаємо шаблон
        require 'views/flights.php';
    }

    private function getFlightsFromAPI($iataCode, $date) {
        $apiUrl = "http://api.aviationstack.com/v1/flights?access_key={$this->apiKey}&dep_iata={$iataCode}&flight_date={$date}";
        error_log("Calling API URL: $apiUrl");

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            
            if(curl_errno($ch)) {
                throw new Exception('Curl error: ' . curl_error($ch));
            }
            
            curl_close($ch);
            
            $data = json_decode($response, true);
            error_log("API Response: " . print_r($data, true));
            
            if (isset($data['data']) && is_array($data['data'])) {
                // Перетворюємо дані API у формат, який очікує наш шаблон
                return array_map(function($flight) {
                    return [
                        'flight_number' => $flight['flight']['iata'],
                        'departure_city' => $flight['departure']['airport'],
                        'departure_airport' => $flight['departure']['iata'],
                        'arrival_city' => $flight['arrival']['airport'],
                        'arrival_airport' => $flight['arrival']['iata'],
                        'departure_time' => $flight['departure']['scheduled'],
                        'arrival_time' => $flight['arrival']['scheduled'],
                        'status' => $this->mapFlightStatus($flight['flight_status']),
                        'base_price' => rand(1000, 5000), // Ціна не надається API, генеруємо випадкову
                        'id' => $flight['flight']['number']
                    ];
                }, $data['data']);
            }
            
            return [];
            
        } catch (Exception $e) {
            error_log("API Error: " . $e->getMessage());
            return [];
        }
    }

    private function getAirportsFromAPI() {
        // Отримуємо список популярних аеропортів
        $airports = [
            ['code' => 'JFK', 'city' => 'New York'],
            ['code' => 'LAX', 'city' => 'Los Angeles'],
            ['code' => 'ORD', 'city' => 'Chicago'],
            ['code' => 'LHR', 'city' => 'London'],
            ['code' => 'CDG', 'city' => 'Paris'],
            ['code' => 'FRA', 'city' => 'Frankfurt'],
            ['code' => 'AMS', 'city' => 'Amsterdam'],
            ['code' => 'MAD', 'city' => 'Madrid'],
            ['code' => 'FCO', 'city' => 'Rome'],
            ['code' => 'DXB', 'city' => 'Dubai']
        ];

        $cities = [
            'departure' => array_column($airports, 'city'),
            'arrival' => array_column($airports, 'city')
        ];

        return $cities;
    }

    private function getCityCode($city) {
        $airports = [
            'New York' => 'JFK',
            'Los Angeles' => 'LAX',
            'Chicago' => 'ORD',
            'London' => 'LHR',
            'Paris' => 'CDG',
            'Frankfurt' => 'FRA',
            'Amsterdam' => 'AMS',
            'Madrid' => 'MAD',
            'Rome' => 'FCO',
            'Dubai' => 'DXB'
        ];

        return isset($airports[$city]) ? $airports[$city] : $city;
    }

    private function mapFlightStatus($status) {
        $statusMap = [
            'active' => 'scheduled',
            'scheduled' => 'scheduled',
            'landed' => 'completed',
            'cancelled' => 'cancelled',
            'incident' => 'cancelled',
            'diverted' => 'delayed',
            'delayed' => 'delayed'
        ];

        return isset($statusMap[$status]) ? $statusMap[$status] : 'scheduled';
    }
} 