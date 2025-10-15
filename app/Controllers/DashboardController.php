<?php
require_once __DIR__ . '/../Models/Animal.php';
require_once __DIR__ . '/../Models/Usuario.php';

class DashboardController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die("Acesso negado. Você não tem permissão para ver esta página.");
        }
    }

    public function index() {
        require_once __DIR__ . '/../Views/dashboard.php';
    }

    public function data() {
        header('Content-Type: application/json');
        $animalModel = new Animal();
        $data = $animalModel->getDashboardData();
        echo json_encode($data);
    }
}