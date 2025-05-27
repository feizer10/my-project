<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class DatabaseInitializer {
    private $pdo;
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../config/database.php';
        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};charset={$this->config['charset']}";
            $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Create database if it doesn't exist
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->config['dbname']}");
            $this->pdo->exec("USE {$this->config['dbname']}");
            
            echo "Connected to database successfully.\n";
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage() . "\n");
        }
    }

    public function resetDatabase() {
        try {
            // Disable foreign key checks
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

            // Get and drop all tables
            $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
                echo "Dropped table {$table}\n";
            }

            // Enable foreign key checks
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            
            echo "All tables dropped successfully.\n";
        } catch (PDOException $e) {
            die("Error dropping tables: " . $e->getMessage() . "\n");
        }
    }

    public function createTables() {
        try {
            // Read and execute schema file
            $schema = file_get_contents(__DIR__ . '/../database_schema.sql');
            $this->pdo->exec($schema);
            echo "Tables created successfully.\n";
        } catch (PDOException $e) {
            die("Error creating tables: " . $e->getMessage() . "\n");
        }
    }

    public function insertSampleData() {
        try {
            // Insert sample airports
            $airports = [
                ['JFK', 'John F. Kennedy International Airport', 'New York', 'USA', 40.6413, -73.7781],
                ['LHR', 'London Heathrow Airport', 'London', 'UK', 51.4700, -0.4543],
                ['CDG', 'Charles de Gaulle Airport', 'Paris', 'France', 49.0097, 2.5479],
                ['DXB', 'Dubai International Airport', 'Dubai', 'UAE', 25.2532, 55.3657],
                ['SIN', 'Singapore Changi Airport', 'Singapore', 'Singapore', 1.3644, 103.9915]
            ];

            $stmt = $this->pdo->prepare("INSERT INTO airports (code, name, city, country, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($airports as $airport) {
                $stmt->execute($airport);
            }

            // Insert sample aircraft
            $aircraft = [
                ['Boeing 737-800', 'Boeing', 189],
                ['Airbus A320', 'Airbus', 180],
                ['Boeing 787-9', 'Boeing', 290]
            ];

            $stmt = $this->pdo->prepare("INSERT INTO aircraft (model, manufacturer, total_seats) VALUES (?, ?, ?)");
            foreach ($aircraft as $plane) {
                $stmt->execute($plane);
            }

            // Insert sample flights
            $flights = [
                ['AA100', 1, 2, 1, '2024-03-20 10:00:00', '2024-03-20 22:00:00', 500.00],
                ['BA200', 2, 3, 2, '2024-03-21 11:00:00', '2024-03-21 14:30:00', 300.00],
                ['EK300', 4, 5, 3, '2024-03-22 00:00:00', '2024-03-22 14:00:00', 800.00]
            ];

            $stmt = $this->pdo->prepare("INSERT INTO flights (flight_number, departure_airport_id, arrival_airport_id, aircraft_id, departure_time, arrival_time, base_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
            foreach ($flights as $flight) {
                $stmt->execute($flight);
            }

            echo "Sample data inserted successfully.\n";
        } catch (PDOException $e) {
            die("Error inserting sample data: " . $e->getMessage() . "\n");
        }
    }

    public function initialize() {
        echo "Starting database initialization...\n";
        $this->resetDatabase();
        $this->createTables();
        $this->insertSampleData();
        echo "Database initialization completed successfully!\n";
    }
}

// Run the initializer
$initializer = new DatabaseInitializer();
$initializer->initialize(); 