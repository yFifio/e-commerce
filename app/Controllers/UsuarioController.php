<?php
require_once __DIR__ . '/../Models/Adocao.php';

class UsuarioController {
    public function showAdocoes() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit();
        }

        $adocaoModel = new Adocao();
        $adocoes = $adocaoModel->findByUserId($_SESSION['usuario_id']);

        require_once __DIR__ . '/../Views/usuario/adocoes.php';
    }

    public function showAdocaoSucesso() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit();
        }
        $adocao_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        require_once __DIR__ . '/../Views/usuario/adocao_sucesso.php';
    }
}