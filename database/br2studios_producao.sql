-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 02/09/2025 às 18:25
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `br2studios`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_imoveis`
--

CREATE TABLE `categorias_imoveis` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `icone` varchar(50) DEFAULT 'fas fa-check',
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias_imoveis`
--

INSERT INTO `categorias_imoveis` (`id`, `nome`, `descricao`, `icone`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 'Academia', 'Academia na área comum do condomínio', 'fas fa-dumbbell', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(2, 'Churrasqueira', 'Churrasqueira na área comum', 'fas fa-fire', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(3, 'Piscina', 'Piscina na área comum', 'fas fa-swimming-pool', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(4, 'Salão de Festas', 'Salão de festas disponível', 'fas fa-glass-cheers', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(5, 'Vagas de Garagem', 'Vagas de garagem cobertas', 'fas fa-car', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(6, 'Elevador', 'Elevador no prédio', 'fas fa-arrow-up', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(7, 'Portaria 24h', 'Portaria funcionando 24 horas', 'fas fa-user-shield', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(8, 'Segurança 24h', 'Sistema de segurança 24 horas', 'fas fa-shield-alt', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(9, 'Jardim', 'Jardim na área comum', 'fas fa-seedling', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(10, 'Playground', 'Playground para crianças', 'fas fa-child', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(11, 'Espaço Gourmet', 'Espaço gourmet na área comum', 'fas fa-utensils', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(12, 'Lavanderia', 'Lavanderia na área comum', 'fas fa-tshirt', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(13, 'Spa', 'Spa na área comum', 'fas fa-spa', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(14, 'Quadra Esportiva', 'Quadra esportiva disponível', 'fas fa-basketball-ball', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(15, 'Sala de Jogos', 'Sala de jogos na área comum', 'fas fa-gamepad', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(16, 'Biblioteca', 'Biblioteca na área comum', 'fas fa-book', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(17, 'Home Office', 'Espaço para home office', 'fas fa-laptop-house', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(18, 'Varanda Gourmet', 'Varanda gourmet privativa', 'fas fa-umbrella-beach', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(19, 'Sacada', 'Sacada privativa', 'fas fa-door-open', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(20, 'Aceita Pets', 'Aceita animais de estimação', 'fas fa-paw', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(21, 'Mobiliado', 'Imóvel mobiliado', 'fas fa-couch', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(22, 'Ar Condicionado', 'Ar condicionado instalado', 'fas fa-snowflake', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(23, 'Aquecimento', 'Sistema de aquecimento', 'fas fa-thermometer-half', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(24, 'Internet', 'Conexão à internet incluída', 'fas fa-wifi', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(25, 'TV a Cabo', 'TV a cabo incluída', 'fas fa-tv', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(26, 'Interfone', 'Sistema de interfone', 'fas fa-phone', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(27, 'Alarme', 'Sistema de alarme', 'fas fa-bell', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(28, 'Câmeras de Segurança', 'Câmeras de segurança', 'fas fa-video', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(29, 'Condomínio Fechado', 'Condomínio com acesso controlado', 'fas fa-lock', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(30, 'Área Verde', 'Área verde preservada', 'fas fa-tree', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(31, 'Estacionamento', 'Estacionamento disponível', 'fas fa-parking', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(32, 'Acessibilidade', 'Adaptado para pessoas com deficiência', 'fas fa-wheelchair', 1, '2025-09-02 14:48:43', '2025-09-02 14:48:43'),
(33, 'teste', 'teste', 'fas fa-home', 1, '2025-09-02 14:55:13', '2025-09-02 14:55:13'),
(35, 'raw', 'awraw', 'fas fa-building', 1, '2025-09-02 14:55:24', '2025-09-02 14:55:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `imovel_id` int(11) DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('novo','lido','respondido') DEFAULT 'novo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `corretores`
--

CREATE TABLE `corretores` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `creci` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `corretores`
--

INSERT INTO `corretores` (`id`, `nome`, `email`, `telefone`, `cidade`, `estado`, `creci`, `foto`, `bio`, `data_cadastro`) VALUES
(1, 'João Silva', 'joao.silva@br2studios.com', '(11) 99999-1111', 'São Paulo', 'SP', '123456-F', NULL, 'Especialista em investimentos imobiliários há mais de 10 anos.', '2025-08-31 11:55:24'),
(2, 'Maria Santos', 'maria.santos@br2studios.com', '(11) 99999-2222', 'Rio de Janeiro', 'RJ', '234567-F', NULL, 'Corretora especializada em studios e apartamentos compactos.', '2025-08-31 11:55:24'),
(11, 'brayan wosch', 'brayanwosch@gmail.com', '41998998431', 'São Paulo', 'SP', '4124', '../uploads/corretores/about-05_1.png', 'teste', '2025-09-02 00:42:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `depoimentos`
--

CREATE TABLE `depoimentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `depoimento` text NOT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `avaliacao` decimal(2,1) DEFAULT 5.0,
  `foto` varchar(500) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `depoimentos`
--

INSERT INTO `depoimentos` (`id`, `nome`, `depoimento`, `cidade`, `estado`, `avaliacao`, `foto`, `cargo`, `empresa`, `destaque`, `ativo`, `data_cadastro`, `data_atualizacao`) VALUES
(2, 'Maria Santos', 'Profissionais muito competentes e transparentes. Recomendo para quem quer investir em imóveis.', 'Rio de Janeiro', 'RJ', 5.0, NULL, 'Advogada', 'Escritório Santos', 1, 1, '2025-09-02 01:15:22', '2025-09-02 01:15:22'),
(3, 'Carlos Oliveira', 'A equipe da Br2Studios é incrível! Me ajudaram a encontrar o investimento perfeito.', 'Curitiba', 'PR', 4.9, NULL, 'Médico', 'Hospital Municipal', 1, 1, '2025-09-02 01:15:22', '2025-09-02 01:15:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialistas`
--

CREATE TABLE `especialistas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `experiencia` varchar(50) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `especialistas`
--

INSERT INTO `especialistas` (`id`, `nome`, `cargo`, `especialidade`, `experiencia`, `bio`, `foto`, `email`, `telefone`, `linkedin`, `destaque`, `ativo`, `data_cadastro`, `data_atualizacao`) VALUES
(2, 'Dra. Patricia Mendes', 'Especialista em Financiamento', 'Financiamento Imobiliário', '12 anos', 'Especialista em financiamentos e análise de crédito para investimentos imobiliários.', NULL, NULL, NULL, NULL, 1, 1, '2025-09-02 01:15:22', '2025-09-02 01:15:22'),
(4, 'Dr. Lucas Ferreira', 'Especialista Jurídico', 'Direito Imobiliário', '10 anos', 'Advogado especializado em direito imobiliário e regularização de imóveis.', NULL, NULL, NULL, NULL, 1, 1, '2025-09-02 01:15:22', '2025-09-02 01:15:22'),
(9, 'Dr. Lucas Ferreira', 'Especialista Jurídico', 'Direito Imobiliário', '10 anos', 'Advogado especializado em direito imobiliário e regularização de imóveis.', NULL, NULL, NULL, NULL, 1, 1, '2025-09-02 01:58:54', '2025-09-02 01:58:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens_imoveis`
--

CREATE TABLE `imagens_imoveis` (
  `id` int(11) NOT NULL,
  `imovel_id` int(11) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imobiliarias`
--

CREATE TABLE `imobiliarias` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imobiliarias`
--

INSERT INTO `imobiliarias` (`id`, `nome`, `cnpj`, `endereco`, `telefone`, `email`, `website`, `logo`, `descricao`, `data_cadastro`) VALUES
(1, 'Br2Studios Imobiliária', '12.345.678/0001-90', 'Av. Paulista, 1000, São Paulo - SP', '(11) 3333-4444', 'contato@br2studios.com', 'www.br2studios.com', NULL, 'Especializada em studios e investimentos imobiliários de alta rentabilidade.', '2025-08-31 11:55:24'),
(2, 'Invest Imóveis', '98.765.432/0001-10', 'Rua Augusta, 500, São Paulo - SP', '(11) 4444-5555', 'contato@investimoveis.com', 'www.investimoveis.com', NULL, 'Focada em investimentos imobiliários e consultoria especializada.', '2025-08-31 11:55:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis`
--

CREATE TABLE `imoveis` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `area` decimal(8,2) DEFAULT NULL,
  `quartos` int(11) DEFAULT 0,
  `banheiros` int(11) DEFAULT 0,
  `vagas` int(11) DEFAULT 0,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'studio',
  `status` enum('disponivel','vendido','reservado') DEFAULT 'disponivel',
  `imagem_principal` varchar(255) DEFAULT NULL,
  `imagens` text DEFAULT NULL,
  `caracteristicas` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `destaque` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imoveis`
--

INSERT INTO `imoveis` (`id`, `titulo`, `descricao`, `preco`, `area`, `quartos`, `banheiros`, `vagas`, `endereco`, `cidade`, `estado`, `cep`, `tipo`, `status`, `imagem_principal`, `imagens`, `caracteristicas`, `data_cadastro`, `data_atualizacao`, `destaque`) VALUES
(23, 'Studio Executivo Jardins', 'Studio de alto padrão no bairro mais nobre de São Paulo. Acabamento de luxo, portaria 24h, academia e piscina.', 350000.00, 38.00, 1, 1, 1, 'Rua Bela Cintra, 456', 'São Paulo', 'SP', '01415-000', 'studio', 'disponivel', '../uploads/imoveis/imovel-2.jpeg', 'assets/images/imoveis/studio-jardins-2.jpg,assets/images/imoveis/studio-jardins-3.jpg,assets/images/imoveis/studio-jardins-4.jpg', 'Acabamento de luxo, Portaria 24h, Academia, Piscina, Bairro nobre', '2025-09-02 02:34:19', '2025-09-02 16:13:53', 1),
(25, 'test', 'eteste', 12222.00, 12.00, 12, 12, 12, 'Rua Padre Paulo Canelles', 'Curitiba', 'RR', '82720350', 'studio', 'disponivel', NULL, NULL, 'teste', '2025-09-02 02:38:39', '2025-09-02 16:13:47', 1),
(26, 'TESTEEE', 'TESTE', 10000.00, 10.00, 2, 2, 2, 'TESTE', 'TESTE', 'AC', '82720350', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1756781457_90d89ad7f3d02e52.jpeg', '/config/../uploads/imoveis/imoveis_1756781457_a5dd9ec11f24ff31.jpeg', 'TESTEE', '2025-09-02 02:50:57', '2025-09-02 16:13:58', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis_caracteristicas`
--

CREATE TABLE `imoveis_caracteristicas` (
  `id` int(11) NOT NULL,
  `imovel_id` int(11) NOT NULL,
  `caracteristica` varchar(100) NOT NULL,
  `valor` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis_imagens`
--

CREATE TABLE `imoveis_imagens` (
  `id` int(11) NOT NULL,
  `imovel_id` int(11) NOT NULL,
  `caminho_imagem` varchar(500) NOT NULL,
  `tipo` enum('imagem_principal','imagem_01','imagem_02','imagem_03','imagem_04') NOT NULL,
  `data_upload` datetime NOT NULL,
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis_venda`
--

CREATE TABLE `imoveis_venda` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo_operacao` enum('alugar','vender') NOT NULL,
  `tipo_imovel` enum('apartamento','casa','casa_condominio','studio','cobertura','terreno','comercial') NOT NULL,
  `valor` decimal(15,2) NOT NULL,
  `valor_condominio` decimal(10,2) DEFAULT NULL,
  `valor_iptu` decimal(10,2) DEFAULT NULL,
  `cep` varchar(10) NOT NULL,
  `endereco` varchar(500) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` char(2) NOT NULL,
  `metragem` decimal(8,2) NOT NULL,
  `dormitorios` int(11) NOT NULL,
  `vagas` int(11) DEFAULT 0,
  `banheiros` int(11) NOT NULL,
  `suites` int(11) DEFAULT 0,
  `detalhes` text DEFAULT NULL,
  `status` enum('pendente','aprovado','rejeitado','vendido','alugado') DEFAULT 'pendente',
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imovel_categorias`
--

CREATE TABLE `imovel_categorias` (
  `id` int(11) NOT NULL,
  `imovel_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imovel_categorias`
--

INSERT INTO `imovel_categorias` (`id`, `imovel_id`, `categoria_id`, `created_at`) VALUES
(22, 25, 17, '2025-09-02 16:13:47'),
(23, 25, 26, '2025-09-02 16:13:47'),
(24, 25, 24, '2025-09-02 16:13:47'),
(25, 25, 10, '2025-09-02 16:13:47'),
(26, 25, 7, '2025-09-02 16:13:47'),
(27, 23, 20, '2025-09-02 16:13:53'),
(28, 23, 27, '2025-09-02 16:13:53'),
(29, 23, 28, '2025-09-02 16:13:53'),
(30, 23, 2, '2025-09-02 16:13:53'),
(31, 23, 29, '2025-09-02 16:13:53'),
(32, 23, 9, '2025-09-02 16:13:53'),
(33, 26, 1, '2025-09-02 16:13:58'),
(34, 26, 20, '2025-09-02 16:13:58'),
(35, 26, 16, '2025-09-02 16:13:58'),
(36, 26, 28, '2025-09-02 16:13:58'),
(37, 26, 2, '2025-09-02 16:13:58'),
(38, 26, 6, '2025-09-02 16:13:58'),
(39, 26, 17, '2025-09-02 16:13:58'),
(40, 26, 26, '2025-09-02 16:13:58'),
(41, 26, 3, '2025-09-02 16:13:58'),
(42, 26, 4, '2025-09-02 16:13:58'),
(43, 26, 5, '2025-09-02 16:13:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_acesso`
--

CREATE TABLE `logs_acesso` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` varchar(100) NOT NULL,
  `tabela` varchar(100) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `dados_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_anteriores`)),
  `dados_novos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_novos`)),
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `data_log` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_inscricao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `regioes`
--

CREATE TABLE `regioes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(500) DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `regioes`
--

INSERT INTO `regioes` (`id`, `nome`, `estado`, `descricao`, `imagem`, `destaque`, `ativo`, `data_cadastro`, `data_atualizacao`) VALUES
(1, 'Vila Madalena', 'SP', 'Região boêmia e cultural de São Paulo, com excelente potencial de valorização.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(2, 'Copacabana', 'RJ', 'Bairro tradicional do Rio de Janeiro com vista para o mar e infraestrutura completa.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(3, 'Batel', 'PR', 'Bairro nobre de Curitiba com excelente localização e infraestrutura.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(4, 'Savassi', 'MG', 'Região central de Belo Horizonte com comércio e serviços de qualidade.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(5, 'Meireles', 'CE', 'Bairro nobre de Fortaleza com vista para o mar e infraestrutura completa.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(6, 'Jardins', 'SP', 'Bairro tradicional de São Paulo com excelente localização e infraestrutura.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(7, 'Ipanema', 'RJ', 'Bairro nobre do Rio de Janeiro com praia e infraestrutura de qualidade.', NULL, 1, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44'),
(8, 'Centro Cívico', 'PR', 'Região central de Curitiba com excelente localização e infraestrutura.', NULL, 0, 1, '2025-09-02 01:16:44', '2025-09-02 01:16:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `creci` varchar(50) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_admin`
--

CREATE TABLE `usuarios_admin` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` enum('admin','gerente','operador') DEFAULT 'operador',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios_admin`
--

INSERT INTO `usuarios_admin` (`id`, `nome`, `email`, `senha`, `nivel`, `status`, `ultimo_login`, `data_cadastro`, `data_atualizacao`) VALUES
(2, 'Administrador', 'admin@br2studios.com.br', '$2y$12$lORXAwHGd5KOzB.h5WzsouHTMbp.klQ8Bw1/C/JSOXeHn2.67Uo7m', 'admin', 'ativo', '2025-09-02 14:55:03', '2025-09-01 23:52:08', '2025-09-02 14:55:03');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias_imoveis`
--
ALTER TABLE `categorias_imoveis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imovel_id` (`imovel_id`);

--
-- Índices de tabela `corretores`
--
ALTER TABLE `corretores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `depoimentos`
--
ALTER TABLE `depoimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_destaque` (`destaque`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_cidade` (`cidade`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `especialistas`
--
ALTER TABLE `especialistas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_destaque` (`destaque`),
  ADD KEY `idx_especialidade` (`especialidade`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `imagens_imoveis`
--
ALTER TABLE `imagens_imoveis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imovel_id` (`imovel_id`);

--
-- Índices de tabela `imobiliarias`
--
ALTER TABLE `imobiliarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `imoveis`
--
ALTER TABLE `imoveis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_imoveis_estado` (`estado`),
  ADD KEY `idx_imoveis_tipo` (`tipo`),
  ADD KEY `idx_imoveis_status` (`status`),
  ADD KEY `idx_imoveis_preco` (`preco`),
  ADD KEY `idx_imoveis_cidade` (`cidade`);

--
-- Índices de tabela `imoveis_caracteristicas`
--
ALTER TABLE `imoveis_caracteristicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_imovel_id` (`imovel_id`),
  ADD KEY `idx_caracteristica` (`caracteristica`);

--
-- Índices de tabela `imoveis_imagens`
--
ALTER TABLE `imoveis_imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_imovel_id` (`imovel_id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_ordem` (`ordem`);

--
-- Índices de tabela `imoveis_venda`
--
ALTER TABLE `imoveis_venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_tipo_operacao` (`tipo_operacao`),
  ADD KEY `idx_tipo_imovel` (`tipo_imovel`),
  ADD KEY `idx_cidade` (`cidade`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_valor` (`valor`),
  ADD KEY `idx_data_cadastro` (`data_cadastro`);

--
-- Índices de tabela `imovel_categorias`
--
ALTER TABLE `imovel_categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_imovel_categoria` (`imovel_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `regioes`
--
ALTER TABLE `regioes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nome_estado` (`nome`,`estado`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_destaque` (`destaque`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Índices de tabela `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias_imoveis`
--
ALTER TABLE `categorias_imoveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `corretores`
--
ALTER TABLE `corretores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `depoimentos`
--
ALTER TABLE `depoimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `especialistas`
--
ALTER TABLE `especialistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `imagens_imoveis`
--
ALTER TABLE `imagens_imoveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imobiliarias`
--
ALTER TABLE `imobiliarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `imoveis`
--
ALTER TABLE `imoveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `imoveis_caracteristicas`
--
ALTER TABLE `imoveis_caracteristicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `imoveis_imagens`
--
ALTER TABLE `imoveis_imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imoveis_venda`
--
ALTER TABLE `imoveis_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imovel_categorias`
--
ALTER TABLE `imovel_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `regioes`
--
ALTER TABLE `regioes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `contatos`
--
ALTER TABLE `contatos`
  ADD CONSTRAINT `contatos_ibfk_1` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `imagens_imoveis`
--
ALTER TABLE `imagens_imoveis`
  ADD CONSTRAINT `imagens_imoveis_ibfk_1` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imoveis_caracteristicas`
--
ALTER TABLE `imoveis_caracteristicas`
  ADD CONSTRAINT `imoveis_caracteristicas_ibfk_1` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis_venda` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imoveis_imagens`
--
ALTER TABLE `imoveis_imagens`
  ADD CONSTRAINT `imoveis_imagens_ibfk_1` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis_venda` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imoveis_venda`
--
ALTER TABLE `imoveis_venda`
  ADD CONSTRAINT `imoveis_venda_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imovel_categorias`
--
ALTER TABLE `imovel_categorias`
  ADD CONSTRAINT `imovel_categorias_ibfk_1` FOREIGN KEY (`imovel_id`) REFERENCES `imoveis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `imovel_categorias_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_imoveis` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD CONSTRAINT `logs_acesso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_admin` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
