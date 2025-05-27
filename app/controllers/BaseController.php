<?php
namespace controllers;

class BaseController {
    public function __construct() {
        // Инициализация базового контроллера
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view($view, $data = []) {
        // Розпаковуємо дані у змінні
        extract($data);
        
        // Підключаємо файл представлення
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }
        
        require $viewPath;
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