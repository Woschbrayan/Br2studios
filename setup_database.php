<?php
/**
 * Script de Configuração do Banco de Dados
 * Sistema Br2Studios
 */

echo "<h2>Configuração do Banco de Dados - Br2Studios</h2>";
echo "<hr>";

// Configurações de conexão
$hosts = [
    'localhost:3306',
    'localhost:3308', 
    '127.0.0.1:3306'
];

$users = ['root'];
$passwords = ['', 'root'];

$database_name = 'br2studios';

echo "<h3>Testando Conexões...</h3>";

$connection_found = false;
$working_config = null;

foreach ($hosts as $host) {
    foreach ($users as $user) {
        foreach ($passwords as $password) {
            try {
                echo "<p>Tentando: $host com usuário '$user'...</p>";
                
                $pdo = new PDO(
                    "mysql:host=$host;charset=utf8",
                    $user,
                    $password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                echo "<p style='color: green;'>✅ Conexão bem-sucedida com $host!</p>";
                
                // Verificar se o banco existe
                $stmt = $pdo->query("SHOW DATABASES LIKE '$database_name'");
                $db_exists = $stmt->fetch();
                
                if ($db_exists) {
                    echo "<p style='color: green;'>✅ Banco de dados '$database_name' já existe!</p>";
                } else {
                    echo "<p style='color: orange;'>⚠️ Banco de dados '$database_name' não existe. Criando...</p>";
                    
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    echo "<p style='color: green;'>✅ Banco de dados '$database_name' criado com sucesso!</p>";
                }
                
                // Testar conexão com o banco específico
                $pdo_db = new PDO(
                    "mysql:host=$host;dbname=$database_name;charset=utf8",
                    $user,
                    $password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                echo "<p style='color: green;'>✅ Conexão com banco '$database_name' bem-sucedida!</p>";
                
                $connection_found = true;
                $working_config = [
                    'host' => $host,
                    'user' => $user,
                    'password' => $password
                ];
                
                // Verificar tabelas
                echo "<h3>Verificando Tabelas...</h3>";
                $stmt = $pdo_db->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (empty($tables)) {
                    echo "<p style='color: orange;'>⚠️ Nenhuma tabela encontrada. Você precisa importar o arquivo SQL.</p>";
                    echo "<p><strong>Arquivo SQL encontrado:</strong> database/brun3811_br2studios (1).sql</p>";
                } else {
                    echo "<p style='color: green;'>✅ Tabelas encontradas:</p>";
                    echo "<ul>";
                    foreach ($tables as $table) {
                        echo "<li>$table</li>";
                    }
                    echo "</ul>";
                }
                
                break 3; // Sair de todos os loops
                
            } catch (PDOException $e) {
                echo "<p style='color: red;'>❌ Falha: " . $e->getMessage() . "</p>";
            }
        }
    }
}

if ($connection_found && $working_config) {
    echo "<hr>";
    echo "<h3>✅ Configuração Funcionando!</h3>";
    echo "<p><strong>Use estas configurações no arquivo config/database.php:</strong></p>";
    echo "<pre>";
    echo "define('DB_HOST', '{$working_config['host']}');\n";
    echo "define('DB_NAME', '$database_name');\n";
    echo "define('DB_USERNAME', '{$working_config['user']}');\n";
    echo "define('DB_PASSWORD', '{$working_config['password']}');\n";
    echo "</pre>";
    
    echo "<h3>Próximos Passos:</h3>";
    echo "<ol>";
    echo "<li>Atualize o arquivo <code>config/database.php</code> com as configurações acima</li>";
    echo "<li>Se não há tabelas, importe o arquivo SQL: <code>database/brun3811_br2studios (1).sql</code></li>";
    echo "<li>Teste a conexão executando <code>test_connection.php</code></li>";
    echo "</ol>";
    
} else {
    echo "<hr>";
    echo "<h3>❌ Nenhuma Conexão Funcionou</h3>";
    echo "<p><strong>Possíveis soluções:</strong></p>";
    echo "<ul>";
    echo "<li>Verifique se o MySQL está rodando</li>";
    echo "<li>Se estiver usando XAMPP, inicie o MySQL no painel de controle</li>";
    echo "<li>Verifique se a porta está correta (3306 ou 3308)</li>";
    echo "<li>Confirme se o usuário 'root' existe e tem as permissões necessárias</li>";
    echo "<li>Tente criar um usuário específico para o projeto</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><em>Script executado em: " . date('d/m/Y H:i:s') . "</em></p>";
?>
