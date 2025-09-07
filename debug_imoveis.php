<?php
/**
 * Script de Debug - Verificar Imóveis no Banco de Dados
 * Sistema Br2Studios
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

echo "<h2>Debug - Imóveis no Banco de Dados</h2>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Conexão com banco de dados estabelecida!</p>";
        
        // Verificar se a tabela imoveis existe
        $sql_check_table = "SHOW TABLES LIKE 'imoveis'";
        $result = $conn->query($sql_check_table);
        
        if ($result->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Tabela 'imoveis' existe!</p>";
            
            // Contar total de imóveis
            $sql_count = "SELECT COUNT(*) as total FROM imoveis";
            $count_result = $conn->query($sql_count);
            $total = $count_result->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo "<p><strong>Total de imóveis cadastrados:</strong> {$total}</p>";
            
            if ($total > 0) {
                // Listar todos os imóveis
                $sql_list = "SELECT id, titulo, tipo, cidade, estado, preco, status, destaque, maior_valorizacao, data_cadastro FROM imoveis ORDER BY data_cadastro DESC";
                $list_result = $conn->query($sql_list);
                $imoveis = $list_result->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<h3>Lista de Imóveis:</h3>";
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr style='background: #f0f0f0;'>";
                echo "<th>ID</th><th>Título</th><th>Tipo</th><th>Cidade</th><th>Estado</th><th>Preço</th><th>Status</th><th>Destaque</th><th>Valorização</th><th>Data</th>";
                echo "</tr>";
                
                foreach ($imoveis as $imovel) {
                    echo "<tr>";
                    echo "<td>{$imovel['id']}</td>";
                    echo "<td>{$imovel['titulo']}</td>";
                    echo "<td>{$imovel['tipo']}</td>";
                    echo "<td>{$imovel['cidade']}</td>";
                    echo "<td>{$imovel['estado']}</td>";
                    echo "<td>R$ " . number_format($imovel['preco'], 2, ',', '.') . "</td>";
                    echo "<td>{$imovel['status']}</td>";
                    echo "<td>" . ($imovel['destaque'] ? 'Sim' : 'Não') . "</td>";
                    echo "<td>" . ($imovel['maior_valorizacao'] ? 'Sim' : 'Não') . "</td>";
                    echo "<td>{$imovel['data_cadastro']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Testar a classe Imovel
                echo "<h3>Teste da Classe Imovel:</h3>";
                
                $imovel = new Imovel();
                
                // Testar busca de imóveis em destaque
                echo "<h4>Imóveis em Destaque:</h4>";
                $filtros_destaque = ['destaque' => 1, 'status' => 'disponivel'];
                $result_destaque = $imovel->listarTodos($filtros_destaque);
                
                if (is_array($result_destaque) && isset($result_destaque['imoveis'])) {
                    $imoveis_destaque = $result_destaque['imoveis'];
                    echo "<p>Encontrados " . count($imoveis_destaque) . " imóveis em destaque:</p>";
                    
                    foreach ($imoveis_destaque as $imovel_destaque) {
                        echo "<p>- {$imovel_destaque['titulo']} - {$imovel_destaque['cidade']}/{$imovel_destaque['estado']}</p>";
                    }
                } else {
                    echo "<p style='color: red;'>❌ Erro ao buscar imóveis em destaque</p>";
                    echo "<pre>" . print_r($result_destaque, true) . "</pre>";
                }
                
                // Testar busca de imóveis de maior valorização
                echo "<h4>Imóveis de Maior Valorização:</h4>";
                $filtros_valorizacao = ['maior_valorizacao' => 1, 'status' => 'disponivel'];
                $result_valorizacao = $imovel->listarTodos($filtros_valorizacao);
                
                if (is_array($result_valorizacao) && isset($result_valorizacao['imoveis'])) {
                    $imoveis_valorizacao = $result_valorizacao['imoveis'];
                    echo "<p>Encontrados " . count($imoveis_valorizacao) . " imóveis de maior valorização:</p>";
                    
                    foreach ($imoveis_valorizacao as $imovel_valorizacao) {
                        echo "<p>- {$imovel_valorizacao['titulo']} - {$imovel_valorizacao['cidade']}/{$imovel_valorizacao['estado']}</p>";
                    }
                } else {
                    echo "<p style='color: red;'>❌ Erro ao buscar imóveis de maior valorização</p>";
                    echo "<pre>" . print_r($result_valorizacao, true) . "</pre>";
                }
                
            } else {
                echo "<p style='color: orange;'>⚠️ Nenhum imóvel cadastrado no banco de dados!</p>";
                echo "<p>Você precisa cadastrar imóveis através do painel administrativo.</p>";
            }
            
        } else {
            echo "<p style='color: red;'>❌ Tabela 'imoveis' não existe!</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Erro ao conectar com banco de dados!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Voltar para o site</a></p>";
?>
