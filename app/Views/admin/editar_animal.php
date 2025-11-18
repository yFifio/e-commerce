<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h2 class="h4 mb-0">Editar Animal: <?= htmlspecialchars($animal['especie']) ?></h2>
                </div>
                <div class="card-body">
                    <a href="/index.php/admin/animais/listar" class="btn btn-secondary mb-3">Voltar para a Lista</a>

                    <?php
                    if (isset($_SESSION['form_feedback'])) {
                        $feedback = $_SESSION['form_feedback'];
                        echo '<div class="alert alert-' . htmlspecialchars($feedback['type']) . '" role="alert">' . htmlspecialchars($feedback['message']) . '</div>';
                        unset($_SESSION['form_feedback']);
                    }
                    ?>

                    <form action="/index.php/admin/animais/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($animal['id']) ?>">

                        <div class="mb-3">
                            <label for="especie" class="form-label">Espécie</label>
                            <input type="text" class="form-control" id="especie" name="especie" value="<?= htmlspecialchars($animal['especie']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="origem" class="form-label">Origem</label>
                            <input type="text" class="form-control" id="origem" name="origem" value="<?= htmlspecialchars($animal['origem']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($animal['descricao']) ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($animal['preco']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estoque" class="form-label">Quantidade em Estoque</label>
                                <input type="number" class="form-control" id="estoque" name="estoque" value="<?= htmlspecialchars($animal['estoque']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Atual</label>
                            <div>
                                <?php if (!empty($animal['imagem_url'])): ?>
                                    <img src="/imagem/<?= htmlspecialchars($animal['imagem_url']) ?>" alt="Foto atual" class="img-thumbnail mb-2" style="max-width: 150px;">
                                <?php else: ?>
                                    <p class="text-muted">Nenhuma imagem cadastrada.</p>
                                <?php endif; ?>
                            </div>
                            <label for="imagem" class="form-label">Alterar Foto do Animal (opcional)</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                            <div class="form-text">Envie uma nova imagem apenas se desejar substituí-la.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>

                    <?php if (isset($animal['ativo']) && $animal['ativo'] == 0): ?>
                        <hr class="my-4">
                        <div class="p-3 bg-light border rounded">
                            <h5 class="text-danger mb-3">Opções para Animal Inativo</h5>
                            <p>Este animal está inativo e não é exibido na loja. Você pode reativá-lo ou excluí-lo permanentemente.</p>
                            <div class="d-flex justify-content-start gap-2">
                                <!-- Formulário para Reativar -->
                                <form action="/index.php/admin/animais/reactivate" method="POST" onsubmit="return confirm('Tem certeza que deseja reativar este animal?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($animal['id']) ?>">
                                    <button type="submit" class="btn btn-success">Reativar Animal</button>
                                </form>
                                <!-- Formulário para Excluir -->
                                <form action="/index.php/admin/animais/delete" method="POST" onsubmit="return confirm('ATENÇÃO: Esta ação é irreversível. Tem certeza que deseja excluir permanentemente este animal?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($animal['id']) ?>">
                                    <button type="submit" class="btn btn-danger">Excluir Permanentemente</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
