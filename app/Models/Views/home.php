<?php require __DIR__ . '/layouts/header.php'; ?>

<h1>Nossos Animais Exóticos</h1>
<div class="vehicle-grid">
    <?php foreach ($animais as $animal): ?>
    <div class="vehicle-card">
        <?php 
            $imageUrl = !empty($animal['imagem_url']) ? $animal['imagem_url'] : 'https://via.placeholder.com/300x200.png?text=Sem+Imagem';
        ?>
        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($animal['especie']); ?>">
        <div class="vehicle-card-content">
            <h3><?php echo htmlspecialchars($animal['especie']); ?></h3>
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