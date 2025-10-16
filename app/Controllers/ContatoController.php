<?php
require_once __DIR__ . '/../Models/Model.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ContatoController {

    /**
     * Exibe o formulário de contato.
     */
    public function showForm() {
        require_once __DIR__ . '/../Views/form.php';
    }

    /**
     * Processa o envio do formulário de contato.
     */
    public function send() {
        $db = Database::getInstance()->getConnection();
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($nome) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($assunto) || empty($mensagem)) {
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Por favor, preencha todos os campos corretamente.'];
            header('Location: /contato');
            exit();
        }

        try {
            $stmt = $db->prepare("INSERT INTO contato_mensagens (nome, email, assunto, mensagem) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $assunto, $mensagem]);
            
            $_SESSION['form_feedback'] = ['type' => 'success', 'message' => 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.'];
            header('Location: /contato');
            exit();
        } catch (Exception $e) {
            // Em um ambiente de produção, seria bom logar o erro.
            $_SESSION['form_feedback'] = ['type' => 'danger', 'message' => 'Ocorreu um erro ao enviar sua mensagem. Tente novamente mais tarde.'];
            header('Location: /contato');
            exit();
        }
    }

    /**
     * Exibe as mensagens de contato para o admin.
     */
    public function showMessages() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die("Acesso negado.");
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM contato_mensagens ORDER BY data_envio DESC");
        $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Marcar mensagens como lidas (opcional, mas útil)
        // $db->query("UPDATE contato_mensagens SET lido = 1 WHERE lido = 0");

        require_once __DIR__ . '/../Views/mensagens.php';
    }
}