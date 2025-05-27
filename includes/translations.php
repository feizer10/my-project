<?php

/**
 * Функція перекладу статусів та інших текстів системи
 */
function translate($key) {
    static $translations = [
        // Статуси рейсів
        'scheduled' => 'За розкладом',
        'delayed' => 'Затримується',
        'cancelled' => 'Скасовано',
        
        // Статуси бронювань
        'pending' => 'Очікує',
        'confirmed' => 'Підтверджено',
        'cancelled_booking' => 'Скасовано',
        
        // Загальні тексти
        'flight' => 'Рейс',
        'booking' => 'Бронювання',
        'search' => 'Пошук',
        'book_now' => 'Забронювати',
        'details' => 'Деталі',
        'price' => 'Вартість',
        'from' => 'від',
        
        // Форми
        'departure_city' => 'Місто відправлення',
        'arrival_city' => 'Місто прибуття',
        'date' => 'Дата',
        'passengers' => 'Пасажири',
        'select_city' => 'Оберіть місто',
        'search_flights' => 'Знайти рейси',
        
        // Повідомлення
        'no_flights_found' => 'Рейсів не знайдено',
        'booking_success' => 'Бронювання успішно створено',
        'booking_error' => 'Помилка при створенні бронювання',
        
        // Профіль користувача
        'profile' => 'Профіль',
        'my_bookings' => 'Мої бронювання',
        'logout' => 'Вийти',
        'login' => 'Увійти',
        'register' => 'Зареєструватися',
        
        // Навігація
        'home' => 'Головна',
        'flights' => 'Рейси',
        'bookings' => 'Бронювання',
        'about' => 'Про нас',
        'contact' => 'Контакти',
        'help' => 'Допомога',
        
        // Час
        'departure_time' => 'Час відправлення',
        'arrival_time' => 'Час прибуття',
        'flight_duration' => 'Тривалість польоту',
        
        // Помилки
        '404_title' => 'Сторінку не знайдено',
        '404_message' => 'На жаль, сторінку, яку ви шукаєте, не знайдено.',
        '500_title' => 'Помилка сервера',
        '500_message' => 'Вибачте, сталася помилка на сервері. Спробуйте пізніше.',
        
        // Інше
        'currency' => 'грн',
        'persons' => 'особи',
        'back_to_home' => 'Повернутися на головну',
        
        // Нові переклади
        'flight_booking_system' => 'Система бронювання авіаквитків',
        'useful_links' => 'Корисні посилання',
        'terms' => 'Умови використання',
        'kyiv_ukraine' => 'Київ, Україна',
        'all_rights_reserved' => 'Всі права захищено',
        'footer_description' => 'Зручний сервіс для бронювання авіаквитків по Україні',
        
        // Статуси пасажирів
        'adult' => 'Дорослий',
        'child' => 'Дитина',
        'infant' => 'Немовля',
        
        // Форма бронювання
        'passenger_details' => 'Дані пасажира',
        'first_name' => "Ім'я",
        'last_name' => 'Прізвище',
        'passport_number' => 'Номер паспорта',
        'birth_date' => 'Дата народження',
        'phone' => 'Телефон',
        'email' => 'Електронна пошта',
        'confirm_booking' => 'Підтвердити бронювання',
        
        // Інформація про рейс
        'flight_info' => 'Інформація про рейс',
        'aircraft' => 'Літак',
        'seat' => 'Місце',
        'gate' => 'Гейт',
        'terminal' => 'Термінал',
        
        // Статуси оплати
        'payment_pending' => 'Очікує оплати',
        'payment_success' => 'Оплачено',
        'payment_failed' => 'Помилка оплати',
        'payment_refunded' => 'Повернуто кошти'
    ];
    
    return isset($translations[$key]) ? $translations[$key] : $key;
}

/**
 * Скорочена функція перекладу
 */
function t($key) {
    return translate($key);
} 