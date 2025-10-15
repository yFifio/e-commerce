<?php
require_once __DIR__ . '/../Models/Animal.php';

class HomeController {
    public function index() {
        $animalModel = new Animal();
        $animais = $animalModel->getAll();
        require_once __DIR__ . '/../Views/home.php';
    }
}