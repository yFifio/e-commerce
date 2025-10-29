<?php require __DIR__ . '/layouts/header.php'; ?>
<h2>Meu Cesto de Adoção</h2>
<?php if (empty($carrinho)): ?>
    <p>Seu cesto está vazio.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Animal</th>
                <th scope="col">Preço Unitário</th>
                <th scope="col" class="text-center">Quantidade</th>
                <th scope="col" class="text-end">Subtotal</th>
                <th scope="col" class="text-center">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($carrinho as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['dados']['especie']); ?></td>
                    <td>R$ <?php echo number_format($item['dados']['preco'], 2, ',', '.'); ?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <form action="/index.php/carrinho/update" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $item['dados']['id']; ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="btn btn-sm btn-secondary">-</button>
                            </form>
                            <span class="mx-2"><?php echo $item['quantidade']; ?></span>
                            <form action="/index.php/carrinho/update" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $item['dados']['id']; ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="btn btn-sm btn-secondary">+</button>
                            </form>
                        </div>
                    </td>
                    <td class="text-end">R$ <?php echo number_format($item['dados']['preco'] * $item['quantidade'], 2, ',', '.'); ?></td>
                    <td class="text-center">
                        <form action="/index.php/carrinho/remove" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['dados']['id']; ?>">
                            <button type="submit" class="btn btn-link text-danger p-0" title="Remover item" style="vertical-align: middle;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php $total += $item['dados']['preco'] * $item['quantidade']; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th class="text-end">R$ <?php echo number_format($total, 2, ',', '.'); ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <div class="text-end">
        <a href="/index.php/carrinho/checkout" class="btn btn-success">Finalizar Adoção</a>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/layouts/footer.php'; ?>