-- Script para adicionar campos necessários na tabela imoveis
-- Execute este script no banco de dados

-- Adicionar campo status_construcao
ALTER TABLE `imoveis` 
ADD COLUMN `status_construcao` ENUM('pronto', 'em_construcao', 'na_planta') DEFAULT 'pronto' 
AFTER `status`;

-- Adicionar campo ano_entrega
ALTER TABLE `imoveis` 
ADD COLUMN `ano_entrega` YEAR DEFAULT NULL 
AFTER `status_construcao`;

-- Adicionar comentários para documentação
ALTER TABLE `imoveis` 
MODIFY COLUMN `status_construcao` ENUM('pronto', 'em_construcao', 'na_planta') DEFAULT 'pronto' 
COMMENT 'Status da construção do imóvel';

ALTER TABLE `imoveis` 
MODIFY COLUMN `ano_entrega` YEAR DEFAULT NULL 
COMMENT 'Ano previsto para entrega do imóvel';

-- Verificar se os campos foram adicionados
DESCRIBE `imoveis`;
