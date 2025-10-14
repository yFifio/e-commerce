<?php require __DIR__ . '/layouts/header.php'; ?>
<h2>Meu Cesto de Adoção</h2>
<?php if (empty($carrinho)): ?>
    <p>Seu cesto está vazio.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Animal</th>
                <th>Preço</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($carrinho as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['modelo']); ?></td>
                    <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <form action="/carrinho/remove" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                        </form>
                    </td>
                </tr>
                <?php $total += $item['preco']; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="1">Total</th>
                <th>R$ <?php echo number_format($total, 2, ',', '.'); ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <button class="btn btn-success">Finalizar Compra</button>
<?php endif; ?>

<?php require __DIR__ . '/layouts/footer.php'; ?>