<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$route = ltrim($request_uri, '/');

if (strpos($route, 'index.php/') === 0) {
    $route = substr($route, strlen('index.php/'));
} elseif ($route === 'index.php') {
    $route = '';
}
$method = $_SERVER['REQUEST_METHOD'];

function loadController($controllerName) {
    require_once __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
}

switch ($route) {
    case '':
        loadController('HomeController');
        $controller = new HomeController();
        $controller->index();
        break;
    
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

    case 'animal':
        loadController('AnimalController');
        $controller = new AnimalController();
        $controller->show();
        break;

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

    case 'carrinho/endereco':
        loadController('CarrinhoController');
        $controller = new CarrinhoController();
        $controller->showEnderecoForm();
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

    case 'pagar-com-mercado-pago':
        loadController('MercadoPagoController');
        $controller = new MercadoPagoController();
        $controller->createPreference();
        break;

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

    case 'contato':
        loadController('ContatoController');
        $controller = new ContatoController();
        if ($method == 'POST') {
            $controller->send();
        } else {
            $controller->showForm();
        }
        break;

    case 'admin/contato':
        loadController('ContatoController');
        $controller = new ContatoController();
        $controller->showMessages();
        break;

    case 'admin/contato/delete':
        loadController('ContatoController');
        $controller = new ContatoController();
        $controller->delete();
        break;

    case 'admin/animais/novo':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->showAddForm();
        break;

    case 'admin/animais/create':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->create();
        break;

    case 'admin/animais/listar':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->listAll();
        break;

    case 'admin/animais/editar':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->showEditForm();
        break;

    case 'admin/animais/update':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->update();
        break;

    case 'admin/animais/deactivate':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->deactivate();
        break;

    case 'admin/animais/reactivate':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->reactivate();
        break;

    case 'admin/animais/delete':
        loadController('AdminAnimalController');
        $controller = new AdminAnimalController();
        $controller->delete();
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada.";
        break;
}