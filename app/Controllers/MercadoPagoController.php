<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../public/vendor/autoload.php';
require_once __DIR__ . '/../Models/Animal.php';

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController
{
    public function __construct()
    {
        // Carregar variáveis de ambiente (simples, sem dependências)
        if (file_exists(__DIR__ . '/../../.env')) {
            $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[$name] = $value;
            }
        }

        $accessToken = $_ENV['MERCADO_PAGO_ACCESS_TOKEN'] ?? null;
        if (!$accessToken) {
            die("Access Token do Mercado Pago não configurado.");
        }
        MercadoPagoConfig::setAccessToken($accessToken);
    }

    public function createPreference()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit();
        }

        if (empty($_SESSION['carrinho'])) {
            header('Location: /carrinho');
            exit();
        }

        // Usar o ID da sessão como referência externa para ligar o pagamento ao carrinho.
        $external_reference = session_id();

        $items = [];
        foreach ($_SESSION['carrinho'] as $id => $item) {
            // O SDK espera dados nos campos 'dados' e 'quantidade' do seu carrinho.
            // Se a estrutura for diferente, ajuste aqui.
            $items[] = [
                'id' => $id,
                'title' => $item['dados']['especie'],
                'quantity' => $item['quantidade'],
                'unit_price' => (float)$item['dados']['preco'],
                'currency_id' => 'BRL'
            ];
        }

        $client = new PreferenceClient();

        try {
            $preference = $client->create([
                "items" => $items,
                "external_reference" => $external_reference,
                "back_urls" => [
                    'success' => 'http://' . $_SERVER['HTTP_HOST'] . '/carrinho/finalizar?source=mp',
                    'failure' => 'http://' . $_SERVER['HTTP_HOST'] . '/carrinho',
                    'pending' => 'http://' . $_SERVER['HTTP_HOST'] . '/carrinho'
                ],
                "auto_return" => "approved",
            ]);

            // Redireciona o usuário para o checkout do Mercado Pago
            header("Location: " . $preference->init_point);
            exit();

        } catch (MPApiException $e) {
            echo "Erro ao criar preferência do Mercado Pago: " . $e->getApiResponse()->getContent();
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}