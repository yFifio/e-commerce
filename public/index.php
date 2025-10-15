<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pega o caminho da URL, por exemplo: /carrinho/add
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove a barra inicial para corresponder aos casos do switch
$route = ltrim($request_uri, '/');

// Se a rota começar com 'index.php/', remove essa parte
if (strpos($route, 'index.php/') === 0) {
    $route = substr($route, strlen('index.php/'));
} elseif ($route === 'index.php') { // Se a rota for exatamente 'index.php', trata como raiz
    $route = '';
}
$method = $_SERVER['REQUEST_METHOD'];

function loadController($controllerName) {
    require_once __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
}

switch ($route) {
    case '':
    // A rota vazia agora corresponde à página inicial
        loadController('HomeController');
        $controller = new HomeController();
        $controller->index();
        break;
    
    // --- Autenticação ---
    case 'login':
        loadController('AuthController');
        $controller = new AuthController();
        if ($method == 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;

    case 'register':
        loadController('AuthController');
        $controller = new AuthController();
        if ($method == 'POST') {
            $controller->register();
        } else {
            $controller->showRegisterForm();
        }
        break;

    case 'logout':
        loadController('AuthController');
        $controller = new AuthController();
        $controller->logout();
        break;

    // --- Dashboard (Admin) ---
    case 'dashboard':
        loadController('DashboardController');
        $controller = new DashboardController();
        $controller->index();
        break;
    
    case 'dashboard/data':
        loadController('DashboardController');
        $controller = new DashboardController();
        $controller->data();
        break;

    // --- Animais ---
    case 'animal':
        loadController('AnimalController');
        $controller = new AnimalController();
        $controller->show();
        break;

    // --- Carrinho e Checkout ---
    case 'carrinho/add':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->add();
        break;

    case 'carrinho':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->show();
        break;

    case 'carrinho/remove':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->remove();
        break;

    case 'carrinho/update':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->update();
        break;

    case 'carrinho/checkout':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->checkout();
        break;

    case 'pagamento':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->showPagamento();
        break;

    case 'carrinho/finalizar':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->finalizar();
        break;

    // --- Área do Usuário ---
    case 'minhas-adocoes':
        loadController('UsuarioController');
        $controller = new UsuarioController();
        $controller->showAdocoes();
        break;
    
    case 'adocao/sucesso':
        loadController('UsuarioController');
        $controller = new UsuarioController();
        $controller->showAdocaoSucesso();
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada.";
        break;
}