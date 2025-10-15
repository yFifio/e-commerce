<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nossos Animais Exóticos</h1>
    <form action="/" method="GET" class="d-flex">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar por espécie ou origem..." aria-label="Buscar" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
        <button class="btn btn-outline-success" type="submit">Buscar</button>
    </form>
</div>

<div class="vehicle-grid">
    <?php foreach ($animais as $animal): ?>
    <div class="vehicle-card">
        <a href="/animal?id=<?php echo $animal['id']; ?>">
            <img src="<?php echo htmlspecialchars(!empty($animal['imagem_url']) ? $animal['imagem_url'] : 'https://via.placeholder.com/300x200.png?text=Sem+Imagem'); ?>" alt="<?php echo htmlspecialchars($animal['especie']); ?>">
        </a>
        <div class="vehicle-card-content">
            <h3><a href="/animal?id=<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['especie']); ?></a></h3>
            <p>Origem: <?php echo htmlspecialchars($animal['origem']); ?></p>
            <div class="price">R$ <?php echo number_format($animal['preco'], 2, ',', '.'); ?></div>
            <form action="/carrinho/add" method="post">
                <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
                <button type="submit" class="btn">Adotar</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>