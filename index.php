<?php
// Включаємо відображення помилок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Визначаємо константи
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');

// Функція автозавантаження
function autoload($class) {
    // Конвертуємо неймспейс в шлях до файлу
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    
    // Логуємо спробу завантаження
    error_log("Trying to load class: " . $class);
    error_log("Looking for file: " . $file);
    
    // Перевіряємо чи існує файл
    if (file_exists($file)) {
        error_log("File found: " . $file);
        require_once $file;
        return true;
    }
    error_log("File not found: " . $file);
    return false;
}

// Реєструємо функцію автозавантаження
spl_autoload_register('autoload');

// Запускаємо сесію
session_start();

// Підключення необхідних файлів
require_once 'controllers/HomeController.php';
require_once 'controllers/FlightsController.php';
require_once 'controllers/BookingController.php';
require_once 'models/Booking.php';
require_once 'models/Flight.php';

try {
    // Отримуємо конфігурацію бази даних
    $config = require 'config/database.php';
    
    // Створення підключення до бази даних
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
    
    // Отримуємо поточний URL
    $request_uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Видаляємо GET параметри для правильної маршрутизації
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    // Видаляємо завершальний слеш, якщо він є
    $path = rtrim($path, '/');
    
    // Якщо шлях порожній, встановлюємо його як /
    if (empty($path)) {
        $path = '/';
    }

    // Завантажуємо маршрути
    $routes = require 'routes.php';
    
    // Шукаємо відповідний маршрут
    $matchedRoute = null;
    $params = [];
    
    foreach ($routes as $pattern => $handler) {
        // Розбиваємо паттерн на метод і шлях
        list($routeMethod, $routePath) = explode('|', $pattern);
        
        // Пропускаємо якщо метод не співпадає
        if ($routeMethod !== $method) {
            continue;
        }
        
        // Перетворюємо шаблон маршруту в регулярний вираз
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        // Перевіряємо чи URL відповідає шаблону
        if (preg_match($pattern, $path, $matches)) {
            $matchedRoute = $handler;
            // Видаляємо числові ключі
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            break;
        }
    }
    
    if ($matchedRoute) {
        // Розбиваємо обробник на контролер і метод
        list($controllerName, $methodName) = explode('@', $matchedRoute);
        
        // Створюємо екземпляр контролера
        $controller = new $controllerName($pdo);
        
        // Викликаємо метод з параметрами
        call_user_func_array([$controller, $methodName], $params);
    } else {
        // 404 сторінка
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>The page you are looking for could not be found.</p>";
        echo "<p><a href='/'>Return to homepage</a></p>";
    }
    
} catch (Exception $e) {
    // Логуємо помилку
    error_log($e->getMessage());
    
    // Показуємо сторінку з помилкою
    header("HTTP/1.0 500 Internal Server Error");
    echo "<h1>Error 500: Internal Server Error</h1>";
    echo "<p>Sorry, something went wrong on our end. Please try again later.</p>";
    
    // Показуємо деталі помилки тільки якщо увімкнено відображення помилок
    if (ini_get('display_errors')) {
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    }
} 