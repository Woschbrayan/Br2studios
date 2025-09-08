<?php
/**
 * Teste do Dashboard Admin
 * Verifica se todas as classes estão funcionando
 */

// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Teste do Dashboard Admin</h1>";

try {
    // Incluir configurações
    require_once '../config/php_limits.php';
    require_once '../config/database.php';
    
    echo "<h2>1. Teste de Inclusão de Classes</h2>";
    
    // Testar Database
    require_once '../classes/Database.php';
    $database = new Database();
    $db = $database->getConnection();
    echo "<p style='color: green;'>✅ Classe Database funcionando</p>";
    
    // Testar Corretor
    require_once '../classes/Corretor.php';
    $corretor = new Corretor();
    echo "<p style='color: green;'>✅ Classe Corretor funcionando</p>";
    
    // Testar Imovel
    require_once '../classes/Imovel.php';
    $imovel = new Imovel();
    echo "<p style='color: green;'>✅ Classe Imovel funcionando</p>";
    
    // Testar Depoimento
    require_once '../classes/Depoimento.php';
    $depoimento = new Depoimento();
    echo "<p style='color: green;'>✅ Classe Depoimento funcionando</p>";
    
    // Testar Especialista
    require_once '../classes/Especialista.php';
    $especialista = new Especialista();
    echo "<p style='color: green;'>✅ Classe Especialista funcionando</p>";
    
    // Testar Regiao
    require_once '../classes/Regiao.php';
    $regiao = new Regiao();
    echo "<p style='color: green;'>✅ Classe Regiao funcionando</p>";
    
    echo "<h2>2. Teste de Métodos</h2>";
    
    // Testar contagem
    $total_corretores = $corretor->contarTotal();
    echo "<p><strong>Total de corretores:</strong> $total_corretores</p>";
    
    $total_imoveis = $imovel->contarTotal();
    echo "<p><strong>Total de imóveis:</strong> $total_imoveis</p>";
    
    $total_depoimentos = $depoimento->contarTotal();
    echo "<p><strong>Total de depoimentos:</strong> $total_depoimentos</p>";
    
    $total_especialistas = $especialista->contarTotal();
    echo "<p><strong>Total de especialistas:</strong> $total_especialistas</p>";
    
    $total_regioes = $regiao->contarTotal();
    echo "<p><strong>Total de regiões:</strong> $total_regioes</p>";
    
    echo "<h2>3. Teste de Listagem</h2>";
    
    // Testar listagem
    $corretores = $corretor->listarTodos();
    echo "<p><strong>Corretores encontrados:</strong> " . count($corretores) . "</p>";
    
    $imoveis = $imovel->listarTodos();
    echo "<p><strong>Imóveis encontrados:</strong> " . count($imoveis) . "</p>";
    
    echo "<hr>";
    echo "<p style='color: green; font-weight: bold;'>✅ Dashboard funcionando perfeitamente!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<p><strong>Teste realizado em:</strong> " . date('d/m/Y H:i:s') . "</p>";
?>
