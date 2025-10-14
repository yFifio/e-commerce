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
}