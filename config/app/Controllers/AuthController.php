<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    public function register() {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $usuarioModel = new Usuario();
        if ($usuarioModel->create($nome, $email, $senha)) {
            header('Location: /login');
        } else {
            echo "Erro ao registrar.";
        }
    }

    public function login() {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['user_role'] = $usuario['role'];
            header('Location: /');
        } else {
            header('Location: /login?error=1');
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../Views/login.php';
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../Views/register.php';
    }
}