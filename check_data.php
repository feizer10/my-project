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
    
    // Перевіряємо дані в таблиці airports
    $airports = $pdo->query("SELECT * FROM airports")->fetchAll();
    echo "Found " . count($airports) . " airports:\n";
    foreach ($airports as $airport) {
        echo "- {$airport['city']} ({$airport['code']}): {$airport['name']}\n";
    }
    
    // Перевіряємо дані в таблиці flights
    $flights = $pdo->query("SELECT COUNT(*) as count FROM flights")->fetch();
    echo "\nFound {$flights['count']} flights in database.\n";
    
    // Перевіряємо дані в таблиці bookings
    $bookings = $pdo->query("SELECT COUNT(*) as count FROM bookings")->fetch();
    echo "Found {$bookings['count']} bookings in database.\n";
    
    // Перевіряємо дані в таблиці passengers
    $passengers = $pdo->query("SELECT COUNT(*) as count FROM passengers")->fetch();
    echo "Found {$passengers['count']} passengers in database.\n";
    
    // Перевіряємо дані в таблиці users
    $users = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch();
    echo "Found {$users['count']} users in database.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} 