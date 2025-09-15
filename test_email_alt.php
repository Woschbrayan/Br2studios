<?php
/**
 * Teste com Configuração Alternativa - BR2Studios
 */

require_once __DIR__ . '/config/email_config_alt.php';

echo "<h1>🔧 Teste com Configuração Alternativa</h1>";

echo "<h2>Configuração Atual:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> " . SMTP_HOST . "</li>";
echo "<li><strong>Port:</strong> " . SMTP_PORT . "</li>";
echo "<li><strong>Username:</strong> " . SMTP_USERNAME . "</li>";
echo "<li><strong>Encryption:</strong> " . SMTP_ENCRYPTION . "</li>";
echo "</ul>";

echo "<h2>Teste de Envio:</h2>";

$para = 'contato@br2imoveis.com.br';
$assunto = 'Teste Configuração Alternativa';
$mensagem = '<h1>Teste</h1><p>Este é um teste com configuração alternativa.</p>';

$resultado = enviarEmailSimples($para, $assunto, $mensagem);

if ($resultado) {
    echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724; margin: 0;'>✅ Sucesso!</h3>";
    echo "<p style='color: #155724; margin: 10px 0 0 0;'>Email enviado com sucesso usando a configuração alternativa.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24; margin: 0;'>❌ Erro</h3>";
    echo "<p style='color: #721c24; margin: 10px 0 0 0;'>Não foi possível enviar o email. Verifique as configurações.</p>";
    echo "</div>";
}

echo "<h2>🔧 Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Se funcionou: Copie as configurações para <code>email_config.php</code></li>";
echo "<li>Se não funcionou: Tente outras configurações:</li>";
echo "<ul>";
echo "<li>Porta 587 com TLS</li>";
echo "<li>Porta 465 com SSL</li>";
echo "<li>Verificar se o email existe no cPanel</li>";
echo "</ul>";
echo "</ol>";

echo "<p><a href='test_smtp_configs.php'>🔍 Testar outras configurações SMTP</a></p>";
echo "<p><a href='test_email.php'>← Voltar para teste principal</a></p>";
?>
