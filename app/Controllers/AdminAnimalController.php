<?php
require_once __DIR__ . '/../Models/Animal.php';

class AdminAnimalController {

    private function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login?acesso_negado=1');
            exit();
        }
    }

    public function listAll() {
        $this->checkAdmin();
        $animalModel = new Animal();
        $animais = $animalModel->getAll(true); // Passa true para obter todos os animais (visão de admin)
        require_once __DIR__ . '/../Views/admin/listar_animais.php';
    }

    public function showAddForm() {
        $this->checkAdmin();
        require_once __DIR__ . '/../Views/admin/adicionar_animal.php';
    }

    public function create() {
        $this->checkAdmin();
        $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);

        // Trata o preço para aceitar vírgula e ponto
        $preco_raw = $_POST['preco'] ?? '0';
        $preco_formatted = str_replace(',', '.', $preco_raw);
        $preco = (float)$preco_formatted;

        // Validação unificada
        if (!$especie || $preco <= 0 || $estoque === false) {
            $errorMessage = 'Erro de validação. Verifique os campos obrigatórios.';
            if ($preco <= 0) {
                $errorMessage = 'O preço deve ser um valor maior que zero.';
            }
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => $errorMessage];
            header('Location: /index.php/admin/animais/novo');
            exit();
        }

        $imagem_url = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['imagem']);
            if ($uploadResult['success']) {
                $imagem_url = $uploadResult['filename'];
            } else {
                $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => $uploadResult['error']];
                header('Location: /index.php/admin/animais/novo');
                exit();
            }
        }

        $animalModel = new Animal();
        try {
            $animalModel->create($especie, $origem, $descricao, $preco, $estoque, $imagem_url);
            $_SESSION['form_feedback'] = ['type' => 'success', 'message' => 'Animal adicionado com sucesso!'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        } catch (Exception $e) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Erro ao adicionar animal: ' . $e->getMessage()];
            if ($imagem_url && file_exists(__DIR__ . '/../../public/imagem/' . $imagem_url)) {
                unlink(__DIR__ . '/../../public/imagem/' . $imagem_url);
            }
            header('Location: /index.php/admin/animais/novo');
            exit();
        }
    }

    public function showEditForm() {
        $this->checkAdmin();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /index.php/admin/animais/listar');
            exit();
        }

        $animalModel = new Animal();
        $animal = $animalModel->find($id);

        if (!$animal) {
            $_SESSION['form_feedback'] = ['type' => 'warning', 'message' => 'Animal não encontrado.'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        }

        require_once __DIR__ . '/../Views/admin/editar_animal.php';
    }

    public function update() {
        $this->checkAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);

        // Trata o preço para aceitar vírgula e ponto
        $preco_raw = $_POST['preco'] ?? '0';
        $preco_formatted = str_replace(',', '.', $preco_raw);
        $preco = (float)$preco_formatted;

        // Validação unificada
        if (!$id || !$especie || $preco <= 0 || $estoque === false) {
            $errorMessage = 'Erro de validação. Verifique os campos obrigatórios.';
            if ($preco <= 0) {
                $errorMessage = 'O preço deve ser um valor maior que zero.';
            }
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => $errorMessage];
            header('Location: /index.php/admin/animais/editar?id=' . $id);
            exit();
        }

        $animalModel = new Animal();
        $animalAtual = $animalModel->find($id);
        $imagem_url = $animalAtual['imagem_url'];

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['imagem'], $imagem_url);
            if ($uploadResult['success']) {
                $imagem_url = $uploadResult['filename'];
            } else {
                $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => $uploadResult['error']];
                header('Location: /index.php/admin/animais/editar?id=' . $id);
                exit();
            }
        }

        try {
            $animalModel->update($id, $especie, $origem, $descricao, $preco, $estoque, $imagem_url);
            $_SESSION['form_feedback'] = ['type' => 'success', 'message' => 'Animal atualizado com sucesso!'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        } catch (Exception $e) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Erro ao atualizar animal: ' . $e->getMessage()];
            header('Location: /index.php/admin/animais/editar?id=' . $id);
            exit();
        }
    }

    public function deactivate() {
        $this->checkAdmin();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'ID do animal inválido.'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        }
        
        $animalModel = new Animal();
        try {
            $animalModel->deactivate($id);
            $_SESSION['form_feedback'] = ['type' => 'success', 'message' => 'Animal desativado com sucesso! Ele não aparecerá mais na loja.'];
        } catch (Exception $e) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Erro ao desativar o animal: ' . $e->getMessage()];
        }

        header('Location: /index.php/admin/animais/listar');
        exit();
    }

    private function handleImageUpload(array $file, ?string $oldImage = null): array
    {
        $uploadDir = __DIR__ . '/../../public/imagem/';
        
        // Validação de segurança
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Formato de imagem inválido. Use JPG, PNG, GIF ou WEBP.'];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'O arquivo de imagem é muito grande (máximo 5MB).'];
        }

        // Gera um nome de arquivo seguro e único
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            // Se for uma atualização e a imagem antiga existir, remove-a
            if ($oldImage && file_exists($uploadDir . $oldImage)) {
                unlink($uploadDir . $oldImage);
            }
            return ['success' => true, 'filename' => $fileName];
        }

        return ['success' => false, 'error' => 'Falha ao mover o arquivo de imagem.'];
    }
}