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
    
    // Вимикаємо перевірку зовнішніх ключів
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Отримуємо список всіх таблиць
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    // Видаляємо кожну таблицю
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
        echo "Dropped table {$table}\n";
    }
    
    // Вмикаємо перевірку зовнішніх ключів
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "\nAll tables dropped successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
} 