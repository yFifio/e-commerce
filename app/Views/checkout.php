<?php require __DIR__ . '/layouts/header.php'; ?>

<h2>Finalizar Adoção</h2>

<div class="row">
    <div class="col-md-7">
        <h4>Resumo do Cesto</h4>
        <ul class="list-group mb-3">
            <?php
            $total = 0;
            foreach ($carrinho as $id => $item) {
                $subtotal = $item['dados']['preco'] * $item['quantidade'];
                $total += $subtotal;
            ?>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0"><?php echo htmlspecialchars($item['dados']['especie']); ?> (x<?php echo $item['quantidade']; ?>)</h6>
                        <small class="text-muted">Origem: <?php echo htmlspecialchars($item['dados']['origem']); ?></small>
                    </div>
                    <span class="text-muted">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                </li>
            <?php } ?>
            <li class="list-group-item d-flex justify-content-between">
                <span>Total (BRL)</span>
                <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
            </li>
        </ul>
    </div>

    <div class="col-md-5">
        <h4>Forma de Pagamento</h4>
        <form action="/index.php/pagamento" method="post">
            <div class="my-3">
                <div class="form-check">
                    <input id="credito" name="metodo_pagamento" type="radio" class="form-check-input" value="cartao_credito" checked required>
                    <label class="form-check-label" for="credito">Cartão de crédito</label>
                </div>
                <div class="form-check">
                    <input id="boleto" name="metodo_pagamento" type="radio" class="form-check-input" value="boleto" required>
                    <label class="form-check-label" for="boleto">Boleto</label>
                </div>
            </div>
            <hr class="my-4">
            <button class="w-100 btn btn-primary btn-lg" type="submit">Continuar para pagamento</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>
