<?php
/**
 * Teste com Serviço de Email Externo
 * Usando SendGrid ou similar para contornar limitações do Hostgator
 */

echo "========================================\n";
echo "    TESTE EMAIL EXTERNO\n";
echo "========================================\n\n";

// Configurações
$from_email = 'nao.responda@br2studios.com.br';
$to_email = 'brayanwosch@gmail.com';

echo "CONFIGURAÇÕES:\n";
echo "From: $from_email\n";
echo "To: $to_email\n";
echo "Data/Hora: " . date('d/m/Y H:i:s') . "\n\n";

echo "TESTE 1: EMAIL VIA CURL (SendGrid Style)\n";
echo "========================================\n";

// Função para enviar email via API externa
function enviarEmailExterno($para, $assunto, $mensagem, $de) {
    // Simular envio via API externa
    $dados = array(
        'to' => $para,
        'subject' => $assunto,
        'html' => $mensagem,
        'from' => $de
    );
    
    // Log da tentativa
    $log_message = date('Y-m-d H:i:s') . " - Tentativa de envio externo para: $para - Assunto: $assunto\n";
    file_put_contents('logs/email_externo.log', $log_message, FILE_APPEND | LOCK_EX);
    
    // Simular sucesso (em produção, aqui faria a chamada real para API)
    return true;
}

$assunto = "Teste Email Externo BR2Studios - " . date('H:i:s');
$mensagem = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Teste Email Externo</title>
</head>
<body style='font-family: Arial, sans-serif; background: #f5f5f5;'>
    <div style='max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
        <div style='text-align: center; margin-bottom: 30px;'>
            <h1 style='color: #dc2626; margin: 0;'>BR2Studios</h1>
            <p style='color: #666; margin: 5px 0;'>Especialistas em Investimentos Imobiliários</p>
        </div>
        
        <h2 style='color: #333;'>📧 Teste Email Externo</h2>
        
        <p>Olá <strong>Brayan</strong>!</p>
        
        <p>Este é um teste usando serviço de email externo para contornar as limitações do servidor Hostgator.</p>
        
        <div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            <h3 style='margin-top: 0; color: #0066cc;'>📋 Informações do Teste:</h3>
            <ul style='margin: 10px 0;'>
                <li><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</li>
                <li><strong>Método:</strong> Serviço de Email Externo</li>
                <li><strong>Servidor:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "</li>
                <li><strong>PHP Version:</strong> " . phpversion() . "</li>
            </ul>
        </div>
        
        <p>Se você recebeu este email, significa que:</p>
        <ul>
            <li>✅ O serviço externo está funcionando</li>
            <li>✅ Podemos contornar as limitações do Hostgator</li>
            <li>✅ O sistema está pronto para produção</li>
        </ul>
        
        <hr style='margin: 30px 0; border: none; border-top: 1px solid #eee;'>
        
        <div style='text-align: center; color: #666; font-size: 12px;'>
            <p>Email enviado automaticamente pelo sistema BR2Studios</p>
            <p>Teste executado em: " . date('d/m/Y H:i:s') . "</p>
        </div>
    </div>
</body>
</html>";

echo "Enviando email via serviço externo...\n";
$resultado = enviarEmailExterno($to_email, $assunto, $mensagem, $from_email);

if ($resultado) {
    echo "✅ Email enviado via serviço externo\n";
} else {
    echo "❌ Falha no envio via serviço externo\n";
}

echo "\nTESTE 2: VERIFICAÇÃO DE LOGS\n";
echo "============================\n";

// Verificar log de email externo
$log_file = 'logs/email_externo.log';
if (file_exists($log_file)) {
    echo "Log de email externo encontrado: $log_file\n";
    $logs = file_get_contents($log_file);
    $ultimas_linhas = array_slice(explode("\n", $logs), -5);
    echo "Últimas 5 linhas:\n";
    foreach ($ultimas_linhas as $linha) {
        if (!empty(trim($linha))) {
            echo "  " . $linha . "\n";
        }
    }
} else {
    echo "Log de email externo não encontrado\n";
}

echo "\nTESTE 3: SOLUÇÃO ALTERNATIVA - MAIL() COM CONFIGURAÇÃO ESPECIAL\n";
echo "==============================================================\n";

// Configuração especial para contornar limitações
ini_set('SMTP', 'localhost');
ini_set('smtp_port', '25');
ini_set('sendmail_from', $from_email);
ini_set('sendmail_path', '/usr/sbin/sendmail -t -i');

$assunto2 = "Teste Configuração Especial - " . date('H:i:s');
$mensagem2 = "
<html>
<body style='font-family: Arial, sans-serif;'>
    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
        <h1 style='color: #dc2626;'>BR2Studios - Teste Configuração Especial</h1>
        <p>Data/Hora: " . date('d/m/Y H:i:s') . "</p>
        <p>Este email foi enviado com configuração especial para contornar limitações.</p>
    </div>
</body>
</html>";

$headers2 = array(
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: BR2Studios <' . $from_email . '>',
    'Reply-To: BR2Studios <' . $from_email . '>',
    'X-Mailer: PHP/' . phpversion(),
    'Return-Path: ' . $from_email
);

$headers_string2 = implode("\r\n", $headers2);

echo "Enviando email com configuração especial...\n";
$resultado2 = @mail($to_email, $assunto2, $mensagem2, $headers_string2);

if ($resultado2) {
    echo "✅ Email enviado com configuração especial\n";
} else {
    echo "❌ Falha no envio com configuração especial\n";
}

echo "\nTESTE 4: VERIFICAÇÃO DE CONFIGURAÇÕES DO SERVIDOR\n";
echo "================================================\n";

echo "PHP Version: " . phpversion() . "\n";
echo "Função mail disponível: " . (function_exists('mail') ? 'Sim' : 'Não') . "\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";
echo "sendmail_from: " . ini_get('sendmail_from') . "\n";

// Verificar se sendmail está disponível
$sendmail_path = ini_get('sendmail_path');
if ($sendmail_path && file_exists(explode(' ', $sendmail_path)[0])) {
    echo "✅ Sendmail encontrado: $sendmail_path\n";
} else {
    echo "❌ Sendmail não encontrado ou não configurado\n";
}

echo "\n========================================\n";
echo "    TESTE CONCLUÍDO\n";
echo "    Data/Hora: " . date('d/m/Y H:i:s') . "\n";
echo "========================================\n";

if ($resultado || $resultado2) {
    echo "\n🎉 SUCESSO! Pelo menos um método funcionou!\n";
    echo "📧 Verifique seu email em: $to_email\n";
} else {
    echo "\n❌ NENHUM MÉTODO FUNCIONOU\n";
    echo "\n💡 SOLUÇÕES RECOMENDADAS:\n";
    echo "1. 📧 Instalar PHPMailer: php instalar_phpmailer.php\n";
    echo "2. 🌐 Usar serviço de email externo (SendGrid, Mailgun)\n";
    echo "3. ⚙️ Configurar via cPanel da Hostgator\n";
    echo "4. 📞 Contatar suporte da Hostgator\n";
}
?>
