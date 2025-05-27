<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'airline_booking',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

try {
    // Пробуем подключиться без базы данных
    $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']}"
    ];

    echo "Trying to connect...<br>";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
    echo "Connected successfully!<br>";

    // Пробуем выбрать базу данных
    echo "Trying to select database...<br>";
    $pdo->exec("USE `{$config['dbname']}`");
    echo "Database selected successfully!<br>";

    // Проверяем таблицы
    echo "Checking tables...<br>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(', ', $tables) . "<br>";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
} 