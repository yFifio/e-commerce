<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5 text-center">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Adoção realizada com sucesso!</h4>
        <p>Obrigado por escolher um de nossos animais exóticos. O seu pedido de adoção número <strong>#<?php echo htmlspecialchars($adocao_id ?? 'N/A'); ?></strong> foi processado.</p>
        <hr>
        <p class="mb-0">Você pode ver os detalhes da sua adoção na sua área de cliente.</p>
    </div>
    <a href="/minhas-adocoes" class="btn btn-primary mt-3">Ver Minhas Adoções</a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>