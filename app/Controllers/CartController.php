<?php
require_once __DIR__ . '/../Models/Animal.php';
require_once __DIR__ . '/../Models/Model.php';

class CartController extends Model {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        parent::__construct();
    }

    public function add() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /');
            exit();
        }

        $animalModel = new Animal();
        $animal = $animalModel->find($id);

        if ($animal && $animal['estoque'] > 0 && !isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = $animal;
        }
        header('Location: /carrinho');
    }
    
    public function show() {
        $carrinho = $_SESSION['carrinho'] ?? [];
        require_once __DIR__ . '/../Views/carrinho.php';
    }

    public function remove() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /carrinho');
            exit();
        }

        if (isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
        header('Location: /carrinho');
    }

    public function checkout() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login?redirect=/carrinho/checkout');
            exit();
        }

        $carrinho = $_SESSION['carrinho'] ?? [];
        if (empty($carrinho)) {
            header('Location: /carrinho');
            exit();
        }

        require_once __DIR__ . '/../Views/checkout.php';
    }

    public function finalizar() {
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
            header('Location: /');
            exit();
        }

        $carrinho = $_SESSION['carrinho'];
        $usuario_id = $_SESSION['usuario_id'];
        $total = array_sum(array_column($carrinho, 'preco'));

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("INSERT INTO adocoes (usuario_id, valor_total) VALUES (?, ?)");
            $stmt->execute([$usuario_id, $total]);
            $adocao_id = $this->db->lastInsertId();

            $animalModel = new Animal();
            foreach ($carrinho as $item) {
                $stmt = $this->db->prepare("INSERT INTO adocao_itens (adocao_id, animal_id, preco_unitario) VALUES (?, ?, ?)");
                $stmt->execute([$adocao_id, $item['id'], $item['preco']]);
                $animalModel->decreaseStock($item['id'], 1);
            }

            $this->db->commit();
            unset($_SESSION['carrinho']);
            header('Location: /dashboard?compra=sucesso');

        } catch (Exception $e) {
            $this->db->rollBack();
            die("Erro ao finalizar a adoção: " . $e->getMessage());
        }
    }
}