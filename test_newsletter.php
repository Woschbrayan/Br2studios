<?php
/**
 * Teste da Newsletter - BR2Studios
 */

echo "<h1>📧 Teste da Newsletter</h1>";

// Incluir configurações
require_once __DIR__ . '/config/email_config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';

echo "<h2>1. Verificando Banco de Dados</h2>";

try {
    $db = new Database();
    echo "<p style='color: green;'>✅ Conexão com banco estabelecida</p>";
    
    // Verificar se tabela newsletter existe
    $tabela_existe = $db->fetchOne("SHOW TABLES LIKE 'newsletter'");
    
    if ($tabela_existe) {
        echo "<p style='color: green;'>✅ Tabela 'newsletter' existe</p>";
        
        // Contar registros
        $total = $db->fetchOne("SELECT COUNT(*) as total FROM newsletter");
        echo "<p><strong>Total de inscrições:</strong> " . $total['total'] . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Tabela 'newsletter' não existe</p>";
        echo "<p>Criando tabela...</p>";
        
        // Criar tabela newsletter
        $sql = "CREATE TABLE IF NOT EXISTS newsletter (
            id int NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            status enum('ativo','inativo') DEFAULT 'ativo',
            data_inscricao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->getConnection()->exec($sql);
        echo "<p style='color: green;'>✅ Tabela 'newsletter' criada</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro no banco: " . $e->getMessage() . "</p>";
}

echo "<h2>2. Teste de Inscrição</h2>";

// Simular POST
$_POST['email'] = 'teste@newsletter.com';

if ($_POST && isset($_POST['email'])) {
    $email = $_POST['email'] ?? '';
    
    echo "<p><strong>Email de teste:</strong> $email</p>";
    
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: green;'>✅ Email válido</p>";
        
        try {
            $db = new Database();
            
            // Verificar se já existe
            $existe = $db->fetchOne("SELECT id FROM newsletter WHERE email = ?", [$email]);
            
            if ($existe) {
                echo "<p style='color: orange;'>⚠️ Email já cadastrado</p>";
                $response = ['success' => false, 'message' => 'Este email já está cadastrado na newsletter.'];
            } else {
                echo "<p style='color: blue;'>📝 Inserindo novo email...</p>";
                
                // Inserir novo email
                $id = $db->insert("INSERT INTO newsletter (email, status, data_inscricao) VALUES (?, 'ativo', NOW())", [$email]);
                
                if ($id) {
                    echo "<p style='color: green;'>✅ Email inserido com ID: $id</p>";
                    
                    // Testar envio de email
                    echo "<p style='color: blue;'>📧 Testando envio de email...</p>";
                    
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
                    $email_enviado = enviarEmail($email, $assunto_newsletter, $template_newsletter);
                    
                    if ($email_enviado) {
                        echo "<p style='color: green;'>✅ Email de confirmação enviado</p>";
                        $response = ['success' => true, 'message' => 'Inscrição realizada com sucesso! Verifique seu email.'];
                    } else {
                        echo "<p style='color: red;'>❌ Erro ao enviar email de confirmação</p>";
                        $response = ['success' => false, 'message' => 'Inscrição salva, mas erro ao enviar confirmação.'];
                    }
                    
                } else {
                    echo "<p style='color: red;'>❌ Erro ao inserir email</p>";
                    $response = ['success' => false, 'message' => 'Erro ao processar inscrição. Tente novamente.'];
                }
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
            $response = ['success' => false, 'message' => 'Erro interno. Tente novamente.'];
        }
    } else {
        echo "<p style='color: red;'>❌ Email inválido</p>";
        $response = ['success' => false, 'message' => 'Email inválido.'];
    }
    
    echo "<h2>3. Resposta Final</h2>";
    echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
    
} else {
    echo "<p style='color: red;'>❌ Nenhum email enviado</p>";
}

echo "<h2>4. Teste Manual</h2>";
echo "<form method='POST'>";
echo "<input type='email' name='email' placeholder='Seu email' required>";
echo "<button type='submit'>Testar Newsletter</button>";
echo "</form>";

echo "<p><a href='index.php'>← Voltar para home</a></p>";
?>
