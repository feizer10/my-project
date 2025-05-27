<?php
// Конфігурація API
$config = require 'config/config.php';
$apiKey = $config['api']['aviationstack']['key'];
$baseUrl = 'http://api.aviationstack.com/v1';

/**
 * Функція для виконання запиту до API
 */
function makeApiRequest($endpoint, $params = []) {
    global $apiKey;
    
    $params['access_key'] = $apiKey;
    $url = 'http://api.aviationstack.com/v1' . $endpoint . '?' . http_build_query($params);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['success' => false, 'error' => $error];
    }
    
    return ['success' => true, 'data' => json_decode($response, true)];
}

/**
 * Отримати розклад рейсів для аеропорту
 */
function getFlightSchedule($iataCode, $type = 'departure') {
    $params = [
        'dep_iata' => $iataCode,
        'flight_status' => 'scheduled'
    ];
    
    if ($type === 'arrival') {
        unset($params['dep_iata']);
        $params['arr_iata'] = $iataCode;
    }
    
    return makeApiRequest('/flights', $params);
}

/**
 * Пошук рейсів між містами
 */
function searchFlightsBetweenCities($fromCity, $toCity, $date = null) {
    $params = [
        'dep_iata' => $fromCity,
        'arr_iata' => $toCity
    ];
    
    if ($date) {
        $params['flight_date'] = date('Y-m-d', strtotime($date));
    }
    
    $result = makeApiRequest('/flights', $params);
    
    if (!$result['success']) {
        return $result;
    }
    
    $flights = [];
    if (isset($result['data']['data'])) {
        foreach ($result['data']['data'] as $flight) {
            $flights[] = [
                'flight_number' => $flight['flight']['iataNumber'],
                'airline' => [
                    'name' => $flight['airline']['name'],
                    'code' => $flight['airline']['iataCode']
                ],
                'departure' => [
                    'city' => $fromCity,
                    'airport' => $flight['departure']['iataCode'],
                    'terminal' => $flight['departure']['terminal'],
                    'gate' => $flight['departure']['gate'],
                    'time' => $flight['departure']['scheduledTime'],
                    'delay' => $flight['departure']['delay']
                ],
                'arrival' => [
                    'city' => $toCity,
                    'airport' => $flight['arrival']['iataCode'],
                    'terminal' => $flight['arrival']['terminal'],
                    'gate' => $flight['arrival']['gate'],
                    'time' => $flight['arrival']['scheduledTime'],
                    'delay' => $flight['arrival']['delay']
                ],
                'status' => $flight['flight_status']
            ];
        }
    }
    
    return [
        'success' => true,
        'flights' => $flights,
        'total' => count($flights)
    ];
}

// Тестовий код, якщо файл викликано напряму
if (php_sapi_name() === 'cli') {
    $result = searchFlightsBetweenCities('KBP', 'LHR');
    print_r($result);
} 