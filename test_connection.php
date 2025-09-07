<?php
/**
 * Script de Teste de Conexão com Banco de Dados
 * Sistema Br2Studios
 */

// Incluir configurações
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';

echo "<h2>Teste de Conexão com Banco de Dados</h2>";
echo "<hr>";

// Mostrar configurações
echo "<h3>Configurações:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> " . (defined('DB_HOST') ? DB_HOST : 'localhost:3306') . "</li>";
echo "<li><strong>Database:</strong> " . (defined('DB_NAME') ? DB_NAME : 'br2studios') . "</li>";
echo "<li><strong>Username:</strong> " . (defined('DB_USERNAME') ? DB_USERNAME : 'root') . "</li>";
echo "<li><strong>Password:</strong> " . (defined('DB_PASSWORD') ? (DB_PASSWORD ? '***' : 'vazio') : 'vazio') . "</li>";
echo "</ul>";

echo "<hr>";

// Testar conexão
try {
    echo "<h3>Testando Conexão...</h3>";
    
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p style='color: green;'><strong>✅ Conexão estabelecida com sucesso!</strong></p>";
    
    // Testar query simples
    echo "<h3>Testando Query...</h3>";
    $stmt = $conn->query("SELECT 1 as test");
    $result = $stmt->fetch();
    
    if ($result && $result['test'] == 1) {
        echo "<p style='color: green;'><strong>✅ Query executada com sucesso!</strong></p>";
    }
    
    // Verificar se as tabelas existem
    echo "<h3>Verificando Tabelas...</h3>";
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($tables)) {
        echo "<p style='color: green;'><strong>✅ Tabelas encontradas:</strong></p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'><strong>⚠️ Nenhuma tabela encontrada no banco de dados.</strong></p>";
    }
    
    // Testar tabela de corretores especificamente
    echo "<h3>Testando Tabela 'corretores'...</h3>";
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM corretores");
        $result = $stmt->fetch();
        echo "<p style='color: green;'><strong>✅ Tabela 'corretores' encontrada com " . $result['total'] . " registros.</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>❌ Erro na tabela 'corretores': " . $e->getMessage() . "</strong></p>";
    }
    
    // Testar tabela de imóveis especificamente
    echo "<h3>Testando Tabela 'imoveis'...</h3>";
    try {
        $stmt = $conn->query("SELECT COUNT(*) as total FROM imoveis");
        $result = $stmt->fetch();
        echo "<p style='color: green;'><strong>✅ Tabela 'imoveis' encontrada com " . $result['total'] . " registros.</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>❌ Erro na tabela 'imoveis': " . $e->getMessage() . "</strong></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Erro de conexão:</strong> " . $e->getMessage() . "</p>";
    
    // Sugestões de solução
    echo "<h3>Sugestões para resolver o problema:</h3>";
    echo "<ul>";
    echo "<li>Verifique se o MySQL está rodando</li>";
    echo "<li>Confirme se a porta está correta (3306 para MySQL padrão)</li>";
    echo "<li>Verifique se o banco de dados 'br2studios' existe</li>";
    echo "<li>Confirme as credenciais de acesso (usuário e senha)</li>";
    echo "<li>Se estiver usando XAMPP, verifique se o MySQL está ativo no painel de controle</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><em>Teste executado em: " . date('d/m/Y H:i:s') . "</em></p>";
?>
