<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Gerenciar Animais</h2>
                </div>
                <div class="card-body">
                    <a href="/dashboard" class="btn btn-secondary mb-3">Voltar ao Dashboard</a>
                    <a href="/index.php/admin/animais/novo" class="btn btn-success mb-3">Adicionar Novo Animal</a>

                    <?php
                    if (isset($_SESSION['form_feedback'])) {
                        $feedback = $_SESSION['form_feedback'];
                        echo '<div class="alert alert-' . htmlspecialchars($feedback['type']) . '" role="alert">' . htmlspecialchars($feedback['message']) . '</div>';
                        unset($_SESSION['form_feedback']);
                    }
                    ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Espécie</th>
                                    <th scope="col">Estoque</th>
                                    <th scope="col">Preço</th>
                                    <th scope="col" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($animais)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum animal cadastrado.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($animais as $animal): ?>
                                        <tr class="<?= $animal['ativo'] ? '' : 'table-secondary text-muted' ?>">
                                            <th scope="row"><?= htmlspecialchars($animal['id']) ?></th>
                                            <td>
                                                <img src="/imagem/<?= htmlspecialchars($animal['imagem_url'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($animal['especie']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                            </td>
                                            <td><?= htmlspecialchars($animal['especie']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($animal['estoque']) ?>
                                                <?= $animal['ativo'] ? '' : '<span class="badge bg-danger ms-2">Inativo</span>' ?>
                                            </td>
                                            <td>R$ <?= htmlspecialchars(number_format($animal['preco'], 2, ',', '.')) ?></td>
                                            <td class="text-center">
                                                <a href="/index.php/admin/animais/editar?id=<?= $animal['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <form action="/index.php/admin/animais/deactivate" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja DESATIVAR este animal? Ele não aparecerá mais na loja.');">
                                                    <input type="hidden" name="id" value="<?= $animal['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Deletar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>