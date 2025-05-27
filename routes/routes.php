<?php
// Визначення маршрутів
$routes = [
    'home' => 'controllers/HomeController.php',
    'flights' => 'controllers/FlightController.php',
    'booking' => 'controllers/BookingController.php',
    'profile' => 'controllers/UserController.php',
    'routes' => 'controllers/RouteController.php',
    'login' => 'controllers/AuthController.php',
    'register' => 'controllers/AuthController.php',
    'airports' => 'controllers/AirportController.php',
    'search' => 'controllers/SearchController.php'
];

// Створюємо екземпляр контролера в залежності від маршруту
function loadController($controllerPath, $pdo) {
    require_once $controllerPath;
    $className = basename($controllerPath, '.php');
    return new $className($pdo);
} 