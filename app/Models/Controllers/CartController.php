<?php
require_once __DIR__ . '/../Animal.php';

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
        $animalModel = new Animal();
        $animal = $animalModel->find($id);

        if ($animal && !isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = [
                'id' => $animal['id'],
                'especie' => $animal['especie'],
                'origem' => $animal['origem'],
                'preco' => $animal['preco'],
                'imagem_url' => $animal['imagem_url']
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