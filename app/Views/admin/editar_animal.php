<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h2 class="h4 mb-0">Editar Animal: <?= htmlspecialchars($animal['especie']) ?></h2>
                </div>
                <div class="card-body">
                    <a href="/index.php/admin/animais/listar" class="btn btn-secondary mb-3">Voltar para a Lista</a>

                    <?php
                    if (isset($_SESSION['form_feedback'])) {
                        $feedback = $_SESSION['form_feedback'];
                        echo '<div class="alert alert-' . htmlspecialchars($feedback['type']) . '" role="alert">' . htmlspecialchars($feedback['message']) . '</div>';
                        unset($_SESSION['form_feedback']);
                    }
                    ?>

                    <form action="/index.php/admin/animais/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($animal['id']) ?>">

                        <div class="mb-3">
                            <label for="especie" class="form-label">Espécie</label>
                            <input type="text" class="form-control" id="especie" name="especie" value="<?= htmlspecialchars($animal['especie']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="origem" class="form-label">Origem</label>
                            <input type="text" class="form-control" id="origem" name="origem" value="<?= htmlspecialchars($animal['origem']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($animal['descricao']) ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($animal['preco']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estoque" class="form-label">Quantidade em Estoque</label>
                                <input type="number" class="form-control" id="estoque" name="estoque" value="<?= htmlspecialchars($animal['estoque']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Atual</label>
                            <div>
                                <?php if (!empty($animal['imagem_url'])): ?>
                                    <img src="/imagem/<?= htmlspecialchars($animal['imagem_url']) ?>" alt="Foto atual" class="img-thumbnail mb-2" style="max-width: 150px;">
                                <?php else: ?>
                                    <p class="text-muted">Nenhuma imagem cadastrada.</p>
                                <?php endif; ?>
                            </div>
                            <label for="imagem" class="form-label">Alterar Foto do Animal (opcional)</label>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                            <div class="form-text">Envie uma nova imagem apenas se desejar substituí-la.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

```

### 3. Adicionar Método `update` ao Model `Animal.php`

Como não tenho o conteúdo do seu arquivo `app/Models/Animal.php`, vou fornecer o código do método `update`. Você deve adicioná-lo dentro da classe `Animal` no arquivo correspondente.

```php
    /**
     * Atualiza os dados de um animal no banco de dados.
     */
    public function update($id, $especie, $origem, $descricao, $preco, $estoque, $imagem_url, $data_nascimento) {
        $sql = "UPDATE animais 
                SET especie = ?, origem = ?, descricao = ?, preco = ?, estoque = ?, imagem_url = ?, data_nascimento = ?
                WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$especie, $origem, $descricao, $preco, $estoque, $imagem_url, $data_nascimento, $id]);
            return true;
        } catch (PDOException $e) {
            // Em um ambiente de produção, você poderia logar o erro em vez de exibi-lo.
            error_log("Erro ao atualizar animal: " . $e->getMessage());
            throw new Exception("Não foi possível atualizar o animal.");
        }
    }
```

Com esses arquivos e o método `update` implementados, a funcionalidade de editar produtos estará completa. O próximo passo seria criar a funcionalidade de deletar, que já tem um botão na tela de listagem.

<!--
[PROMPT_SUGGESTION]Crie o método para deletar um animal no AdminAnimalController e no Animal Model.[/PROMPT_SUGGESTION]
[PROMPT_SUGGESTION]Como posso adicionar paginação na lista de animais?[/PROMPT_SUGGESTION]
-->