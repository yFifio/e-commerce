<?php require __DIR__ . '/layouts/header.php'; ?>

<h2>Endereço de Entrega</h2>
<p class="lead">Por favor, preencha os dados para a entrega da sua adoção.</p>

<div class="row">
    <div class="col-md-8">
        <form action="/pagamento" method="POST">
            <!-- Campo oculto para manter o método de pagamento selecionado -->
            <input type="hidden" name="metodo_pagamento" value="<?php echo htmlspecialchars($metodo_pagamento); ?>">

            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" required>
                </div>

                <div class="col-sm-12">
                    <label for="logradouro" class="form-label">Endereço (Rua, Av.)</label>
                    <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Rua dos Bobos" required>
                </div>

                <div class="col-sm-4">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" class="form-control" id="numero" name="numero" placeholder="123" required>
                </div>

                <div class="col-sm-8">
                    <label for="complemento" class="form-label">Complemento <span class="text-muted">(Opcional)</span></label>
                    <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Apto, casa, etc.">
                </div>

                <div class="col-md-5"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" name="bairro" required></div>
                <div class="col-md-4"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" name="cidade" required></div>
                <div class="col-md-3"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" name="estado" required></div>
            </div>

            <hr class="my-4">
            <button class="w-100 btn btn-primary btn-lg" type="submit">Continuar para Pagamento</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>