<?php
require_once __DIR__ . '/../Models/Animal.php';

class HomeController {
    public function index() {
        $animalModel = new Animal();
        $searchTerm = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($searchTerm) {
            $animais = $animalModel->search($searchTerm);
        } else {
            $animais = $animalModel->getAll();
        }
        
        require_once __DIR__ . '/../Views/home.php';
    }
}