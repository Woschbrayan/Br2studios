<?php
/**
 * Script para adicionar campos necessários na tabela imoveis
 */

require_once '../config/database.php';
require_once '../classes/Database.php';

echo "<h2>Adicionando campos à tabela imoveis</h2>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<p style='color: green;'>✓ Conexão estabelecida</p>";
    
    // Verificar se os campos já existem
    $stmt = $conn->query("SHOW COLUMNS FROM imoveis LIKE 'status_construcao'");
    $status_construcao_exists = $stmt->fetch();
    
    $stmt = $conn->query("SHOW COLUMNS FROM imoveis LIKE 'ano_entrega'");
    $ano_entrega_exists = $stmt->fetch();
    
    // Adicionar campo status_construcao se não existir
    if (!$status_construcao_exists) {
        echo "<p>Adicionando campo 'status_construcao'...</p>";
        $sql = "ALTER TABLE `imoveis` ADD COLUMN `status_construcao` ENUM('pronto', 'em_construcao', 'na_planta') DEFAULT 'pronto' AFTER `status`";
        $conn->exec($sql);
        echo "<p style='color: green;'>✓ Campo 'status_construcao' adicionado</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Campo 'status_construcao' já existe</p>";
    }
    
    // Adicionar campo ano_entrega se não existir
    if (!$ano_entrega_exists) {
        echo "<p>Adicionando campo 'ano_entrega'...</p>";
        $sql = "ALTER TABLE `imoveis` ADD COLUMN `ano_entrega` YEAR DEFAULT NULL AFTER `status_construcao`";
        $conn->exec($sql);
        echo "<p style='color: green;'>✓ Campo 'ano_entrega' adicionado</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Campo 'ano_entrega' já existe</p>";
    }
    
    // Mostrar estrutura atual da tabela
    echo "<h3>Estrutura atual da tabela imoveis:</h3>";
    $stmt = $conn->query("DESCRIBE imoveis");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p style='color: green; font-weight: bold;'>✅ Campos adicionados com sucesso!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='../admin/imoveis.php'>Voltar para Imóveis</a></p>";
?>
