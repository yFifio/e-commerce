<?php
require_once __DIR__ . '/Model.php';

class Animal extends Model {
    // O construtor e a propriedade $db são herdados de Model.php

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM animais WHERE estoque > 0 ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRelated(string $especie, int $excludeId, int $limit = 3) {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE especie = ? AND id != ? AND estoque > 0 ORDER BY RAND() LIMIT ?");
        $stmt->bindValue(1, $especie, PDO::PARAM_STR);
        $stmt->bindValue(2, $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $especie, ?string $origem, ?string $descricao, float $preco, int $estoque, ?string $imagem_url, string $data_nascimento) {
        $sql = "INSERT INTO animais (especie, origem, descricao, preco, estoque, imagem_url, data_nascimento, data_cadastro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$especie, $origem, $descricao, $preco, $estoque, $imagem_url, $data_nascimento]);
    }

    public function decreaseStock(int $id, int $quantity) {
        $stmt = $this->db->prepare("UPDATE animais SET estoque = estoque - ? WHERE id = ? AND estoque >= ?");
        return $stmt->execute([$quantity, $id, $quantity]);
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM animais WHERE estoque > 0");
        return $stmt->fetchColumn();
    }

    public function getRecentlyAdded(int $limit = 5, string $period = 'all_time', ?string $startDate = null, ?string $endDate = null) {
        $whereClause = $this->getDateWhereClause($period, 'data_cadastro', $startDate, $endDate);

        $sql = "
            SELECT id, especie, imagem_url, data_nascimento 
            FROM animais"
            . $whereClause .
            " ORDER BY data_cadastro DESC 
            LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}