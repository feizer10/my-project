<?php
namespace app\core;

class BaseController {
    public function __construct() {
        // Ініціалізація базового контролера
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);
        
        // Include the view file
        $viewFile = APP_PATH . "/views/{$view}.php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new \Exception("View file not found: {$viewFile}");
        }
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/auth/login');
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 