<?php

class Flight {
    private $pdo;

    public function __construct() {
        $config = require 'config/database.php';
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function getLatestFlights($limit = 6) {
        $query = "SELECT 
                    f.id,
                    f.flight_number,
                    f.departure_time,
                    f.arrival_time,
                    f.status,
                    f.base_price as price,
                    a1.city as departure_city,
                    a1.code as departure_airport,
                    a2.city as arrival_city,
                    a2.code as arrival_airport
                FROM flights f
                JOIN airports a1 ON f.departure_airport_id = a1.id
                JOIN airports a2 ON f.arrival_airport_id = a2.id
                WHERE f.departure_time > NOW()
                ORDER BY f.departure_time ASC
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function searchFlights($departure, $arrival, $date) {
        $query = "SELECT 
                    f.id,
                    f.flight_number,
                    f.departure_time,
                    f.arrival_time,
                    f.status,
                    f.base_price as price,
                    a1.city as departure_city,
                    a1.code as departure_airport,
                    a2.city as arrival_city,
                    a2.code as arrival_airport
                FROM flights f
                JOIN airports a1 ON f.departure_airport_id = a1.id
                JOIN airports a2 ON f.arrival_airport_id = a2.id
                WHERE a1.code = :departure 
                AND a2.code = :arrival
                AND DATE(f.departure_time) = :date
                AND f.departure_time > NOW()
                ORDER BY f.departure_time ASC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':departure', $departure);
        $stmt->bindValue(':arrival', $arrival);
        $stmt->bindValue(':date', $date);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT 
                    f.id,
                    f.flight_number,
                    f.departure_time,
                    f.arrival_time,
                    f.status,
                    f.base_price as price,
                    a1.city as departure_city,
                    a1.code as departure_airport,
                    a2.city as arrival_city,
                    a2.code as arrival_airport
                FROM flights f
                JOIN airports a1 ON f.departure_airport_id = a1.id
                JOIN airports a2 ON f.arrival_airport_id = a2.id
                WHERE f.id = :id";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
} 