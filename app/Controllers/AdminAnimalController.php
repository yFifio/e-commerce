<?php

require_once __DIR__ . '/../Models/Animal.php';

class AdminAnimalController
{
    public function __construct()
    {
        // Proteção para garantir que apenas administradores acessem este controlador
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die('Acesso negado.');
        }
    }

    /**
     * Exibe a lista de todos os animais para o administrador.
     */
    public function listAll()
    {
        $animalModel = new Animal();
        $animais = $animalModel->getAll(true); // true para buscar todos, incluindo inativos
        require_once __DIR__ . '/../Views/admin/listar_animais.php';
    }

    /**
     * Exibe o formulário para adicionar um novo animal.
     */
    public function showAddForm()
    {
        require_once __DIR__ . '/../Views/admin/adicionar_animal.php';
    }

    /**
     * Processa o formulário de criação de um novo animal.
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_SPECIAL_CHARS);
        $origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
        $estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);
        $imagem_url = null;

        // Lógica de upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/imagem/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetPath)) {
                $imagem_url = $fileName;
            }
        }

        $animalModel = new Animal();
        $animalModel->create($especie, $origem, $descricao, $preco, $estoque, $imagem_url);

        $_SESSION['list_feedback'] = ['type' => 'success', 'message' => 'Animal adicionado com sucesso!'];
        header('Location: /index.php/admin/animais/listar');
        exit();
    }

    /**
     * Exibe o formulário para editar um animal existente.
     */
    public function showEditForm()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(400);
            die('ID do animal inválido.');
        }

        $animalModel = new Animal();
        $animal = $animalModel->find($id);

        if (!$animal) {
            http_response_code(404);
            die('Animal não encontrado.');
        }

        require_once __DIR__ . '/../Views/admin/editar_animal.php';
    }

    /**
     * Processa o formulário de atualização de um animal.
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_SPECIAL_CHARS);
        $origem = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
        $estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);

        $animalModel = new Animal();
        $animalAtual = $animalModel->find($id);

        if (!$animalAtual) {
            $_SESSION['list_feedback'] = ['type' => 'danger', 'message' => 'Animal não encontrado para atualização.'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        }

        $imagem_url = $animalAtual['imagem_url']; // Manter a imagem atual por padrão

        // Lógica de upload de nova imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/imagem/';
            
            // Remove a imagem antiga se uma nova for enviada
            if (!empty($imagem_url) && file_exists($uploadDir . $imagem_url)) {
                unlink($uploadDir . $imagem_url);
            }

            $fileName = uniqid() . '-' . basename($_FILES['imagem']['name']);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetPath)) {
                $imagem_url = $fileName; // Atualiza para o nome da nova imagem
            }
        }

        $animalModel->update($id, $especie, $origem, $descricao, $preco, $estoque, $imagem_url);

        $_SESSION['form_feedback'] = ['type' => 'success', 'message' => 'Animal atualizado com sucesso!'];
        header('Location: /index.php/admin/animais/editar?id=' . $id);
        exit();
    }

    /**
     * Reativa um animal que estava inativo.
     */
    public function reactivate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'ID do animal inválido.'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        }

        $animalModel = new Animal();
        try {
            if ($animalModel->reactivate($id)) {
                $_SESSION['list_feedback'] = ['type' => 'success', 'message' => 'Animal reativado com sucesso!'];
            } else {
                $_SESSION['list_feedback'] = ['type' => 'danger', 'message' => 'Não foi possível reativar o animal.'];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['list_feedback'] = ['type' => 'danger', 'message' => 'Ocorreu um erro ao reativar o animal.'];
        }

        header('Location: /index.php/admin/animais/listar');
        exit();
    }

    /**
     * Desativa um animal.
     */
    public function deactivate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['list_feedback'] = ['type' => 'danger', 'message' => 'ID do animal inválido.'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        }

        $animalModel = new Animal();
        $animalModel->deactivate($id);

        $_SESSION['list_feedback'] = ['type' => 'info', 'message' => 'Animal desativado com sucesso.'];
        header('Location: /index.php/admin/animais/listar');
        exit();
    }

    /**
     * Exclui permanentemente um animal do banco de dados.
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'ID do animal inválido.'];
            header('Location: /index.php/admin/animais/listar');
            exit();
        }

        $animalModel = new Animal();
        try {
            // Opcional: Excluir a imagem associada do servidor
            $animal = $animalModel->find($id);
            if ($animal && !empty($animal['imagem_url'])) {
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/imagem/' . $animal['imagem_url'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            if ($animalModel->delete($id)) {
                $_SESSION['list_feedback'] = ['type' => 'success', 'message' => 'Animal excluído permanentemente.'];
            } else {
                $_SESSION['list_feedback'] = ['type' => 'danger', 'message' => 'Não foi possível excluir o animal.'];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['list_feedback'] = ['type' => 'danger', 'message' => 'Ocorreu um erro ao excluir o animal.'];
        }

        header('Location: /index.php/admin/animais/listar');
        exit();
    }
}