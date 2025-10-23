<?php

require_once __DIR__ . '/../../public/vendor/autoload.php';
require_once __DIR__ . '/../Models/Animal.php';

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController
{
    public function __construct()
    {
        if (file_exists(__DIR__ . '/../../.env')) {
            $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[$name] = trim($value, '"\'');
            }
        }

        $accessToken = $_ENV['MERCADO_PAGO_ACCESS_TOKEN'] ?? null;
        if (!$accessToken) {
            die("Access Token do Mercado Pago não configurado.");
        }
        MercadoPagoConfig::setAccessToken($accessToken);

        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
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

        $external_reference = session_id();

        $items = [];
        foreach ($_SESSION['carrinho'] as $id => $item) {
            $items[] = [
                'id' => $id,
                'title' => $item['dados']['especie'],
                'quantity' => $item['quantidade'],
                'unit_price' => (float)$item['dados']['preco'],
                'currency_id' => 'BRL'
            ];
        }

        $base_url = 'http://' . $_SERVER['HTTP_HOST'];

        $client = new PreferenceClient();

        try {
            $preference = $client->create([
                "items" => $items,
                "external_reference" => $external_reference,
                "back_urls" => [
                    'success' => $base_url . '/index.php/carrinho/finalizar?source=mp',
                    'failure' => $base_url . '/index.php/carrinho',
                    'pending' => $base_url . '/index.php/carrinho'
                ],
            ]);

            header("Location: " . $preference->init_point);
            exit();

        } catch (MPApiException $e) {
            echo "Erro ao criar preferência do Mercado Pago: <pre>";
            print_r($e->getApiResponse()->getContent());
            echo "</pre>";
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}