<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "Starting database check...\n";
    
    // Отримуємо конфігурацію бази даних
    $config = require 'config/database.php';
    echo "Configuration loaded.\n";
    
    // Створюємо підключення
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
    echo "Connecting to database...\n";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected successfully.\n";
    
    // Отримуємо список таблиць
    echo "Getting table list...\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Found " . count($tables) . " tables.\n";
    
    // Необхідні таблиці
    $required_tables = [
        'airports',
        'flights',
        'bookings',
        'passengers',
        'users'
    ];
    
    // Перевіряємо наявність кожної таблиці
    echo "\nChecking required tables:\n";
    $missing_tables = [];
    foreach ($required_tables as $table) {
        if (in_array($table, $tables)) {
            echo "✓ Table '{$table}' exists\n";
        } else {
            echo "✗ Table '{$table}' does not exist\n";
            $missing_tables[] = $table;
        }
    }
    
    if (!empty($missing_tables)) {
        echo "\nMissing tables: " . implode(', ', $missing_tables) . "\n";
        echo "Please run the database migrations to create these tables.\n";
    } else {
        echo "\nAll required tables exist!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} 