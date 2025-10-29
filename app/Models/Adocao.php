<?php
require_once __DIR__ . '/Model.php';

class Adocao extends Model {
    // O construtor e a propriedade $db são herdados de Model.php

    public function findByUserId(int $userId) {
        $sql = "
            SELECT
                a.id as adocao_id,
                a.data_adocao,
                a.valor_total,
                ai.quantidade,
                ai.preco_unitario,
                an.especie,
                an.imagem_url
            FROM adocoes a
            JOIN adocao_itens ai ON a.id = ai.adocao_id
            JOIN animais an ON ai.animal_id = an.id
            WHERE a.usuario_id = ?
            ORDER BY a.data_adocao DESC, a.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar itens por adoção
        return $this->groupAdoptionItems($results);
    }

    public function getRecentAdoptions(int $limit = 5, string $period = 'all_time', ?string $startDate = null, ?string $endDate = null) {
        $whereClause = $this->getDateWhereClause($period, 'a.data_adocao', $startDate, $endDate);

        $sql = "
            SELECT 
                a.id, 
                a.data_adocao, 
                a.valor_total, 
                u.nome as usuario_nome 
            FROM adocoes a
            JOIN usuarios u ON a.usuario_id = u.id"
            . $whereClause .
            "
            ORDER BY a.data_adocao DESC 
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDashboardSummary(string $period = 'all_time', ?string $startDate = null, ?string $endDate = null) {
        $whereClause = $this->getDateWhereClause($period, 'data_adocao', $startDate, $endDate);

        $queryAdocoes = "SELECT COUNT(*) FROM adocoes" . $whereClause;
        $stmtAdocoes = $this->db->query($queryAdocoes);
        $totalAdocoes = $stmtAdocoes->fetchColumn() ?: 0;

        $queryFaturamento = "SELECT SUM(valor_total) FROM adocoes" . $whereClause;
        $stmtFaturamento = $this->db->query($queryFaturamento);
        $faturamentoTotal = $stmtFaturamento->fetchColumn() ?: 0;

        return [
            'total_adocoes' => $totalAdocoes,
            'faturamento_total' => $faturamentoTotal,
        ];
    }

    private function groupAdoptionItems(array $results): array {
        $adocoes = [];
        foreach ($results as $row) {
            $adocaoId = $row['adocao_id'];
            if (!isset($adocoes[$adocaoId])) {
                $adocoes[$adocaoId] = [
                    'id' => $adocaoId,
                    'data_adocao' => $row['data_adocao'],
                    'valor_total' => $row['valor_total'],
                    'itens' => []
                ];
            }
            $adocoes[$adocaoId]['itens'][] = [
                'especie' => $row['especie'],
                'quantidade' => $row['quantidade'],
                'preco_unitario' => $row['preco_unitario']
            ];
        }
        return array_values($adocoes);
    }

}