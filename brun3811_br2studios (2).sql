-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/09/2025 às 09:45
-- Versão do servidor: 8.0.43-34
-- Versão do PHP: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `brun3811_br2studios`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_imoveis`
--

CREATE TABLE `categorias_imoveis` (
  `id` int NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `icone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-check',
  `ativo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci,
  `imovel_id` int DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('novo','lido','respondido') COLLATE utf8mb4_unicode_ci DEFAULT 'novo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `corretores`
--

CREATE TABLE `corretores` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creci` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `corretores`
--

INSERT INTO `corretores` (`id`, `nome`, `email`, `telefone`, `cidade`, `estado`, `creci`, `foto`, `bio`, `data_cadastro`) VALUES
(14, 'NÁTHALY DUARTE BICHARA', 'imoveisbr024@gmail.com', '41 988049999', NULL, NULL, '53876', NULL, NULL, '2025-09-05 00:25:39'),
(15, 'Elis Amanda Dartico', 'darticoelis@gmail.com', '41 99896-6869', NULL, NULL, '53429', NULL, '', '2025-09-05 00:28:42'),
(16, 'Carlos Wagner Barreto de Oliveira Gomes', 'contato@br2imoveis.com.br', '(41) 41410093', NULL, NULL, '53495', NULL, NULL, '2025-09-05 00:38:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `depoimentos`
--

CREATE TABLE `depoimentos` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depoimento` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avaliacao` decimal(2,1) DEFAULT '5.0',
  `foto` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `especialidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experiencia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `id` int NOT NULL,
  `imovel_id` int NOT NULL,
  `nome_arquivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordem` int DEFAULT '0',
  `data_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imobiliarias`
--

CREATE TABLE `imobiliarias` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `id` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `preco` decimal(15,2) NOT NULL,
  `area` decimal(8,2) DEFAULT NULL,
  `quartos` int DEFAULT '0',
  `banheiros` int DEFAULT '0',
  `vagas` int DEFAULT '0',
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'studio',
  `status` enum('disponivel','vendido','reservado') COLLATE utf8mb4_unicode_ci DEFAULT 'disponivel',
  `imagem_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagens` text COLLATE utf8mb4_unicode_ci,
  `caracteristicas` text COLLATE utf8mb4_unicode_ci,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `destaque` tinyint(1) DEFAULT '0',
  `status_construcao` enum('pronto','em_construcao','na_planta') COLLATE utf8mb4_unicode_ci DEFAULT 'pronto',
  `ano_entrega` year DEFAULT NULL,
  `maior_valorizacao` tinyint(1) DEFAULT '0' COMMENT 'Indica se o imóvel tem maior potencial de valorização'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imoveis`
--

INSERT INTO `imoveis` (`id`, `titulo`, `descricao`, `preco`, `area`, `quartos`, `banheiros`, `vagas`, `endereco`, `cidade`, `estado`, `cep`, `tipo`, `status`, `imagem_principal`, `imagens`, `caracteristicas`, `data_cadastro`, `data_atualizacao`, `destaque`, `status_construcao`, `ano_entrega`, `maior_valorizacao`) VALUES
(33, 'Atmos PowerEd by Housi', 'A unidade 1409 do Atmos PowerEd by Housi é um studio moderno de 28,53m², localizado em andar alto, com uma vista privilegiada para o pôr do sol. Projetado para oferecer praticidade sem abrir mão do conforto, o apartamento conta com acabamentos de alto padrão, como piso vinílico, porcelanato no banheiro e sacada, bancada em granito e fechadura digital inteligente. Ideal tanto para moradia quanto para investimento, o imóvel une design contemporâneo, infraestrutura tecnológica e a exclusividade de um empreendimento completo, administrado pela Housi, que agrega conveniência e valorização em uma das regiões mais centrais e estratégicas de Curitiba.', 360000.00, 28.53, 1, 1, 1, 'Av. Visconde de Guarapuava, 2807 – Centro', 'Curitiba', 'PR', '80010-100', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1756994975_b62a61540f6bcdc8.png', NULL, 'Características principais:\r\n- Unidade 1409\r\n- Vista livre para o pôr do sol\r\n- Área privativa: 28,53m²\r\n- Studio compacto de alto padrão\r\n\r\nÁrea comum:\r\n- Piscina coberta com raia de 12,5m\r\n- Sauna e SPA\r\n- Skybar com terraço descoberto\r\n- Salões de festas com cozinha gourmet\r\n- Espaço gourmet externo com churrasqueiras\r\n- Academia, pilates, crossfit e funcional\r\n- Pet place e cuidados com animais de estimação\r\n- Coworking e salas de reuniões\r\n- Minimarket e lavanderia compartilhada\r\n- Espaços de lazer: brinquedoteca, game space, estúdio de banda/DJ, basket space, redário, fireplace\r\n\r\nInfraestrutura:\r\n- Fechadura digital inteligente\r\n- Interruptores inteligentes instalados\r\n- Preparação para ar-condicionado\r\n- Sistema central de aquecimento de água\r\n- Elevador panorâmico\r\n- Sistema de vedação acústica\r\n- Estacionamento rotativo com pontos de recarga para veículos elétricos\r\n- Lockers inteligentes para encomendas e bagagens\r\n- Carros e bicicletas compartilhados\r\n\r\nCaracterísticas:\r\n- Piso vinílico no apto\r\n- Piso porcelanato no banheiro e sacada\r\n- Rodapé em poliestireno\r\n- Bancada em granito com cuba de sobrepor\r\n- Torneira monocomando\r\n- Pintura off-white\r\n- Sistema de aquecimento de água central (hoteleiro)', '2025-09-04 14:09:35', '2025-09-04 20:53:58', 0, 'em_construcao', NULL, 0),
(34, 'Studio Park - Novo Mundo', 'O Studio Park é um empreendimento moderno e compacto, projetado para quem busca praticidade, conforto e tecnologia no dia a dia. São estúdios inteligentes em um condomínio completo, com áreas comuns funcionais e infraestrutura de alto padrão.', 199000.00, 20.60, 1, 1, 0, 'Novo Mundo', 'Curitiba', 'PR', '', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757031716_6edaf4a5056bda90.png', '/config/../uploads/imoveis/imoveis_1757031716_a3bf43933f966d5e.png,/config/../uploads/imoveis/imoveis_1757031716_79848849e4a60345.png,/config/../uploads/imoveis/imoveis_1757031716_84e99752c27e4a90.png,/config/../uploads/imoveis/imoveis_1757031716_3ca061ffc6308d18.png,/config/../uploads/imoveis/imoveis_1757031717_d96003badd817cdf.png,/config/../uploads/imoveis/imoveis_1757031717_44a896515fdddd68.png', 'Características principais\r\n- 24 unidades\r\n- 4 pavimentos – estúdios tipos\r\n- Fechadura eletrônica nas portas de entrada das unidades\r\n\r\nÁrea comum\r\n- Sala Fitness\r\n- Coworking\r\n- Área gourmet\r\n- Lavanderia\r\n- Bicicletário\r\n- Rooftop\r\n\r\nInfraestrutura\r\n- Infraestrutura para ar-condicionado nas unidades\r\n- Wi-Fi e cabeamento nas áreas comuns\r\n- Preparação para automação\r\n- Infraestrutura para toalheiro aquecido\r\n- Infraestrutura para espelho antiembaçante\r\n\r\nCaracterísticas\r\n- Estúdios com planta otimizada\r\n- Portão de entrada com fechadura facial\r\n- Ambientes integrados: estar, cozinha e quarto\r\n- Banheiros com preparação para tecnologia de conforto', '2025-09-04 18:54:22', '2025-09-05 00:21:57', 0, 'em_construcao', '2025', 0),
(35, 'Zion José Loureiro - Centro de Curitiba', 'O Zion JL está localizado no coração de Curitiba, em uma região vibrante, jovem e conectada. O projeto oferece apartamentos compactos com plantas inteligentes, design moderno e áreas compartilhadas que proporcionam praticidade e bem-estar. Ideal para quem busca morar ou investir no centro urbano, com todas as facilidades ao redor.', 311621.00, 55.00, 2, 1, 1, 'Rua José Loureiro, 812 – esquina com Rua Conselheiro Laurindo, Centro, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757031664_8db3a7d475932f21.png', '/config/../uploads/imoveis/imoveis_1757031664_5cd0363b38b1ec79.png,/config/../uploads/imoveis/imoveis_1757031664_979e9768db411bbe.png,/config/../uploads/imoveis/imoveis_1757031664_d9844703f3d097b0.png,/config/../uploads/imoveis/imoveis_1757031665_1fc2f2904ca8741d.png,/config/../uploads/imoveis/imoveis_1757031665_297258af5eb117f4.png,/config/../uploads/imoveis/imoveis_1757031665_dc822739fb3caa6f.png', 'Características principais\r\n- 98 unidades compactas\r\n- 18 pavimentos\r\n- Apartamentos de 24,91m² a 55,14m²\r\n- Opções de 1 ou 2 dormitórios\r\n- Plantas inteligentes e otimizadas\r\n- Localização central em Curitiba\r\n\r\nÁrea comum\r\n- Hall de entrada\r\n- Marketplace\r\n- Espaço Office\r\n- Espaço Gourmet\r\n- Academia equipada\r\n- Lavanderia coletiva\r\n- Bicicletário com oficina\r\n- Rooftop externo com fireplace e espaço gourmet\r\n- Espaço de recreação e jogos\r\n- Vagas para carro elétrico\r\n\r\nInfraestrutura\r\n- Fechadura digital nos apartamentos\r\n- Isolamento acústico entre unidades\r\n- Porta com guilhotina de vedação\r\n- Infraestrutura para ar-condicionado\r\n- Vidro laminado em dormitórios e sala\r\n- Central de aquecimento elétrico para chuveiro e lavatórios\r\n- Energia solar para o condomínio\r\n- Preparação para monitoramento remoto\r\n- Reaproveitamento de água das chuvas\r\n\r\nCaracterísticas\r\n- Áreas comuns mobiliadas e decoradas\r\n- Paisagismo nas áreas externas\r\n- Salão de festas gourmet no ático\r\n- Sala de convivência\r\n- Copa de funcionários\r\n- Rooftop com floreiras', '2025-09-04 19:04:03', '2025-09-05 00:21:05', 0, 'em_construcao', '2025', 0),
(36, 'Atílio Bório - Cristo Rei', 'Studios modernos no bairro Cristo Rei, ideais para quem busca praticidade e localização estratégica em Curitiba. Unidades compactas com plantas inteligentes, perfeitas para morar ou investir, próximas a hospitais, universidades e ao Mercado Municipal.', 208000.00, 33.00, 1, 1, 0, 'Rua Atílio Bório, 456 – Bairro Cristo Rei', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757030056_9083cc7c730eef6b.png', '/config/../uploads/imoveis/imoveis_1757030056_205096b71c8671b3.png,/config/../uploads/imoveis/imoveis_1757030056_4577a1e628626b86.png,/config/../uploads/imoveis/imoveis_1757030056_9d89358794e5852c.png,/config/../uploads/imoveis/imoveis_1757030057_c0da311284515fc1.png,/config/../uploads/imoveis/imoveis_1757030057_d20dbd01e9479b89.png,/config/../uploads/imoveis/imoveis_1757030057_5d37281cb9f1b000.png,/config/../uploads/imoveis/imoveis_1757030057_2741b6853f07c20d.png,/config/../uploads/imoveis/imoveis_1757030057_b64e91ab39e0f8b3.png,/config/../uploads/imoveis/imoveis_1757030057_06087bc4e47e8586.png', 'Características principais\r\n- A partir de R$ 208.000 (à vista)\r\n- Studios compactos e funcionais\r\n- Plantas inteligentes e otimizadas\r\n- Localização privilegiada no bairro Cristo Rei\r\n- Lançamento com entrega prevista para setembro/2027\r\n\r\nÁrea comum\r\n- Hall de entrada\r\n- Espaços integrados e modernos\r\n\r\nInfraestrutura\r\n- Estrutura planejada para investidores\r\n- Condomínio com padrão atual e sofisticado\r\n\r\nCaracterísticas\r\n- Studios compactos\r\n- Ambientes otimizados\r\n- Acabamentos modernos\r\n Endereço completo\r\n- Suíça Flats Atílio Bório\r\n- Rua Atílio Bório – Bairro Cristo Rei\r\n- Curitiba – PR', '2025-09-04 19:12:15', '2025-09-04 23:54:18', 0, 'em_construcao', '2027', 0),
(37, 'Atmos PowerEd - Centro de Curitiba', 'O ATMOS é um projeto de estúdios de alto padrão desenvolvido pela VOLT by ATR em parceria com a HOUSI. Conta com 326 unidades distribuídas em 15 pavimentos, trazendo design contemporâneo, infraestrutura completa e serviços modernos integrados via aplicativo. Localizado na Av. Visconde de Guarapuava, uma das regiões mais estratégicas de Curitiba, o empreendimento une sofisticação, funcionalidade e alta rentabilidade para investidores e moradores.', 360000.00, 0.00, 2, 1, 51, 'Av. Visconde de Guarapuava, 2807 – Curitiba/PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757030369_10e1c3f0c5f5a46a.png', '/config/../uploads/imoveis/imoveis_1757030369_4d04bd006ab737c9.png,/config/../uploads/imoveis/imoveis_1757030369_8307a008565294d5.png,/config/../uploads/imoveis/imoveis_1757030369_38728b5af582b17d.png,/config/../uploads/imoveis/imoveis_1757030370_a20a4b853f4ab169.png,/config/../uploads/imoveis/imoveis_1757030370_3797e07036528cb9.png,/config/../uploads/imoveis/imoveis_1757030370_53132d97e5695839.png,/config/../uploads/imoveis/imoveis_1757030370_4b5720c0d7557e48.png', 'Características principais\r\n- 326 studios de alto padrão\r\n- Unidades de 15,62m² a 40,45m²\r\n- Opções de 1 e 2 quartos\r\n- Pé direito normal (2,60m), Plex (4,10m) e Garden (5,30m)\r\n- 51 vagas de carros + 7 vagas de motos (uso rotativo)\r\n- Airbnb Friendly (curta, média e longa duração)\r\n- Fachada contemporânea assinada por Planos Arquitetura\r\n\r\nÁrea comum\r\n- Hall com pé direito duplo\r\n- Coworking interno/externo + salas de reunião\r\n- Minimarket e lockers inteligentes\r\n- Bicicletário, oficina e lava bike\r\n- Estúdios para banda/DJ e game space\r\n- Brinquedoteca, salão de festas e espaço gourmet\r\n- Academia, pilates, crossfit e funcional\r\n- Pet place e pet care\r\n- Piscina coberta com raia de 12,5m\r\n- Sauna e SPA\r\n- Skybar com terraço descoberto\r\n- Beauty space e espaço de massagem\r\n\r\nInfraestrutura\r\n- Kit inteligente incluso: fechadura eletrônica, interruptores inteligentes, piso vinílico e porcelanato\r\n- Sistema de vedação acústica e drywall com isolamento térmico\r\n- Elevador panorâmico instagramável\r\n- Floreiras com irrigação automática\r\n- Preparação para ar-condicionado\r\n- Água aquecida por sistema central (hotelaria)\r\n- Serviços pay-per-use (laundry, maid, car wash, dog walker, personal trainer etc.)\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais\r\n- Plantas inteligentes e otimizadas\r\n- Opções com varanda e terraço\r\n- Entregues com acabamentos modernos\r\n- Flexibilidade de uso: short, mid e long stay\r\n- Rentabilidade e valorização garantida', '2025-09-04 19:22:47', '2025-09-04 23:59:30', 0, 'em_construcao', NULL, 0),
(38, 'Mora Conví - São Francisco, Curitiba', 'O Mora Conví é um empreendimento moderno localizado em uma das regiões mais valorizadas de Curitiba, pensado para quem busca praticidade, conforto e bem-estar em um só lugar. Com studios e apartamentos compactos, o projeto une design inteligente, infraestrutura completa e áreas de convivência que valorizam o estilo de vida urbano.', 340000.00, 40.45, 2, 1, 51, 'Rua Atílio Bório – Bairro Cristo Rei, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757031557_685da13529c55053.png', '/config/../uploads/imoveis/imoveis_1757031557_2301d8d5b510ced4.png,/config/../uploads/imoveis/imoveis_1757031558_1d402d0d1031e2c4.png,/config/../uploads/imoveis/imoveis_1757031558_6efc682e6986a0fd.png,/config/../uploads/imoveis/imoveis_1757031558_87d86afe7080a07b.png,/config/../uploads/imoveis/imoveis_1757031558_a80daab3861c4093.png,/config/../uploads/imoveis/imoveis_1757031558_38cc2debb69d9f67.png,/config/../uploads/imoveis/imoveis_1757031558_7d79b18ac95ba727.png,/config/../uploads/imoveis/imoveis_1757031559_64f08617c2a62978.png,/config/../uploads/imoveis/imoveis_1757031559_6d9d79ed5e763e47.png,/config/../uploads/imoveis/imoveis_1757031559_f9202ce261cc155b.png', 'Características principais\r\n- Unidades de 15,62m² a 40,45m²\r\n- Opções de studios, 1 e 2 quartos\r\n- 1 banheiro por unidade\r\n- 51 vagas de carros + 7 vagas de motos (uso rotativo)\r\n- Localização privilegiada em Curitiba\r\n- Projeto arquitetônico contemporâneo\r\n- Ideal para morar ou investir\r\n- Entrega prevista para setembro/2027\r\n\r\nÁrea comum\r\n- Academia equipada\r\n- Espaço gourmet\r\n- Coworking\r\n- Bicicletário\r\n- Salão de festas\r\n- Lounge de convivência\r\n\r\nInfraestrutura\r\n- Fechadura eletrônica nas unidades\r\n- Preparação para ar-condicionado\r\n- Áreas comuns decoradas e mobiliadas\r\n- Estrutura planejada para investidores\r\n- Condomínio moderno e seguro\r\n\r\nCaracterísticas\r\n- Ambientes integrados\r\n- Acabamentos de alto padrão\r\n- Plantas otimizadas\r\n- Opções com sacada\r\n- Conforto acústico e térmico', '2025-09-04 19:33:50', '2025-09-05 00:19:19', 0, 'em_construcao', '2027', 0),
(39, 'Fernando Amaro', 'O Suíça Flats Fernando Amaro foi projetado para unir qualidade, conforto e modernidade em cada detalhe. Com fachada imponente, ambientes bem planejados e serviços que facilitam o dia a dia, é um empreendimento ideal para quem busca praticidade e investimento seguro. Todas as unidades possuem sacada e áreas otimizadas para oferecer uma experiência única aos moradores e hóspedes.', 220000.00, 27.16, 1, 1, 0, 'Rua Fernando Amaro, 82 – Bairro Alto da XV, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757030243_7d239d3b3855b697.png', '/config/../uploads/imoveis/imoveis_1757029494_d0377522cd459dbc.png,/config/../uploads/imoveis/imoveis_1757029494_47d4c1e64d87c0ad.png,/config/../uploads/imoveis/imoveis_1757030243_ae611fd4f62bb3c1.png,/config/../uploads/imoveis/imoveis_1757030243_0865cd0275b3bb1b.png,/config/../uploads/imoveis/imoveis_1757030244_62b3d3fdfe5096a5.png,/config/../uploads/imoveis/imoveis_1757030244_ec50460378a2f241.png,/config/../uploads/imoveis/imoveis_1757030244_1bbb3e1dd102fccf.png,/config/../uploads/imoveis/imoveis_1757030244_ed1987687af01427.png,/config/../uploads/imoveis/imoveis_1757030244_5c3f44c85758ded3.png,/config/../uploads/imoveis/imoveis_1757030244_7e3d92f28fd6d1b5.png,/config/../uploads/imoveis/imoveis_1757030244_a549794d850d20fe.png', 'Características principais\r\n- 59 unidades\r\n- Plantas de 22,57 m² a 27,16 m²\r\n- Todas as unidades com sacada\r\n- Opções com garden\r\n- Fachada moderna e imponente\r\n\r\nÁrea comum\r\n- Cinema ao ar livre\r\n- Pub\r\n- Área gourmet\r\n- Academia equipada\r\n- Pet place\r\n- Coworking\r\n- Bicicletário\r\n- Minimercado\r\n- Lavanderia\r\n\r\nInfraestrutura\r\n- Office administrativo para gestão dos studios\r\n- Check-in e check-out simplificados\r\n- Serviços pay per use (limpeza, manutenção e governança sob demanda)\r\n- Apoio administrativo e comercial\r\n- Manutenção rápida e constante\r\n- Solução completa para valorização do imóvel\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais\r\n- Unidades entregues com sacada\r\n- Possibilidade de garden\r\n- Ambientes planejados para locação short stay\r\n- Opção de mobília e decoração completas através da Suíça Möbel', '2025-09-04 19:41:59', '2025-09-04 23:57:25', 0, 'em_construcao', NULL, 0),
(40, 'Sense 1910', 'O SENSE 1910 é um empreendimento moderno e compacto, projetado para oferecer praticidade e qualidade de vida em uma das regiões mais completas de Curitiba. Com unidades tipo studio, garden e cobertura, é ideal tanto para morar quanto para investir, unindo conforto, mobilidade e excelente rentabilidade.', 156000.00, 19.11, 1, 1, 1, 'Rua Capitão João Zaleski, 1910 – Bairro Lindóia – Curitiba/PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757031504_c35219fec00360a1.png', '/config/../uploads/imoveis/imoveis_1757031505_8c8e068c14101701.png,/config/../uploads/imoveis/imoveis_1757031505_41f72b5c987f52de.png,/config/../uploads/imoveis/imoveis_1757031505_e791d1a91f0976ab.png,/config/../uploads/imoveis/imoveis_1757031505_986b57d3baea6e20.png,/config/../uploads/imoveis/imoveis_1757031505_a4456e29da4d3749.png,/config/../uploads/imoveis/imoveis_1757031506_dcfb8d1213b7df89.png', 'Características principais\r\n- Studios a partir de 19,11 m²\r\n- Opções de plantas: tipo, garden e cobertura\r\n- Localização estratégica próxima a shoppings, mercados e transporte\r\n- Projeto moderno e funcional\r\n- Excelente opção de investimento com boa liquidez e valorização\r\n- Sem vagas de garagem\r\n- Prazo de entrega: não informado\r\n\r\nÁrea comum\r\n- Não especificada no material disponibilizado\r\n\r\nInfraestrutura\r\n- Estrutura pensada para short stay\r\n- Rentabilidade média projetada com ocupação de 60% a 80%\r\n- Investimento escalável e de baixo risco\r\n- Opção de gerar renda mensal com locação\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais (19,11 m²)\r\n- Opções com garden e cobertura\r\n- Ambientes planejados para moradia ou investimento\r\n- Sugestões de decoração disponíveis (não inclusas na entrega)\r\n- Sem quartos separados (planta tipo studio)', '2025-09-04 19:48:35', '2025-09-05 00:18:26', 0, 'em_construcao', NULL, 0),
(41, 'Cabral Smart Comfort - Bairro Cabral', 'O Cabral Smart Comfort é um empreendimento projetado para proporcionar alta lucratividade a investidores e praticidade, conforto e qualidade de vida aos moradores. Composto exclusivamente por studios, une acabamentos de alto padrão, fachada imponente e infraestrutura completa, sendo uma oportunidade sólida de investimento imobiliário e renda passiva', 230000.00, 24.47, 1, 1, 9, 'Rua Leão Sallum, 564 – Bairro Boa Vista, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757032026_8aee859ba469ce46.png', '/config/../uploads/imoveis/imoveis_1757032026_f378c6b2b672c2db.png,/config/../uploads/imoveis/imoveis_1757032026_139dde6ea5b4327d.png,/config/../uploads/imoveis/imoveis_1757032026_c366b1051a3141d0.png,/config/../uploads/imoveis/imoveis_1757032027_791353c404e71995.png', 'Características principais\r\n- 37 unidades\r\n- 4 diferentes plantas (22,57m² a 24,47m²)\r\n- 2 unidades garden\r\n- Todas as unidades com sacada\r\n- Studios entregues com acabamentos de alto padrão\r\n- Opções com e sem vaga de garagem\r\n- 6 pavimentos + loja comercial no térreo\r\n- Fachada contemporânea\r\n\r\nÁrea comum\r\n- Rooftop com áreas de lazer\r\n- Academia equipada\r\n- 2 churrasqueiras com espaço para festas\r\n- Sollarium com deck e lareira externa\r\n- Lavanderia coletiva (pay per use)\r\n\r\nInfraestrutura\r\n- Serviços pay per use: lavanderia, faxina, manutenção e administração de locações\r\n- Guarita delivery\r\n- Preparação para ar-condicionado\r\n- Projeto arquitetônico assinado por Rafael Haliski\r\n- Empreendimento com 1.322m² construídos\r\n- 9 vagas de garagem\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais\r\n- Plantas inteligentes e bem distribuídas\r\n- Banheiros com ventilação natural em algumas unidades\r\n- Porcelanato no banheiro e sacada\r\n- Louças e metais no banheiro instalados\r\n- Portas com espuma anti-impacto\r\n- Gesso nas áreas úmidas\r\n- Possibilidade de mobília e decoração completas através da Cabral Imóveis', '2025-09-04 20:03:27', '2025-09-05 00:27:07', 0, 'em_construcao', '2025', 0),
(43, 'Mora Parque Bacacheri - Curitiba', 'O Mora Conví é um empreendimento moderno localizado no Centro Cívico, em Curitiba. Pensado para quem busca praticidade e estilo de vida urbano, oferece studios compactos, plantas inteligentes e áreas comuns completas para lazer, trabalho e convivência.', 289000.00, 27.00, 1, 1, 1, 'Rua Prudente de Moraes, nº 646 – Bairro Centro Cívico, Curitiba – PR.', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757031181_ea946a2f957bb743.png', '/config/../uploads/imoveis/imoveis_1757031104_6754e97cc018b892.png,/config/../uploads/imoveis/imoveis_1757031104_6a2344d99aa5f1b3.png,/config/../uploads/imoveis/imoveis_1757031105_32636b03c3e33fac.png,/config/../uploads/imoveis/imoveis_1757031105_ed3d7b6b7586ff4c.png,/config/../uploads/imoveis/imoveis_1757031105_31b1554a84e201cd.png,/config/../uploads/imoveis/imoveis_1757031105_d7900cb737929fd6.png,/config/../uploads/imoveis/imoveis_1757031105_fa96419e1febf298.png,/config/../uploads/imoveis/imoveis_1757031106_77af1c881fba03d5.png,/config/../uploads/imoveis/imoveis_1757031182_129147c48c301fa9.png,/config/../uploads/imoveis/imoveis_1757031182_a77a3dd25ddb7302.png,/config/../uploads/imoveis/imoveis_1757031182_7e27a19792fdd2ee.png,/config/../uploads/imoveis/imoveis_1757031182_18b645186fd4fc19.png,/config/../uploads/imoveis/imoveis_1757031182_e9acb4ca7e216a6d.png,/config/../uploads/imoveis/imoveis_1757031182_df37f46cb78db0b6.png,/config/../uploads/imoveis/imoveis_1757031183_996a476fc64e6418.png,/config/../uploads/imoveis/imoveis_1757031183_90a3c6dd17dd6221.png,/config/../uploads/imoveis/imoveis_1757031183_ce01864d103c6244.png,/config/../uploads/imoveis/imoveis_1757031183_3a5ca2cbd2975784.png', 'Características principais\r\n– Studios de alto padrão\r\n– Plantas inteligentes e funcionais\r\n– Localização estratégica no Centro Cívico\r\n– Ambientes modernos e sofisticados\r\n– Opção ideal para moradia ou investimento\r\n\r\nÁrea comum\r\n– Rooftop com áreas de lazer\r\n– Piscina\r\n– Academia equipada\r\n– Espaços gourmet e de convivência\r\n– Coworking integrado\r\n– Salão de festas\r\n\r\nInfraestrutura\r\n– Hall de entrada decorado\r\n– Fechaduras digitais nas unidades\r\n– Preparação para ar-condicionado\r\n– Lockers inteligentes\r\n– Estrutura pensada para locação por temporada\r\n\r\nCaracterísticas\r\n– Studios compactos e funcionais\r\n– Acabamentos de alto padrão\r\n– Ambientes integrados (sala, quarto e cozinha)\r\n– Plantas versáteis e otimizadas', '2025-09-04 20:23:15', '2025-09-05 00:13:03', 0, 'em_construcao', '2026', 0),
(44, 'Studio Park 02', 'Studios compactos e funcionais, pensados para praticidade e conforto no dia a dia. O empreendimento conta com áreas modernas de lazer e infraestrutura inteligente, ideal para moradia ou investimento.', 213704.00, 51.00, 1, 1, 0, 'Guaíra', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757031296_37bc616b4af109f1.jpg', '/config/../uploads/imoveis/imoveis_1757031296_60c10d5f1217ac0c.jpg,/config/../uploads/imoveis/imoveis_1757031296_72b1e7417f1f3aaa.jpg,/config/../uploads/imoveis/imoveis_1757031297_dc53d51946374d10.jpg,/config/../uploads/imoveis/imoveis_1757031297_87d3ee9b66d5684b.jpg,/config/../uploads/imoveis/imoveis_1757031297_1954cbd5bdcb79fa.jpg,/config/../uploads/imoveis/imoveis_1757031298_2d381708a96b9ac7.jpg,/config/../uploads/imoveis/imoveis_1757031298_4e6a1a023c6fdfca.jpg,/config/../uploads/imoveis/imoveis_1757031298_cb02da08b9bd324d.jpg,/config/../uploads/imoveis/imoveis_1757031298_8a04d11187a33892.jpg,/config/../uploads/imoveis/imoveis_1757031299_2247b71959ec20d4.jpg', 'Características principais\r\n- 24 unidades\r\n- 4 pavimentos\r\n- Fechadura facial no portão de entrada\r\n\r\nÁrea comum\r\n- Coworking\r\n- Espaço Gourmet\r\n- Lavanderia\r\n- Rooftop\r\n\r\nInfraestrutura\r\n- Infraestrutura para ar-condicionado nas unidades\r\n- Wi-Fi e cabeamento nas áreas comuns\r\n- Infraestrutura para automação\r\n- Infraestrutura para toalheiro aquecido\r\n- Infraestrutura para espelho antiembaçante\r\n- Garagem\r\n- Elevador\r\n\r\nCaracterísticas\r\n- Studios com planta otimizada\r\n- Ambientes integrados: estar, cozinha e quarto', '2025-09-04 20:47:46', '2025-09-05 00:14:59', 0, 'em_construcao', NULL, 0),
(45, 'AOI TATEMONO', 'O AOI TATEMONO é um empreendimento moderno e exclusivo localizado no bairro Água Verde, em Curitiba. Com design sofisticado e apenas 36 unidades distribuídas em 9 pavimentos, o projeto traz praticidade, conforto e elegância em cada detalhe.', 309923.00, 32.00, 1, 1, 8, 'Rua Dr. Alexandre Gutierrez, 766 – Água Verde, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757029171_ca9a0fbd739aec61.png', '/config/../uploads/imoveis/imoveis_1757029171_590c3f5bf02621f8.png,/config/../uploads/imoveis/imoveis_1757029172_171a27950162baa1.png,/config/../uploads/imoveis/imoveis_1757029172_371fe17e8898eb55.png', 'Características principais\r\n\r\n36 unidades\r\n\r\n9 pavimentos\r\n\r\n8 vagas de garagem', '2025-09-04 21:20:51', '2025-09-04 23:39:32', 0, 'em_construcao', '2026', 0),
(47, 'Joy Living - Piacentini Construtora', 'O My Place Jardim Botânico by Helbor é um empreendimento moderno e sofisticado, localizado em uma das regiões mais valorizadas de Curitiba. Com opções de studios e apartamentos de 2 dormitórios, oferece plantas funcionais de 32 m² a 46 m² privativos, além de unidades garden. O projeto foi desenvolvido por renomados arquitetos e paisagistas, unindo praticidade, conforto e contato direto com o Jardim Botânico.', 296958.00, 58.00, 2, 1, 1, 'Rua Gov. Agamenon Magalhães, nº 78 e Rua João Dranka, nº 81 Bairro Jardim Botânico – Curitiba/PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757030540_9af86d129b36e067.png', '/config/../uploads/imoveis/imoveis_1757030540_90dffd1c619debd5.png,/config/../uploads/imoveis/imoveis_1757030540_5f96720a538c4716.png,/config/../uploads/imoveis/imoveis_1757030540_dab495c13415e892.png,/config/../uploads/imoveis/imoveis_1757030540_d26cb63a8f9177a8.png,/config/../uploads/imoveis/imoveis_1757030540_8d5f1e529d578986.png,/config/../uploads/imoveis/imoveis_1757030541_16836b9fc3324b2e.png,/config/../uploads/imoveis/imoveis_1757030541_8d236da741a48db8.png,/config/../uploads/imoveis/imoveis_1757030541_eda7a3bbd3bf91da.png,/config/../uploads/imoveis/imoveis_1757030541_786c066ac8303e19.png', 'Características principais\r\n- 1 torre com 17 pavimentos\r\n- 146 unidades\r\n- Studios a partir de 32 m² privativos\r\n- Apartamentos de 2 dormitórios a partir de 46 m² privativos\r\n- Unidades garden com terraço descoberto\r\n- 1 vaga por unidade (com ou sem vaga, dependendo da tipologia)\r\n- Arquitetura: Ricardo Amaral Arquitetos Associados\r\n- Paisagismo: Benedito Abbud Paisagismo\r\n- Decoração: Sandra Pini Arquitetos Associados\r\n\r\nÁrea comum\r\n- Piscina climatizada\r\n- Deck molhado\r\n- Pool house e pool bar\r\n- Salão de festas com apoio\r\n- Gourmet\r\n- Playground\r\n- Brinquedoteca\r\n- Solarium\r\n- Lounge lareira e lounge mirante (rooftop)\r\n- Fitness (rooftop)\r\n- Lavanderia coletiva\r\n- Bicicletário\r\n\r\nInfraestrutura\r\n- Portaria com acesso social e de serviços\r\n- Delivery e praça de apoio\r\n- Fechadura biométrica nos apartamentos\r\n- Tomadas USB nos dormitórios e living\r\n- Projeto com áreas sociais mobiliadas e decoradas\r\n\r\nCaracterísticas\r\n- Studios de 32 m² privativos\r\n- Apartamentos de 2 dormitórios de 46 m² privativos\r\n- Unidades garden com até 58 m² (incluindo terraço)\r\n- Ambientes otimizados e funcionais\r\n- Localização privilegiada próxima ao Jardim Botânico\r\n- Fácil acesso ao centro, aeroporto, shoppings e Linha Verde', '2025-09-04 21:52:16', '2025-09-05 00:02:21', 0, 'em_construcao', NULL, 0),
(48, 'Compass Coliving - Jal Empreendimentos', 'Empreendimento moderno que combina arquitetura sofisticada, áreas de lazer completas e infraestrutura planejada, ideal para moradia ou investimento.', 263119.00, 40.98, 1, 1, 1, 'Rua Visconde de Nácar, 1050 – Centro, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757029000_09cf3b5398ca067a.png', '/config/../uploads/imoveis/imoveis_1757029000_5590269fa1320172.png,/config/../uploads/imoveis/imoveis_1757029000_773124cff1893109.png', 'Características principais\r\n- 1 torre com 17 pavimentos\r\n- 146 unidades\r\n- Studios a partir de 32 m² privativos\r\n- Apartamentos de 2 dormitórios a partir de 46 m² privativos\r\n- Unidades garden com terraço descoberto\r\n- 1 vaga por unidade (com ou sem vaga, dependendo da tipologia)\r\n- Arquitetura: Ricardo Amaral Arquitetos Associados\r\n- Paisagismo: Benedito Abbud Paisagismo\r\n- Decoração: Sandra Pini Arquitetos Associados\r\n\r\nÁrea comum\r\n- Piscina climatizada\r\n- Deck molhado\r\n- Pool house e pool bar\r\n- Salão de festas com apoio gourmet\r\n- Playground\r\n- Brinquedoteca\r\n- Solarium\r\n- Lounge lareira e lounge mirante (rooftop)\r\n- Fitness (rooftop)\r\n- Lavanderia coletiva\r\n- Bicicletário\r\n\r\nInfraestrutura\r\n- Portaria com acesso social e de serviços\r\n- Delivery e praça de apoio\r\n- Fechadura biométrica nos apartamentos\r\n- Tomadas USB nos dormitórios e living\r\n- Projeto com áreas sociais mobiliadas e decoradas\r\n\r\nCaracterísticas\r\n- Studios de 32 m²\r\n- Apartamentos de 2 dormitórios a partir de 46 m²\r\n- Unidades garden com terraço privativo\r\n- Vagas de garagem (dependendo da tipologia)', '2025-09-04 22:04:18', '2025-09-04 23:36:41', 0, 'em_construcao', NULL, 0),
(49, 'Aya Carlos de Carvalho - Residencial Blue', 'Empreendimento moderno e completo, com localização estratégica no coração de Curitiba, próximo ao Batel e Centro. Oferece studios e apartamentos funcionais, com opções flexíveis e áreas comuns sofisticadas, ideais tanto para moradia quanto para investimento.', 417039.00, 54.00, 1, 1, 208, 'Av. Visconde de Nácar, esquina com Av. Carlos de Carvalho e Rua Cruz Machado – Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757028783_9af6e1b3d6a61239.png', '/config/../uploads/imoveis/imoveis_1757028784_6635317cd4d2e0ca.png,/config/../uploads/imoveis/imoveis_1757028784_777e2a4372bc4257.png,/config/../uploads/imoveis/imoveis_1757028784_8fab055f1d78e24b.png,/config/../uploads/imoveis/imoveis_1757028784_152fa44f13ccd13f.png,/config/../uploads/imoveis/imoveis_1757028784_0a2e5b7a4eb96353.png,/config/../uploads/imoveis/imoveis_1757028784_d1b4a21875e16137.png,/config/../uploads/imoveis/imoveis_1757028785_a4aa008cafd4a313.png', 'Características principais\r\n- 882 unidades residenciais\r\n- Tipologias: Studios (24 a 28m²), Studios PLEX, 1 dormitório (37m²), 1 suíte + home (48m²), 1 suíte + home com churrasqueira (54m²), 2 suítes flex (53 a 54m²)\r\n- 6 lojas comerciais\r\n- 208 vagas (capacidade para até 257 veículos)\r\n- 9 elevadores (1 de emergência)\r\n- 32 pavimentos + pavimento técnico e ático\r\n- 3 subsolos\r\n\r\nÁrea comum\r\n- Térreo: lounge, delivery, market, administração\r\n- 2º pavimento: lavanderia, coworking, sala de reunião, salão de festas, 2 churrasqueiras, praça externa, pet place, playground, sala de jogos\r\n- Ático: piscina coberta, academia, solarium, lounge externo, espaço wellness\r\n- Rooftop com áreas de convivência\r\n\r\nInfraestrutura\r\n- Hall de entrada decorado\r\n- Projeto de segurança Haganá\r\n- 86 vagas preparadas para carro elétrico\r\n- Fechaduras eletrônicas nas unidades\r\n- Preparação para ar-condicionado (pontos split)\r\n- Central de aquecimento para água quente em banheiros e cozinha\r\n- Áreas comuns entregues mobiliadas e decoradas\r\n- Ampla praça privativa (gramado)\r\n\r\nCaracterísticas\r\n- Sacadas amplas com guarda-corpo em vidro\r\n- Ambientes funcionais e otimizados\r\n- Pé-direito diferenciado (3,6m nas unidades PLEX)\r\n- Banheiros entregues com tampo, metais e louças\r\n- Fachada contemporânea e elegante\r\n- Plantas inteligentes e flexíveis', '2025-09-04 22:14:10', '2025-09-04 23:33:05', 0, 'em_construcao', NULL, 0),
(50, 'Ama - Aliv Incorporadora', 'Uma oportunidade estratégica para investir no futuro.\r\nNo coração do bairro Portão, um dos pontos mais promissores de Curitiba, nasce um empreendimento pensado para quem busca valorização e praticidade. Com projeto inovador assinado pelo renomado Estúdio 41, o edifício redefine o conceito de moradia inteligente, combinando arquitetura contemporânea com funcionalidade.\r\nA fachada com terraços escalonados amplia os espaços de convivência e traz uma atmosfera única ao projeto. As áreas comuns foram planejadas de forma enxuta, garantindo taxa condominial reduzida e maior atratividade para locação.', 257920.00, 24.00, 2, 1, 0, 'Bairro Portão', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757028596_74a1ebd5b5dc7c0a.png', '/config/../uploads/imoveis/imoveis_1757028596_afdcfe8a4494d2d7.png,/config/../uploads/imoveis/imoveis_1757028596_d1a2687a878eb53d.png,/config/../uploads/imoveis/imoveis_1757028596_214a1e74fb56acc4.png,/config/../uploads/imoveis/imoveis_1757028597_e36145ec9687de9b.png,/config/../uploads/imoveis/imoveis_1757028597_a0ad16028ffc9ccc.png,/config/../uploads/imoveis/imoveis_1757028597_37584872b6f0bf9e.png,/config/../uploads/imoveis/imoveis_1757028597_32e75711ea21e635.png,/config/../uploads/imoveis/imoveis_1757028597_76a0bd5660848a54.png', 'Características principais\r\n- Localização estratégica no bairro Portão, Curitiba\r\n- Projeto assinado pelo Estúdio 41\r\n- Conceito de moradia inteligente\r\n- Arquitetura contemporânea e funcional\r\n- Fachada com terraços escalonados\r\n\r\nÁrea comum\r\n- Espaços de convivência otimizados\r\n- Áreas planejadas para garantir taxa condominial reduzida\r\nInfraestrutura\r\n- Estrutura pensada para valorização e praticidade\r\n- Projeto voltado para atratividade em locação\r\n\r\nCaracterísticas\r\n- Moradia inteligente\r\n- Arquitetura inovadora e contemporânea\r\n- Funcionalidade e praticidade no dia a dia', '2025-09-04 22:28:03', '2025-09-04 23:29:57', 0, 'em_construcao', NULL, 0),
(51, 'Civitas - Studio - Blue', 'O Civitas é um empreendimento moderno localizado no coração do Centro Cívico, em Curitiba. Planejado para atender às demandas de investidores e moradores, oferece studios funcionais, plantas inteligentes e áreas comuns completas. Com localização estratégica próxima a pontos turísticos, órgãos públicos e centros de negócios, o Civitas é ideal tanto para moradia quanto para investimento em locações de curta e longa duração.', 314972.00, 45.71, 1, 1, 43, 'Rua da Glória, 369 – Centro Cívico, Curitiba – PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757028324_11eab4dfe26f5eb8.png', '/config/../uploads/imoveis/imoveis_1757028324_f5248814cf6d228e.png,/config/../uploads/imoveis/imoveis_1757028324_641fc530c66f94c1.png,/config/../uploads/imoveis/imoveis_1757028324_190275bf3b997607.png,/config/../uploads/imoveis/imoveis_1757028324_c64979f45236075b.png,/config/../uploads/imoveis/imoveis_1757028325_721d73206344d067.png,/config/../uploads/imoveis/imoveis_1757028325_07a1097347646cdc.png,/config/../uploads/imoveis/imoveis_1757028325_8f7af57ece2883d6.png,/config/../uploads/imoveis/imoveis_1757028325_b0ea46fb3aec03eb.png,/config/../uploads/imoveis/imoveis_1757028325_ef51c25f9f46cd1d.png,/config/../uploads/imoveis/imoveis_1757028325_ca3640c64524f54f.png', 'Características principais\r\n- Área do terreno: 1.082,3 m²\r\n- 1 torre com térreo + 9 pavimentos + ático\r\n- Total de 130 unidades\r\n- Distribuição: Térreo com 6 unidades, 2º ao 5º pavimento com 16 unidades por andar, 6º ao 10º pavimento com 12 unidades por andar\r\n- Tipologia: Studios\r\n- Metragens: 20,37 m² a 45,71 m²\r\n- Pé direito: 2,80 m e opção Plex com 3,60 m\r\n- Sacadas em todas as unidades\r\n- 28 vagas cobertas, 11 descobertas e 4 de moto\r\n\r\nÁrea comum\r\n- Hall de entrada decorado\r\n- Academia equipada\r\n- Espaço gourmet\r\n- Mercadinho\r\n- Coworking\r\n- Lavanderia\r\n- Rooftop com vista panorâmica\r\n\r\n Infraestrutura\r\n- Fechadura eletrônica em todos os apartamentos\r\n- Infraestrutura para carro elétrico\r\n- Infraestrutura para instalação de painéis fotovoltaicos\r\n- Áreas comuns com acessibilidade\r\n- Central de aquecimento a gás para chuveiros\r\n- Banheiros com bancadas em granito\r\n- Preparação para ar-condicionado\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais\r\n- Plantas otimizadas e inteligentes\r\n- Ambientes integrados: estar, cozinha e quarto\r\n- Opções de unidades com pé direito duplo (Plex)\r\n- Possibilidade de unidades garden (45,71 m²)', '2025-09-04 22:34:50', '2025-09-04 23:25:26', 0, 'em_construcao', NULL, 0),
(52, 'Cosmo Hype Living - Hype', 'O Cosmo Hype Living é um empreendimento moderno e urbano, inspirado no design contemporâneo e na vida conectada. Projetado para quem valoriza sofisticação funcional, tecnologia inteligente e interação, oferece studios, gardens, apartamentos de 1, 2 e 3 quartos com suíte, além de unidades duplex. Cada detalhe foi pensado para proporcionar conforto térmico, sustentabilidade e bem-estar, unindo conveniência e qualidade de vida em um só lugar.', 392900.00, 26.00, 1, 1, 0, 'Rua Fernando Amaro, 363 – Bairro Alto da XV – Curitiba/PR', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757028809_696a1d8e6dc23f2a.png', '/config/../uploads/imoveis/imoveis_1757028809_cb6e327d4e2d21b5.png,/config/../uploads/imoveis/imoveis_1757028810_6dc6145c344a357c.png,/config/../uploads/imoveis/imoveis_1757028810_904d175964ffb3ef.png,/config/../uploads/imoveis/imoveis_1757028810_e213aeddb6ffe7c4.png,/config/../uploads/imoveis/imoveis_1757028810_a1ec870680a2fac8.png,/config/../uploads/imoveis/imoveis_1757028810_6a1fc87a894ebdec.png', 'Características principais\r\n- 1 torre\r\n- 7 pavimentos + ático\r\n- 129 unidades\r\n- 2 elevadores\r\n- Tipologias: studios, gardens, apartamentos de 1, 2 e 3 quartos com suíte e loft duplex\r\n- Metragens: 26 m² a 70 m²\r\n- Ambientes entregues decorados e mobiliados\r\n\r\nÁrea comum\r\n- Bicicletário\r\n- Hall & coworking\r\n- Salas de reunião\r\n- Espaço gourmet\r\n- Game room\r\n- Academia / Fitness\r\n- Piscina aquecida\r\n- Pet place\r\n- Pub\r\n- Lavanderia\r\n- Terraço\r\n- Market\r\n- Co-Kitchen\r\n- Espaço Wellness\r\n- Paisagismo regenerativo\r\n\r\nInfraestrutura\r\n- Projeto certificado GBC Biodiversidade\r\n- Conforto térmico e lumínico com otimização de luz natural\r\n- Uso de espécies nativas e endêmicas no paisagismo\r\n- Redução da necessidade de irrigação e manutenção\r\n- Ambientes planejados para bem-estar e sustentabilidade\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais\r\n- Apartamentos de 1 a 3 quartos com suíte\r\n- Unidades duplex com terraços\r\n- Gardens privativos\r\n- Ambientes modernos, práticos e sofisticados\r\n- Estrutura voltada para vida urbana e conectada', '2025-09-04 22:45:19', '2025-09-04 23:33:30', 0, 'em_construcao', NULL, 0),
(53, 'Atto - RA Empreendimentos', 'O ATTO é um empreendimento moderno e funcional, localizado em uma das regiões mais valorizadas de Curitiba. Com plantas inteligentes, áreas comuns completas e infraestrutura pensada para bem-estar e praticidade, é ideal tanto para morar quanto para investir.', 412391.00, 26.00, 1, 1, 0, 'Rua Brigadeiro Franco, 1580 – Rebouças, Curitiba – PR.', 'Curitiba', 'PR', '80010-000', 'studio', 'disponivel', '/config/../uploads/imoveis/imoveis_1757028106_2394eafac595716b.png', NULL, 'Características principais\r\n- 1 torre\r\n- 7 pavimentos + ático\r\n- 129 unidades\r\n- 2 elevadores\r\n- Tipologias: studios, gardens, apartamentos de 1, 2 e 3 quartos com suíte e loft duplex\r\n- Metragens de 26 m² a 70 m²\r\n- Ambientes entregues decorados e mobiliados\r\n\r\nÁrea comum\r\n- Bicicletário\r\n- Hall & coworking\r\n- Salas de reunião\r\n- Espaço gourmet\r\n- Game room\r\n- Academia / Fitness\r\n- Piscina aquecida\r\n- Pet place\r\n- Pub\r\n- Lavanderia\r\n- Terraço\r\n- Market\r\n- Co-Kitchen\r\n- Espaço Wellness\r\n- Paisagismo regenerativo\r\n\r\nInfraestrutura\r\n- Projeto certificado GBC Biodiversidade\r\n- Conforto térmico e lumínico com otimização de luz natural\r\n- Uso de espécies nativas e endêmicas no paisagismo\r\n- Redução da necessidade de irrigação e manutenção\r\n- Ambientes planejados para bem-estar e sustentabilidade\r\n\r\nCaracterísticas\r\n- Studios compactos e funcionais\r\n- Apartamentos de 1 a 3 quartos com suíte\r\n- Unidades duplex com terraço\r\n- Ambientes otimizados e planejados', '2025-09-04 22:57:51', '2025-09-04 23:21:46', 0, 'em_construcao', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis_caracteristicas`
--

CREATE TABLE `imoveis_caracteristicas` (
  `id` int NOT NULL,
  `imovel_id` int NOT NULL,
  `caracteristica` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis_imagens`
--

CREATE TABLE `imoveis_imagens` (
  `id` int NOT NULL,
  `imovel_id` int NOT NULL,
  `caminho_imagem` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('imagem_principal','imagem_01','imagem_02','imagem_03','imagem_04') COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_upload` datetime NOT NULL,
  `ordem` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imoveis_venda`
--

CREATE TABLE `imoveis_venda` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `tipo_operacao` enum('alugar','vender') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_imovel` enum('apartamento','casa','casa_condominio','studio','cobertura','terreno','comercial') COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(15,2) NOT NULL,
  `valor_condominio` decimal(10,2) DEFAULT NULL,
  `valor_iptu` decimal(10,2) DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endereco` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bairro` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metragem` decimal(8,2) NOT NULL,
  `dormitorios` int NOT NULL,
  `vagas` int DEFAULT '0',
  `banheiros` int NOT NULL,
  `suites` int DEFAULT '0',
  `detalhes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pendente','aprovado','rejeitado','vendido','alugado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente',
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imovel_categorias`
--

CREATE TABLE `imovel_categorias` (
  `id` int NOT NULL,
  `imovel_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_acesso`
--

CREATE TABLE `logs_acesso` (
  `id` int NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `acao` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabela` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro_id` int DEFAULT NULL,
  `dados_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `dados_novos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `data_log` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ativo','inativo') COLLATE utf8mb4_unicode_ci DEFAULT 'ativo',
  `data_inscricao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `regioes`
--

CREATE TABLE `regioes` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `imagem` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creci` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_cadastro` datetime NOT NULL,
  `status` enum('ativo','inativo') COLLATE utf8mb4_unicode_ci DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_admin`
--

CREATE TABLE `usuarios_admin` (
  `id` int NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nivel` enum('admin','gerente','operador') COLLATE utf8mb4_unicode_ci DEFAULT 'operador',
  `status` enum('ativo','inativo') COLLATE utf8mb4_unicode_ci DEFAULT 'ativo',
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios_admin`
--

INSERT INTO `usuarios_admin` (`id`, `nome`, `email`, `senha`, `nivel`, `status`, `ultimo_login`, `data_cadastro`, `data_atualizacao`) VALUES
(2, 'Administrador', 'admin@br2studios.com.br', '$2y$12$lORXAwHGd5KOzB.h5WzsouHTMbp.klQ8Bw1/C/JSOXeHn2.67Uo7m', 'admin', 'ativo', '2025-09-08 13:51:57', '2025-09-01 23:52:08', '2025-09-08 13:51:57');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `corretores`
--
ALTER TABLE `corretores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `depoimentos`
--
ALTER TABLE `depoimentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `especialistas`
--
ALTER TABLE `especialistas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `imagens_imoveis`
--
ALTER TABLE `imagens_imoveis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imobiliarias`
--
ALTER TABLE `imobiliarias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `imoveis`
--
ALTER TABLE `imoveis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `imoveis_caracteristicas`
--
ALTER TABLE `imoveis_caracteristicas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `imoveis_imagens`
--
ALTER TABLE `imoveis_imagens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imoveis_venda`
--
ALTER TABLE `imoveis_venda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imovel_categorias`
--
ALTER TABLE `imovel_categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `regioes`
--
ALTER TABLE `regioes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
