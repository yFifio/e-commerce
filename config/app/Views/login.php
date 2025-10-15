<?php require __DIR__ . '/layouts/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Login</h2>
        <form action="/login" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/layouts/footer.php'; ?>