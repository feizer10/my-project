<?php
namespace controllers;

class AdminController extends BaseController {
    public function __construct() {
        // Проверка авторизации для всех методов админки
        if (!isset($_SESSION['admin'])) {
            $this->redirect('/admin/login');
        }
    }

    public function index() {
        $this->view('admin/dashboard', [
            'title' => 'Админ-панель',
            'planes' => $this->getPlanes()
        ]);
    }

    private function getPlanes() {
        // Здесь будет логика получения списка самолетов
        return [
            1 => [
                'from' => 'Киев',
                'to' => 'Львов',
                'price' => 1500,
                'flight_date' => '2025-06-15 10:00:00'
            ],
            2 => [
                'from' => 'Одесса',
                'to' => 'Харьков',
                'price' => 1800,
                'flight_date' => '2025-06-22 12:00:00'
            ]
        ];
    }
}
