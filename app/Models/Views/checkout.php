<?php require __DIR__ . '/layouts/header.php'; ?>

<h2>Finalizar Adoção</h2>

<div class="row">
    <div class="col-md-7">
        <h4>Resumo do Pedido</h4>
        <ul class="list-group mb-3">
            <?php 
            $total = 0;
            foreach ($carrinho as $item): 
                $total += $item['preco'];
            ?>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0"><?php echo htmlspecialchars($item['especie']); ?></h6>
                        <small class="text-muted">Origem: <?php echo htmlspecialchars($item['origem']); ?></small>
                    </div>
                    <span class="text-muted">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
                <span>Total (BRL)</span>
                <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
            </li>
        </ul>

        <form action="/carrinho/finalizar" method="POST">
            <p>Clique abaixo para confirmar sua adoção. (Isso é uma simulação de pagamento)</p>
            <button type="submit" class="w-100 btn btn-primary btn-lg">Confirmar Adoção e Pagar</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>