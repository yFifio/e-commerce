<?php
require_once 'Model.php';

class Veiculo extends Model {
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM veiculos WHERE estoque > 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM veiculos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getDashboardData() {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM veiculos) as total_veiculos,
                (SELECT COUNT(*) FROM usuarios) as total_usuarios,
                (SELECT COUNT(*) FROM pedidos) as total_pedidos,
                (SELECT SUM(valor_total) FROM pedidos) as faturamento_total
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}