<?php
/**
 * Teste de Conexão com Banco de Dados
 * Sistema Br2Studios
 */

require_once 'config/database.php';
require_once 'classes/Database.php';

echo "<h2>Teste de Conexão - Br2Studios</h2>";

// Mostrar configurações detectadas
echo "<h3>Configurações Detectadas:</h3>";
echo "<p><strong>Host:</strong> " . DB_HOST . "</p>";
echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
echo "<p><strong>Username:</strong> " . DB_USERNAME . "</p>";
echo "<p><strong>Password:</strong> " . (DB_PASSWORD ? '***' : 'vazio') . "</p>";

// Detectar ambiente
$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
             strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
             strpos($_SERVER['HTTP_HOST'], '::1') !== false);

echo "<p><strong>Ambiente:</strong> " . ($is_local ? 'Local' : 'Hostgator') . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";

// Testar conexão
echo "<h3>Teste de Conexão:</h3>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ <strong>Conexão estabelecida com sucesso!</strong></p>";
        
        // Testar uma query simples
        $stmt = $conn->query("SELECT VERSION() as version");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p><strong>Versão do MySQL:</strong> " . $result['version'] . "</p>";
        
        // Testar se as tabelas existem
        $stmt = $conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Tabelas encontradas:</strong> " . count($tables) . "</p>";
        
        if (count($tables) > 0) {
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>" . $table . "</li>";
            }
            echo "</ul>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ <strong>Falha na conexão!</strong></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erro:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><em>Teste realizado em: " . date('d/m/Y H:i:s') . "</em></p>";
?>