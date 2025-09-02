<?php
/**
 * Script para verificar a estrutura real do banco de dados
 * Sistema Br2Studios
 */

// Incluir configurações
require_once 'config/database.php';

try {
    // Conexão com o banco
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado ao banco de dados com sucesso!\n\n";
    
    // Listar todas as tabelas
    echo "📋 TABELAS EXISTENTES:\n";
    echo str_repeat("-", 50) . "\n";
    
    $stmt = $pdo->query("SHOW TABLES");
    $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tabelas as $tabela) {
        echo "• $tabela\n";
    }
    
    echo "\n";
    
    // Verificar estrutura da tabela corretores
    if (in_array('corretores', $tabelas)) {
        echo "🔍 ESTRUTURA DA TABELA 'corretores':\n";
        echo str_repeat("-", 50) . "\n";
        
        $stmt = $pdo->query("DESCRIBE corretores");
        $colunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($colunas as $coluna) {
            echo "• {$coluna['Field']} - {$coluna['Type']} - {$coluna['Null']} - {$coluna['Key']} - {$coluna['Default']}\n";
        }
        
        echo "\n";
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM corretores");
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        echo "📊 Total de corretores: $total\n\n";
        
        // Verificar alguns registros
        if ($total > 0) {
            echo "📝 PRIMEIROS REGISTROS:\n";
            echo str_repeat("-", 50) . "\n";
            
            $stmt = $pdo->query("SELECT id, nome, email FROM corretores LIMIT 3");
            $corretores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($corretores as $corretor) {
                echo "• ID: {$corretor['id']} | Nome: {$corretor['nome']} | Email: {$corretor['email']}\n";
            }
        }
        
    } else {
        echo "❌ Tabela 'corretores' não encontrada!\n";
    }
    
    // Verificar estrutura da tabela usuarios_admin
    if (in_array('usuarios_admin', $tabelas)) {
        echo "\n🔍 ESTRUTURA DA TABELA 'usuarios_admin':\n";
        echo str_repeat("-", 50) . "\n";
        
        $stmt = $pdo->query("DESCRIBE usuarios_admin");
        $colunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($colunas as $coluna) {
            echo "• {$coluna['Field']} - {$coluna['Type']} - {$coluna['Null']} - {$coluna['Key']} - {$coluna['Default']}\n";
        }
        
        echo "\n";
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios_admin");
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        echo "📊 Total de usuários admin: $total\n\n";
        
    } else {
        echo "\n❌ Tabela 'usuarios_admin' não encontrada!\n";
    }
    
    // Verificar estrutura da tabela imoveis
    if (in_array('imoveis', $tabelas)) {
        echo "\n🔍 ESTRUTURA DA TABELA 'imoveis':\n";
        echo str_repeat("-", 50) . "\n";
        
        $stmt = $pdo->query("DESCRIBE imoveis");
        $colunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($colunas as $coluna) {
            echo "• {$coluna['Field']} - {$coluna['Type']} - {$coluna['Null']} - {$coluna['Key']} - {$coluna['Default']}\n";
        }
        
        echo "\n";
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM imoveis");
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        echo "📊 Total de imóveis: $total\n\n";
        
    } else {
        echo "\n❌ Tabela 'imoveis' não encontrada!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
