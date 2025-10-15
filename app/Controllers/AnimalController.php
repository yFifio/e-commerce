<?php
require_once __DIR__ . '/../Models/Animal.php';

class AnimalController {
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
