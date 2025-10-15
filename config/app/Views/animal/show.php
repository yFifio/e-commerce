<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php if ($animal): ?>
    <div class="row">
        <div class="col-md-8">
            <?php 
                $imageUrl = !empty($animal['imagem_url']) ? '/imagem/' . $animal['imagem_url'] : 'https://via.placeholder.com/600x400.png?text=Sem+Imagem';
            ?>
            <img src="<?php echo htmlspecialchars($imageUrl); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($animal['especie']); ?>">
        </div>
        <div class="col-md-4">
            <h1><?php echo htmlspecialchars($animal['especie']); ?></h1>
            <p class="lead">Origem: <?php echo htmlspecialchars($animal['origem']); ?></p>
            <h2 class="price my-4">R$ <?php echo number_format($animal['preco'], 2, ',', '.'); ?></h2>
            
            <p><strong>Data de Nascimento:</strong> <?php echo date("d/m/Y", strtotime($animal['data_nascimento'])); ?></p>
            <p><strong>Em estoque:</strong> <?php echo htmlspecialchars($animal['estoque']); ?> unidades</p>

            <form action="/carrinho/add" method="post" class="mt-4">
                <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
                <button type="submit" class="btn btn-primary btn-lg">Adotar</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <h1>Animal não encontrado</h1>
    <p>O animal que você está procurando não existe ou não está mais disponível.</p>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
