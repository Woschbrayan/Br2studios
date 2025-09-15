<?php
/**
 * Teste de Diferentes Configurações SMTP - BR2Studios
 */

echo "<h1>🔧 Teste de Configurações SMTP</h1>";

// Configurações para testar
$configs = [
    [
        'name' => 'Configuração 1: Porta 25 (sem TLS)',
        'host' => 'plenaplaygrounds.com.br',
        'port' => 25,
        'tls' => false
    ],
    [
        'name' => 'Configuração 2: Porta 587 (com TLS)',
        'host' => 'plenaplaygrounds.com.br',
        'port' => 587,
        'tls' => true
    ],
    [
        'name' => 'Configuração 3: Porta 465 (SSL)',
        'host' => 'plenaplaygrounds.com.br',
        'port' => 465,
        'tls' => false,
        'ssl' => true
    ]
];

foreach ($configs as $config) {
    echo "<h2>{$config['name']}</h2>";
    
    // Testar conexão
    $socket = @fsockopen($config['host'], $config['port'], $errno, $errstr, 10);
    
    if (!$socket) {
        echo "<p style='color: red;'>❌ Erro de conexão: $errstr ($errno)</p>";
        continue;
    }
    
    echo "<p style='color: green;'>✅ Conexão estabelecida</p>";
    
    // Ler resposta inicial
    $response = fgets($socket, 512);
    echo "<p><strong>Resposta inicial:</strong> " . trim($response) . "</p>";
    
    // EHLO
    fputs($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>EHLO:</strong> " . trim($response) . "</p>";
    
    // Se for TLS, testar STARTTLS
    if ($config['tls']) {
        fputs($socket, "STARTTLS\r\n");
        $response = fgets($socket, 512);
        echo "<p><strong>STARTTLS:</strong> " . trim($response) . "</p>";
        
        if (strpos($response, '220') === 0) {
            echo "<p style='color: green;'>✅ STARTTLS suportado</p>";
        } else {
            echo "<p style='color: red;'>❌ STARTTLS não suportado</p>";
        }
    }
    
    // QUIT
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    echo "<hr>";
}

echo "<h2>📋 Recomendações:</h2>";
echo "<ul>";
echo "<li><strong>Porta 25:</strong> Usar sem TLS para servidores que não suportam</li>";
echo "<li><strong>Porta 587:</strong> Usar com TLS para maior segurança</li>";
echo "<li><strong>Porta 465:</strong> Usar SSL direto (menos comum)</li>";
echo "</ul>";

echo "<h2>🔧 Configuração Atual:</h2>";
echo "<p>O sistema está configurado para usar <strong>porta 25 sem TLS</strong>.</p>";
echo "<p>Se não funcionar, tente mudar para porta 587 com TLS.</p>";

echo "<p><a href='test_email.php'>← Voltar para teste de email</a></p>";
?>
