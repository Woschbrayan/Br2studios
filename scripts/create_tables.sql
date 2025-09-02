-- ===== SISTEMA BR2STUDIOS - BANCO DE DADOS =====

-- Tabela de Corretores
CREATE TABLE IF NOT EXISTS corretores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    cpf_cnpj VARCHAR(20) UNIQUE NOT NULL,
    creci VARCHAR(20),
    endereco TEXT,
    cidade VARCHAR(100),
    estado VARCHAR(2),
    cep VARCHAR(10),
    especialidade TEXT,
    experiencia_anos INT DEFAULT 0,
    foto_perfil VARCHAR(255),
    status ENUM('ativo', 'inativo', 'pendente') DEFAULT 'pendente',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Usuários Administrativos
CREATE TABLE IF NOT EXISTS usuarios_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin', 'gerente', 'operador') DEFAULT 'operador',
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    ultimo_login TIMESTAMP NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Imóveis
CREATE TABLE IF NOT EXISTS imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    tipo ENUM('studio', 'apartamento', 'casa', 'comercial', 'terreno') NOT NULL,
    categoria ENUM('venda', 'aluguel', 'investimento') NOT NULL,
    preco DECIMAL(12,2),
    preco_aluguel DECIMAL(10,2),
    area_total DECIMAL(8,2),
    area_construida DECIMAL(8,2),
    quartos INT DEFAULT 0,
    banheiros INT DEFAULT 0,
    vagas_garagem INT DEFAULT 0,
    endereco TEXT NOT NULL,
    bairro VARCHAR(100),
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    cep VARCHAR(10),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    caracteristicas JSON,
    status ENUM('disponivel', 'vendido', 'alugado', 'reservado', 'inativo') DEFAULT 'disponivel',
    destaque BOOLEAN DEFAULT FALSE,
    corretor_id INT,
    usuario_cadastro_id INT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (corretor_id) REFERENCES corretores(id) ON DELETE SET NULL,
    FOREIGN KEY (usuario_cadastro_id) REFERENCES usuarios_admin(id) ON DELETE SET NULL
);

-- Tabela de Imagens dos Imóveis
CREATE TABLE IF NOT EXISTS imagens_imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    caminho VARCHAR(500) NOT NULL,
    tipo VARCHAR(50),
    tamanho INT,
    ordem INT DEFAULT 0,
    principal BOOLEAN DEFAULT FALSE,
    data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
);

-- Tabela de Regiões
CREATE TABLE IF NOT EXISTS regioes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    cidade VARCHAR(100),
    descricao TEXT,
    imagem VARCHAR(255),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Contatos
CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    assunto VARCHAR(200),
    mensagem TEXT NOT NULL,
    tipo_contato ENUM('investimento', 'consulta', 'parceria', 'outro') DEFAULT 'outro',
    status ENUM('novo', 'em_atendimento', 'respondido', 'fechado') DEFAULT 'novo',
    data_contato TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_resposta TIMESTAMP NULL
);

-- Tabela de Newsletter
CREATE TABLE IF NOT EXISTS newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Logs de Acesso
CREATE TABLE IF NOT EXISTS logs_acesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(100) NOT NULL,
    tabela VARCHAR(100),
    registro_id INT,
    dados_anteriores JSON,
    dados_novos JSON,
    ip VARCHAR(45),
    user_agent TEXT,
    data_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios_admin(id) ON DELETE SET NULL
);

-- Índices para melhorar performance
CREATE INDEX idx_imoveis_cidade ON imoveis(cidade);
CREATE INDEX idx_imoveis_estado ON imoveis(estado);
CREATE INDEX idx_imoveis_tipo ON imoveis(tipo);
CREATE INDEX idx_imoveis_categoria ON imoveis(categoria);
CREATE INDEX idx_imoveis_status ON imoveis(status);
CREATE INDEX idx_imoveis_preco ON imoveis(preco);
CREATE INDEX idx_corretores_cidade ON corretores(cidade);
CREATE INDEX idx_corretores_estado ON corretores(estado);
CREATE INDEX idx_corretores_status ON corretores(status);

-- Inserir usuário administrador padrão (senha: admin123)
INSERT INTO usuarios_admin (nome, email, senha, nivel) VALUES 
('Administrador', 'admin@br2studios.com.br', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Inserir algumas regiões padrão
INSERT INTO regioes (nome, estado, cidade, descricao) VALUES 
('São Paulo Capital', 'SP', 'São Paulo', 'Capital financeira do Brasil, com excelentes oportunidades de investimento'),
('Rio de Janeiro', 'RJ', 'Rio de Janeiro', 'Cidade maravilhosa com alto potencial turístico e imobiliário'),
('Curitiba', 'PR', 'Curitiba', 'Cidade modelo com excelente qualidade de vida e crescimento sustentável'),
('Fortaleza', 'CE', 'Fortaleza', 'Capital do Ceará com forte potencial turístico e imobiliário'),
('Bahia', 'BA', 'Salvador', 'Capital histórica com rica cultura e oportunidades únicas');
