<?php
/**
 * Configurações de Email - BR2Studios
 * Sistema de envio de emails usando SMTP
 */

// Configurações SMTP - SERVIDOR REAL (cPanel)
define('SMTP_HOST', 'plenaplaygrounds.com.br'); // Seu servidor
define('SMTP_PORT', 587); // Porta SMTP com TLS
define('SMTP_USERNAME', 'contato@br2imoveis.com.br'); // Email do domínio
define('SMTP_PASSWORD', 'Br2@empreendimentos2025'); // Senha do email no cPanel
define('SMTP_ENCRYPTION', 'tls');

// Configurações do remetente - EMAIL OFICIAL
define('FROM_EMAIL', 'contato@br2imoveis.com.br'); // Email oficial
define('FROM_NAME', 'BR2Studios - Contato');

// Email de destino (onde receber os contatos) - EMAIL OFICIAL
define('TO_EMAIL', 'contato@br2imoveis.com.br'); // Email oficial
define('TO_NAME', 'BR2Studios');

// Configurações adicionais
define('REPLY_TO_EMAIL', 'contato@br2imoveis.com.br');
define('REPLY_TO_NAME', 'BR2Studios');

// Configurações de debug
define('EMAIL_DEBUG', true); // Mude para true para debug
define('EMAIL_LOG_FILE', __DIR__ . '/../logs/email.log');

/**
 * Função para enviar email (usa SMTP real - funciona em localhost)
 */
function enviarEmail($para, $assunto, $mensagem, $nome_remetente = '', $email_remetente = '') {
    // Usar mail() nativo - mais compatível com servidores de hospedagem
    return enviarEmailNativo($para, $assunto, $mensagem, $nome_remetente, $email_remetente);
}

/**
 * Função para enviar email usando SMTP real (funciona em localhost)
 */
function enviarEmailSMTP($para, $assunto, $mensagem, $nome_remetente = '', $email_remetente = '') {
    // Configurações SMTP
    $smtp_host = SMTP_HOST;
    $smtp_port = SMTP_PORT;
    $smtp_user = SMTP_USERNAME;
    $smtp_pass = SMTP_PASSWORD;
    
    // Debug
    if (EMAIL_DEBUG) {
        echo "<h3>SMTP Debug:</h3>";
        echo "<p><strong>Host:</strong> $smtp_host:$smtp_port</p>";
        echo "<p><strong>User:</strong> $smtp_user</p>";
        echo "<p><strong>Para:</strong> $para</p>";
        echo "<p><strong>Assunto:</strong> $assunto</p>";
    }
    
    // Conectar ao servidor SMTP
    $socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);
    if (!$socket) {
        if (EMAIL_DEBUG) {
            echo "<p style='color: red;'>❌ Erro de conexão: $errstr ($errno)</p>";
        }
        return false;
    }
    
    // Ler resposta inicial
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>Resposta inicial:</strong> " . trim($response) . "</p>";
    }
    
    // EHLO
    fputs($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>EHLO:</strong> " . trim($response) . "</p>";
    }
    
    // Verificar se deve usar TLS (porta 587) ou não (porta 25)
    if ($smtp_port == 587) {
        // STARTTLS para porta 587
        fputs($socket, "STARTTLS\r\n");
        $response = fgets($socket, 512);
        if (EMAIL_DEBUG) {
            echo "<p><strong>STARTTLS:</strong> " . trim($response) . "</p>";
        }
        
        // Iniciar TLS
        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            if (EMAIL_DEBUG) {
                echo "<p style='color: red;'>❌ Erro ao iniciar TLS</p>";
            }
            fclose($socket);
            return false;
        }
        
        // EHLO novamente após TLS
        fputs($socket, "EHLO localhost\r\n");
        $response = fgets($socket, 512);
        if (EMAIL_DEBUG) {
            echo "<p><strong>EHLO TLS:</strong> " . trim($response) . "</p>";
        }
    } else {
        // Porta 25 - sem TLS
        if (EMAIL_DEBUG) {
            echo "<p><strong>Usando porta 25 - sem TLS</strong></p>";
        }
    }
    
    // AUTH LOGIN
    fputs($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>AUTH LOGIN:</strong> " . trim($response) . "</p>";
    }
    
    // Enviar username
    fputs($socket, base64_encode($smtp_user) . "\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>Username:</strong> " . trim($response) . "</p>";
    }
    
    // Enviar password
    fputs($socket, base64_encode($smtp_pass) . "\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>Password:</strong> " . trim($response) . "</p>";
    }
    
    // MAIL FROM
    fputs($socket, "MAIL FROM: <" . FROM_EMAIL . ">\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>MAIL FROM:</strong> " . trim($response) . "</p>";
    }
    
    // RCPT TO
    fputs($socket, "RCPT TO: <$para>\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>RCPT TO:</strong> " . trim($response) . "</p>";
    }
    
    // DATA
    fputs($socket, "DATA\r\n");
    $response = fgets($socket, 512);
    if (EMAIL_DEBUG) {
        echo "<p><strong>DATA:</strong> " . trim($response) . "</p>";
    }
    
    // Headers e conteúdo
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
    if (EMAIL_DEBUG) {
        echo "<p><strong>Email enviado:</strong> " . trim($response) . "</p>";
    }
    
    // QUIT
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    
    // Verificar se foi sucesso
    $sucesso = strpos($response, '250') === 0;
    
    if (EMAIL_DEBUG) {
        echo "<p><strong>Resultado:</strong> " . ($sucesso ? 'SUCESSO' : 'ERRO') . "</p>";
    }
    
    // Log
    if (EMAIL_LOG_FILE) {
        $log_message = date('Y-m-d H:i:s') . " - SMTP enviado para: $para - Assunto: $assunto - Status: " . ($sucesso ? 'SUCESSO' : 'ERRO') . "\n";
        file_put_contents(EMAIL_LOG_FILE, $log_message, FILE_APPEND | LOCK_EX);
    }
    
    return $sucesso;
}

/**
 * Função para enviar email usando mail() nativo do PHP
 */
function enviarEmailNativo($para, $assunto, $mensagem, $nome_remetente = '', $email_remetente = '') {
    // Verificar se mail() está disponível
    if (!function_exists('mail')) {
        if (EMAIL_DEBUG) {
            echo "<p style='color: red;'>❌ Função mail() não está disponível no servidor</p>";
        }
        return false;
    }
    
    // Configurar parâmetros do servidor (para servidores de hospedagem)
    ini_set('SMTP', SMTP_HOST);
    ini_set('smtp_port', SMTP_PORT);
    ini_set('sendmail_from', FROM_EMAIL);
    
    // Headers do email
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>',
        'Reply-To: ' . REPLY_TO_NAME . ' <' . REPLY_TO_EMAIL . '>',
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 3',
        'Return-Path: ' . FROM_EMAIL
    );
    
    // Se houver remetente específico, adicionar como reply-to
    if ($email_remetente && $nome_remetente) {
        $headers[] = 'Reply-To: ' . $nome_remetente . ' <' . $email_remetente . '>';
    }
    
    $headers_string = implode("\r\n", $headers);
    
    // Debug: mostrar informações
    if (EMAIL_DEBUG) {
        echo "<h3>Debug Email (mail() nativo):</h3>";
        echo "<p><strong>Para:</strong> $para</p>";
        echo "<p><strong>Assunto:</strong> $assunto</p>";
        echo "<p><strong>From:</strong> " . FROM_EMAIL . "</p>";
        echo "<p><strong>SMTP Host:</strong> " . SMTP_HOST . "</p>";
        echo "<p><strong>SMTP Port:</strong> " . SMTP_PORT . "</p>";
        echo "<p><strong>Headers:</strong></p>";
        echo "<pre>" . htmlspecialchars($headers_string) . "</pre>";
    }
    
    // Enviar email
    $resultado = mail($para, $assunto, $mensagem, $headers_string);
    
    // Log do envio
    if (EMAIL_LOG_FILE) {
        $log_message = date('Y-m-d H:i:s') . " - Email nativo enviado para: $para - Assunto: $assunto - Status: " . ($resultado ? 'SUCESSO' : 'ERRO') . "\n";
        file_put_contents(EMAIL_LOG_FILE, $log_message, FILE_APPEND | LOCK_EX);
    }
    
    // Debug: mostrar resultado
    if (EMAIL_DEBUG) {
        echo "<p><strong>Resultado:</strong> " . ($resultado ? 'SUCESSO' : 'ERRO') . "</p>";
        if (!$resultado) {
            echo "<p style='color: red;'><strong>Erro:</strong> mail() retornou false.</p>";
            echo "<p style='color: orange;'><strong>Possíveis causas:</strong></p>";
            echo "<ul>";
            echo "<li>Servidor não configurado para envio de emails</li>";
            echo "<li>Email 'contato@br2imoveis.com.br' não existe no cPanel</li>";
            echo "<li>Configurações SMTP incorretas</li>";
            echo "<li>Firewall bloqueando envio</li>";
            echo "</ul>";
        }
    }
    
    return $resultado;
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
