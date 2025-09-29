<?php
/**
 * Configuração de Email - BR2Studios
 * Sistema de envio de emails usando PHPMailer
 */

// Verificar se PHPMailer está disponível
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Configurações do remetente
define('FROM_EMAIL', 'nao.responda@br2studios.com.br');
define('FROM_NAME', 'BR2Studios - Contato');
define('TO_EMAIL', 'contato@br2imoveis.com.br');
define('TO_NAME', 'BR2Studios');
define('REPLY_TO_EMAIL', 'nao.responda@br2studios.com.br');
define('REPLY_TO_NAME', 'BR2Studios');

// Configurações de debug
define('EMAIL_DEBUG', false);
define('EMAIL_LOG_FILE', __DIR__ . '/../logs/email.log');

/**
 * Função para enviar email usando PHPMailer
 */
function enviarEmail($para, $assunto, $mensagem, $nome_remetente = "", $email_remetente = "") {
    // Verificar se PHPMailer está disponível
    if (!class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
        if (EMAIL_DEBUG) {
            echo "<p style='color: red;'>❌ PHPMailer não está disponível</p>";
        }
        return false;
    }
    
    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host = 'mail.br2studios.com.br';
        $mail->SMTPAuth = true;
        $mail->Username = 'nao.responda@br2studios.com.br';
        $mail->Password = 'Br2studios!';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Configurações do email
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($para);
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;
        
        // Se houver remetente específico, adicionar como reply-to
        if ($email_remetente && $nome_remetente) {
            $mail->addReplyTo($email_remetente, $nome_remetente);
        }
        
        // Debug
        if (EMAIL_DEBUG) {
            echo "<h3>Debug Email (PHPMailer):</h3>";
            echo "<p><strong>Para:</strong> $para</p>";
            echo "<p><strong>Assunto:</strong> $assunto</p>";
            echo "<p><strong>From:</strong> " . FROM_EMAIL . "</p>";
            echo "<p><strong>Host:</strong> mail.br2studios.com.br:587</p>";
        }
        
        // Enviar email
        $resultado = $mail->send();
        
        // Log do envio
        if (EMAIL_LOG_FILE) {
            $log_message = date('Y-m-d H:i:s') . " - PHPMailer enviado para: $para - Assunto: $assunto - Status: SUCESSO\n";
            file_put_contents(EMAIL_LOG_FILE, $log_message, FILE_APPEND | LOCK_EX);
        }
        
        // Debug: mostrar resultado
        if (EMAIL_DEBUG) {
            echo "<p style='color: green;'><strong>✅ SUCESSO!</strong> Email enviado via PHPMailer</p>";
        }
        
        return $resultado;
        
    } catch (Exception $e) {
        // Log do erro
        if (EMAIL_LOG_FILE) {
            $log_message = date('Y-m-d H:i:s') . " - PHPMailer ERRO para: $para - Assunto: $assunto - Erro: " . $e->getMessage() . "\n";
            file_put_contents(EMAIL_LOG_FILE, $log_message, FILE_APPEND | LOCK_EX);
        }
        
        // Debug: mostrar erro
        if (EMAIL_DEBUG) {
            echo "<p style='color: red;'><strong>❌ ERRO:</strong> " . $e->getMessage() . "</p>";
        }
        
        return false;
    }
}

/**
 * Função para criar template de email de contato
 */
function criarTemplateEmailContato($dados) {
    $nome = htmlspecialchars($dados['nome']);
    $email = htmlspecialchars($dados['email']);
    $telefone = htmlspecialchars($dados['telefone'] ?? 'Não informado');
    $assunto = htmlspecialchars($dados['assunto'] ?? 'Sem assunto');
    $tipo_contato = htmlspecialchars($dados['tipo_contato'] ?? 'Não especificado');
    $mensagem = nl2br(htmlspecialchars($dados['mensagem']));
    
    $template = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Novo Contato - BR2Studios</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #dc2626; }
            .value { margin-top: 5px; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Novo Contato - BR2Studios</h1>
                <p>Formulário de contato do site</p>
            </div>
            
            <div class='content'>
                <div class='field'>
                    <div class='label'>Nome:</div>
                    <div class='value'>{$nome}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>{$email}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Telefone:</div>
                    <div class='value'>{$telefone}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Tipo de Contato:</div>
                    <div class='value'>{$tipo_contato}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Assunto:</div>
                    <div class='value'>{$assunto}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Mensagem:</div>
                    <div class='value'>{$mensagem}</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Data/Hora:</div>
                    <div class='value'>" . date('d/m/Y H:i:s') . "</div>
                </div>
            </div>
            
            <div class='footer'>
                <p>Este email foi enviado automaticamente pelo sistema BR2Studios</p>
                <p>Para responder, clique em 'Responder' ou envie para: {$email}</p>
            </div>
        </div>
    </body>
    </html>";
    
    return $template;
}

/**
 * Função para criar template de confirmação para o cliente
 */
function criarTemplateConfirmacaoCliente($dados) {
    $nome = htmlspecialchars($dados['nome']);
    
    $template = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Contato Recebido - BR2Studios</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            .btn { display: inline-block; background: #dc2626; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>BR2Studios</h1>
                <p>Especialistas em Investimentos Imobiliários</p>
            </div>
            
            <div class='content'>
                <h2>Olá, {$nome}!</h2>
                
                <p>Recebemos sua mensagem e agradecemos pelo contato!</p>
                
                <p>Nossa equipe de especialistas em investimentos imobiliários analisará sua solicitação e retornará em até <strong>2 horas</strong> durante o horário comercial.</p>
                
                <p>Enquanto isso, você pode:</p>
                <ul>
                    <li>Conhecer nossos <a href='https://br2studios.com.br/imoveis.php'>imóveis disponíveis</a></li>
                    <li>Falar conosco via <a href='https://wa.me/554141410093'>WhatsApp</a></li>
                    <li>Conhecer nossas <a href='https://br2studios.com.br/regioes.php'>regiões de atuação</a></li>
                </ul>
                
                <p>Se você tem alguma urgência, não hesite em nos contatar pelo WhatsApp: <strong>(41) 4141-0093</strong></p>
                
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
    
    return $template;
}
?>