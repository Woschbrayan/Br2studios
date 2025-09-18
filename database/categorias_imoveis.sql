-- Tabela de Categorias de Imóveis
CREATE TABLE IF NOT EXISTS categorias_imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    icone VARCHAR(50) DEFAULT 'fas fa-check',
    ativo BOOLEAN DEFAULT TRUE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Relacionamento Imóvel-Categoria
CREATE TABLE IF NOT EXISTS imovel_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    categoria_id INT NOT NULL,
    data_associacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias_imoveis(id) ON DELETE CASCADE,
    UNIQUE KEY unique_imovel_categoria (imovel_id, categoria_id)
);

-- Inserir categorias padrão
INSERT INTO categorias_imoveis (nome, descricao, icone) VALUES 
('Studio', 'Apartamentos tipo studio', 'fas fa-home'),
('Apartamento', 'Apartamentos tradicionais', 'fas fa-building'),
('Casa', 'Casas residenciais', 'fas fa-house'),
('Comercial', 'Imóveis comerciais', 'fas fa-store'),
('Terreno', 'Terrenos para construção', 'fas fa-map'),
('Lançamento', 'Imóveis em lançamento', 'fas fa-rocket'),
('Pronto', 'Imóveis prontos para morar', 'fas fa-check-circle'),
('Na Planta', 'Imóveis na planta', 'fas fa-drafting-compass'),
('Alto Padrão', 'Imóveis de alto padrão', 'fas fa-crown'),
('Popular', 'Imóveis populares', 'fas fa-users');
