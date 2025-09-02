-- Script para criar novas tabelas do sistema Br2Studios
-- Execute este script no seu banco de dados MySQL

-- Tabela de Depoimentos
CREATE TABLE IF NOT EXISTS `depoimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `data_cadastro` timestamp DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_destaque` (`destaque`),
  KEY `idx_estado` (`estado`),
  KEY `idx_cidade` (`cidade`),
  KEY `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Especialistas
CREATE TABLE IF NOT EXISTS `especialistas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `data_cadastro` timestamp DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_destaque` (`destaque`),
  KEY `idx_especialidade` (`especialidade`),
  KEY `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Regiões (atualizar se já existir)
CREATE TABLE IF NOT EXISTS `regioes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(500) DEFAULT NULL,
  `destaque` tinyint(1) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `data_cadastro` timestamp DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_nome_estado` (`nome`, `estado`),
  KEY `idx_estado` (`estado`),
  KEY `idx_destaque` (`destaque`),
  KEY `idx_ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir dados de exemplo para Depoimentos
INSERT INTO `depoimentos` (`nome`, `depoimento`, `cidade`, `estado`, `avaliacao`, `cargo`, `empresa`, `destaque`) VALUES
('João Silva', 'Excelente experiência com a Br2Studios! Consegui investir em um studio que já valorizou 25% em apenas 1 ano.', 'São Paulo', 'SP', 5.0, 'Engenheiro', 'Construtora ABC', 1),
('Maria Santos', 'Profissionais muito competentes e transparentes. Recomendo para quem quer investir em imóveis.', 'Rio de Janeiro', 'RJ', 5.0, 'Advogada', 'Escritório Santos', 1),
('Carlos Oliveira', 'A equipe da Br2Studios é incrível! Me ajudaram a encontrar o investimento perfeito.', 'Curitiba', 'PR', 4.9, 'Médico', 'Hospital Municipal', 1),
('Ana Costa', 'Investimento seguro e lucrativo. A Br2Studios cumpriu todas as promessas.', 'Belo Horizonte', 'MG', 5.0, 'Dentista', 'Clínica Costa', 1),
('Roberto Lima', 'Processo muito profissional e transparente. Recomendo fortemente!', 'Fortaleza', 'CE', 4.8, 'Empresário', 'Lima Ltda', 0);

-- Inserir dados de exemplo para Especialistas
INSERT INTO `especialistas` (`nome`, `cargo`, `especialidade`, `experiencia`, `bio`, `destaque`) VALUES
('Dr. Fernando Almeida', 'Diretor de Investimentos', 'Mercado Imobiliário', '15 anos', 'Especialista em análise de mercado e investimentos imobiliários com mais de 15 anos de experiência.', 1),
('Dra. Patricia Mendes', 'Especialista em Financiamento', 'Financiamento Imobiliário', '12 anos', 'Especialista em financiamentos e análise de crédito para investimentos imobiliários.', 1),
('Eng. Ricardo Costa', 'Analista de Projetos', 'Engenharia Civil', '18 anos', 'Engenheiro civil especializado em análise de projetos e qualidade construtiva.', 1),
('Dr. Lucas Ferreira', 'Especialista Jurídico', 'Direito Imobiliário', '10 anos', 'Advogado especializado em direito imobiliário e regularização de imóveis.', 1),
('Ana Beatriz Silva', 'Consultora de Vendas', 'Vendas Imobiliárias', '8 anos', 'Consultora experiente em vendas e relacionamento com clientes.', 0);

-- Inserir dados de exemplo para Regiões (se não existirem)
INSERT IGNORE INTO `regioes` (`nome`, `estado`, `descricao`, `destaque`) VALUES
('Vila Madalena', 'SP', 'Região boêmia e cultural de São Paulo, com excelente potencial de valorização.', 1),
('Copacabana', 'RJ', 'Bairro tradicional do Rio de Janeiro com vista para o mar e infraestrutura completa.', 1),
('Batel', 'PR', 'Bairro nobre de Curitiba com excelente localização e infraestrutura.', 1),
('Savassi', 'MG', 'Região central de Belo Horizonte com comércio e serviços de qualidade.', 1),
('Meireles', 'CE', 'Bairro nobre de Fortaleza com vista para o mar e infraestrutura completa.', 1),
('Jardins', 'SP', 'Bairro tradicional de São Paulo com excelente localização e infraestrutura.', 1),
('Ipanema', 'RJ', 'Bairro nobre do Rio de Janeiro com praia e infraestrutura de qualidade.', 1),
('Centro Cívico', 'PR', 'Região central de Curitiba com excelente localização e infraestrutura.', 0);

-- Verificar se as tabelas foram criadas
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME
FROM 
    information_schema.TABLES 
WHERE 
    TABLE_SCHEMA = 'br2studios' 
    AND TABLE_NAME IN ('depoimentos', 'especialistas', 'regioes')
ORDER BY 
    TABLE_NAME;
