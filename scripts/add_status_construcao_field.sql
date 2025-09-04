-- Script para adicionar campo status_construcao na tabela imoveis
-- Executar este script no banco de dados br2studios

USE br2studios;

-- Adicionar campo status_construcao
ALTER TABLE imoveis 
ADD COLUMN status_construcao ENUM('pronto', 'na_planta') DEFAULT 'pronto' AFTER tipo;

-- Atualizar dados existentes (todos como 'pronto' por padrão)
UPDATE imoveis SET status_construcao = 'pronto' WHERE status_construcao IS NULL;

-- Verificar se o campo foi adicionado corretamente
DESCRIBE imoveis;
