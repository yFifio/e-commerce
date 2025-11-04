<?php
require_once __DIR__ . '/../Models/Animal.php';
require_once __DIR__ . '/../Models/Model.php';

class CarrinhoController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function add() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /index.php');
            exit();
        }

        $animalModel = new Animal();
        $animal = $animalModel->find($id);

        if ($animal && $animal['estoque'] > 0) {
            if (isset($_SESSION['carrinho'][$id])) {
                $_SESSION['carrinho'][$id]['quantidade']++;
            } else {
                $_SESSION['carrinho'][$id] = [
                    'dados' => $animal,
                    'quantidade' => 1
                ];
            }
        }
        header('Location: /index.php/carrinho');
    }
    
    public function show() {
        $carrinho = $_SESSION['carrinho'] ?? [];

        foreach ($carrinho as $id => $item) {
            if (!isset($item['dados']) || !isset($item['quantidade'])) {
                unset($carrinho[$id]);
            }
        }
        $_SESSION['carrinho'] = $carrinho;

        require_once __DIR__ . '/../Views/carrinho.php';
    }

    public function remove() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /index.php/carrinho');
            exit();
        }

        if (isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
        header('Location: /index.php/carrinho');
    }

    public function update() {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($id && $action && isset($_SESSION['carrinho'][$id])) {
            if ($action === 'increase') {
                if ($_SESSION['carrinho'][$id]['quantidade'] < $_SESSION['carrinho'][$id]['dados']['estoque']) {
                    $_SESSION['carrinho'][$id]['quantidade']++;
                }
            } elseif ($action === 'decrease') {
                $_SESSION['carrinho'][$id]['quantidade']--;
                if ($_SESSION['carrinho'][$id]['quantidade'] <= 0) {
                    unset($_SESSION['carrinho'][$id]);
                }
            }
        }
        header('Location: /index.php/carrinho');
    }

    public function checkout() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /index.php/login?redirect=/index.php/carrinho/checkout');
            exit();
        }

        $carrinho = $_SESSION['carrinho'] ?? [];
        if (empty($carrinho)) {
            header('Location: /index.php/carrinho');
            exit();
        }

        require_once __DIR__ . '/../Views/checkout.php';
    }

    public function showEnderecoForm() {
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
            header('Location: /index.php');
            exit();
        }

        $metodo_pagamento = filter_input(INPUT_POST, 'metodo_pagamento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$metodo_pagamento) {
            header('Location: /carrinho/checkout');
            exit();
        }

        $_SESSION['metodo_pagamento'] = $metodo_pagamento;

        require_once __DIR__ . '/../Views/endereco.php';
    }

    public function showPagamento() {
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
            header('Location: /index.php');
            exit();
        }

        $metodo_pagamento = $_SESSION['metodo_pagamento'] ?? filter_input(INPUT_POST, 'metodo_pagamento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (!$metodo_pagamento) {
            header('Location: /index.php/carrinho/checkout');
            exit();
        }

        if ($metodo_pagamento === 'mercadopago') {
            header('Location: /index.php/pagar-com-mercado-pago');
            exit();
        }

        $_SESSION['endereco'] = [
            'cep' => filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'logradouro' => filter_input(INPUT_POST, 'logradouro', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'numero' => filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'complemento' => filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'bairro' => filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'cidade' => filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'estado' => filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];

        $labels = ['cartao_credito' => 'Cartão de Crédito', 'boleto' => 'Boleto Bancário', 'pix' => 'PIX'];
        $metodo_pagamento_label = $labels[$metodo_pagamento] ?? 'Desconhecido';

        require_once __DIR__ . '/../Views/pagamento.php';
    }

    public function finalizar() {
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
            header('Location: /index.php');
            exit();
        }

        $source = filter_input(INPUT_GET, 'source');

        if ($source === 'mp') {
            $metodo_pagamento = 'mercadopago_' . filter_input(INPUT_GET, 'payment_type');
            $transacao_id = filter_input(INPUT_GET, 'payment_id');
            $status_pagamento = filter_input(INPUT_GET, 'status');
        } else {
            $metodo_pagamento = $_SESSION['metodo_pagamento'] ?? filter_input(INPUT_POST, 'metodo_pagamento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nome_cartao = filter_input(INPUT_POST, 'nome_cartao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $numero_cartao = filter_input(INPUT_POST, 'numero_cartao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $validade_cartao = filter_input(INPUT_POST, 'validade_cartao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $numero_cartao_final = $numero_cartao ? substr($numero_cartao, -4) : null;
            $transacao_id = 'simulacao_' . uniqid();
            $status_pagamento = 'aprovado';
        }

        $carrinho = $_SESSION['carrinho'];
        $endereco = $_SESSION['endereco'] ?? [];
        $usuario_id = $_SESSION['usuario_id'];
        $total = array_sum(array_map(fn($item) => $item['dados']['preco'] * $item['quantidade'], $carrinho));

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO adocoes (usuario_id, valor_total, endereco_logradouro, endereco_numero, endereco_complemento, endereco_bairro, endereco_cidade, endereco_estado, endereco_cep) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $usuario_id, $total,
                $endereco['logradouro'] ?? null, $endereco['numero'] ?? null, $endereco['complemento'] ?? null,
                $endereco['bairro'] ?? null, $endereco['cidade'] ?? null, $endereco['estado'] ?? null,
                $endereco['cep'] ?? null
            ]);

            $adocao_id = $this->db->lastInsertId();

            $animalModel = new Animal();
        
            foreach ($carrinho as $id => $item) {
                $animalAtual = $animalModel->find($id);
                if (!$animalAtual || $animalAtual['estoque'] < $item['quantidade']) {
                    throw new Exception("Estoque insuficiente para o animal: " . htmlspecialchars($item['dados']['especie']));
                }
            }

            foreach ($carrinho as $item) {
                $stmt = $this->db->prepare("INSERT INTO adocao_itens (adocao_id, animal_id, preco_unitario, quantidade) VALUES (?, ?, ?, ?)");
                $stmt->execute([$adocao_id, $item['dados']['id'], $item['dados']['preco'], $item['quantidade']]);
                $animalModel->decreaseStock($item['dados']['id'], $item['quantidade']);
            }
            
            $stmt = $this->db->prepare(
                "INSERT INTO pagamentos (adocao_id, metodo_pagamento, status_pagamento, transacao_id, nome_cartao, numero_cartao_final, validade_cartao) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$adocao_id, $metodo_pagamento, $status_pagamento, $transacao_id, $nome_cartao ?? null, $numero_cartao_final ?? null, $validade_cartao ?? null]);

            $this->db->commit();
            unset($_SESSION['carrinho']);
            unset($_SESSION['endereco']);
            unset($_SESSION['metodo_pagamento']);
            header('Location: /index.php/adocao/sucesso?id=' . $adocao_id);

        } catch (Exception $e) {
            $this->db->rollBack();
            die("Erro ao finalizar a adoção: " . $e->getMessage());
        }
    }
}