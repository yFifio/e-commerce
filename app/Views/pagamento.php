<?php require __DIR__ . '/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-7">
        <h2>Detalhes do Pagamento</h2>
        <p>Método selecionado: <strong><?php echo htmlspecialchars($metodo_pagamento_label); ?></strong></p>

        <form action="/carrinho/finalizar" method="POST">
            <input type="hidden" name="metodo_pagamento" value="<?php echo htmlspecialchars($metodo_pagamento); ?>">

            <?php if ($metodo_pagamento === 'cartao_credito'): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados do Cartão de Crédito (Simulação)</h5>
                        <div class="mb-3">
                            <label for="nome_cartao" class="form-label">Nome no Cartão</label>
                            <input type="text" class="form-control" id="nome_cartao" name="nome_cartao" required>
                        </div>
                        <div class="mb-3">
                            <label for="numero_cartao" class="form-label">Número do Cartão</label>
                            <input type="text" class="form-control" id="numero_cartao" name="numero_cartao" placeholder="0000 0000 0000 0000" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validade_cartao" class="form-label">Validade (MM/AA)</label>
                                <input type="text" class="form-control" id="validade_cartao" name="validade_cartao" placeholder="12/28" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cvv_cartao" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv_cartao" name="cvv_cartao" placeholder="123" required>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($metodo_pagamento === 'boleto'): ?>
                <div class="alert alert-info">
                    <p>Ao clicar em "Confirmar Adoção", um boleto simulado será gerado.</p>
                    <p>Em um sistema real, você seria redirecionado para a página de visualização do boleto.</p>
                </div>
            <?php endif; ?>

            <hr class="my-4">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Confirmar Adoção</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>