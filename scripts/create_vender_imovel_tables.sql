-- ===== CRIAÇÃO DAS TABELAS PARA VENDER IMÓVEL =====

-- Tabela de usuários que cadastram imóveis
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    celular VARCHAR(20) NOT NULL,
    creci VARCHAR(50) NULL,
    data_cadastro DATETIME NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    INDEX idx_email (email),
    INDEX idx_status (status)
);

-- Tabela de imóveis para venda/aluguel
CREATE TABLE IF NOT EXISTS imoveis_venda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_operacao ENUM('alugar', 'vender') NOT NULL,
    tipo_imovel ENUM('apartamento', 'casa', 'casa_condominio', 'studio', 'cobertura', 'terreno', 'comercial') NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    valor_condominio DECIMAL(10,2) NULL,
    valor_iptu DECIMAL(10,2) NULL,
    cep VARCHAR(10) NOT NULL,
    endereco VARCHAR(500) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado CHAR(2) NOT NULL,
    metragem DECIMAL(8,2) NOT NULL,
    dormitorios INT NOT NULL,
    vagas INT DEFAULT 0,
    banheiros INT NOT NULL,
    suites INT DEFAULT 0,
    detalhes TEXT NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado', 'vendido', 'alugado') DEFAULT 'pendente',
    data_cadastro DATETIME NOT NULL,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_tipo_operacao (tipo_operacao),
    INDEX idx_tipo_imovel (tipo_imovel),
    INDEX idx_cidade (cidade),
    INDEX idx_estado (estado),
    INDEX idx_status (status),
    INDEX idx_valor (valor),
    INDEX idx_data_cadastro (data_cadastro)
);

-- Tabela de imagens dos imóveis
CREATE TABLE IF NOT EXISTS imoveis_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    caminho_imagem VARCHAR(500) NOT NULL,
    tipo ENUM('imagem_principal', 'imagem_01', 'imagem_02', 'imagem_03', 'imagem_04') NOT NULL,
    data_upload DATETIME NOT NULL,
    ordem INT DEFAULT 0,
    FOREIGN KEY (imovel_id) REFERENCES imoveis_venda(id) ON DELETE CASCADE,
    INDEX idx_imovel_id (imovel_id),
    INDEX idx_tipo (tipo),
    INDEX idx_ordem (ordem)
);

-- Tabela de características dos imóveis (opcional para futuras expansões)
CREATE TABLE IF NOT EXISTS imoveis_caracteristicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    caracteristica VARCHAR(100) NOT NULL,
    valor BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (imovel_id) REFERENCES imoveis_venda(id) ON DELETE CASCADE,
    INDEX idx_imovel_id (imovel_id),
    INDEX idx_caracteristica (caracteristica)
);

-- Inserir algumas características padrão
INSERT INTO imoveis_caracteristicas (imovel_id, caracteristica, valor) VALUES
(1, 'academia', TRUE),
(1, 'aceita_pets', TRUE),
(1, 'churrasqueira', FALSE),
(1, 'elevador', TRUE),
(1, 'playground', TRUE),
(1, 'portaria_24h', TRUE),
(1, 'piscina', FALSE),
(1, 'condominio_fechado', TRUE);

-- Tabela de histórico de status dos imóveis
CREATE TABLE IF NOT EXISTS imoveis_historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    status_anterior ENUM('pendente', 'aprovado', 'rejeitado', 'vendido', 'alugado') NULL,
    status_novo ENUM('pendente', 'aprovado', 'rejeitado', 'vendido', 'alugado') NOT NULL,
    observacao TEXT NULL,
    data_mudanca DATETIME NOT NULL,
    usuario_admin_id INT NULL, -- Para rastrear quem fez a mudança
    FOREIGN KEY (imovel_id) REFERENCES imoveis_venda(id) ON DELETE CASCADE,
    INDEX idx_imovel_id (imovel_id),
    INDEX idx_status_novo (status_novo),
    INDEX idx_data_mudanca (data_mudanca)
);

-- Tabela de contatos/interesses nos imóveis
CREATE TABLE IF NOT EXISTS imoveis_interesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    mensagem TEXT NULL,
    data_interesse DATETIME NOT NULL,
    status ENUM('novo', 'contatado', 'convertido', 'desistiu') DEFAULT 'novo',
    FOREIGN KEY (imovel_id) REFERENCES imoveis_venda(id) ON DELETE CASCADE,
    INDEX idx_imovel_id (imovel_id),
    INDEX idx_status (status),
    INDEX idx_data_interesse (data_interesse)
);

-- Índices adicionais para performance
CREATE INDEX idx_imoveis_venda_completo ON imoveis_venda (tipo_operacao, tipo_imovel, cidade, estado, status);
CREATE INDEX idx_imoveis_venda_valor_cidade ON imoveis_venda (cidade, valor, status);
CREATE INDEX idx_usuarios_data_cadastro ON usuarios (data_cadastro);

-- Comentários das tabelas
ALTER TABLE usuarios COMMENT = 'Usuários que cadastram imóveis para venda/aluguel';
ALTER TABLE imoveis_venda COMMENT = 'Imóveis cadastrados pelos usuários';
ALTER TABLE imoveis_imagens COMMENT = 'Imagens dos imóveis cadastrados';
ALTER TABLE imoveis_caracteristicas COMMENT = 'Características específicas dos imóveis';
ALTER TABLE imoveis_historico COMMENT = 'Histórico de mudanças de status dos imóveis';
ALTER TABLE imoveis_interesses COMMENT = 'Interesses de compradores nos imóveis';

-- Verificar se as tabelas foram criadas corretamente
SHOW TABLES LIKE '%imoveis%';
SHOW TABLES LIKE '%usuarios%';

-- Mostrar estrutura das tabelas principais
DESCRIBE usuarios;
DESCRIBE imoveis_venda;
DESCRIBE imoveis_imagens;
