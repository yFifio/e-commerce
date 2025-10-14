<?php
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

function loadController($controllerName) {
    require_once __DIR__ . "/../app/Models/Controllers/{$controllerName}.php";
}

switch ($request_uri) {
    case '/':
        loadController('HomeController');
        $controller = new HomeController();
        $controller->index();
        break;
    case '/login':
        loadController('AuthController');
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
    case '/register':
        loadController('AuthController');
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegisterForm();
        }
        break;
    case '/logout':
        loadController('AuthController');
        $controller = new AuthController();
        $controller->logout();
        break;
    case '/carrinho':
        loadController('CartController');
        $controller = new CartController();
        $controller->show();
        break;
    case '/carrinho/add':
        loadController('CartController');
        $controller = new CartController();
        $controller->add();
        break;
    case '/carrinho/remove':
        loadController('CartController');
        $controller = new CartController();
        $controller->remove();
        break;
    case '/dashboard':
        loadController('DashboardController');
        $controller = new DashboardController();
        $controller->index();
        break;
    case '/dashboard/data':
        loadController('DashboardController');
        $controller = new DashboardController();
        $controller->data();
        break;
    default:
        http_response_code(404);
        echo "Página não encontrada.";
        break;
}