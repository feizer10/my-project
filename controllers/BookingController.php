<?php

class BookingController {
    private $bookingModel;
    private $flightModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->flightModel = new Flight();
    }

    public function index() {
        // Отримання ID користувача з сесії
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $bookings = $this->bookingModel->getAllByUser($userId);
        require 'views/booking/index.php';
    }

    public function create() {
        $flight_number = isset($_GET['flight']) ? $_GET['flight'] : null;
        $passengers = isset($_GET['passengers']) ? (int)$_GET['passengers'] : 1;
        
        if (!$flight_number) {
            header('Location: /flights');
            exit;
        }

        // Отримання інформації про рейс
        $flight = $this->flightModel->getByNumber($flight_number);
        if (!$flight) {
            $_SESSION['error'] = 'Рейс не знайдено';
            header('Location: /flights');
            exit;
        }

        require 'views/booking/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /flights');
            exit;
        }

        try {
            // Валідація даних
            $flight_number = $_POST['flight_number'] ?? null;
            $passengers = $_POST['passengers'] ?? [];
            $contact = $_POST['contact'] ?? [];

            if (!$flight_number || empty($passengers) || empty($contact)) {
                throw new Exception('Будь ласка, заповніть всі обов\'язкові поля');
            }

            // Отримання інформації про рейс
            $flight = $this->flightModel->getByNumber($flight_number);
            if (!$flight) {
                throw new Exception('Рейс не знайдено');
            }

            // Розрахунок загальної вартості
            $total_price = $flight['price'] * count($passengers);

            // Підготовка даних для збереження
            $bookingData = [
                'flight_number' => $flight_number,
                'total_price' => $total_price,
                'passengers' => $passengers,
                'contact' => $contact,
                'user_id' => $_SESSION['user_id'] ?? null
            ];

            // Створення бронювання
            $bookingId = $this->bookingModel->create($bookingData);

            // Перенаправлення на сторінку деталей бронювання
            $_SESSION['success'] = 'Бронювання успішно створено';
            header("Location: /booking/details/{$bookingId}");
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function details($id) {
        $booking = $this->bookingModel->getById($id);
        
        if (!$booking) {
            $_SESSION['error'] = 'Бронювання не знайдено';
            header('Location: /bookings');
            exit;
        }

        require 'views/booking/details.php';
    }

    public function cancel($id) {
        try {
            $booking = $this->bookingModel->getById($id);
            
            if (!$booking) {
                throw new Exception('Бронювання не знайдено');
            }

            if ($booking['status'] !== 'pending') {
                throw new Exception('Неможливо скасувати це бронювання');
            }

            if ($this->bookingModel->cancel($id)) {
                $_SESSION['success'] = 'Бронювання успішно скасовано';
            } else {
                throw new Exception('Помилка при скасуванні бронювання');
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /bookings');
        exit;
    }
}