<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "Starting database migrations...\n";
    
    // Отримуємо конфігурацію бази даних
    $config = require __DIR__ . '/../config/database.php';
    echo "Configuration loaded.\n";
    
    // Створюємо підключення
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected to database.\n\n";
    
    // Список міграцій в порядку їх застосування
    $migrations = [
        'create_airports_table.sql',
        'create_flights_table.sql',
        'create_users_table.sql',
        'create_bookings_tables.sql'
    ];
    
    // Застосовуємо кожну міграцію
    foreach ($migrations as $migration) {
        echo "Applying migration {$migration}...\n";
        
        // Читаємо SQL файл
        $sql = file_get_contents(__DIR__ . '/migrations/' . $migration);
        
        // Виконуємо запити
        $pdo->exec($sql);
        
        echo "Migration {$migration} applied successfully.\n\n";
    }
    
    echo "All migrations completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} 