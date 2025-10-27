<?php
require_once __DIR__ . '/Model.php';

class Animal extends Model {
    public function getAll() {
        $searchTerm = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $sql = "SELECT * FROM animais WHERE estoque > 0";
        $params = [];

        if ($searchTerm) {
            $sql .= " AND (especie LIKE :searchTermEspecie OR origem LIKE :searchTermOrigem)";
            $params[':searchTermEspecie'] = '%' . $searchTerm . '%';
            $params[':searchTermOrigem'] = '%' . $searchTerm . '%';
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

    public function getRelated($especie, $currentId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM animais WHERE especie = ? AND id != ? AND estoque > 0 ORDER BY RAND() LIMIT 3"
        );
        $stmt->execute([$especie, $currentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($especie, $origem, $descricao, $preco, $estoque, $imagem_url, $data_nascimento) {
        $sql = "INSERT INTO animais (especie, origem, descricao, preco, estoque, imagem_url, data_nascimento) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$especie, $origem, $descricao, $preco, $estoque, $imagem_url, $data_nascimento]);
    }
}
