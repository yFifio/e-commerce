Com certeza\! O README foi atualizado com os nomes dos integrantes.

---

# **Exotic Pets Emporium**

## **Descrição do Propósito do Sistema**

O Exotic Pets Emporium é uma plataforma de e-commerce desenvolvida em PHP, criada para simular a adoção de animais exóticos. O sistema oferece uma experiência completa, desde a visualização dos animais em um catálogo detalhado até a finalização do processo de adoção, com um carrinho de compras funcional e um checkout que simula diferentes formas de pagamento. Além disso, a plataforma conta com uma área de cliente para o acompanhamento do histórico de adoções e um dashboard administrativo para a visualização de indicadores chave de desempenho, como total de animais, usuários, adoções e faturamento.

## **Instruções de Instalação e Execução**

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local.

### **Pré-requisitos**

- **Servidor Local**: É necessário ter um ambiente como XAMPP, WAMP ou MAMP instalado, que inclua Apache, MySQL e PHP (versão 8 ou superior).
- **Node.js e npm**: Para gerenciar as dependências de frontend do projeto.

### **Passo 1: Clonar o Repositório**

Abra o seu terminal ou Git Bash e clone o projeto para o diretório `htdocs` (ou o diretório web do seu servidor).

```bash
cd C:/xampp/htdocs/
git clone https://github.com/seu-usuario/e-commerce.git
cd e-commerce
```

### **Passo 2: Configurar o Banco de Dados**

1.  **Inicie o Apache e o MySQL** no painel de controle do seu servidor local (ex: XAMPP).

2.  Acesse o **phpMyAdmin** em `http://localhost/phpmyadmin/`.

3.  Crie um novo banco de dados com o nome `e-comercce`.

    ```sql
    CREATE DATABASE `e-comercce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
    ```

4.  Selecione o banco de dados `e-comercce` e execute os scripts SQL abaixo na aba "SQL" para criar todas as tabelas e inserir os dados iniciais.

#### **Scripts SQL**

- **Tabela de Animais**: Armazena os dados dos animais disponíveis.

  ```sql
  CREATE TABLE `animais` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `especie` varchar(255) NOT NULL,
    `origem` varchar(255) DEFAULT NULL,
    `preco` decimal(10,2) NOT NULL,
    `estoque` int(11) NOT NULL DEFAULT 0,
    `imagem_url` varchar(255) DEFAULT NULL,
    `descricao` text DEFAULT NULL,
    `data_nascimento` date DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ```

- **Tabela de Usuários**: Armazena as informações dos clientes e administradores.

  ```sql
  CREATE TABLE `usuarios` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nome` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `senha` varchar(255) NOT NULL,
    `role` varchar(50) NOT NULL DEFAULT 'cliente',
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ```

- **Tabela de Adoções**: Guarda o registro de cada adoção realizada.

  ```sql
  CREATE TABLE `adocoes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` int(11) NOT NULL,
    `valor_total` decimal(10,2) NOT NULL,
    `data_adocao` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `usuario_id` (`usuario_id`),
    CONSTRAINT `adocoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ```

- **Tabela de Itens da Adoção**: Detalha os animais de cada adoção.

  ```sql
  CREATE TABLE `adocao_itens` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `adocao_id` int(11) NOT NULL,
    `animal_id` int(11) NOT NULL,
    `quantidade` int(11) NOT NULL,
    `preco_unitario` decimal(10,2) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `adocao_id` (`adocao_id`),
    KEY `animal_id` (`animal_id`),
    CONSTRAINT `adocao_itens_ibfk_1` FOREIGN KEY (`adocao_id`) REFERENCES `adocoes` (`id`),
    CONSTRAINT `adocao_itens_ibfk_2` FOREIGN KEY (`animal_id`) REFERENCES `animais` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ```

- **Tabela de Pagamentos**: Simula o registro dos pagamentos.

  ```sql
  CREATE TABLE `pagamentos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `adocao_id` int(11) NOT NULL,
    `metodo_pagamento` varchar(50) NOT NULL,
    `status_pagamento` varchar(50) NOT NULL,
    `transacao_id` varchar(255) DEFAULT NULL,
    `nome_cartao` varchar(255) DEFAULT NULL,
    `numero_cartao_final` varchar(4) DEFAULT NULL,
    `validade_cartao` varchar(7) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `adocao_id` (`adocao_id`),
    CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`adocao_id`) REFERENCES `adocoes` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ```

- **Inserir Dados Iniciais**: Adiciona animais e um usuário administrador.

  ```sql
  INSERT INTO `animais` (`especie`, `origem`, `preco`, `estoque`, `imagem_url`) VALUES
  ('Arara-Vermelha', 'América do Sul', 2500.00, 10, 'Arara-Vermelha.jpg'),
  ('Pato-Mandarim', 'Ásia', 1200.00, 15, 'pato-mandarim.jpeg'),
  ('Jaguatirica', 'Américas', 7000.00, 5, 'jaguatirica.jpg'),
  ('Caracal', 'África', 9500.00, 3, 'caracal.webp'),
  ('Cobra-de-Milho', 'América do Norte', 800.00, 20, 'cobra-de-milho.webp'),
  ('Lesma-do-Mar', 'Oceanos', 500.00, 30, 'lesma-do-mar.png');

  INSERT INTO `usuarios` (`nome`, `email`, `senha`, `role`) VALUES
  ('Admin', 'admin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
  ```

### **Passo 3: Instalar Dependências e Executar**

1.  No terminal, dentro da pasta do projeto, instale as dependências de frontend.

    ```bash
    npm install
    ```

2.  Acesse o projeto no seu navegador. Normalmente, o endereço será:

    `http://localhost/e-commerce/public/`

    A aplicação estará pronta para ser utilizada\!

## **Integrantes**

- Lucas de Fiori Viudes
- Vitto Lorenzo Barboza Legnani
- Lucas Gozer Lopes
