<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exotic Pets Emporium</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Exotic Pets Emporium</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white me-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/minhas-adocoes">Minhas Adoções</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Sair</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-light me-2">Login</a>
                    <a href="/register" class="btn btn-primary me-2">Registrar</a>
                <?php endif; ?>
                <a href="/carrinho" class="btn btn-warning">Carrinho</a>
            </div>
        </div>
    </div>
</nav>
<main class="flex-grow-1">
    <div class="container mt-4">