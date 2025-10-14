<?php require __DIR__ . '/layouts/header.php'; ?>

<h1>Nossos Animais Exóticos</h1>
<div class="vehicle-grid">
    <?php foreach ($veiculos as $veiculo): ?>
    <div class="vehicle-card">
        <img src="<?php echo htmlspecialchars($veiculo['imagem_url']); ?>" alt="<?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?>">
        <div class="vehicle-card-content">
            <h3><?php echo htmlspecialchars($veiculo['modelo']); ?></h3>
            <p>Origem: <?php echo htmlspecialchars($veiculo['marca']); ?></p>
            <div class="price">R$ <?php echo number_format($veiculo['preco'], 2, ',', '.'); ?></div>
            <form action="/carrinho/add" method="post">
                <input type="hidden" name="id" value="<?php echo $veiculo['id']; ?>">
                <button type="submit" class="btn">Adotar</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>