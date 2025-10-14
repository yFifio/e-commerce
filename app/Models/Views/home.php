<?php require __DIR__ . '/layouts/header.php'; ?>

<h1>Nossos Veículos</h1>
<div class="row">
    <?php foreach ($veiculos as $veiculo): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="<?php echo htmlspecialchars($veiculo['imagem_url']); ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?></h5>
                <p class="card-text">Ano: <?php echo htmlspecialchars($veiculo['ano']); ?></p>
                <p class="card-text"><strong>Preço: R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></strong></p>
                <form action="/carrinho/add" method="post">
                    <input type="hidden" name="id" value="<?php echo $veiculo['id']; ?>">
                    <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>