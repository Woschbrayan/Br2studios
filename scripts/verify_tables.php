<?php
/**
 * Script para verificar todas as tabelas
 * Sistema Br2Studios
 */

// Configurações do banco
$host = 'localhost:3308';
$dbname = 'br2studios';
$username = 'root';
$password = '';

try {
    // Conectar ao banco
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conectado ao banco de dados com sucesso!\n\n";
    
    // Listar todas as tabelas
    echo "📋 Verificando todas as tabelas do sistema...\n\n";
    
    $tables = ['depoimentos', 'especialistas', 'regioes', 'corretores', 'imoveis', 'usuarios_admin'];
    
    foreach ($tables as $table) {
        echo "🔍 Tabela: {$table}\n";
        
        try {
            // Verificar se a tabela existe
            $exists_sql = "SHOW TABLES LIKE '{$table}'";
            $exists = $pdo->query($exists_sql)->fetch();
            
            if ($exists) {
                // Contar registros
                $count_sql = "SELECT COUNT(*) as total FROM {$table}";
                $count = $pdo->query($count_sql)->fetch(PDO::FETCH_ASSOC);
                
                echo "   ✅ Existe - {$count['total']} registros\n";
                
                // Mostrar estrutura
                $columns_sql = "SHOW COLUMNS FROM {$table}";
                $columns = $pdo->query($columns_sql)->fetchAll(PDO::FETCH_ASSOC);
                
                echo "   📊 Estrutura:\n";
                foreach ($columns as $column) {
                    echo "      - {$column['Field']}: {$column['Type']}\n";
                }
                
                // Mostrar alguns dados de exemplo
                if ($count['total'] > 0) {
                    $sample_sql = "SELECT * FROM {$table} LIMIT 2";
                    $sample = $pdo->query($sample_sql)->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo "   📝 Exemplos:\n";
                    foreach ($sample as $row) {
                        $first_field = array_key_first($row);
                        $second_field = array_keys($row)[1] ?? '';
                        $value1 = $row[$first_field] ?? 'N/A';
                        $value2 = $row[$second_field] ?? '';
                        
                        if ($second_field) {
                            echo "      - {$first_field}: {$value1}, {$second_field}: {$value2}\n";
                        } else {
                            echo "      - {$first_field}: {$value1}\n";
                        }
                    }
                }
                
            } else {
                echo "   ❌ Não existe\n";
            }
            
        } catch (PDOException $e) {
            echo "   ⚠️  Erro: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    echo "🎉 Verificação concluída!\n";
    echo "\n📱 Agora você pode acessar:\n";
    echo "   - Dashboard: http://localhost:8080/br2studios/admin/dashboard.php\n";
    echo "   - Depoimentos: http://localhost:8080/br2studios/admin/depoimentos.php\n";
    echo "   - Especialistas: http://localhost:8080/br2studios/admin/especialistas.php\n";
    echo "   - Regiões: http://localhost:8080/br2studios/admin/regioes.php\n";
    echo "   - Imóveis: http://localhost:8080/br2studios/admin/imoveis.php\n";
    echo "   - Corretores: http://localhost:8080/br2studios/admin/corretores.php\n";
    
} catch (PDOException $e) {
    echo "❌ Erro ao conectar ao banco: " . $e->getMessage() . "\n";
}
?>
