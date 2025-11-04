Exotic Pets Emporium
Descrição do Propósito do Sistema
O Exotic Pets Emporium é uma plataforma de e-commerce desenvolvida em PHP, criada para simular a adoção de animais exóticos. O sistema oferece uma experiência completa, desde a visualização dos animais em um catálogo detalhado até a finalização do processo de adoção, com um carrinho de compras funcional e um checkout que simula diferentes formas de pagamento. Além disso, a plataforma conta com uma área de cliente para o acompanhamento do histórico de adoções e um dashboard administrativo para a visualização de indicadores chave de desempenho, como total de animais, usuários, adoções e faturamento.

Instruções de Instalação e Execução
Siga os passos abaixo para configurar e executar o projeto em seu ambiente local.

Pré-requisitos
Servidor Local: É necessário ter um ambiente como XAMPP, WAMP ou MAMP instalado, que inclua Apache, MySQL e PHP (versão 8 ou superior).

Node.js e npm: Para gerenciar as dependências de frontend do projeto.

Passo 1: Clonar o Repositório
Abra o seu terminal ou Git Bash e clone o projeto para o diretório htdocs (ou o diretório web do seu servidor).

Bash

cd C:/xampp/htdocs/
git clone https://github.com/seu-usuario/e-commerce.git
cd e-commerce
Passo 2: Configurar o Banco de Dados
Inicie o Apache e o MySQL no painel de controle do seu servidor local (ex: XAMPP).

Acesse o phpMyAdmin em http://localhost/phpmyadmin/.

Crie um novo banco de dados com o nome e-comercce.

SQL

CREATE DATABASE `e-comercce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
Selecione o banco de dados e-comercce e execute os scripts SQL abaixo na aba "SQL".

IMPORTANTE: Os scripts de "Recursos Avançados" (Function, Trigger, Procedure) devem ser colados e executados um de cada vez, pois eles usam a sintaxe DELIMITER.

Scripts SQL

1. Criação das Tabelas Principais
   SQL

-- Tabela de Usuários
CREATE TABLE `usuarios` (
`id` INT PRIMARY KEY AUTO_INCREMENT,
`nome` VARCHAR(255) NOT NULL,
`email` VARCHAR(255) NOT NULL UNIQUE,
`senha` VARCHAR(255) NOT NULL,
`role` VARCHAR(50) NOT NULL DEFAULT 'cliente',
`data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Animais
CREATE TABLE `animais` (
`id` INT PRIMARY KEY AUTO_INCREMENT,
`especie` VARCHAR(100) NOT NULL,
`origem` VARCHAR(100),
`data_nascimento` DATE,
`preco` DECIMAL(10, 2) NOT NULL,
`estoque` INT NOT NULL DEFAULT 0,
`descricao` TEXT,
`imagem_url` VARCHAR(255),
`data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Adoções
CREATE TABLE `adocoes` (
`id` INT PRIMARY KEY AUTO_INCREMENT,
`usuario_id` INT NOT NULL,
`valor_total` DECIMAL(10, 2) NOT NULL,
`data_adocao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
`endereco_logradouro` VARCHAR(255) DEFAULT NULL,
`endereco_numero` VARCHAR(50) DEFAULT NULL,
`endereco_complemento` VARCHAR(100) DEFAULT NULL,
`endereco_bairro` VARCHAR(100) DEFAULT NULL,
`endereco_cidade` VARCHAR(100) DEFAULT NULL,
`endereco_estado` VARCHAR(50) DEFAULT NULL,
`endereco_cep` VARCHAR(20) DEFAULT NULL,
FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Itens da Adoção
CREATE TABLE `adocao_itens` (
`id` INT PRIMARY KEY AUTO_INCREMENT,
`adocao_id` INT NOT NULL,
`animal_id` INT NOT NULL,
`quantidade` INT NOT NULL DEFAULT 1,
`preco_unitario` DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (adocao_id) REFERENCES adocoes (id) ON DELETE CASCADE,
FOREIGN KEY (animal_id) REFERENCES animais (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Pagamentos
CREATE TABLE `pagamentos` (
`id` INT PRIMARY KEY AUTO_INCREMENT,
`adocao_id` INT NOT NULL,
`metodo_pagamento` VARCHAR(50) NOT NULL,
`status_pagamento` VARCHAR(50) NOT NULL,
`transacao_id` VARCHAR(255) DEFAULT NULL,
`nome_cartao` VARCHAR(255) DEFAULT NULL,
`numero_cartao_final` VARCHAR(4) DEFAULT NULL,
`validade_cartao` VARCHAR(7) DEFAULT NULL,
`data_pagamento` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (adocao_id) REFERENCES adocoes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Mensagens de Contato
CREATE TABLE `contato_mensagens` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`nome` VARCHAR(255) NOT NULL,
`email` VARCHAR(255) NOT NULL,
`assunto` VARCHAR(255) NOT NULL,
`mensagem` TEXT NOT NULL,
`data_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`lido` TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Auditoria de Preços
CREATE TABLE `auditoria_precos` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`animal_id` INT NOT NULL,
`preco_antigo` DECIMAL(10, 2),
`preco_novo` DECIMAL(10, 2),
`usuario_modificacao` VARCHAR(255),
`data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 2. Criação de Índices (Otimização)
SQL

-- Índices para buscas rápidas
CREATE INDEX idx_animais_busca ON animais(especie, origem);
CREATE INDEX idx_adocoes_usuario_id ON adocoes(usuario_id);
CREATE INDEX idx_adocao_itens_adocao_id ON adocao_itens(adocao_id);
CREATE INDEX idx_adocao_itens_animal_id ON adocao_itens(animal_id);
CREATE INDEX idx_pagamentos_adocao_id ON pagamentos(adocao_id); 3. Recursos Avançados de BD (Function, Trigger, Procedure)
Aviso: Execute os 3 blocos abaixo separadamente no phpMyAdmin, um de cada vez, incluindo os comandos DELIMITER.

A. Função (Verificar Estoque)

SQL

DELIMITER $$
CREATE FUNCTION `fn_verifica_estoque`(
p_animal_id INT,
p_quantidade_desejada INT
)
RETURNS BOOLEAN
READS SQL DATA
BEGIN
DECLARE v_estoque_atual INT;

    SELECT estoque INTO v_estoque_atual
    FROM animais
    WHERE id = p_animal_id;

    IF v_estoque_atual >= p_quantidade_desejada THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;

END$$
DELIMITER ;
B. Trigger (Auditoria de Preços)

SQL

DELIMITER $$
CREATE TRIGGER `trg_auditoria_preco_update`
BEFORE UPDATE ON `animais`
FOR EACH ROW
BEGIN
    IF OLD.preco <> NEW.preco THEN
        INSERT INTO auditoria_precos (animal_id, preco_antigo, preco_novo, usuario_modificacao)
        VALUES (OLD.id, OLD.preco, NEW.preco, USER()); 
    END IF;
END$$
DELIMITER ;
C. Procedure (Inserção Massiva de Teste)

SQL

DELIMITER $$
CREATE PROCEDURE `sp_insere_animais_massa`(
    IN p_quantidade_inserir INT
)
BEGIN
    DECLARE i INT DEFAULT 1;
    WHILE i <= p_quantidade_inserir DO
        INSERT INTO `animais`
            (especie, origem, preco, estoque, descricao, data_nascimento, data_cadastro)
        VALUES
            (
                CONCAT('Animal de Teste ', i),
                'Origem de Teste',
                RAND() * 1000 + 50,
                10,
                'Descrição de teste para o animal.',
                '2025-01-01',
                NOW()
            );
        SET i = i + 1;
    END WHILE;
    SELECT CONCAT(p_quantidade_inserir, ' animais de teste inseridos.') AS Resultado;
END$$
DELIMITER ; 4. Inserir Dados Iniciais
SQL

-- Inserir Usuário Administrador (Senha: admin)
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `role`) VALUES
('Admin', 'admin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Inserir Animais
INSERT INTO `animais` (especie, origem, data_nascimento, preco, estoque, imagem_url, descricao)
VALUES
('Agama Cabeça Vermelha', 'África', '2022-08-15', 800.00, 3, 'agama-cabeça-vermelha.jpg', 'A Agama Cabeça Vermelha é um réptil africano conhecido por sua coloração vibrante...'),
('Arara Azul', 'Brasil', '2021-09-20', 3500.00, 2, 'arara-azul.webp', 'A Arara Azul é uma das aves mais icônicas do Brasil...'),
('Arara Vermelha', 'Brasil', '2022-05-11', 3200.00, 1, 'Arara-Vermelha.jpg', 'A Arara Vermelha é uma ave exuberante de penas vermelhas e azuis...'),
('Axolote', 'México', '2023-01-05', 1500.00, 5, 'axalote.jpg', 'O Axolote é um anfíbio mexicano fascinante, capaz de regenerar partes do corpo...'),
('Cacatua', 'Austrália', '2022-03-09', 2800.00, 2, 'cacatua.webp', 'A Cacatua é uma ave inteligente e sociável...'),
('Caracal', 'África', '2021-07-30', 4200.00, 1, 'caracal.webp', 'O Caracal é um felino africano de porte médio...'),
('Camaleão Pantera', 'Madagascar', '2023-04-22', 1100.00, 4, 'ccamaleao-pantera.jpg', 'O Camaleão Pantera é uma das espécies mais coloridas do mundo...'),
('Chinchilla', 'América do Sul', '2022-10-03', 600.00, 6, 'chinchilla.jpg', 'A Chinchilla é um pequeno roedor originário dos Andes...'),
('Cobra de Milho', 'EUA', '2023-02-25', 750.00, 3, 'cobra-de-milho.webp', 'A Cobra de Milho é uma serpente americana dócil e inofensiva...'),
('Dragão Marinho Folhado', 'Austrália', '2021-12-12', 5000.00, 1, 'Dragao-marinho-folhado.png', 'O Dragão Marinho Folhado é um peixe marinho com aparência única...'),
('Fennec', 'Saara', '2022-06-10', 2500.00, 2, 'faneco.jpg', 'O Fennec, também conhecido como raposa-do-deserto...'),
('Iguana Verde', 'América Central', '2022-09-17', 950.00, 4, 'iguana.jpg', 'A Iguana Verde é um réptil herbívoro de hábitos diurnos...'),
('Lagosta Boxeadora', 'Oceano Índico', '2023-03-28', 1200.00, 2, 'lagosta-boxeadora.jpg', 'A Lagosta Boxeadora é um crustáceo conhecido por seus golpes rápidos...'),
('Lêmure de Cauda Anelada', 'Madagascar', '2021-08-02', 1800.00, 3, 'lemure-anelada.jpg', 'O Lêmure de Cauda Anelada é um primata endêmico de Madagascar...'),
('Leopardo das Neves', 'Ásia Central', '2020-12-20', 7000.00, 1, 'leopardo-das-neves.jpg', 'O Leopardo das Neves habita as montanhas geladas da Ásia...'),
('Lesma do Mar', 'Pacífico', '2023-05-11', 450.00, 6, 'lesma-do-mar.png', 'A Lesma do Mar, ou nudibrânquio, é um molusco marinho colorido...'),
('Okapi', 'Congo', '2021-11-07', 9000.00, 1, 'okapi.jpg', 'O Okapi é um mamífero africano parente da girafa...'),
('Panda Vermelho', 'Himalaia', '2022-04-25', 6500.00, 1, 'panda-vermelho.webp', 'O Panda Vermelho é um pequeno mamífero arborícola...'),
('Pato Mandarim', 'China e Japão', '2023-05-10', 1200.00, 2, 'pato-mandarim.jpeg', 'O Pato Mandarim é uma ave ornamental asiática...'),
('Pavão Indiano', 'Índia', '2022-02-14', 2200.00, 3, 'pavao-indiano.jpg', 'O Pavão Indiano é famoso por sua cauda exuberante...'),
('Peixe Palhaço', 'Pacífico', '2023-01-30', 500.00, 5, 'peixe-palhaço.webp', 'O Peixe Palhaço vive em simbiose com anêmonas do mar...'),
('Polvo Anel Azul', 'Oceano Índico', '2022-06-19', 1800.00, 2, 'polvo-anel-azul.jpg', 'O Polvo Anel Azul é um pequeno molusco marinho...'),
('Quetzal', 'América Central', '2022-09-03', 3500.00, 1, 'quetzal.webp', 'O Quetzal é uma ave sagrada para as antigas civilizações maias...'),
('Rolieiro de Peito Lilás', 'África', '2023-02-21', 1400.00, 2, 'Rolieiro-de-peito-lilás.jpg', 'O Rolieiro de Peito Lilás é uma ave africana de plumagem colorida...'),
('Tartaruga d\'Água', 'Brasil', '2021-05-16', 700.00, 4, 'tartaruga-dagua.jpg', 'A Tartaruga d’Água é um réptil aquático...'),
('Tucano Arco-Íris', 'América Central', '2022-03-07', 2000.00, 3, 'tucano-arco-iris.jpeg', 'O Tucano Arco-Íris é conhecido pelo bico colorido...');
Passo 3: Instalar Dependências e Executar
No terminal, dentro da pasta do projeto, instale as dependências de frontend.

Bash

npm install
Acesse o projeto no seu navegador. Normalmente, o endereço será:

http://localhost/e-commerce/public/

A aplicação estará pronta para ser utilizada!

Integrantes
Lucas de Fiori Viudes

Vitto Lorenzo Barboza Legnani

Lucas Gozer Lopes
