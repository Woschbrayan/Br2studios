-- Script para corrigir o campo preco na tabela imoveis
-- Aumenta o tamanho do campo para suportar valores maiores

ALTER TABLE `imoveis` MODIFY COLUMN `preco` DECIMAL(15,2) NOT NULL;
