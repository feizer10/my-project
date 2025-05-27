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
    
    // Отримуємо структуру таблиці airports
    $stmt = $pdo->query("SHOW CREATE TABLE airports");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Airports table structure:\n";
    echo $result['Create Table'] . "\n\n";
    
    // Отримуємо дані з таблиці airports
    $stmt = $pdo->query("SELECT * FROM airports");
    $airports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Airports data:\n";
    foreach ($airports as $airport) {
        echo "ID: {$airport['id']}, Code: {$airport['code']}, City: {$airport['city']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} 