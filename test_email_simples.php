<?php
/**
 * Teste Simples de Email - BR2Studios
 * Diagnóstico básico do sistema de email
 */

echo "<h1>🔍 Diagnóstico do Sistema de Email</h1>";

// Verificar se mail() está disponível
echo "<h2>1. Verificação do Servidor</h2>";
if (function_exists('mail')) {
    echo "<p style='color: green;'>✅ Função mail() está disponível</p>";
} else {
    echo "<p style='color: red;'>❌ Função mail() NÃO está disponível</p>";
    echo "<p>O servidor não suporta envio de emails nativo.</p>";
    exit;
}

// Verificar configurações PHP
echo "<h2>2. Configurações PHP</h2>";
echo "<p><strong>sendmail_path:</strong> " . ini_get('sendmail_path') . "</p>";
echo "<p><strong>SMTP:</strong> " . ini_get('SMTP') . "</p>";
echo "<p><strong>smtp_port:</strong> " . ini_get('smtp_port') . "</p>";

// Teste básico de email
echo "<h2>3. Teste Básico de Email</h2>";

$para = 'brayanwosch@gmail.com';
$assunto = 'Teste Simples - BR2Studios';
$mensagem = 'Este é um teste simples do sistema de email.';
$headers = 'From: BR2Studios <brayanwosch@gmail.com>' . "\r\n" .
           'Reply-To: brayanwosch@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

echo "<p><strong>Enviando para:</strong> $para</p>";
echo "<p><strong>Assunto:</strong> $assunto</p>";

$resultado = mail($para, $assunto, $mensagem, $headers);

if ($resultado) {
    echo "<p style='color: green;'>✅ Email enviado com sucesso!</p>";
    echo "<p>Verifique sua caixa de entrada (e spam) em alguns minutos.</p>";
} else {
    echo "<p style='color: red;'>❌ Erro ao enviar email</p>";
    echo "<p>Possíveis causas:</p>";
    echo "<ul>";
    echo "<li>Servidor não configurado para envio de emails</li>";
    echo "<li>Firewall bloqueando envio</li>";
    echo "<li>Configurações SMTP incorretas</li>";
    echo "</ul>";
}

// Verificar logs de erro
echo "<h2>4. Logs de Erro</h2>";
$error_log = ini_get('error_log');
if ($error_log) {
    echo "<p><strong>Arquivo de log:</strong> $error_log</p>";
} else {
    echo "<p>Nenhum arquivo de log configurado</p>";
}

// Verificar se estamos em ambiente local
echo "<h2>5. Ambiente</h2>";
echo "<p><strong>Servidor:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Local:</strong> " . (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? 'Sim' : 'Não') . "</p>";

if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    echo "<div style='background: #fff3cd; padding: 15px; border: 1px solid #ffeaa7; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>⚠️ Ambiente Local Detectado</h3>";
    echo "<p>Em ambiente local (localhost), o envio de emails pode não funcionar porque:</p>";
    echo "<ul>";
    echo "<li>Não há servidor SMTP configurado</li>";
    echo "<li>XAMPP/WAMP não vem com servidor de email</li>";
    echo "<li>Precisa configurar um servidor SMTP externo</li>";
    echo "</ul>";
    echo "<p><strong>Solução:</strong> Use um servidor SMTP externo (Gmail, SendGrid, etc.)</p>";
    echo "</div>";
}

echo "<h2>6. Próximos Passos</h2>";
echo "<ol>";
echo "<li>Se estiver em localhost, configure um servidor SMTP externo</li>";
echo "<li>Se estiver em servidor real, verifique configurações do servidor</li>";
echo "<li>Teste com um serviço como SendGrid ou Mailgun</li>";
echo "<li>Verifique logs do servidor para mais detalhes</li>";
echo "</ol>";

echo "<p><a href='test_email.php'>← Voltar para teste completo</a></p>";
?>
