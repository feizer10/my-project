<?php

class HomeController {
    private $pdo;
    private $flightModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->flightModel = new Flight();
    }

    public function index() {
        // Отримуємо останні рейси
        $latestFlights = $this->flightModel->getLatestFlights();
        
        // Перевіряємо чи користувач авторизований
        $isLoggedIn = isset($_SESSION['user_id']);
        
        // Якщо користувач авторизований, отримуємо його бронювання
        $userBookings = [];
        if ($isLoggedIn) {
            $bookingModel = new Booking();
            $userBookings = $bookingModel->getAllByUser($_SESSION['user_id']);
        }
        
        // Відображаємо головну сторінку
        require 'views/home/index.php';
    }
} 