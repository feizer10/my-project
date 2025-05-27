<?php

class AdminController {
    private $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=flight_booking;port=3306", "root", "");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function index() {
        if (!$this->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }

        $stats = $this->getSystemStats();
        require_once 'views/admin/dashboard.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($this->validateCredentials($username, $password)) {
                $_SESSION['admin_authenticated'] = true;
                header('Location: /admin');
                exit;
            }
        }
        
        require_once 'views/admin/login.php';
    }

    public function flights() {
        if (!$this->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }

        $stmt = $this->db->query("SELECT * FROM flights ORDER BY departure_time DESC");
        $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/flights.php';
    }

    public function bookings() {
        if (!$this->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }

        $stmt = $this->db->query("SELECT b.*, f.flight_number FROM bookings b 
                                 JOIN flights f ON b.flight_id = f.id 
                                 ORDER BY b.created_at DESC");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/admin/bookings.php';
    }

    private function isAuthenticated() {
        return isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;
    }

    private function validateCredentials($username, $password) {
        // In a real application, you would check against a database
        // This is just a simple example
        return $username === 'admin' && $password === 'admin123';
    }

    private function getSystemStats() {
        $stats = [];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total_flights FROM flights");
        $stats['total_flights'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_flights'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total_bookings FROM bookings");
        $stats['total_bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_bookings'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as today_bookings FROM bookings 
                                 WHERE DATE(created_at) = CURDATE()");
        $stats['today_bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['today_bookings'];
        
        return $stats;
    }
} 