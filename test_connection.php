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

echo "<pre>";
echo "Testing database connection...\n\n";

// Проверяем наличие PDO
echo "Checking PDO extension...\n";
if (!extension_loaded('pdo')) {
    die("PDO extension is not loaded!\n");
}
echo "PDO extension is loaded.\n\n";

// Проверяем наличие PDO MySQL
echo "Checking PDO MySQL driver...\n";
if (!in_array('mysql', PDO::getAvailableDrivers())) {
    die("PDO MySQL driver is not loaded!\n");
}
echo "PDO MySQL driver is loaded.\n\n";

// Пробуем подключиться к MySQL
echo "Attempting to connect to MySQL...\n";
echo "DSN: mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}\n";

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']}",
        PDO::ATTR_TIMEOUT => 5
    ];
    
    $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
    echo "Successfully connected to MySQL!\n\n";
    
    // Проверяем версию MySQL
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "MySQL Version: $version\n\n";
    
    // Пробуем выбрать базу данных
    echo "Attempting to select database '{$config['dbname']}'...\n";
    $pdo->exec("USE `{$config['dbname']}`");
    echo "Successfully selected database!\n\n";
    
    // Проверяем таблицы
    echo "Checking tables...\n";
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    if (empty($tables)) {
        echo "No tables found in the database.\n";
    } else {
        echo "Tables found:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Connection failed!\n";
    echo "Error code: " . $e->getCode() . "\n";
    echo "Error message: " . $e->getMessage() . "\n\n";
    
    echo "Additional debug information:\n";
    echo "PHP Version: " . PHP_VERSION . "\n";
    echo "OS: " . PHP_OS . "\n";
    
    // Проверяем доступность порта
    $connection = @fsockopen($config['host'], $config['port'], $errno, $errstr, 5);
    if (!$connection) {
        echo "\nPort {$config['port']} is not accessible!\n";
        echo "Error $errno: $errstr\n";
    } else {
        fclose($connection);
        echo "\nPort {$config['port']} is accessible.\n";
    }
}

echo "</pre>"; 