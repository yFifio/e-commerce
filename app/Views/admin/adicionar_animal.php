<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h2 class="h4 mb-0">Adicionar Novo Animal</h2>
                </div>
                <div class="card-body">
                    <a href="/dashboard" class="btn btn-secondary mb-3">Voltar ao Dashboard</a>

                    <?php
                    if (isset($_SESSION['form_feedback'])) {
                        $feedback = $_SESSION['form_feedback'];
                        echo '<div class="alert alert-' . htmlspecialchars($feedback['type']) . '" role="alert">' . htmlspecialchars($feedback['message']) . '</div>';
                        unset($_SESSION['form_feedback']);
                    }
                    ?>

                    <form action="/index.php/admin/animais/create" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="especie" class="form-label">Espécie</label>
                            <input type="text" class="form-control" id="especie" name="especie" required>
                        </div>
                        <div class="mb-3">
                            <label for="origem" class="form-label">Origem</label>
                            <input type="text" class="form-control" id="origem" name="origem">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estoque" class="form-label">Quantidade em Estoque</label>
                                <input type="number" class="form-control" id="estoque" name="estoque" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Foto do Animal</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Animal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>