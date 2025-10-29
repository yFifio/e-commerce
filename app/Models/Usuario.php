<?php
require_once __DIR__ . '/Model.php';

class Usuario extends Model {
    public function create($nome, $email, $senha) {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM usuarios");
        return $stmt->fetchColumn();
    }
}