<?php
require_once __DIR__ . '/Model.php';

class Adocao extends Model {
    public function findByUserId($usuario_id) {
        $stmt = $this->db->prepare("
            SELECT 
                a.id, 
                a.valor_total, 
                a.data_adocao,
                GROUP_CONCAT(an.especie SEPARATOR ', ') as itens
            FROM adocoes a
            JOIN adocao_itens ai ON a.id = ai.adocao_id
            JOIN animais an ON ai.animal_id = an.id
            WHERE a.usuario_id = :usuario_id
            GROUP BY a.id, a.valor_total, a.data_adocao
            ORDER BY a.data_adocao DESC
        ");
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    public function commit() {
        $this->db->commit();
    }

    public function rollback() {
        $this->db->rollBack();
    }

    public function create($usuario_id, $valor_total, $itens) {
        $stmt = $this->db->prepare(
            "INSERT INTO adocoes (usuario_id, valor_total) VALUES (:usuario_id, :valor_total)"
        );
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':valor_total' => $valor_total
        ]);
        
        $adocaoId = $this->db->lastInsertId();

        if (!$adocaoId) {
            return false;
        }

        $stmtItem = $this->db->prepare(
            "INSERT INTO adocao_itens (adocao_id, animal_id, quantidade, preco_unitario) VALUES (:adocao_id, :animal_id, :quantidade, :preco_unitario)"
        );

        foreach ($itens as $item) {
            $stmtItem->execute([
                ':adocao_id' => $adocaoId,
                ':animal_id' => $item['id'],
                ':quantidade' => $item['quantidade'],
                ':preco_unitario' => $item['preco']
            ]);
        }

        return $adocaoId;
    }
}