<?php
/**
 * Teste de Envio de Email - BR2Studios
 * Teste específico para brayanwosch@gmail.com
 */

// Incluir configurações de email
require_once __DIR__ . '/config/email_config.php';

echo "<h1>📧 Teste de Envio de Email</h1>";
echo "<p>Testando envio para: <strong>brayanwosch@gmail.com</strong></p>";

// Dados do teste
$para = 'brayanwosch@gmail.com';
$assunto = 'Teste de Email - BR2Studios';
$mensagem = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Teste de Email - BR2Studios</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BR2Studios</h1>
            <p>Teste de Sistema de Email</p>
        </div>
        
        <div class="content">
            <h2>✅ Email de Teste Enviado com Sucesso!</h2>
            
            <p>Este é um email de teste para verificar se o sistema de email da BR2Studios está funcionando corretamente.</p>
            
            <p><strong>Detalhes do teste:</strong></p>
            <ul>
                <li>Data/Hora: ' . date('d/m/Y H:i:s') . '</li>
                <li>Destinatário: brayanwosch@gmail.com</li>
                <li>Assunto: Teste de Email - BR2Studios</li>
                <li>Status: SUCESSO</li>
            </ul>
            
            <p>Se você está recebendo este email, significa que o sistema está funcionando perfeitamente!</p>
            
            <p>Atenciosamente,<br>
            <strong>Sistema BR2Studios</strong></p>
        </div>
        
        <div class="footer">
            <p>BR2Studios - Especialistas em Studios e Investimentos Imobiliários</p>
            <p>WhatsApp: (41) 4141-0093 | Email: contato@br2imoveis.com.br</p>
        </div>
    </div>
</body>
</html>';

echo "<h2>📤 Enviando email...</h2>";

// Tentar enviar o email
try {
    $resultado = enviarEmail($para, $assunto, $mensagem);
    
    if ($resultado) {
        echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3 style='color: #155724; margin: 0;'>✅ SUCESSO!</h3>";
        echo "<p style='color: #155724; margin: 10px 0 0 0;'>Email enviado com sucesso para <strong>brayanwosch@gmail.com</strong></p>";
        echo "<p style='color: #155724; margin: 5px 0 0 0;'>Verifique sua caixa de entrada (e pasta de spam) em alguns minutos.</p>";
        echo "</div>";
        
        echo "<h3>📋 Informações do Envio:</h3>";
        echo "<ul>";
        echo "<li><strong>Para:</strong> $para</li>";
        echo "<li><strong>Assunto:</strong> $assunto</li>";
        echo "<li><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</li>";
        echo "<li><strong>Status:</strong> Enviado com sucesso</li>";
        echo "</ul>";
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3 style='color: #721c24; margin: 0;'>❌ ERRO!</h3>";
        echo "<p style='color: #721c24; margin: 10px 0 0 0;'>Não foi possível enviar o email para <strong>brayanwosch@gmail.com</strong></p>";
        echo "<p style='color: #721c24; margin: 5px 0 0 0;'>Verifique as configurações SMTP e logs de erro.</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24; margin: 0;'>❌ EXCEÇÃO!</h3>";
    echo "<p style='color: #721c24; margin: 10px 0 0 0;'>Erro ao enviar email: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<h2>🔍 Verificações Adicionais:</h2>";

// Verificar configurações
echo "<h3>Configurações SMTP:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> " . SMTP_HOST . "</li>";
echo "<li><strong>Porta:</strong> " . SMTP_PORT . "</li>";
echo "<li><strong>Username:</strong> " . SMTP_USERNAME . "</li>";
echo "<li><strong>From Email:</strong> " . FROM_EMAIL . "</li>";
echo "<li><strong>Debug:</strong> " . (EMAIL_DEBUG ? 'Ativo' : 'Desabilitado') . "</li>";
echo "</ul>";

// Verificar se função mail() está disponível
echo "<h3>Verificação do Servidor:</h3>";
if (function_exists('mail')) {
    echo "<p style='color: green;'>✅ Função mail() está disponível</p>";
} else {
    echo "<p style='color: red;'>❌ Função mail() NÃO está disponível</p>";
}

// Verificar logs
echo "<h3>Logs de Email:</h3>";
$log_file = EMAIL_LOG_FILE;
if (file_exists($log_file)) {
    echo "<p style='color: green;'>✅ Arquivo de log existe: $log_file</p>";
    
    // Mostrar últimas 5 linhas do log
    $logs = file($log_file, FILE_IGNORE_NEW_LINES);
    $ultimos_logs = array_slice($logs, -5);
    
    if (!empty($ultimos_logs)) {
        echo "<h4>Últimas 5 entradas do log:</h4>";
        echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;'>";
        foreach ($ultimos_logs as $log) {
            echo htmlspecialchars($log) . "\n";
        }
        echo "</pre>";
    }
} else {
    echo "<p style='color: red;'>❌ Arquivo de log não existe: $log_file</p>";
}

echo "<h2>🔧 Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Verifique sua caixa de entrada em <strong>brayanwosch@gmail.com</strong></li>";
echo "<li>Verifique também a pasta de <strong>spam/lixo eletrônico</strong></li>";
echo "<li>Se não receber em 5-10 minutos, verifique os logs de erro do servidor</li>";
echo "<li>Teste também o formulário de contato no site</li>";
echo "</ol>";

echo "<p><a href='contato.php'>📝 Testar Formulário de Contato</a></p>";
echo "<p><a href='index.php'>🏠 Voltar para Home</a></p>";
?>
