<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Отримуємо конфігурацію бази даних
    $config = require 'config/database.php';
    
    // Створюємо підключення
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Видаляємо таблицю, якщо вона існує
    $pdo->exec("DROP TABLE IF EXISTS flights");
    echo "Dropped flights table if existed.\n";
    
    // Створюємо таблицю
    $sql = "CREATE TABLE flights (
        id INT AUTO_INCREMENT PRIMARY KEY,
        flight_number VARCHAR(10) NOT NULL UNIQUE,
        departure_airport_id INT NOT NULL,
        arrival_airport_id INT NOT NULL,
        departure_time DATETIME NOT NULL,
        arrival_time DATETIME NOT NULL,
        base_price DECIMAL(10,2) NOT NULL,
        status ENUM('scheduled', 'delayed', 'cancelled') NOT NULL DEFAULT 'scheduled',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (departure_airport_id) REFERENCES airports(id),
        FOREIGN KEY (arrival_airport_id) REFERENCES airports(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "Created flights table.\n";
    
    // Додаємо тестові дані
    $sql = "INSERT INTO flights (flight_number, departure_airport_id, arrival_airport_id, departure_time, arrival_time, base_price, status) 
            SELECT 'PS101', a1.id, a2.id, '2024-03-20 08:00:00', '2024-03-20 09:15:00', 1500.00, 'scheduled'
            FROM airports a1, airports a2 
            WHERE a1.code = 'KBP' AND a2.code = 'LWO'
            LIMIT 1";
    
    $pdo->exec($sql);
    echo "Added test flight.\n";
    
    // Перевіряємо дані
    $stmt = $pdo->query("SELECT f.*, a1.code as departure_code, a2.code as arrival_code 
                         FROM flights f
                         JOIN airports a1 ON f.departure_airport_id = a1.id
                         JOIN airports a2 ON f.arrival_airport_id = a2.id");
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nFlights in database:\n";
    foreach ($flights as $flight) {
        echo "Flight {$flight['flight_number']}: {$flight['departure_code']} -> {$flight['arrival_code']}\n";
        echo "Departure: {$flight['departure_time']}\n";
        echo "Arrival: {$flight['arrival_time']}\n";
        echo "Price: {$flight['base_price']}\n";
        echo "Status: {$flight['status']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} 