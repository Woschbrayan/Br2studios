<?php
/**
 * Script para criar as tabelas de categorias de imóveis
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Database.php';

try {
    $db = new Database();
    
    echo "🔧 Criando tabelas de categorias...\n\n";
    
    // Ler o arquivo SQL
    $sql_file = __DIR__ . '/../database/categorias_imoveis.sql';
    
    if (!file_exists($sql_file)) {
        throw new Exception("Arquivo SQL não encontrado: $sql_file");
    }
    
    $sql_content = file_get_contents($sql_file);
    
    // Dividir o SQL em comandos individuais
    $commands = array_filter(array_map('trim', explode(';', $sql_content)));
    
    $tables_created = 0;
    $categories_inserted = 0;
    
    foreach ($commands as $command) {
        if (empty($command)) continue;
        
        try {
            if (strpos($command, 'CREATE TABLE') !== false) {
                $db->execute($command);
                $tables_created++;
                echo "✅ Tabela criada com sucesso\n";
            } elseif (strpos($command, 'INSERT INTO') !== false) {
                $db->execute($command);
                $categories_inserted++;
                echo "✅ Categoria inserida com sucesso\n";
            } else {
                $db->execute($command);
                echo "✅ Comando executado com sucesso\n";
            }
        } catch (Exception $e) {
            // Ignorar erros de tabela já existente
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "⚠️  Tabela já existe, continuando...\n";
            } else {
                echo "❌ Erro: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n🎉 Processo concluído!\n";
    echo "📊 Resumo:\n";
    echo "   - Tabelas criadas: $tables_created\n";
    echo "   - Categorias inseridas: $categories_inserted\n";
    
    // Verificar se as tabelas foram criadas
    echo "\n🔍 Verificando tabelas criadas:\n";
    
    $tables = ['categorias_imoveis', 'imovel_categorias'];
    
    foreach ($tables as $table) {
        try {
            $result = $db->fetchOne("SHOW TABLES LIKE '$table'");
            if ($result) {
                echo "✅ Tabela '$table' existe\n";
                
                // Contar registros
                $count = $db->fetchOne("SELECT COUNT(*) as total FROM $table");
                echo "   📝 Registros: " . $count['total'] . "\n";
            } else {
                echo "❌ Tabela '$table' não foi criada\n";
            }
        } catch (Exception $e) {
            echo "❌ Erro ao verificar tabela '$table': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n✨ Sistema de categorias configurado com sucesso!\n";
    
} catch (Exception $e) {
    echo "❌ Erro fatal: " . $e->getMessage() . "\n";
    exit(1);
}
