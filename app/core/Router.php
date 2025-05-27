<?php
namespace app\core;

class Router {
    private $routes = [
        '/' => ['HomeController', 'index'],
        '/route' => ['RouteController', 'index'],
        '/booking' => ['BookingController', 'index'],
        '/booking/confirm' => ['BookingController', 'confirm'],
        '/booking/success' => ['BookingController', 'success']
    ];

    public function handleRequest($method, $uri) {
        // Видаляємо GET параметри з URI якщо вони є
        $uri = parse_url($uri, PHP_URL_PATH);
        error_log("Processing URI after parse_url: " . $uri);
        
        // Видаляємо завершальний слеш, якщо він є
        $uri = rtrim($uri, '/');
        error_log("Processing URI after rtrim: " . $uri);
        
        // Якщо URI порожній, використовуємо '/'
        if (empty($uri)) {
            $uri = '/';
        }
        error_log("Final URI to process: " . $uri);
        error_log("Available routes: " . print_r($this->routes, true));

        if (isset($this->routes[$uri])) {
            error_log("Route found for URI: " . $uri);
            list($controllerName, $actionName) = $this->routes[$uri];
            
            // Додаємо повний неймспейс для контролера
            $controllerClass = "app\\controllers\\{$controllerName}";
            error_log("Looking for controller class: " . $controllerClass);
            
            if (class_exists($controllerClass)) {
                error_log("Controller class found: " . $controllerClass);
                $controller = new $controllerClass();
                if (method_exists($controller, $actionName)) {
                    error_log("Method found: " . $actionName);
                    return $controller->$actionName();
                } else {
                    error_log("Method not found: " . $actionName);
                }
            } else {
                error_log("Controller class not found: " . $controllerClass);
            }
        } else {
            error_log("No route found for URI: " . $uri);
        }

        // Якщо маршрут не знайдено, показуємо 404 помилку
        error_log("Showing 404 page");
        http_response_code(404);
        require APP_PATH . '/views/errors/404.php';
    }
} 