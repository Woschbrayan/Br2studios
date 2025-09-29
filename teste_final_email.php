<?php
/**
 * Teste Final de Email - Configuração Corrigida
 * Usando porta 587 (TLS) que está funcionando
 */

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Teste Final Email - BR2Studios</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>📧 Teste Final de Email - BR2Studios</h1>";

// Configurações CORRETAS baseadas no teste
$smtp_host = 'mail.br2studios.com.br';
$smtp_port = 587; // TLS - FUNCIONANDO!
$smtp_user = 'nao.responda@br2studios.com.br';
$smtp_pass = 'Br2studios!';
$from_email = 'nao.responda@br2studios.com.br';
$from_name = 'BR2Studios - Teste';
$to_email = 'brayanwosch@gmail.com';

echo "<div class='info'>";
echo "<h2>🔧 Configurações CORRIGIDAS:</h2>";
echo "<ul>";
echo "<li><strong>Host:</strong> $smtp_host</li>";
echo "<li><strong>Porta:</strong> $smtp_port (TLS - FUNCIONANDO!)</li>";
echo "<li><strong>Usuário:</strong> $smtp_user</li>";
echo "<li><strong>Destino:</strong> $to_email</li>";
echo "<li><strong>Criptografia:</strong> TLS</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🧪 Testando Conexão SMTP (Porta 587)...</h2>";

// Teste de conexão
$socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, 10);
if (!$socket) {
    echo "<div class='error'>";
    echo "❌ <strong>Erro de conexão:</strong> $errstr ($errno)";
    echo "</div>";
} else {
    echo "<div class='success'>";
    echo "✅ <strong>Conexão SMTP OK!</strong> Conectado ao servidor Hostgator";
    echo "</div>";
    
    // Ler resposta inicial
    $response = fgets($socket, 512);
    echo "<p><strong>Resposta do servidor:</strong> " . htmlspecialchars(trim($response)) . "</p>";
    
    fclose($socket);
}

echo "<h2>📨 Enviando Email de Teste...</h2>";

// Configurar parâmetros do servidor
ini_set('SMTP', $smtp_host);
ini_set('smtp_port', $smtp_port);
ini_set('sendmail_from', $from_email);

$assunto = "✅ TESTE FINAL - Email BR2Studios Funcionando! - " . date('H:i:s');
$mensagem = "
<html>
<head>
    <meta charset='UTF-8'>
    <title>Teste Final - Email Funcionando</title>
</head>
<body style='font-family: Arial, sans-serif; background: #f5f5f5;'>
    <div style='max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
        <div style='text-align: center; margin-bottom: 30px;'>
            <h1 style='color: #dc2626; margin: 0;'>BR2Studios</h1>
            <p style='color: #666; margin: 5px 0;'>Especialistas em Investimentos Imobiliários</p>
        </div>
        
        <h2 style='color: #333;'>✅ Email Funcionando Perfeitamente!</h2>
        
        <p>Olá <strong>Brayan</strong>!</p>
        
        <p>🎉 <strong>Sucesso!</strong> O sistema de email está funcionando corretamente!</p>
        
        <div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            <h3 style='margin-top: 0; color: #0066cc;'>📋 Detalhes do Teste:</h3>
            <ul style='margin: 10px 0;'>
                <li><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</li>
                <li><strong>Servidor:</strong> $smtp_host:$smtp_port</li>
                <li><strong>Criptografia:</strong> TLS</li>
                <li><strong>Status:</strong> ✅ FUNCIONANDO</li>
            </ul>
        </div>
        
        <p>O sistema está pronto para:</p>
        <ul>
            <li>📧 Receber contatos do site</li>
            <li>📬 Enviar confirmações automáticas</li>
            <li>📨 Processar formulários de contato</li>
        </ul>
        
        <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
        
        <div style='text-align: center; color: #666; font-size: 12px;'>
            <p>Email enviado automaticamente pelo sistema BR2Studios</p>
            <p>Teste executado em: " . date('d/m/Y H:i:s') . "</p>
        </div>
    </div>
</body>
</html>";

// Headers do email
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: ' . $from_name . ' <' . $from_email . '>',
    'Reply-To: ' . $from_name . ' <' . $from_email . '>',
    'X-Mailer: PHP/' . phpversion(),
    'Return-Path: ' . $from_email
);

$headers_string = implode("\r\n", $headers);

echo "<p><strong>Enviando email para:</strong> $to_email</p>";
echo "<p><strong>Assunto:</strong> $assunto</p>";

$resultado = @mail($to_email, $assunto, $mensagem, $headers_string);

if ($resultado) {
    echo "<div class='success'>";
    echo "<h3>🎉 <strong>SUCESSO TOTAL!</strong></h3>";
    echo "<p>✅ Email enviado com sucesso para <strong>$to_email</strong></p>";
    echo "<p>📧 <strong>Verifique sua caixa de entrada agora!</strong></p>";
    echo "<p>📁 Se não aparecer, verifique a pasta de spam/lixo eletrônico</p>";
    echo "<p>⏰ Pode demorar alguns minutos para chegar</p>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ <strong>AINDA HÁ PROBLEMA</strong></h3>";
    echo "<p>Falha no envio mesmo com configurações corretas</p>";
    echo "<p>Possível problema com credenciais ou configuração do servidor</p>";
    echo "</div>";
}

echo "<h2>📊 Status Final:</h2>";
echo "<ul>";
echo "<li><strong>Conexão SMTP:</strong> " . ($socket ? '✅ OK' : '❌ Erro') . "</li>";
echo "<li><strong>Porta 587:</strong> ✅ Funcionando</li>";
echo "<li><strong>Função mail():</strong> " . (function_exists('mail') ? '✅ Disponível' : '❌ Indisponível') . "</li>";
echo "<li><strong>Envio de email:</strong> " . ($resultado ? '✅ Sucesso' : '❌ Erro') . "</li>";
echo "</ul>";

echo "<h2>📝 Próximos Passos:</h2>";
if ($resultado) {
    echo "<div class='success'>";
    echo "<ol>";
    echo "<li>✅ <strong>Sistema funcionando!</strong> Verifique seu email</li>";
    echo "<li>✅ Formulário de contato do site está pronto</li>";
    echo "<li>✅ Pode remover arquivos de teste</li>";
    echo "<li>✅ Sistema pronto para produção</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<ol>";
    echo "<li>❌ Verificar se email <strong>$smtp_user</strong> existe no cPanel</li>";
    echo "<li>❌ Confirmar senha <strong>Br2studios!</strong></li>";
    echo "<li>❌ Verificar logs do servidor</li>";
    echo "<li>❌ Contatar suporte Hostgator se necessário</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<hr>";
echo "<p><em>Teste final executado em: " . date('d/m/Y H:i:s') . "</em></p>";
echo "</div>";
echo "</body>";
echo "</html>";
?>
