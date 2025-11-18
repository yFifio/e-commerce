<?php
require_once __DIR__ . '/../Models/Animal.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Adocao.php';
require_once __DIR__ . '/../Models/Contato.php';

class DashboardController {
    private $db;

    private function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login?acesso_negado=1');
            exit();
        }
    }

    public function index() {
        $this->checkAdmin();
        require_once __DIR__ . '/../Views/dashboard.php';
    }

    public function data() {
        $this->checkAdmin();
        header('Content-Type: application/json');
        
        $period = filter_input(INPUT_GET, 'period', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'all_time';
        $startDate = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $endDate = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $animalModel = new Animal();
        $adocaoModel = new Adocao();
        $this->db = Database::getInstance()->getConnection(); // Adicionado para inicializar o DB
        $usuarioModel = new Usuario();
        $contatoModel = new Contato();

        $data = $adocaoModel->getDashboardSummary($period, $startDate, $endDate);
        $data['total_animais'] = $animalModel->countAll();
        $data['total_usuarios'] = $usuarioModel->countAll();
        $data['total_messages'] = $contatoModel->countByPeriod($period, $startDate, $endDate);

        $data['recent_animals'] = $animalModel->getRecentlyAdded(5, $period, $startDate, $endDate);
        $data['recent_adoptions'] = $adocaoModel->getRecentAdoptions(5, $period, $startDate, $endDate);

        echo json_encode($data);
    }

}