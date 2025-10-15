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

            <?php if (!empty($animal['descricao'])): ?>
                <div class="description my-4">
                    <h5>Sobre este animal:</h5>
                    <p><?php echo nl2br(htmlspecialchars($animal['descricao'])); ?></p>
                </div>
            <?php endif; ?>
            
            <p><strong>Data de Nascimento:</strong> <?php echo date("d/m/Y", strtotime($animal['data_nascimento'])); ?></p>
            <p><strong>Em estoque:</strong> <?php echo htmlspecialchars($animal['estoque']); ?> unidades</p>

            <form action="/carrinho/add" method="post" class="mt-4">
                <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">
                <button type="submit" class="btn btn-primary btn-lg">Adotar</button>
            </form>
        </div>
    </div>

    <?php if (!empty($relatedAnimals)): ?>
        <hr class="my-5">
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Animais Relacionados</h2>
            </div>
            <?php foreach ($relatedAnimals as $relatedAnimal): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <?php 
                            $imageUrl = !empty($relatedAnimal['imagem_url']) ? '/imagem/' . $relatedAnimal['imagem_url'] : 'https://via.placeholder.com/300x200.png?text=Sem+Imagem';
                        ?>
                        <a href="/animal/show/<?php echo $relatedAnimal['id']; ?>">
                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($relatedAnimal['especie']); ?>" style="height: 225px; width: 100%; display: block;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($relatedAnimal['especie']); ?></h5>
                            <p class="card-text">R$ <?php echo number_format($relatedAnimal['preco'], 2, ',', '.'); ?></p>
                            <a href="/animal/show/<?php echo $relatedAnimal['id']; ?>" class="btn btn-sm btn-outline-secondary">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <h1>Animal não encontrado</h1>
    <p>O animal que você está procurando não existe ou não está mais disponível.</p>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
