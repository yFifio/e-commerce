<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    public function register() {
        // Usar filter_input para mais segurança ao obter dados do POST
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha']; // A senha será tratada com password_hash

        // Validar se os dados foram recebidos corretamente
        if (!$nome || !$email || !$senha) {
            header('Location: /register?error=invalid_data');
            exit();
        }

        $usuarioModel = new Usuario();

        // 1. Verificar se o e-mail já existe no banco de dados
        if ($usuarioModel->findByEmail($email)) {
            // Se existir, redireciona de volta para o formulário com um erro
            header('Location: /register?error=email_exists');
            exit();
        }

        // 2. Se não existir, tenta criar o usuário
        if ($usuarioModel->create($nome, $email, $senha)) {
            header('Location: /login');
        }
    }

    public function login() {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha']; // Não filtramos a senha para não remover caracteres especiais

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['user_role'] = $usuario['role'];
            header('Location: /');
        } else {
            header('Location: /login?error=invalid_credentials');
        }
    }

    public function logout() {
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