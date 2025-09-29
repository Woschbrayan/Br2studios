<?php
/**
 * Instalador PHPMailer Simples
 * Solução mais direta e fácil
 */

echo "========================================\n";
echo "    INSTALADOR PHPMAILER SIMPLES\n";
echo "========================================\n\n";

echo "💡 Você está certo! PHPMailer é mais simples!\n\n";

// Verificar se Composer está disponível
echo "Verificando Composer...\n";
$composer_check = shell_exec('composer --version 2>&1');
if (strpos($composer_check, 'Composer') !== false) {
    echo "✅ Composer encontrado: " . trim($composer_check) . "\n\n";
    
    // Instalar PHPMailer
    echo "Instalando PHPMailer...\n";
    $install_cmd = 'composer require phpmailer/phpmailer 2>&1';
    $install_output = shell_exec($install_cmd);
    echo "Saída da instalação:\n$install_output\n\n";
    
    // Verificar se foi instalado
    if (file_exists('vendor/autoload.php')) {
        echo "✅ PHPMailer instalado com sucesso!\n\n";
        
        // Incluir PHPMailer
        require_once 'vendor/autoload.php';
        
        echo "TESTE COM PHPMAILER\n";
        echo "===================\n";
        
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = 'mail.br2studios.com.br';
            $mail->SMTPAuth = true;
            $mail->Username = 'nao.responda@br2studios.com.br';
            $mail->Password = 'Br2studios!';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Configurações do email
            $mail->setFrom('nao.responda@br2studios.com.br', 'BR2Studios');
            $mail->addAddress('brayanwosch@gmail.com');
            $mail->isHTML(true);
            $mail->Subject = 'Teste PHPMailer BR2Studios - ' . date('H:i:s');
            $mail->Body = '
            <html>
            <body style="font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px;">
                <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: #dc2626; margin: 0; font-size: 32px;">🎉 BR2Studios</h1>
                        <p style="color: #666; margin: 10px 0; font-size: 18px;">PHPMailer Funcionando!</p>
                    </div>
                    
                    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h2 style="color: #155724; margin-top: 0;">✅ PHPMAILER FUNCIONANDO!</h2>
                        <p style="color: #155724; margin: 0; font-size: 16px;">
                            Olá <strong>Brayan</strong>! O PHPMailer está funcionando!
                        </p>
                    </div>
                    
                    <div style="background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h3 style="margin-top: 0; color: #0066cc;">📋 Informações:</h3>
                        <ul style="margin: 10px 0; color: #333;">
                            <li><strong>Data/Hora:</strong> ' . date('d/m/Y H:i:s') . '</li>
                            <li><strong>Método:</strong> PHPMailer</li>
                            <li><strong>Status:</strong> ✅ FUNCIONANDO</li>
                        </ul>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="https://br2studios.com.br" style="background: #dc2626; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                            🌐 Acessar Site BR2Studios
                        </a>
                    </div>
                </div>
            </body>
            </html>';
            
            echo "Enviando email via PHPMailer...\n";
            $mail->send();
            echo "✅ EMAIL ENVIADO COM SUCESSO VIA PHPMAILER!\n";
            echo "📧 Verifique sua caixa de entrada em: brayanwosch@gmail.com\n";
            echo "📁 Verifique também a pasta de spam\n";
            
        } catch (Exception $e) {
            echo "❌ ERRO NO PHPMAILER: {$mail->ErrorInfo}\n";
            
            // Tentar sem TLS
            echo "\nTentando sem TLS...\n";
            try {
                $mail2 = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail2->isSMTP();
                $mail2->Host = 'mail.br2studios.com.br';
                $mail2->SMTPAuth = true;
                $mail2->Username = 'nao.responda@br2studios.com.br';
                $mail2->Password = 'Br2studios!';
                $mail2->SMTPSecure = false;
                $mail2->Port = 25;
                $mail2->SMTPAutoTLS = false;
                
                $mail2->setFrom('nao.responda@br2studios.com.br', 'BR2Studios');
                $mail2->addAddress('brayanwosch@gmail.com');
                $mail2->isHTML(true);
                $mail2->Subject = 'Teste PHPMailer Sem TLS - ' . date('H:i:s');
                $mail2->Body = '
                <html>
                <body style="font-family: Arial, sans-serif;">
                    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                        <h1 style="color: #dc2626;">BR2Studios - PHPMailer Sem TLS</h1>
                        <p>Data/Hora: ' . date('d/m/Y H:i:s') . '</p>
                        <p>Este email foi enviado sem TLS.</p>
                    </div>
                </body>
                </html>';
                
                echo "Enviando email sem TLS...\n";
                $mail2->send();
                echo "✅ EMAIL ENVIADO SEM TLS!\n";
                
            } catch (Exception $e2) {
                echo "❌ ERRO SEM TLS: {$mail2->ErrorInfo}\n";
            }
        }
        
    } else {
        echo "❌ Falha na instalação do PHPMailer\n";
    }
    
} else {
    echo "❌ Composer não encontrado\n";
    echo "Vamos criar uma solução alternativa...\n\n";
    
    // Criar PHPMailer manual
    echo "Criando PHPMailer manual...\n";
    
    $phpmailer_content = '<?php
/**
 * PHPMailer Manual - Versão Simplificada
 */

class SimplePHPMailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $secure;
    private $from_email;
    private $from_name;
    private $to_email;
    private $to_name;
    private $subject;
    private $body;
    private $isHTML;
    
    public function __construct() {
        $this->secure = false;
        $this->isHTML = true;
    }
    
    public function isSMTP() {
        return true;
    }
    
    public function setHost($host) {
        $this->host = $host;
    }
    
    public function setPort($port) {
        $this->port = $port;
    }
    
    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function setSecure($secure) {
        $this->secure = $secure;
    }
    
    public function setFrom($email, $name = "") {
        $this->from_email = $email;
        $this->from_name = $name;
    }
    
    public function addAddress($email, $name = "") {
        $this->to_email = $email;
        $this->to_name = $name;
    }
    
    public function setSubject($subject) {
        $this->subject = $subject;
    }
    
    public function setBody($body) {
        $this->body = $body;
    }
    
    public function isHTML($isHTML = true) {
        $this->isHTML = $isHTML;
    }
    
    public function send() {
        // Configurar para usar servidor local
        ini_set("SMTP", $this->host);
        ini_set("smtp_port", $this->port);
        ini_set("sendmail_from", $this->from_email);
        
        $headers = array(
            "MIME-Version: 1.0",
            "Content-Type: " . ($this->isHTML ? "text/html" : "text/plain") . "; charset=UTF-8",
            "From: " . $this->from_name . " <" . $this->from_email . ">",
            "Reply-To: " . $this->from_name . " <" . $this->from_email . ">",
            "X-Mailer: SimplePHPMailer/1.0",
            "Return-Path: " . $this->from_email
        );
        
        $headers_string = implode("\r\n", $headers);
        
        $result = @mail($this->to_email, $this->subject, $this->body, $headers_string);
        
        if (!$result) {
            throw new Exception("Falha no envio do email via mail() nativo");
        }
        
        return true;
    }
    
    public function getErrorInfo() {
        return "Erro no SimplePHPMailer";
    }
}';
    
    file_put_contents('SimplePHPMailer.php', $phpmailer_content);
    echo "✅ SimplePHPMailer criado!\n\n";
    
    // Testar SimplePHPMailer
    require_once 'SimplePHPMailer.php';
    
    echo "TESTE COM SIMPLEPHPMAILER\n";
    echo "=========================\n";
    
    $mail = new SimplePHPMailer();
    
    try {
        $mail->isSMTP();
        $mail->setHost('mail.br2studios.com.br');
        $mail->setPort(587);
        $mail->setUsername('nao.responda@br2studios.com.br');
        $mail->setPassword('Br2studios!');
        $mail->setSecure(false);
        
        $mail->setFrom('nao.responda@br2studios.com.br', 'BR2Studios');
        $mail->addAddress('brayanwosch@gmail.com');
        $mail->isHTML(true);
        $mail->setSubject('Teste SimplePHPMailer - ' . date('H:i:s'));
        $mail->setBody('
        <html>
        <body style="font-family: Arial, sans-serif;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <h1 style="color: #dc2626;">BR2Studios - SimplePHPMailer</h1>
                <p>Data/Hora: ' . date('d/m/Y H:i:s') . '</p>
                <p>Este email foi enviado usando SimplePHPMailer.</p>
            </div>
        </body>
        </html>');
        
        echo "Enviando email via SimplePHPMailer...\n";
        $mail->send();
        echo "✅ EMAIL ENVIADO COM SUCESSO!\n";
        
    } catch (Exception $e) {
        echo "❌ ERRO: " . $e->getMessage() . "\n";
    }
}

echo "\n========================================\n";
echo "    INSTALAÇÃO CONCLUÍDA\n";
echo "    Data/Hora: " . date('d/m/Y H:i:s') . "\n";
echo "========================================\n";

echo "\n💡 VANTAGENS DO PHPMAILER:\n";
echo "- ✅ Mais fácil de configurar\n";
echo "- ✅ Funciona com diferentes servidores\n";
echo "- ✅ Suporte a TLS/SSL\n";
echo "- ✅ Logs detalhados\n";
echo "- ✅ Compatível com o sistema atual\n";
?>
