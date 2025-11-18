<?php
require_once __DIR__ . '/../Models/Animal.php';

class AnimalController {
    /**
     * Exibe o formulário para adicionar um novo animal.
     */
    public function create() {
        require_once __DIR__ . '/../Views/animal/create.php';
    }

    /**
     * Processa os dados do formulário e salva o novo animal no banco de dados.
     */
    public function store() {
        // Usar FILTER_SANITIZE_SPECIAL_CHARS para permitir caracteres especiais de forma segura.
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_SPECIAL_CHARS);
        $raca = filter_input(INPUT_POST, 'raca', FILTER_SANITIZE_SPECIAL_CHARS);
        $idade = filter_input(INPUT_POST, 'idade', FILTER_SANITIZE_NUMBER_INT);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        if (!$nome || !$especie || !$usuario_id) {
            header('Location: /animais/novo?error=missing_data');
            exit();
        }

        $animalModel = new Animal();
        $animalModel->create($nome, $especie, $raca, $idade, $descricao, $usuario_id);
        header('Location: /dashboard?success=animal_added');
        exit();
    }

    public function show() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(404);
            echo "Animal não encontrado.";
            return;
        }

        $animalModel = new Animal();
        $animal = $animalModel->find($id);

        if ($animal) {
            $relatedAnimals = $animalModel->getRelated($animal['especie'], $animal['id']);
        } else {
            $relatedAnimals = [];
        }

        require_once __DIR__ . '/../Views/animal/show.php'; 
    }
}
