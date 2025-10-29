<?php
require_once __DIR__ . '/../Models/Animal.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Adocao.php'; // Adicionado para buscar adoções recentes
require_once __DIR__ . '/../Models/Contato.php'; // Adicionado para buscar mensagens

class DashboardController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die("Acesso negado. Você não tem permissão para ver esta página.");
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        require_once __DIR__ . '/../Views/dashboard.php';
    }

    public function data() {
        header('Content-Type: application/json');
        
        $period = filter_input(INPUT_GET, 'period', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'all_time';
        $startDate = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $endDate = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $animalModel = new Animal();
        $adocaoModel = new Adocao();
        $usuarioModel = new Usuario();
        $contatoModel = new Contato();

        // Buscar cada dado de seu respectivo model
        $data = $adocaoModel->getDashboardSummary($period, $startDate, $endDate);
        $data['total_animais'] = $animalModel->countAll();
        $data['total_usuarios'] = $usuarioModel->countAll();
        $data['total_messages'] = $contatoModel->countByPeriod($period, $startDate, $endDate);

        // Novas funcionalidades
        $data['recent_animals'] = $animalModel->getRecentlyAdded(5, $period, $startDate, $endDate);
        $data['recent_adoptions'] = $adocaoModel->getRecentAdoptions(5, $period, $startDate, $endDate);

        echo json_encode($data);
    }

}