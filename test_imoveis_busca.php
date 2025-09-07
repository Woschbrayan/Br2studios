<?php
/**
 * Script de Teste - Verificar Busca de Imóveis
 * Sistema Br2Studios
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

echo "<h2>Teste de Busca de Imóveis - Br2Studios</h2>";

try {
    $imovel = new Imovel();
    
    echo "<h3>1. Teste de Conexão</h3>";
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Conexão estabelecida!</p>";
    } else {
        echo "<p style='color: red;'>❌ Erro na conexão!</p>";
        exit;
    }
    
    echo "<h3>2. Busca de Todos os Imóveis</h3>";
    $todos_imoveis = $imovel->listarTodos([], 1, 50);
    
    if (is_array($todos_imoveis) && isset($todos_imoveis['imoveis'])) {
        $imoveis = $todos_imoveis['imoveis'];
        echo "<p style='color: green;'>✅ Total de imóveis encontrados: " . count($imoveis) . "</p>";
        
        if (count($imoveis) > 0) {
            echo "<h4>Primeiros 5 imóveis:</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background: #f0f0f0;'>";
            echo "<th>ID</th><th>Título</th><th>Status</th><th>Destaque</th><th>Valorização</th><th>Ano Entrega</th><th>Preço</th>";
            echo "</tr>";
            
            for ($i = 0; $i < min(5, count($imoveis)); $i++) {
                $im = $imoveis[$i];
                echo "<tr>";
                echo "<td>{$im['id']}</td>";
                echo "<td>{$im['titulo']}</td>";
                echo "<td>{$im['status']}</td>";
                echo "<td>" . ($im['destaque'] ? 'SIM' : 'NÃO') . "</td>";
                echo "<td>" . ($im['maior_valorizacao'] ? 'SIM' : 'NÃO') . "</td>";
                echo "<td>{$im['ano_entrega']}</td>";
                echo "<td>R$ " . number_format($im['preco'], 2, ',', '.') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'>❌ Erro na busca de imóveis</p>";
        echo "<pre>" . print_r($todos_imoveis, true) . "</pre>";
    }
    
    echo "<h3>3. Busca de Imóveis em Destaque</h3>";
    $filtros_destaque = ['destaque' => 1, 'status' => 'disponivel'];
    $imoveis_destaque = $imovel->listarTodos($filtros_destaque, 1, 20);
    
    if (is_array($imoveis_destaque) && isset($imoveis_destaque['imoveis'])) {
        $destaque = $imoveis_destaque['imoveis'];
        echo "<p style='color: green;'>✅ Imóveis em destaque encontrados: " . count($destaque) . "</p>";
        
        if (count($destaque) > 0) {
            echo "<h4>Imóveis em destaque:</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background: #f0f0f0;'>";
            echo "<th>ID</th><th>Título</th><th>Destaque</th><th>Valorização</th><th>Ano Entrega</th>";
            echo "</tr>";
            
            foreach ($destaque as $im) {
                echo "<tr>";
                echo "<td>{$im['id']}</td>";
                echo "<td>{$im['titulo']}</td>";
                echo "<td>" . ($im['destaque'] ? 'SIM' : 'NÃO') . "</td>";
                echo "<td>" . ($im['maior_valorizacao'] ? 'SIM' : 'NÃO') . "</td>";
                echo "<td>{$im['ano_entrega']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'>❌ Erro na busca de imóveis em destaque</p>";
        echo "<pre>" . print_r($imoveis_destaque, true) . "</pre>";
    }
    
    echo "<h3>4. Busca de Imóveis de Valorização</h3>";
    $filtros_valorizacao = ['maior_valorizacao' => 1, 'status' => 'disponivel'];
    $imoveis_valorizacao = $imovel->listarTodos($filtros_valorizacao, 1, 20);
    
    if (is_array($imoveis_valorizacao) && isset($imoveis_valorizacao['imoveis'])) {
        $valorizacao = $imoveis_valorizacao['imoveis'];
        echo "<p style='color: green;'>✅ Imóveis de valorização encontrados: " . count($valorizacao) . "</p>";
        
        if (count($valorizacao) > 0) {
            echo "<h4>Imóveis de valorização:</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background: #f0f0f0;'>";
            echo "<th>ID</th><th>Título</th><th>Destaque</th><th>Valorização</th><th>Ano Entrega</th>";
            echo "</tr>";
            
            foreach ($valorizacao as $im) {
                echo "<tr>";
                echo "<td>{$im['id']}</td>";
                echo "<td>{$im['titulo']}</td>";
                echo "<td>" . ($im['destaque'] ? 'SIM' : 'NÃO') . "</td>";
                echo "<td>" . ($im['maior_valorizacao'] ? 'SIM' : 'NÃO') . "</td>";
                echo "<td>{$im['ano_entrega']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'>❌ Erro na busca de imóveis de valorização</p>";
        echo "<pre>" . print_r($imoveis_valorizacao, true) . "</pre>";
    }
    
    echo "<h3>5. Teste de Fallback</h3>";
    $filtros_fallback = ['status' => 'disponivel'];
    $imoveis_fallback = $imovel->listarTodos($filtros_fallback, 1, 10);
    
    if (is_array($imoveis_fallback) && isset($imoveis_fallback['imoveis'])) {
        $fallback = $imoveis_fallback['imoveis'];
        echo "<p style='color: green;'>✅ Imóveis disponíveis (fallback): " . count($fallback) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Erro na busca de fallback</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Voltar para o site</a></p>";
?>
