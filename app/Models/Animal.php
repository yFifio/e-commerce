<?php
require_once 'Model.php';

class Animal extends Model {
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE estoque > 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = ?");
        $stmt->execute([$id]);
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