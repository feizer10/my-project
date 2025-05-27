<?php
namespace app\models;

class Flight {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createFlight($routeId, $departureTime, $price) {
        $flightNumber = $this->generateFlightNumber();
        $stmt = $this->db->prepare('
            INSERT INTO flights (route_id, flight_number, departure_time, price) 
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([$routeId, $flightNumber, $departureTime, $price]);
        return $this->db->lastInsertId();
    }

    public function getFlightsForRoute($routeId) {
        $stmt = $this->db->prepare('
            SELECT f.*, r.from_city, r.to_city, r.distance 
            FROM flights f 
            JOIN routes r ON f.route_id = r.id 
            WHERE r.id = ? AND f.departure_time > NOW() AND f.available_seats > 0
            ORDER BY f.departure_time ASC
        ');
        $stmt->execute([$routeId]);
        return $stmt->fetchAll();
    }

    public function getAllAvailableFlights() {
        $stmt = $this->db->query('
            SELECT f.*, r.from_city, r.to_city, r.distance 
            FROM flights f 
            JOIN routes r ON f.route_id = r.id 
            WHERE f.departure_time > NOW() AND f.available_seats > 0
            ORDER BY f.departure_time ASC
        ');
        return $stmt->fetchAll();
    }

    public function getFlightById($id) {
        $stmt = $this->db->prepare('
            SELECT f.*, r.from_city, r.to_city, r.distance 
            FROM flights f 
            JOIN routes r ON f.route_id = r.id 
            WHERE f.id = ? AND f.departure_time > NOW() AND f.available_seats > 0
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function bookFlight($flightId, $passengerName, $passengerEmail) {
        try {
            // Начинаем транзакцию
            $this->db->beginTransaction();

            // Проверяем наличие мест
            $flight = $this->getFlightById($flightId);
            if (!$flight) {
                throw new \Exception('Рейс не знайдено або всі місця зайняті');
            }

            if ($flight['available_seats'] <= 0) {
                throw new \Exception('На жаль, всі місця на цей рейс вже заброньовані');
            }

            // Уменьшаем количество доступных мест
            $stmt = $this->db->prepare('
                UPDATE flights 
                SET available_seats = available_seats - 1 
                WHERE id = ? AND available_seats > 0
            ');
            $result = $stmt->execute([$flightId]);

            if (!$result) {
                throw new \Exception('Помилка при бронюванні місця');
            }

            // Создаем запись о бронировании
            $stmt = $this->db->prepare('
                INSERT INTO bookings (flight_id, passenger_name, passenger_email, booking_number, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ');
            $bookingNumber = $this->generateBookingNumber();
            $stmt->execute([$flightId, $passengerName, $passengerEmail, $bookingNumber]);

            // Завершаем транзакцию
            $this->db->commit();
            return $bookingNumber;

        } catch (\Exception $e) {
            // В случае ошибки откатываем все изменения
            $this->db->rollBack();
            throw $e;
        }
    }

    private function generateFlightNumber() {
        $prefix = 'AB'; // Airline Booking
        $number = mt_rand(1000, 9999);
        return $prefix . $number;
    }

    private function generateBookingNumber() {
        return 'BK' . date('Ymd') . mt_rand(1000, 9999);
    }
} 