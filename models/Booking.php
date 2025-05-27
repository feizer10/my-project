<?php

class Booking {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        try {
            // Початок транзакції
            $this->db->beginTransaction();

            // Створення бронювання
            $stmt = $this->db->prepare("
                INSERT INTO bookings (
                    flight_number, 
                    total_price, 
                    passengers_count, 
                    contact_email, 
                    contact_phone, 
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, 'pending', NOW())
            ");

            $stmt->execute([
                $data['flight_number'],
                $data['total_price'],
                count($data['passengers']),
                $data['contact']['email'],
                $data['contact']['phone']
            ]);

            $bookingId = $this->db->lastInsertId();

            // Додавання пасажирів
            $stmt = $this->db->prepare("
                INSERT INTO passengers (
                    booking_id,
                    first_name,
                    last_name,
                    passport_number,
                    birth_date
                ) VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($data['passengers'] as $passenger) {
                $stmt->execute([
                    $bookingId,
                    $passenger['first_name'],
                    $passenger['last_name'],
                    $passenger['passport'],
                    $passenger['birth_date']
                ]);
            }

            // Підтвердження транзакції
            $this->db->commit();
            return $bookingId;

        } catch (Exception $e) {
            // Відкат транзакції у випадку помилки
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   f.departure_city, f.departure_airport, f.departure_time,
                   f.arrival_city, f.arrival_airport, f.arrival_time,
                   f.price as flight_price
            FROM bookings b
            JOIN flights f ON b.flight_number = f.flight_number
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            // Отримання пасажирів
            $stmt = $this->db->prepare("
                SELECT * FROM passengers 
                WHERE booking_id = ?
            ");
            $stmt->execute([$id]);
            $booking['passengers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $booking;
    }

    public function getAllByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   f.departure_city, f.departure_airport, f.departure_time,
                   f.arrival_city, f.arrival_airport, f.arrival_time
            FROM bookings b
            JOIN flights f ON b.flight_number = f.flight_number
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancel($id) {
        $stmt = $this->db->prepare("
            UPDATE bookings 
            SET status = 'cancelled', 
                updated_at = NOW() 
            WHERE id = ? AND status = 'pending'
        ");
        return $stmt->execute([$id]);
    }
} 