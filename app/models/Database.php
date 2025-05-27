<?php
namespace app\models;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require APP_PATH . '/config/database.php';
        error_log("Database config loaded: " . print_r($config, true));
        
        try {
            error_log("Attempting to connect to database...");
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            error_log("DSN: " . $dsn);
            
            $this->connection = new \PDO(
                $dsn,
                $config['username'],
                $config['password']
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            
            error_log("Database connection successful");
            
            // Ініціалізуємо таблиці при першому підключенні
            $this->initTables();
            error_log("Tables initialized successfully");
        } catch (\PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new \Exception("Помилка підключення до бази даних: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            error_log("Creating new Database instance");
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function initTables() {
        try {
            error_log("Initializing database tables...");
            
            // Створюємо таблиці, якщо вони не існують
            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS routes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    from_city VARCHAR(100) NOT NULL,
                    to_city VARCHAR(100) NOT NULL,
                    distance DECIMAL(10,2) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            error_log("Routes table created/checked");

            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS flights (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    route_id INT NOT NULL,
                    flight_number VARCHAR(10) NOT NULL,
                    departure_time DATETIME NOT NULL,
                    price DECIMAL(10,2) NOT NULL,
                    available_seats INT NOT NULL DEFAULT 100,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            error_log("Flights table created/checked");

            $this->connection->exec("
                CREATE TABLE IF NOT EXISTS bookings (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    flight_id INT NOT NULL,
                    passenger_name VARCHAR(100) NOT NULL,
                    passenger_email VARCHAR(100) NOT NULL,
                    booking_number VARCHAR(20) NOT NULL UNIQUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            error_log("Bookings table created/checked");
        } catch (\PDOException $e) {
            error_log("Error initializing tables: " . $e->getMessage());
            throw $e;
        }
    }
} 