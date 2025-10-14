<?php
require_once __DIR__ . '/../Animal.php';

class DashboardController {
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