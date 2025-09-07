<?php
/**
 * Script de Configuração para XAMPP
 * Sistema Br2Studios
 */

echo "<h2>🔧 Configuração XAMPP - Br2Studios</h2>";
echo "<hr>";

// Configurações específicas do XAMPP
$host = 'localhost:3308';
$user = 'root';
$password = '';
$database = 'br2studios';

echo "<h3>📋 Configurações:</h3>";
echo "<ul>";
echo "<li><strong>Servidor:</strong> $host</li>";
echo "<li><strong>Usuário:</strong> $user</li>";
echo "<li><strong>Senha:</strong> " . ($password ? '***' : 'vazio') . "</li>";
echo "<li><strong>Banco:</strong> $database</li>";
echo "</ul>";

echo "<hr>";

try {
    echo "<h3>🔌 Testando Conexão com MySQL...</h3>";
    
    // Conectar sem especificar banco
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p style='color: green;'>✅ <strong>Conexão com MySQL estabelecida!</strong></p>";
    
    // Verificar se o banco existe
    echo "<h3>🗄️ Verificando Banco de Dados...</h3>";
    $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
    $db_exists = $stmt->fetch();
    
    if ($db_exists) {
        echo "<p style='color: green;'>✅ <strong>Banco '$database' já existe!</strong></p>";
    } else {
        echo "<p style='color: orange;'>⚠️ <strong>Banco '$database' não existe. Criando...</strong></p>";
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p style='color: green;'>✅ <strong>Banco '$database' criado com sucesso!</strong></p>";
    }
    
    // Conectar ao banco específico
    echo "<h3>🔗 Conectando ao Banco '$database'...</h3>";
    $pdo_db = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p style='color: green;'>✅ <strong>Conexão com banco '$database' estabelecida!</strong></p>";
    
    // Verificar tabelas
    echo "<h3>📊 Verificando Tabelas...</h3>";
    $stmt = $pdo_db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠️ <strong>Nenhuma tabela encontrada!</strong></p>";
        echo "<p><strong>Você precisa importar o arquivo SQL:</strong></p>";
        echo "<ol>";
        echo "<li>Abra o phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Selecione o banco '$database'</li>";
        echo "<li>Vá na aba 'Importar'</li>";
        echo "<li>Escolha o arquivo: <code>database/brun3811_br2studios (1).sql</code></li>";
        echo "<li>Clique em 'Executar'</li>";
        echo "</ol>";
    } else {
        echo "<p style='color: green;'>✅ <strong>Tabelas encontradas:</strong></p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
        
        // Verificar dados nas tabelas principais
        echo "<h3>📈 Verificando Dados...</h3>";
        
        // Verificar corretores
        try {
            $stmt = $pdo_db->query("SELECT COUNT(*) as total FROM corretores");
            $result = $stmt->fetch();
            echo "<p>👥 <strong>Corretores:</strong> " . $result['total'] . " registros</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erro na tabela 'corretores': " . $e->getMessage() . "</p>";
        }
        
        // Verificar imóveis
        try {
            $stmt = $pdo_db->query("SELECT COUNT(*) as total FROM imoveis");
            $result = $stmt->fetch();
            echo "<p>🏠 <strong>Imóveis:</strong> " . $result['total'] . " registros</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erro na tabela 'imoveis': " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>🎉 <strong>Configuração Concluída!</strong></h3>";
    echo "<p>Seu ambiente XAMPP está configurado corretamente para o Br2Studios.</p>";
    
    echo "<h3>🚀 <strong>Próximos Passos:</strong></h3>";
    echo "<ol>";
    echo "<li>Se não há tabelas, importe o arquivo SQL via phpMyAdmin</li>";
    echo "<li>Teste o site: <a href='index.php' target='_blank'>index.php</a></li>";
    echo "<li>Verifique se os dados estão sendo exibidos corretamente</li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ <strong>Erro de conexão:</strong> " . $e->getMessage() . "</p>";
    
    echo "<h3>🔧 <strong>Soluções:</strong></h3>";
    echo "<ul>";
    echo "<li>Verifique se o XAMPP está rodando</li>";
    echo "<li>Inicie o MySQL no painel de controle do XAMPP</li>";
    echo "<li>Verifique se a porta 3308 está correta</li>";
    echo "<li>Confirme se o usuário 'root' existe</li>";
    echo "</ul>";
    
    echo "<h3>📱 <strong>Painel de Controle XAMPP:</strong></h3>";
    echo "<p>Acesse: <a href='http://localhost/dashboard/' target='_blank'>http://localhost/dashboard/</a></p>";
}

echo "<hr>";
echo "<p><em>Script executado em: " . date('d/m/Y H:i:s') . "</em></p>";
?>
