<?php
require_once __DIR__ . '/Model.php';

class Animal extends Model {
    public function getAll() {
        $searchTerm = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $sql = "SELECT * FROM animais WHERE estoque > 0";
        $params = [];

        if ($searchTerm) {
            $sql .= " AND (especie LIKE :searchTerm OR origem LIKE :searchTerm)";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getDashboardData() {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM animais) as total_animais,
                (SELECT COUNT(*) FROM usuarios) as total_usuarios,
                (SELECT COUNT(*) FROM adocoes) as total_adocoes,
                (SELECT SUM(valor_total) FROM adocoes) as faturamento_total
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function decreaseStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE animais SET estoque = estoque - ? WHERE id = ?");
        return $stmt->execute([$quantity, $id]);
    }
}