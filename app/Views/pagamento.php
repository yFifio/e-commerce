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
            <?php elseif ($metodo_pagamento === 'pix'): ?>
                <div class="alert alert-info text-center">
                    <p>Para concluir, faça um PIX para a chave abaixo ou leia o QR Code.</p>
                    <p><strong>Chave PIX (Celular):</strong> (44) 99904-3230</p>
                    <img src="/imagem/QRcodejpg.jpg" alt="QR Code PIX" class="img-fluid my-3" style="max-width: 150px;">
                    <p class="small">Este é um QR Code de simulação. Após a confirmação, clique no botão abaixo.</p>
                </div>
            <?php endif; ?>

            <hr class="my-4">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Confirmar Adoção</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const numeroCartaoInput = document.getElementById('numero_cartao');
    const validadeCartaoInput = document.getElementById('validade_cartao');

    if (numeroCartaoInput) {
        numeroCartaoInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = value;
        });
    }

    if (validadeCartaoInput) {
        validadeCartaoInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
});
</script>

<?php require __DIR__ . '/layouts/footer.php'; ?>