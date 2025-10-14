<?php
require_once __DIR__ . '/../Veiculos.php';

class HomeController {
    public function index() {
        $veiculoModel = new Veiculo();
        $veiculos = $veiculoModel->getAll();
        require_once __DIR__ . '/../Views/home.php';
    }
}