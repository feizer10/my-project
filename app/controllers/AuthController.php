<?php
namespace controllers;

use models\User;

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login() {
        // Якщо користувач вже авторизований, перенаправляємо на головну
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/admin');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                $user = $this->userModel->findByUsername($username);
                
                if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['admin'] = ($user['role'] === 'admin');
                    
                    $this->redirect('/admin');
                } else {
                    $error = 'Невірні облікові дані';
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $error = 'Помилка при вході в систему. Спробуйте пізніше.';
            }
        }

        $this->view('auth/login', [
            'title' => 'Вхід до панелі керування',
            'error' => $error ?? null
        ]);
    }

    public function logout() {
        // Перевіряємо, чи авторизований користувач
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
            return;
        }

        // Очищаємо всі дані сесії
        session_unset();
        session_destroy();
        
        // Перенаправляємо на головну
        $this->redirect('/');
    }
}
