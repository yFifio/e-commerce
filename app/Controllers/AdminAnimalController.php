<?php
require_once __DIR__ . '/../Models/Animal.php';

class AdminAnimalController {

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die("Acesso negado. Você não tem permissão para ver esta página.");
        }
    }

    public function showAddForm() {
        require_once __DIR__ . '/../Views/admin/adicionar_animal.php';
    }

    public function create() {
        $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
        $estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);
        $data_nascimento = filter_input(INPUT_POST, 'data_nascimento');

        if (!$especie || !$preco || $estoque === false || !$data_nascimento) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Erro de validação. Verifique os campos obrigatórios.'];
            header('Location: /index.php/admin/animais/novo');
            exit();
        }

        $imagem_url = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/imagem/';
            $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
                $imagem_url = $fileName;
            } else {
                $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Erro ao fazer upload da imagem.'];
                header('Location: /index.php/admin/animais/novo');
                exit();
            }
        }

        $animalModel = new Animal();
        try {
            $animalModel->create($especie, $origem, $descricao, $preco, $estoque, $imagem_url, $data_nascimento);
            $_SESSION['form_feedback'] = ['type' => 'success', 'message' => 'Animal adicionado com sucesso!'];
            header('Location: /index.php/admin/animais/novo');
            exit();
        } catch (Exception $e) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Erro ao adicionar animal: ' . $e->getMessage()];
            if ($imagem_url && file_exists($uploadDir . $imagem_url)) {
                unlink($uploadDir . $imagem_url);
            }
            header('Location: /index.php/admin/animais/novo');
            exit();
        }
    }
}