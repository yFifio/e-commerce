<?php require __DIR__ . '/../Views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">Fale Conosco</h2>
                </div>
                <div class="card-body">
                    <p>Tem alguma dúvida, sugestão ou precisa de ajuda? Preencha o formulário abaixo e nossa equipe entrará em contato.</p>

                    <?php
                    if (isset($_SESSION['form_feedback'])) {
                        $feedback = $_SESSION['form_feedback'];
                        echo '<div class="alert alert-' . htmlspecialchars($feedback['type']) . '" role="alert">' . htmlspecialchars($feedback['message']) . '</div>';
                        unset($_SESSION['form_feedback']);
                    }
                    ?>

                    <form action="/contato" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Seu E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto</label>
                            <input type="text" class="form-control" id="assunto" name="assunto" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../Views/layouts/footer.php'; ?>