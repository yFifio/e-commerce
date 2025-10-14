<?php
require_once __DIR__ . '/../Veiculos.php';

class CartController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public function add() {
        $id = $_POST['id'];
        $veiculoModel = new Veiculo();
        $veiculo = $veiculoModel->find($id);

        if ($veiculo && !isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = [
                'id' => $veiculo['id'],
                'modelo' => $veiculo['modelo'],
                'marca' => $veiculo['marca'],
                'preco' => $veiculo['preco'],
                'imagem_url' => $veiculo['imagem_url']
            ];
        }
        header('Location: /carrinho');
    }
    
    public function show() {
        $carrinho = $_SESSION['carrinho'] ?? [];
        require_once __DIR__ . '/../Views/carrinho.php';
    }

    public function remove() {
        $id = $_POST['id'];
        if (isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
        header('Location: /carrinho');
    }
}