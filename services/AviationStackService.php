<?php

class AviationStackService {
    private $apiKey;
    private $baseUrl = 'http://api.aviationstack.com/v1';

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * Тестовий метод для перевірки API
     */
    public function testApiConnection() {
        $endpoint = '/timetable';
        $params = [
            'access_key' => $this->apiKey,
            'iata_code' => 'JFK',
            'type' => 'departure'
        ];

        return $this->makeRequest($endpoint, $params);
    }

    /**
     * Отримати розклад рейсів для аеропорту
     */
    public function getFlightSchedule($iataCode, $type = 'departure') {
        $endpoint = '/timetable';
        $params = [
            'access_key' => $this->apiKey,
            'iata_code' => $iataCode,
            'type' => $type,
            'limit' => 100 // Maximum allowed by API
        ];

        return $this->makeRequest($endpoint, $params);
    }

    /**
     * Оновити базу даних реальними даними про рейси
     */
    public function updateFlightsDatabase($pdo) {
        try {
            // Спочатку перевіряємо з'єднання з API
            $testResult = $this->testApiConnection();
            if (!isset($testResult['data'])) {
                error_log("Failed to connect to Aviation Stack API: " . json_encode($testResult));
                return false;
            }

            // Отримуємо список всіх аеропортів з бази даних
            $stmt = $pdo->query("SELECT code FROM airports");
            $airports = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($airports as $airportCode) {
                // Отримуємо розклад вильотів
                $departures = $this->getFlightSchedule($airportCode, 'departure');
                if (isset($departures['data'])) {
                    $this->processFlights($pdo, $departures['data']);
                }

                // Затримка між запитами для дотримання ліміту API
                sleep(1);
            }

            return true;
        } catch (Exception $e) {
            error_log("Error updating flights database: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Зробити запит до API
     */
    private function makeRequest($endpoint, $params) {
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);
        error_log("Making API request to: " . $url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("API Error: " . $error);
            return ['error' => $error, 'http_code' => $httpCode];
        }

        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            error_log("API Error Response: " . print_r($data['error'], true));
            return $data;
        }

        return $data;
    }

    /**
     * Обробити отримані рейси та оновити базу даних
     */
    private function processFlights($pdo, $flights) {
        foreach ($flights as $flight) {
            try {
                // Перевіряємо чи існують аеропорти
                $depAirport = $this->getOrCreateAirport($pdo, $flight['departure']);
                $arrAirport = $this->getOrCreateAirport($pdo, $flight['arrival']);

                if (!$depAirport || !$arrAirport) {
                    continue;
                }

                // Оновлюємо або створюємо рейс
                $stmt = $pdo->prepare("
                    INSERT INTO flights (
                        departure_airport_id, arrival_airport_id, 
                        flight_number, departure_time, arrival_time,
                        base_price, available_seats, status
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        departure_time = VALUES(departure_time),
                        arrival_time = VALUES(arrival_time),
                        status = VALUES(status)
                ");

                $stmt->execute([
                    $depAirport['id'],
                    $arrAirport['id'],
                    $flight['flight']['iata'],
                    $flight['departure']['scheduled'],
                    $flight['arrival']['scheduled'],
                    rand(1000, 5000), // Базова ціна (оскільки API не надає цінову інформацію)
                    100, // Базова кількість місць
                    $flight['flight_status']
                ]);
            } catch (Exception $e) {
                error_log("Error processing flight: " . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Отримати або створити аеропорт в базі даних
     */
    private function getOrCreateAirport($pdo, $airportData) {
        try {
            $stmt = $pdo->prepare("
                SELECT id, city, code, country FROM airports 
                WHERE code = ?
            ");
            $stmt->execute([$airportData['iata']]);
            $airport = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($airport) {
                return $airport;
            }

            // Створюємо новий аеропорт
            $stmt = $pdo->prepare("
                INSERT INTO airports (city, code, name, country) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $airportData['airport'],
                $airportData['iata'],
                $airportData['airport'],
                $airportData['country']
            ]);

            return [
                'id' => $pdo->lastInsertId(),
                'city' => $airportData['airport'],
                'code' => $airportData['iata'],
                'country' => $airportData['country']
            ];
        } catch (Exception $e) {
            error_log("Error processing airport: " . $e->getMessage());
            return null;
        }
    }
} 