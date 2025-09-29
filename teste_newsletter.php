<?php
/**
 * Teste do Sistema de Newsletter
 * Testando newsletter com PHPMailer
 */

// Incluir configurações
require_once 'config/email_config.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Teste Newsletter - BR2Studios</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".form-group { margin-bottom: 15px; }";
echo ".form-group label { display: block; margin-bottom: 5px; font-weight: bold; }";
echo ".form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }";
echo ".btn { background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }";
echo ".btn:hover { background: #b91c1c; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>📧 Teste do Sistema de Newsletter - BR2Studios</h1>";

// Verificar se PHPMailer está disponível
if (!class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
    echo "<div class='error'>";
    echo "❌ <strong>PHPMailer não está disponível!</strong><br>";
    echo "Execute: php instalar_phpmailer_simples.php";
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='info'>";
echo "✅ <strong>PHPMailer disponível!</strong><br>";
echo "Sistema de newsletter pronto para funcionar";
echo "</div>";

// Processar formulário se foi enviado
if ($_POST) {
    echo "<h2>📨 Testando Newsletter...</h2>";
    
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        echo "<div class='error'>❌ Email é obrigatório</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error'>❌ Email inválido</div>";
    } else {
        // Criar template de newsletter
        $template_newsletter = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Newsletter BR2Studios</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>BR2Studios</h1>
                    <p>Newsletter - Investimentos Imobiliários</p>
                </div>
                
                <div class='content'>
                    <h2>Bem-vindo à nossa Newsletter!</h2>
                    
                    <p>Obrigado por se inscrever na newsletter da BR2Studios!</p>
                    
                    <p>Agora você receberá:</p>
                    <ul>
                        <li>📈 <strong>Oportunidades exclusivas</strong> de investimento</li>
                        <li>🏠 <strong>Novos imóveis</strong> em primeira mão</li>
                        <li>📊 <strong>Análises de mercado</strong> e tendências</li>
                        <li>💰 <strong>Dicas de investimento</strong> especializadas</li>
                    </ul>
                    
                    <p>Nossa equipe de especialistas trabalha constantemente para trazer as melhores oportunidades do mercado imobiliário de Curitiba.</p>
                    
                    <p>Atenciosamente,<br>
                    <strong>Equipe BR2Studios</strong></p>
                </div>
                
                <div class='footer'>
                    <p>BR2Studios - Especialistas em Studios e Investimentos Imobiliários</p>
                    <p>WhatsApp: (41) 4141-0093 | Email: contato@br2imoveis.com.br</p>
                </div>
            </div>
        </body>
        </html>";
        
        $assunto_newsletter = "Bem-vindo à Newsletter BR2Studios!";
        
        // Enviar email de newsletter
        $resultado = enviarEmail($email, $assunto_newsletter, $template_newsletter);
        
        if ($resultado) {
            echo "<div class='success'>";
            echo "<h3>🎉 <strong>SUCESSO!</strong></h3>";
            echo "<p>✅ Email de newsletter enviado para: " . $email . "</p>";
            echo "<p>📧 Verifique sua caixa de entrada!</p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h3>❌ <strong>ERRO!</strong></h3>";
            echo "<p>Falha no envio do email de newsletter</p>";
            echo "<p>Verifique os logs em: " . EMAIL_LOG_FILE . "</p>";
            echo "</div>";
        }
    }
}

echo "<h2>📝 Teste de Newsletter</h2>";
echo "<form method='POST'>";
echo "<div class='form-group'>";
echo "<label for='email'>Email para teste</label>";
echo "<input type='email' id='email' name='email' value='" . htmlspecialchars($_POST['email'] ?? 'brayanwosch@gmail.com') . "' required>";
echo "</div>";
echo "<button type='submit' class='btn'>📧 Testar Newsletter</button>";
echo "</form>";

echo "<h2>📊 Status do Sistema</h2>";
echo "<ul>";
echo "<li><strong>PHPMailer:</strong> " . (class_exists('\PHPMailer\PHPMailer\PHPMailer') ? '✅ Disponível' : '❌ Indisponível') . "</li>";
echo "<li><strong>Email de destino:</strong> " . TO_EMAIL . "</li>";
echo "<li><strong>Email remetente:</strong> " . FROM_EMAIL . "</li>";
echo "<li><strong>Log de emails:</strong> " . EMAIL_LOG_FILE . "</li>";
echo "</ul>";

echo "<h2>📋 Logs Recentes</h2>";
if (file_exists(EMAIL_LOG_FILE)) {
    $logs = file_get_contents(EMAIL_LOG_FILE);
    $linhas = explode("\n", $logs);
    $ultimas_linhas = array_slice($linhas, -10);
    
    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6; font-family: monospace; font-size: 12px;'>";
    foreach ($ultimas_linhas as $linha) {
        if (!empty(trim($linha))) {
            echo htmlspecialchars($linha) . "\n";
        }
    }
    echo "</pre>";
} else {
    echo "<p>Nenhum log encontrado.</p>";
}

echo "<hr>";
echo "<p><em>Teste de newsletter executado em: " . date('d/m/Y H:i:s') . "</em></p>";
echo "</div>";
echo "</body>";
echo "</html>";
?>
