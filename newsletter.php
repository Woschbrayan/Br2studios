<?php
/**
 * Processamento da Newsletter - BR2Studios
 */

// Configurar headers para JSON
header('Content-Type: application/json');

// Incluir configurações de email
require_once __DIR__ . '/config/email_config.php';

// Processar inscrição na newsletter
if ($_POST && isset($_POST['email'])) {
    $email = $_POST['email'] ?? '';
    
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Salvar no banco de dados
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/classes/Database.php';
            
            $db = new Database();
            
            // Verificar se tabela newsletter existe, se não, criar
            $tabela_existe = $db->fetchOne("SHOW TABLES LIKE 'newsletter'");
            if (!$tabela_existe) {
                $sql = "CREATE TABLE IF NOT EXISTS newsletter (
                    id int NOT NULL AUTO_INCREMENT,
                    email varchar(100) NOT NULL,
                    status enum('ativo','inativo') DEFAULT 'ativo',
                    data_inscricao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    UNIQUE KEY email (email)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                $db->getConnection()->exec($sql);
            }
            
            // Verificar se já existe
            $existe = $db->fetchOne("SELECT id FROM newsletter WHERE email = ?", [$email]);
            
            if ($existe) {
                $response = ['success' => false, 'message' => 'Este email já está cadastrado na newsletter.'];
            } else {
                // Inserir novo email
                $id = $db->insert("INSERT INTO newsletter (email, status, data_inscricao) VALUES (?, 'ativo', NOW())", [$email]);
                
                if ($id) {
                    // Enviar email de confirmação
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
                                <p>WhatsApp: (41) 8804-9999 | Email: contato@br2imoveis.com.br</p>
                            </div>
                        </div>
                    </body>
                    </html>";
                    
                    $assunto_newsletter = "Bem-vindo à Newsletter BR2Studios!";
                    
                    // Tentar enviar email de confirmação (sem debug)
                    try {
                        $email_enviado = enviarEmail($email, $assunto_newsletter, $template_newsletter);
                    } catch (Exception $emailError) {
                        // Se der erro no email, continuar mesmo assim
                        $email_enviado = false;
                        error_log("Erro ao enviar email newsletter: " . $emailError->getMessage());
                    }
                    
                    $response = ['success' => true, 'message' => 'Inscrição realizada com sucesso! Verifique seu email.'];
                } else {
                    $response = ['success' => false, 'message' => 'Erro ao processar inscrição. Tente novamente.'];
                }
            }
            
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Erro interno. Tente novamente.'];
            error_log("Erro newsletter: " . $e->getMessage());
        }
    } else {
        $response = ['success' => false, 'message' => 'Email inválido.'];
    }
    
    // Retornar resposta JSON
    echo json_encode($response);
    exit;
}

// Se não for POST, retornar erro
echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
?>
