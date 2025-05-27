<?php
// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Load configuration
require_once __DIR__ . '/../app/config/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    // Convert namespace separators to directory separators
    $class = str_replace('\\', '/', $class);
    
    // Base directory for class files
    $baseDir = APP_PATH;
    
    // Full path to the class file
    $file = $baseDir . '/' . $class . '.php';
    
    // For debugging
    if (!file_exists($file)) {
        error_log("File not found: " . $file);
        return false;
    }
    
    require_once $file;
    return true;
});

// Get current URL path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove /public from path if it exists
$path = str_replace('/public', '', $path);
// Remove trailing slashes
$path = rtrim($path, '/');
// If path is empty, set it to /
if (empty($path)) {
    $path = '/';
}

try {
    // For debugging
    error_log("Current path: " . $path);
    
    $routes = require APP_PATH . '/routes.php';
    
    // For debugging
    error_log("Available routes: " . print_r($routes, true));

    // Route to appropriate controller
    if (isset($routes[$path])) {
        list($controller, $method) = explode('@', $routes[$path]);
        $controllerClass = "controllers\\{$controller}";
        
        // For debugging
        error_log("Loading controller: " . $controllerClass);
        
        if (!class_exists($controllerClass)) {
            throw new Exception("Controller {$controllerClass} not found");
        }
        
        $controllerInstance = new $controllerClass();
        
        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("Method {$method} not found in controller {$controllerClass}");
        }
        
        $controllerInstance->$method();
    } else {
        header("HTTP/1.0 404 Not Found");
        require APP_PATH . '/views/404.php';
    }
} catch (Exception $e) {
    // For debugging
    error_log("Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    if (DEBUG_MODE) {
        echo "<h1>Error</h1>";
        echo "<pre>";
        echo $e->getMessage();
        echo "\n\nStack trace:\n";
        echo $e->getTraceAsString();
        echo "</pre>";
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        require APP_PATH . '/views/404.php';
    }
} 