<?php
require_once 'services/AviationStackService.php';
require_once 'ipa.php';

class FlightsController {
    private $pdo;
    private $aviationStack;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $config = require 'config/config.php';
        $this->aviationStack = new AviationStackService($config['api']['aviationstack']['key']);
    }

    private function debug($message, $data = null) {
        error_log("DEBUG: " . $message);
        if ($data !== null) {
            error_log(print_r($data, true));
        }
    }

    /**
     * Тестовий метод для перевірки API
     */
    public function testApi() {
        $result = $this->aviationStack->testApiConnection();
        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    public function index() {
        // Відображаємо форму пошуку
        require 'views/flights/index.php';
    }

    public function search() {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        $passengers = isset($_GET['passengers']) ? (int)$_GET['passengers'] : 1;

        if (!$from || !$to) {
            header('Location: /flights');
            exit;
        }

        // Шукаємо рейси між містами
        $result = searchFlightsBetweenCities($from, $to, $date);
        
        if (!$result['success']) {
            $error = isset($result['error']) ? $result['error'] : 'Could not find flights';
            require 'views/error.php';
            return;
        }

        $flights = $result['flights'];
        
        // Групуємо рейси за днями тижня
        $flights_by_day = [];
        foreach ($flights as $flight) {
            $day = date('l', strtotime($flight['departure']['time']));
            if (!isset($flights_by_day[$day])) {
                $flights_by_day[$day] = [];
            }
            $flights_by_day[$day][] = $flight;
        }

        require 'views/flights/search.php';
    }

    public function updateFlights() {
        if (updateFlightsDatabase($this->pdo)) {
            echo "Flights database updated successfully";
        } else {
            echo "Error updating flights database";
        }
    }
} 