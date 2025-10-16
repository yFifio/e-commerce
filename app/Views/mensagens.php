<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mensagens de Contato</h2>
        <a href="/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
    </div>

    <?php if (empty($mensagens)): ?>
        <div class="alert alert-info">Nenhuma mensagem de contato recebida até o momento.</div>
    <?php else: ?>
        <div class="accordion" id="accordionMensagens">
            <?php foreach ($mensagens as $index => $msg): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-<?php echo $msg['id']; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $msg['id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $msg['id']; ?>">
                            <strong><?php echo htmlspecialchars($msg['assunto']); ?></strong>&nbsp;-&nbsp;
                            <span class="text-muted">De: <?php echo htmlspecialchars($msg['nome']); ?> (<?php echo htmlspecialchars($msg['email']); ?>) em <?php echo date('d/m/Y H:i', strtotime($msg['data_envio'])); ?></span>
                        </button>
                    </h2>
                    <div id="collapse-<?php echo $msg['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $msg['id']; ?>" data-bs-parent="#accordionMensagens">
                        <div class="accordion-body">
                            <?php echo nl2br(htmlspecialchars($msg['mensagem'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>