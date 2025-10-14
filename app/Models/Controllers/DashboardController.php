<?php
require_once __DIR__ . '/../Veiculos.php';

class DashboardController {
    public function index() {
        require_once __DIR__ . '/../Views/dashboard.php';
    }

    public function data() {
        header('Content-Type: application/json');
        $veiculoModel = new Veiculo();
        $data = $veiculoModel->getDashboardData();
        echo json_encode($data);
    }
}