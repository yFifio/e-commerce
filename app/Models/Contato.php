<?php
require_once __DIR__ . '/Model.php';

class Contato extends Model {

    public function countByPeriod(string $period = 'all_time', ?string $startDate = null, ?string $endDate = null) {
        $whereClause = $this->getDateWhereClause($period, 'data_envio', $startDate, $endDate);
        $query = "SELECT COUNT(*) FROM contato_mensagens" . $whereClause;
        $stmt = $this->db->query($query);
        return $stmt->fetchColumn() ?: 0;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM contato_mensagens ORDER BY data_envio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id) {
        $stmt = $this->db->prepare("DELETE FROM contato_mensagens WHERE id = ?");
        return $stmt->execute([$id]);
    }

}