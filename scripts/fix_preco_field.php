<?php
/**
 * Script para corrigir o campo preco na tabela imoveis
 * Aumenta o tamanho do campo para suportar valores maiores
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';

$db = new Database();

try {
    // Alterar o campo preco para suportar valores maiores
    $db->executeQuery("ALTER TABLE `imoveis` MODIFY COLUMN `preco` DECIMAL(15,2) NOT NULL");
    echo "✅ Campo 'preco' alterado com sucesso para DECIMAL(15,2)!<br>";
    echo "Agora suporta valores até R$ 999.999.999.999,99<br>";
    
} catch (PDOException $e) {
    echo "❌ Erro ao alterar campo 'preco': " . $e->getMessage() . "<br>";
}

echo "<br>Script de correção do campo preco concluído.";
?>
