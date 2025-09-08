<?php
/**
 * Verificação de Tabelas do Admin
 * Verifica se as tabelas necessárias existem
 */

// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Verificação de Tabelas - Admin</h1>";

try {
    // Incluir configurações
    require_once '../config/database.php';
    
    // Conectar ao banco
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "<p style='color: green;'>✅ Conexão estabelecida</p>";
    
    // Listar todas as tabelas
    echo "<h2>Tabelas Existentes</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Verificar tabela usuarios_admin
    echo "<h2>Verificação da Tabela usuarios_admin</h2>";
    if (in_array('usuarios_admin', $tables)) {
        echo "<p style='color: green;'>✅ Tabela 'usuarios_admin' existe</p>";
        
        // Verificar estrutura
        $stmt = $pdo->query("DESCRIBE usuarios_admin");
        $columns = $stmt->fetchAll();
        
        echo "<h3>Estrutura da Tabela:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
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
        
        // Contar usuários
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios_admin");
        $result = $stmt->fetch();
        echo "<p><strong>Total de usuários admin:</strong> " . $result['total'] . "</p>";
        
        // Listar usuários
        if ($result['total'] > 0) {
            $stmt = $pdo->query("SELECT id, nome, email, nivel, status FROM usuarios_admin");
            $users = $stmt->fetchAll();
            
            echo "<h3>Usuários Admin:</h3>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Nível</th><th>Status</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['nome'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "<td>" . $user['nivel'] . "</td>";
                echo "<td>" . $user['status'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Tabela 'usuarios_admin' não existe</p>";
        echo "<p>Vou criar a tabela...</p>";
        
        // Criar tabela usuarios_admin
        $sql = "CREATE TABLE IF NOT EXISTS usuarios_admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            senha VARCHAR(255) NOT NULL,
            nivel ENUM('admin', 'editor') DEFAULT 'editor',
            status ENUM('ativo', 'inativo') DEFAULT 'ativo',
            data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ultimo_login TIMESTAMP NULL
        )";
        
        $pdo->exec($sql);
        echo "<p style='color: green;'>✅ Tabela 'usuarios_admin' criada</p>";
        
        // Criar usuário admin padrão
        $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios_admin (nome, email, senha, nivel, status) VALUES ('Administrador', 'admin@br2studios.com.br', :senha, 'admin', 'ativo')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':senha' => $senha_hash]);
        
        echo "<p style='color: green;'>✅ Usuário admin padrão criado</p>";
        echo "<p><strong>Email:</strong> admin@br2studios.com.br</p>";
        echo "<p><strong>Senha:</strong> admin123</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<p><strong>Verificação realizada em:</strong> " . date('d/m/Y H:i:s') . "</p>";
?>
