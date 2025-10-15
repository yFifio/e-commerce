<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nossos Animais Exóticos</h1>
    <form action="/index.php" method="GET" class="d-flex">
        <input class="form-control me-2" type="search" name="q" placeholder="Buscar por espécie ou origem..." aria-label="Buscar" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
        <button class="btn btn-outline-success" type="submit">Buscar</button>
    </form>
</div>

<?php if (empty($animais)): ?>
    <div class="alert alert-info text-center" role="alert">
        Nenhum animal encontrado com os critérios de busca.
    </div>
<?php else: ?>
    <div class="vehicle-grid">
        <?php foreach ($animais as $animal): ?>
        <div class="vehicle-card">
            <a href="/index.php/animal?id=<?php echo $animal['id']; ?>">
                <img src="<?php echo htmlspecialchars(!empty($animal['imagem_url']) ? '/imagem/' . $animal['imagem_url'] : 'https://via.placeholder.com/300x200.png?text=Sem+Imagem'); ?>" alt="<?php echo htmlspecialchars($animal['especie']); ?>">
            </a>
            <div class="vehicle-card-content">
                <h3><a href="/index.php/animal?id=<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['especie']); ?></a></h3>
                <p>Origem: <?php echo htmlspecialchars($animal['origem']); ?></p>
                <form action="/index.php/carrinho/add" method="post">
                    <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
                    <div class="price">R$ <?php echo number_format($animal['preco'], 2, ',', '.'); ?></div>
                    <button type="submit" class="btn btn-primary">Adotar</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/layouts/footer.php'; ?>