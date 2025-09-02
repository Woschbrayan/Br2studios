-- Tabela de categorias/características dos imóveis
CREATE TABLE IF NOT EXISTS categorias_imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    icone VARCHAR(50) DEFAULT 'fas fa-check',
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de relacionamento entre imóveis e categorias
CREATE TABLE IF NOT EXISTS imovel_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    categoria_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias_imoveis(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_imovel_categoria (imovel_id, categoria_id)
);

-- Inserir categorias padrão comuns em imóveis
INSERT INTO categorias_imoveis (nome, descricao, icone) VALUES
('Academia', 'Academia na área comum do condomínio', 'fas fa-dumbbell'),
('Churrasqueira', 'Churrasqueira na área comum', 'fas fa-fire'),
('Piscina', 'Piscina na área comum', 'fas fa-swimming-pool'),
('Salão de Festas', 'Salão de festas disponível', 'fas fa-glass-cheers'),
('Vagas de Garagem', 'Vagas de garagem cobertas', 'fas fa-car'),
('Elevador', 'Elevador no prédio', 'fas fa-arrow-up'),
('Portaria 24h', 'Portaria funcionando 24 horas', 'fas fa-user-shield'),
('Segurança 24h', 'Sistema de segurança 24 horas', 'fas fa-shield-alt'),
('Jardim', 'Jardim na área comum', 'fas fa-seedling'),
('Playground', 'Playground para crianças', 'fas fa-child'),
('Espaço Gourmet', 'Espaço gourmet na área comum', 'fas fa-utensils'),
('Lavanderia', 'Lavanderia na área comum', 'fas fa-tshirt'),
('Spa', 'Spa na área comum', 'fas fa-spa'),
('Quadra Esportiva', 'Quadra esportiva disponível', 'fas fa-basketball-ball'),
('Sala de Jogos', 'Sala de jogos na área comum', 'fas fa-gamepad'),
('Biblioteca', 'Biblioteca na área comum', 'fas fa-book'),
('Home Office', 'Espaço para home office', 'fas fa-laptop-house'),
('Varanda Gourmet', 'Varanda gourmet privativa', 'fas fa-umbrella-beach'),
('Sacada', 'Sacada privativa', 'fas fa-door-open'),
('Aceita Pets', 'Aceita animais de estimação', 'fas fa-paw'),
('Mobiliado', 'Imóvel mobiliado', 'fas fa-couch'),
('Ar Condicionado', 'Ar condicionado instalado', 'fas fa-snowflake'),
('Aquecimento', 'Sistema de aquecimento', 'fas fa-thermometer-half'),
('Internet', 'Conexão à internet incluída', 'fas fa-wifi'),
('TV a Cabo', 'TV a cabo incluída', 'fas fa-tv'),
('Interfone', 'Sistema de interfone', 'fas fa-phone'),
('Alarme', 'Sistema de alarme', 'fas fa-bell'),
('Câmeras de Segurança', 'Câmeras de segurança', 'fas fa-video'),
('Condomínio Fechado', 'Condomínio com acesso controlado', 'fas fa-lock'),
('Área Verde', 'Área verde preservada', 'fas fa-tree'),
('Estacionamento', 'Estacionamento disponível', 'fas fa-parking'),
('Acessibilidade', 'Adaptado para pessoas com deficiência', 'fas fa-wheelchair');
