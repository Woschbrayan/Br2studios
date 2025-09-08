<?php
/**
 * Teste de Conexão para Admin
 * Verifica se a conexão com o banco está funcionando
 */

// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Teste de Conexão - Admin</h1>";

try {
    // Testar inclusão dos arquivos de configuração
    echo "<h2>1. Teste de Inclusão de Arquivos</h2>";
    
    if (file_exists('../config/php_limits.php')) {
        require_once '../config/php_limits.php';
        echo "<p style='color: green;'>✅ php_limits.php carregado</p>";
    } else {
        echo "<p style='color: red;'>❌ php_limits.php não encontrado</p>";
    }
    
    if (file_exists('../config/database.php')) {
        require_once '../config/database.php';
        echo "<p style='color: green;'>✅ database.php carregado</p>";
    } else {
        echo "<p style='color: red;'>❌ database.php não encontrado</p>";
    }
    
    // Testar constantes do banco
    echo "<h2>2. Teste de Constantes</h2>";
    echo "<p><strong>DB_HOST:</strong> " . (defined('DB_HOST') ? DB_HOST : 'Não definido') . "</p>";
    echo "<p><strong>DB_NAME:</strong> " . (defined('DB_NAME') ? DB_NAME : 'Não definido') . "</p>";
    echo "<p><strong>DB_USERNAME:</strong> " . (defined('DB_USERNAME') ? DB_USERNAME : 'Não definido') . "</p>";
    echo "<p><strong>DB_PASSWORD:</strong> " . (defined('DB_PASSWORD') ? (DB_PASSWORD ? 'Definido' : 'Vazio') : 'Não definido') . "</p>";
    
    // Testar conexão com banco
    echo "<h2>3. Teste de Conexão com Banco</h2>";
    
    if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USERNAME')) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            echo "<p style='color: green;'>✅ Conexão com banco estabelecida</p>";
            
            // Testar se a tabela admin existe
            $stmt = $pdo->query("SHOW TABLES LIKE 'admin'");
            if ($stmt->rowCount() > 0) {
                echo "<p style='color: green;'>✅ Tabela 'admin' encontrada</p>";
                
                // Contar admins
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM admin");
                $result = $stmt->fetch();
                echo "<p><strong>Total de admins:</strong> " . $result['total'] . "</p>";
            } else {
                echo "<p style='color: red;'>❌ Tabela 'admin' não encontrada</p>";
            }
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Erro na conexão: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Constantes do banco não definidas</p>";
    }
    
    // Testar sessão
    echo "<h2>4. Teste de Sessão</h2>";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        echo "<p style='color: green;'>✅ Sessão iniciada</p>";
    } else {
        echo "<p style='color: green;'>✅ Sessão já ativa</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Erro geral: " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<p><strong>Teste realizado em:</strong> " . date('d/m/Y H:i:s') . "</p>";
?>
