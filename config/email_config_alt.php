<?php
/**
 * Configurações Alternativas de Email - BR2Studios
 * Para testar diferentes configurações SMTP
 */

// Configurações SMTP - ALTERNATIVAS PARA TESTE
define('SMTP_HOST', 'plenaplaygrounds.com.br');
define('SMTP_PORT', 25); // Tente: 25, 587, 465
define('SMTP_USERNAME', 'contato@br2imoveis.com.br');
define('SMTP_PASSWORD', 'Br2@empreendimentos2025');
define('SMTP_ENCRYPTION', 'none'); // Tente: 'none', 'tls', 'ssl'

// Configurações do remetente
define('FROM_EMAIL', 'contato@br2imoveis.com.br');
define('FROM_NAME', 'BR2Studios - Contato');
define('TO_EMAIL', 'contato@br2imoveis.com.br');
define('TO_NAME', 'BR2Studios');
define('REPLY_TO_EMAIL', 'contato@br2imoveis.com.br');
define('REPLY_TO_NAME', 'BR2Studios');

// Configurações de debug
define('EMAIL_DEBUG', false);
define('EMAIL_LOG_FILE', __DIR__ . '/../logs/email.log');

/**
 * Função SMTP simplificada para teste
 */
function enviarEmailSimples($para, $assunto, $mensagem) {
    $smtp_host = SMTP_HOST;
    $smtp_port = SMTP_PORT;
    $smtp_user = SMTP_USERNAME;
    $smtp_pass = SMTP_PASSWORD;
    
    echo "<h3>Teste SMTP Simplificado:</h3>";
    echo "<p><strong>Host:</strong> $smtp_host:$smtp_port</p>";
    echo "<p><strong>User:</strong> $smtp_user</p>";
    echo "<p><strong>Para:</strong> $para</p>";
    
    // Conectar
    $socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);
    if (!$socket) {
        echo "<p style='color: red;'>❌ Erro de conexão: $errstr ($errno)</p>";
        return false;
    }
    
    echo "<p style='color: green;'>✅ Conectado</p>";
    
    // Ler resposta inicial
    $response = fgets($socket, 512);
    echo "<p><strong>Resposta:</strong> " . trim($response) . "</p>";
    
    // EHLO
    fputs($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>EHLO:</strong> " . trim($response) . "</p>";
    
    // AUTH LOGIN
    fputs($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>AUTH:</strong> " . trim($response) . "</p>";
    
    // Username
    fputs($socket, base64_encode($smtp_user) . "\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>Username:</strong> " . trim($response) . "</p>";
    
    // Password
    fputs($socket, base64_encode($smtp_pass) . "\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>Password:</strong> " . trim($response) . "</p>";
    
    // MAIL FROM
    fputs($socket, "MAIL FROM: <" . FROM_EMAIL . ">\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>MAIL FROM:</strong> " . trim($response) . "</p>";
    
    // RCPT TO
    fputs($socket, "RCPT TO: <$para>\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>RCPT TO:</strong> " . trim($response) . "</p>";
    
    // DATA
    fputs($socket, "DATA\r\n");
    $response = fgets($socket, 512);
    echo "<p><strong>DATA:</strong> " . trim($response) . "</p>";
    
    // Conteúdo do email
    $email_data = "From: " . FROM_NAME . " <" . FROM_EMAIL . ">\r\n";
    $email_data .= "To: $para\r\n";
    $email_data .= "Subject: $assunto\r\n";
    $email_data .= "MIME-Version: 1.0\r\n";
    $email_data .= "Content-Type: text/html; charset=UTF-8\r\n";
    $email_data .= "\r\n";
    $email_data .= $mensagem . "\r\n";
    $email_data .= ".\r\n";
    
    fputs($socket, $email_data);
    $response = fgets($socket, 512);
    echo "<p><strong>Email enviado:</strong> " . trim($response) . "</p>";
    
    // QUIT
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    $sucesso = strpos($response, '250') === 0;
    echo "<p><strong>Resultado:</strong> " . ($sucesso ? 'SUCESSO' : 'ERRO') . "</p>";
    
    return $sucesso;
}
?>
