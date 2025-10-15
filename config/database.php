<?php

class Database {
    private const DB_HOST = 'localhost';
    private const DB_USER = 'root';
    private const DB_PASS = '323099';
    private const DB_NAME = 'e-comercce';
    private const DB_CHARSET = 'utf8mb4';

    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Útil para não precisar especificar sempre
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
        } catch(PDOException $e) {
            die("Falha na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        self::$instance ??= new self();
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}