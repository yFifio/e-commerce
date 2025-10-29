<?php
require_once __DIR__ . '/../../config/database.php';

abstract class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function getDateWhereClause(string $period, string $dateColumn, ?string $startDate = null, ?string $endDate = null): string {
        if ($startDate && $endDate) {
            // Adiciona +1 dia ao endDate para incluir o dia inteiro na busca (de 00:00 a 23:59)
            return " WHERE $dateColumn >= '$startDate 00:00:00' AND $dateColumn < DATE_ADD('$endDate', INTERVAL 1 DAY)";
        } else {
            return match ($period) {
                '7_days' => " WHERE $dateColumn >= DATE_SUB(NOW(), INTERVAL 7 DAY)",
                '30_days' => " WHERE $dateColumn >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
                'last_month' => " WHERE YEAR($dateColumn) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH($dateColumn) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)",
                'this_month' => " WHERE YEAR($dateColumn) = YEAR(NOW()) AND MONTH($dateColumn) = MONTH(NOW())",
                'this_year' => " WHERE YEAR($dateColumn) = YEAR(NOW())",
                default => "",
            };
        }
    }
}