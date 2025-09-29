<?php
/**
 * Teste Final CMD - Sistema de Email BR2Studios
 * Teste completo via terminal
 */

echo "========================================\n";
echo "    TESTE FINAL - SISTEMA DE EMAIL\n";
echo "========================================\n\n";

// Verificar se PHPMailer está disponível
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "❌ PHPMailer não encontrado!\n";
    echo "Execute: php instalar_phpmailer_simples.php\n";
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if (!class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
    echo "❌ PHPMailer não carregado!\n";
    exit;
}

echo "✅ PHPMailer carregado com sucesso!\n\n";

// Incluir configurações
require_once __DIR__ . '/config/email_config.php';

echo "CONFIGURAÇÕES:\n";
echo "Host: mail.br2studios.com.br:587\n";
echo "Usuário: nao.responda@br2studios.com.br\n";
echo "From: " . FROM_EMAIL . "\n";
echo "To: " . TO_EMAIL . "\n";
echo "Data/Hora: " . date('d/m/Y H:i:s') . "\n\n";

echo "TESTE 1: EMAIL DE CONTATO\n";
echo "=========================\n";

// Dados de teste para contato
$dados_contato = [
    'nome' => 'Brayan Wosch',
    'email' => 'brayanwosch@gmail.com',
    'telefone' => '(41) 99999-9999',
    'assunto' => 'Teste Final Sistema',
    'tipo_contato' => 'Teste',
    'mensagem' => 'Este é um teste final do sistema de email para verificar se está funcionando perfeitamente.'
];

// Criar template de contato
$template_contato = criarTemplateEmailContato($dados_contato);
$assunto_contato = "Teste Final - Novo Contato BR2Studios: " . $dados_contato['assunto'];

echo "Enviando email de contato...\n";
echo "Para: " . TO_EMAIL . "\n";
echo "Assunto: $assunto_contato\n";

$resultado_contato = enviarEmail(TO_EMAIL, $assunto_contato, $template_contato, $dados_contato['nome'], $dados_contato['email']);

if ($resultado_contato) {
    echo "✅ EMAIL DE CONTATO ENVIADO COM SUCESSO!\n";
} else {
    echo "❌ FALHA NO EMAIL DE CONTATO\n";
}

echo "\nTESTE 2: EMAIL DE CONFIRMAÇÃO\n";
echo "=============================\n";

// Criar template de confirmação
$template_confirmacao = criarTemplateConfirmacaoCliente($dados_contato);
$assunto_confirmacao = "Contato Recebido - BR2Studios";

echo "Enviando email de confirmação...\n";
echo "Para: " . $dados_contato['email'] . "\n";
echo "Assunto: $assunto_confirmacao\n";

$resultado_confirmacao = enviarEmail($dados_contato['email'], $assunto_confirmacao, $template_confirmacao, $dados_contato['nome'], $dados_contato['email']);

if ($resultado_confirmacao) {
    echo "✅ EMAIL DE CONFIRMAÇÃO ENVIADO COM SUCESSO!\n";
} else {
    echo "❌ FALHA NO EMAIL DE CONFIRMAÇÃO\n";
}

echo "\nTESTE 3: EMAIL DE NEWSLETTER\n";
echo "============================\n";

// Template de newsletter
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

echo "Enviando email de newsletter...\n";
echo "Para: " . $dados_contato['email'] . "\n";
echo "Assunto: $assunto_newsletter\n";

$resultado_newsletter = enviarEmail($dados_contato['email'], $assunto_newsletter, $template_newsletter);

if ($resultado_newsletter) {
    echo "✅ EMAIL DE NEWSLETTER ENVIADO COM SUCESSO!\n";
} else {
    echo "❌ FALHA NO EMAIL DE NEWSLETTER\n";
}

echo "\nTESTE 4: VERIFICAÇÃO DE LOGS\n";
echo "============================\n";

if (file_exists(EMAIL_LOG_FILE)) {
    echo "Log de email encontrado: " . EMAIL_LOG_FILE . "\n";
    $logs = file_get_contents(EMAIL_LOG_FILE);
    $linhas = explode("\n", $logs);
    $ultimas_linhas = array_slice($linhas, -10);
    
    echo "Últimas 10 linhas do log:\n";
    foreach ($ultimas_linhas as $linha) {
        if (!empty(trim($linha))) {
            echo "  " . $linha . "\n";
        }
    }
} else {
    echo "Log de email não encontrado\n";
}

echo "\nTESTE 5: RESUMO DOS RESULTADOS\n";
echo "=============================\n";

$total_testes = 3;
$sucessos = 0;

if ($resultado_contato) $sucessos++;
if ($resultado_confirmacao) $sucessos++;
if ($resultado_newsletter) $sucessos++;

echo "📊 RESULTADOS:\n";
echo "✅ Email de contato: " . ($resultado_contato ? "SUCESSO" : "ERRO") . "\n";
echo "✅ Email de confirmação: " . ($resultado_confirmacao ? "SUCESSO" : "ERRO") . "\n";
echo "✅ Email de newsletter: " . ($resultado_newsletter ? "SUCESSO" : "ERRO") . "\n";
echo "📈 Taxa de sucesso: " . round(($sucessos / $total_testes) * 100, 1) . "%\n";

if ($sucessos == $total_testes) {
    echo "\n🎉 SISTEMA FUNCIONANDO PERFEITAMENTE!\n";
    echo "✅ Todos os emails foram enviados com sucesso\n";
    echo "✅ Sistema pronto para produção\n";
    echo "✅ Formulários de contato funcionando\n";
    echo "✅ Newsletter funcionando\n";
} elseif ($sucessos > 0) {
    echo "\n⚠️ SISTEMA PARCIALMENTE FUNCIONAL\n";
    echo "Alguns emails foram enviados, mas há problemas\n";
    echo "Verifique os logs para mais detalhes\n";
} else {
    echo "\n❌ SISTEMA COM PROBLEMAS\n";
    echo "Nenhum email foi enviado com sucesso\n";
    echo "Verifique a configuração do PHPMailer\n";
}

echo "\n========================================\n";
echo "    TESTE FINAL CONCLUÍDO\n";
echo "    Data/Hora: " . date('d/m/Y H:i:s') . "\n";
echo "========================================\n";

echo "\n📧 VERIFICAÇÕES:\n";
echo "1. Verifique sua caixa de entrada em: brayanwosch@gmail.com\n";
echo "2. Verifique a pasta de spam/lixo eletrônico\n";
echo "3. Verifique o email da empresa em: " . TO_EMAIL . "\n";

if ($sucessos == $total_testes) {
    echo "\n🚀 PRÓXIMOS PASSOS:\n";
    echo "1. ✅ Sistema funcionando - pode usar em produção\n";
    echo "2. ✅ Formulários de contato prontos\n";
    echo "3. ✅ Newsletter funcionando\n";
    echo "4. ✅ Pode remover arquivos de teste\n";
}
?>
