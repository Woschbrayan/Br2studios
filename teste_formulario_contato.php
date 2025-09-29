<?php
/**
 * Teste do Formulário de Contato
 * Testando o sistema completo com PHPMailer
 */

// Incluir configurações
require_once 'config/email_config.php';

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Teste Formulário de Contato - BR2Studios</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo ".form-group { margin-bottom: 15px; }";
echo ".form-group label { display: block; margin-bottom: 5px; font-weight: bold; }";
echo ".form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }";
echo ".btn { background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }";
echo ".btn:hover { background: #b91c1c; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>📧 Teste do Formulário de Contato - BR2Studios</h1>";

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
echo "Sistema pronto para envio de emails";
echo "</div>";

// Processar formulário se foi enviado
if ($_POST) {
    echo "<h2>📨 Processando Formulário...</h2>";
    
    // Validar dados
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $tipo_contato = trim($_POST['tipo_contato'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    $erros = [];
    
    if (empty($nome)) $erros[] = "Nome é obrigatório";
    if (empty($email)) $erros[] = "Email é obrigatório";
    if (empty($mensagem)) $erros[] = "Mensagem é obrigatória";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Email inválido";
    
    if (empty($erros)) {
        // Criar dados para o template
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'assunto' => $assunto,
            'tipo_contato' => $tipo_contato,
            'mensagem' => $mensagem
        ];
        
        // Criar templates
        $template_contato = criarTemplateEmailContato($dados);
        $template_confirmacao = criarTemplateConfirmacaoCliente($dados);
        
        // Enviar email para a empresa
        $assunto_empresa = "Novo Contato - BR2Studios: " . $assunto;
        $resultado_empresa = enviarEmail(TO_EMAIL, $assunto_empresa, $template_contato, $nome, $email);
        
        // Enviar email de confirmação para o cliente
        $assunto_cliente = "Contato Recebido - BR2Studios";
        $resultado_cliente = enviarEmail($email, $assunto_cliente, $template_confirmacao, $nome, $email);
        
        if ($resultado_empresa && $resultado_cliente) {
            echo "<div class='success'>";
            echo "<h3>🎉 <strong>SUCESSO!</strong></h3>";
            echo "<p>✅ Email enviado para a empresa: " . TO_EMAIL . "</p>";
            echo "<p>✅ Email de confirmação enviado para: " . $email . "</p>";
            echo "<p>📧 Verifique sua caixa de entrada!</p>";
            echo "</div>";
        } elseif ($resultado_empresa) {
            echo "<div class='success'>";
            echo "<h3>⚠️ <strong>PARCIALMENTE SUCESSO!</strong></h3>";
            echo "<p>✅ Email enviado para a empresa: " . TO_EMAIL . "</p>";
            echo "<p>❌ Falha no envio de confirmação para: " . $email . "</p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h3>❌ <strong>ERRO!</strong></h3>";
            echo "<p>Falha no envio dos emails</p>";
            echo "<p>Verifique os logs em: " . EMAIL_LOG_FILE . "</p>";
            echo "</div>";
        }
        
    } else {
        echo "<div class='error'>";
        echo "<h3>❌ <strong>ERROS ENCONTRADOS:</strong></h3>";
        echo "<ul>";
        foreach ($erros as $erro) {
            echo "<li>" . htmlspecialchars($erro) . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

echo "<h2>📝 Formulário de Contato</h2>";
echo "<form method='POST'>";
echo "<div class='form-group'>";
echo "<label for='nome'>Nome *</label>";
echo "<input type='text' id='nome' name='nome' value='" . htmlspecialchars($_POST['nome'] ?? '') . "' required>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='email'>Email *</label>";
echo "<input type='email' id='email' name='email' value='" . htmlspecialchars($_POST['email'] ?? '') . "' required>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='telefone'>Telefone</label>";
echo "<input type='text' id='telefone' name='telefone' value='" . htmlspecialchars($_POST['telefone'] ?? '') . "'>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='tipo_contato'>Tipo de Contato</label>";
echo "<select id='tipo_contato' name='tipo_contato'>";
echo "<option value='' " . (($_POST['tipo_contato'] ?? '') == '' ? 'selected' : '') . ">Selecione...</option>";
echo "<option value='Interesse em Imóvel' " . (($_POST['tipo_contato'] ?? '') == 'Interesse em Imóvel' ? 'selected' : '') . ">Interesse em Imóvel</option>";
echo "<option value='Investimento' " . (($_POST['tipo_contato'] ?? '') == 'Investimento' ? 'selected' : '') . ">Investimento</option>";
echo "<option value='Dúvidas' " . (($_POST['tipo_contato'] ?? '') == 'Dúvidas' ? 'selected' : '') . ">Dúvidas</option>";
echo "<option value='Outros' " . (($_POST['tipo_contato'] ?? '') == 'Outros' ? 'selected' : '') . ">Outros</option>";
echo "</select>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='assunto'>Assunto</label>";
echo "<input type='text' id='assunto' name='assunto' value='" . htmlspecialchars($_POST['assunto'] ?? '') . "'>";
echo "</div>";

echo "<div class='form-group'>";
echo "<label for='mensagem'>Mensagem *</label>";
echo "<textarea id='mensagem' name='mensagem' rows='5' required>" . htmlspecialchars($_POST['mensagem'] ?? '') . "</textarea>";
echo "</div>";

echo "<button type='submit' class='btn'>📧 Enviar Contato</button>";
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
echo "<p><em>Teste do formulário executado em: " . date('d/m/Y H:i:s') . "</em></p>";
echo "</div>";
echo "</body>";
echo "</html>";
?>
