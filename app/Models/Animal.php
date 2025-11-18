<?php
require_once __DIR__ . '/Model.php';

class Animal extends Model {
    // O construtor e a propriedade $db são herdados de Model.php

    public function getAll(bool $adminView = false) {
        $sql = "SELECT * FROM animais";
        if (!$adminView) { // Para a loja pública, mostrar apenas ativos e com estoque
            $sql .= " WHERE estoque > 0 AND ativo = 1";
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $term) {
        $searchTerm = '%' . $term . '%';
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE (especie LIKE ? OR origem LIKE ?) AND estoque > 0 AND ativo = 1 ORDER BY id DESC");
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRelated(string $especie, int $excludeId, int $limit = 3) {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE especie = ? AND id != ? AND estoque > 0 AND ativo = 1 ORDER BY RAND() LIMIT ?");
        $stmt->bindValue(1, $especie, PDO::PARAM_STR);
        $stmt->bindValue(2, $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $especie, ?string $origem, ?string $descricao, float $preco, int $estoque, ?string $imagem_url) {
        $sql = "INSERT INTO animais (especie, origem, descricao, preco, estoque, imagem_url, data_cadastro) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$especie, $origem, $descricao, $preco, $estoque, $imagem_url]);
    }

    public function decreaseStock(int $id, int $quantity) {
        $stmt = $this->db->prepare("UPDATE animais SET estoque = estoque - ? WHERE id = ? AND estoque >= ?");
        return $stmt->execute([$quantity, $id, $quantity]);
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM animais WHERE estoque > 0 AND ativo = 1");
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

    /**
     * Atualiza os dados de um animal no banco de dados.
     */
    public function update(int $id, string $especie, ?string $origem, ?string $descricao, float $preco, int $estoque, ?string $imagem_url)
    {
        $sql = "UPDATE animais 
                SET especie = ?, origem = ?, descricao = ?, preco = ?, estoque = ?, imagem_url = ?
                WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$especie, $origem, $descricao, $preco, $estoque, $imagem_url, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar animal: " . $e->getMessage());
            throw new Exception("Não foi possível atualizar o animal.");
        }
    }

    /**
     * Desativa um animal no banco de dados.
     */
    public function deactivate(int $id): bool {
        $sql = "UPDATE animais SET ativo = 0 WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao desativar animal: " . $e->getMessage());
            throw new Exception("Não foi possível desativar o animal.");
        }
    }

    /**
     * Reativa um animal no banco de dados.
     */
    public function reactivate(int $id): bool {
        $sql = "UPDATE animais SET ativo = 1 WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao reativar animal: " . $e->getMessage());
            throw new Exception("Não foi possível reativar o animal.");
        }
    }

    /**
     * Exclui permanentemente um animal do banco de dados.
     * CUIDADO: Esta ação não pode ser desfeita.
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM animais WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao excluir animal: " . $e->getMessage());
            throw new Exception("Não foi possível excluir o animal.");
        }
    }
}