<?php require __DIR__ . '/../layouts/header.php'; ?>

<h2>Minhas Adoções</h2>

<?php if (empty($adocoes)): ?>
    <p>Você ainda não realizou nenhuma adoção.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Pedido #</th>
                    <th scope="col">Data</th>
                    <th scope="col">Itens</th>
                    <th scope="col">Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adocoes as $adocao): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($adocao['id']); ?></th>
                        <td><?php echo date("d/m/Y H:i", strtotime($adocao['data_adocao'])); ?></td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($adocao['itens'] as $item): ?>
                                    <li>
                                        <?php echo htmlspecialchars($item['quantidade']); ?>x <?php echo htmlspecialchars($item['especie']); ?>
                                        (R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?> cada)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>R$ <?php echo number_format($adocao['valor_total'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>