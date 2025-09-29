<?php
/**
 * Teste Simples Final
 * Usando apenas mail() nativo com configurações corretas
 */

echo "========================================\n";
echo "    TESTE SIMPLES FINAL\n";
echo "========================================\n\n";

// Configurações
$from_email = 'nao.responda@br2studios.com.br';
$to_email = 'brayanwosch@gmail.com';

echo "CONFIGURAÇÕES:\n";
echo "From: $from_email\n";
echo "To: $to_email\n";
echo "Data/Hora: " . date('d/m/Y H:i:s') . "\n\n";

echo "TESTE 1: MAIL() NATIVO (Configuração Limpa)\n";
echo "===========================================\n";

// Limpar todas as configurações SMTP
ini_set('SMTP', '');
ini_set('smtp_port', '');
ini_set('sendmail_from', '');

$assunto = "Teste Final BR2Studios - " . date('H:i:s');
$mensagem = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Teste Final</title>
</head>
<body style='font-family: Arial, sans-serif; background: #f5f5f5;'>
    <div style='max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
        <div style='text-align: center; margin-bottom: 30px;'>
            <h1 style='color: #dc2626; margin: 0;'>BR2Studios</h1>
            <p style='color: #666; margin: 5px 0;'>Especialistas em Investimentos Imobiliários</p>
        </div>
        
        <h2 style='color: #333;'>✅ Teste Final de Email</h2>
        
        <p>Olá <strong>Brayan</strong>!</p>
        
        <p>Este é o teste final para verificar se o sistema de email está funcionando.</p>
        
        <div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            <h3 style='margin-top: 0; color: #0066cc;'>📋 Informações do Teste:</h3>
            <ul style='margin: 10px 0;'>
                <li><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</li>
                <li><strong>Método:</strong> mail() nativo</li>
                <li><strong>Servidor:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "</li>
                <li><strong>PHP Version:</strong> " . phpversion() . "</li>
            </ul>
        </div>
        
        <p>Se você recebeu este email, significa que:</p>
        <ul>
            <li>✅ O servidor está configurado corretamente</li>
            <li>✅ A função mail() está funcionando</li>
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

// Headers do email
$headers = array(
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: BR2Studios <' . $from_email . '>',
    'Reply-To: BR2Studios <' . $from_email . '>',
    'X-Mailer: PHP/' . phpversion(),
    'Return-Path: ' . $from_email
);

$headers_string = implode("\r\n", $headers);

echo "Enviando email...\n";
echo "Assunto: $assunto\n";
echo "Headers: " . count($headers) . " headers configurados\n\n";

$resultado = @mail($to_email, $assunto, $mensagem, $headers_string);

if ($resultado) {
    echo "✅ SUCESSO! Email enviado\n";
    echo "📧 Verifique sua caixa de entrada em: $to_email\n";
    echo "📁 Verifique também a pasta de spam\n";
} else {
    echo "❌ ERRO! Falha no envio\n";
}

echo "\nTESTE 2: VERIFICAÇÃO DE CONFIGURAÇÕES\n";
echo "=====================================\n";

echo "PHP Version: " . phpversion() . "\n";
echo "Função mail disponível: " . (function_exists('mail') ? 'Sim' : 'Não') . "\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";
echo "sendmail_from: " . ini_get('sendmail_from') . "\n";

echo "\nTESTE 3: VERIFICAÇÃO DE LOGS\n";
echo "============================\n";

// Verificar logs de erro
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    echo "Log de erro PHP: $error_log\n";
    $logs = file_get_contents($error_log);
    $ultimas_linhas = array_slice(explode("\n", $logs), -10);
    echo "Últimas 10 linhas do log:\n";
    foreach ($ultimas_linhas as $linha) {
        if (!empty(trim($linha))) {
            echo "  " . $linha . "\n";
        }
    }
} else {
    echo "Log de erro PHP não encontrado ou não configurado\n";
}

echo "\nTESTE 4: TESTE COM CONFIGURAÇÃO SMTP LOCAL\n";
echo "==========================================\n";

// Tentar com configuração SMTP local
ini_set('SMTP', 'localhost');
ini_set('smtp_port', '25');
ini_set('sendmail_from', $from_email);

$assunto2 = "Teste SMTP Local - " . date('H:i:s');
$mensagem2 = "<html><body><h1>Teste SMTP Local</h1><p>Data: " . date('d/m/Y H:i:s') . "</p></body></html>";

$headers2 = "From: $from_email\r\n";
$headers2 .= "Reply-To: $from_email\r\n";
$headers2 .= "MIME-Version: 1.0\r\n";
$headers2 .= "Content-Type: text/html; charset=UTF-8\r\n";

echo "Enviando email via SMTP local...\n";
$resultado2 = @mail($to_email, $assunto2, $mensagem2, $headers2);

if ($resultado2) {
    echo "✅ SMTP local retornou TRUE\n";
} else {
    echo "❌ SMTP local retornou FALSE\n";
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
    echo "Possíveis soluções:\n";
    echo "1. Verificar configurações do servidor\n";
    echo "2. Instalar PHPMailer\n";
    echo "3. Usar serviço de email externo\n";
}
?>
