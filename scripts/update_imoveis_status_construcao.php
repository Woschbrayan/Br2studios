<?php
/**
 * Script para atualizar o campo status_construcao na tabela imoveis
 * Executar este script para adicionar o novo campo e atualizar dados existentes
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';

try {
    $db = new Database();
    
    echo "Iniciando atualização da tabela imoveis...\n";
    
    // Verificar se o campo já existe
    $checkField = $db->fetchOne("SHOW COLUMNS FROM imoveis LIKE 'status_construcao'");
    
    if (!$checkField) {
        echo "Adicionando campo status_construcao...\n";
        
        // Adicionar o campo
        $sql = "ALTER TABLE imoveis ADD COLUMN status_construcao ENUM('pronto', 'na_planta') DEFAULT 'pronto' AFTER tipo";
        $db->query($sql);
        
        echo "Campo status_construcao adicionado com sucesso!\n";
    } else {
        echo "Campo status_construcao já existe.\n";
    }
    
    // Atualizar dados existentes
    echo "Atualizando dados existentes...\n";
    
    $updateSql = "UPDATE imoveis SET status_construcao = 'pronto' WHERE status_construcao IS NULL OR status_construcao = ''";
    $affectedRows = $db->update($updateSql);
    
    echo "Atualizados $affectedRows registros.\n";
    
    // Verificar os dados
    echo "Verificando dados atualizados...\n";
    
    $stats = $db->fetchAll("SELECT status_construcao, COUNT(*) as total FROM imoveis GROUP BY status_construcao");
    
    foreach ($stats as $stat) {
        echo "- {$stat['status_construcao']}: {$stat['total']} imóveis\n";
    }
    
    echo "\nAtualização concluída com sucesso!\n";
    
} catch (Exception $e) {
    echo "Erro durante a atualização: " . $e->getMessage() . "\n";
    exit(1);
}
?>
