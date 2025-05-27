<?php
namespace controllers;

use models\Flight as FlightModel;
use models\Route as RouteModel;

class FlightController extends BaseController {
    private $flightModel;
    private $routeModel;

    public function __construct() {
        parent::__construct();
        $this->flightModel = new FlightModel();
        $this->routeModel = new RouteModel();
    }

    public function book($routeId = null) {
        if (!$routeId) {
            $this->redirect('/route');
            return;
        }

        $error = null;
        $success = null;
        $flights = [];
        $selectedFlight = null;

        try {
            $route = $this->routeModel->getRouteById($routeId);
            if (!$route) {
                throw new \Exception('Маршрут не знайдено');
            }

            $flights = $this->flightModel->getFlightsForRoute($routeId);
            if (empty($flights)) {
                throw new \Exception('На даний момент немає доступних рейсів за цим маршрутом');
            }

            // Отримуємо вибраний рейс з GET параметра або сесії
            $selectedFlightId = $_GET['flight_id'] ?? $_SESSION['selected_flight_id'] ?? null;
            if ($selectedFlightId) {
                $selectedFlight = $this->flightModel->getFlightById($selectedFlightId);
                unset($_SESSION['selected_flight_id']); // Очищаємо сесію після використання
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $flightId = $_POST['flight_id'] ?? null;
                $passengerName = $_POST['passenger_name'] ?? '';
                $passengerEmail = $_POST['passenger_email'] ?? '';

                if (!$flightId || !$passengerName || !$passengerEmail) {
                    throw new \Exception('Будь ласка, заповніть всі поля');
                }

                // Проверяем корректность email
                if (!filter_var($passengerEmail, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Будь ласка, введіть коректну email адресу');
                }

                // Получаем информацию о выбранном рейсе
                $selectedFlight = $this->flightModel->getFlightById($flightId);
                if (!$selectedFlight) {
                    throw new \Exception('Обраний рейс недоступний');
                }

                // Бронируем билет
                $bookingNumber = $this->flightModel->bookFlight($flightId, $passengerName, $passengerEmail);

                $success = "Квиток успішно заброньовано! Ваш номер бронювання: {$bookingNumber}. " .
                          "Деталі відправлено на вашу електронну пошту.";

                // Обновляем список доступных рейсов
                $flights = $this->flightModel->getFlightsForRoute($routeId);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $error = $e->getMessage();
        }

        $this->view('flight/book', [
            'title' => 'Бронювання квитка',
            'route' => $route ?? null,
            'flights' => $flights,
            'selectedFlight' => $selectedFlight,
            'error' => $error,
            'success' => $success
        ]);
    }
} 