-- Таблиця літаків
CREATE TABLE planes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    route VARCHAR(255),
    departure_time DATETIME
);

-- Таблиця місць
CREATE TABLE seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plane_id INT,
    seat_number VARCHAR(10),
    is_booked BOOLEAN DEFAULT 0,
    FOREIGN KEY (plane_id) REFERENCES planes(id) ON DELETE CASCADE
);

-- Таблиця бронювань
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seat_id INT,
    passenger_name VARCHAR(100),
    FOREIGN KEY (seat_id) REFERENCES seats(id) ON DELETE CASCADE
);

-- Додаємо літаки
INSERT INTO planes (name, route, departure_time) VALUES
('Літак 1', 'Київ → Львів', '2025-06-01 08:00:00'),
('Літак 2', 'Одеса → Харків', '2025-06-01 13:30:00');

-- Додаємо місця
INSERT INTO seats (plane_id, seat_number) VALUES
(1, '1A'), (1, '1B'), (1, '1C'),
(2, '1A'), (2, '1B'), (2, '1C');
