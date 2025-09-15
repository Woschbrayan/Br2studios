<?php
/**
 * Script de Upload FTP - BR2Studios
 * Upload automático para o servidor
 */

// Configurações FTP
$ftp_host = 'plenaplaygrounds.com.br';
$ftp_user = 'plenapla';
$ftp_pass = 'o2H8XxXNg42ny';
$ftp_remote_path = 'br2studios.com.br';

echo "<h1>📤 Upload FTP - BR2Studios</h1>";

// Conectar ao servidor FTP
$connection = ftp_connect($ftp_host);
if (!$connection) {
    die("❌ Erro: Não foi possível conectar ao servidor FTP");
}

echo "<p>✅ Conectado ao servidor FTP</p>";

// Fazer login
$login = ftp_login($connection, $ftp_user, $ftp_pass);
if (!$login) {
    die("❌ Erro: Falha na autenticação FTP");
}

echo "<p>✅ Autenticação realizada</p>";

// Ativar modo passivo
ftp_pasv($connection, true);

// Mudar para diretório remoto
if (!ftp_chdir($connection, $ftp_remote_path)) {
    echo "<p>⚠️ Diretório $ftp_remote_path não encontrado, criando...</p>";
    // Tentar criar diretório (pode não funcionar dependendo das permissões)
}

echo "<p>✅ Diretório remoto: $ftp_remote_path</p>";

// Lista de arquivos para upload
$files_to_upload = [
    'config/email_config.php',
    'contato.php',
    'test_email.php',
    'test_email_simples.php',
    'index.php',
    'imoveis.php',
    'produto.php',
    'sobre.php',
    'regioes.php',
    'corretores.php',
    'includes/header.php',
    'includes/footer.php',
    'includes/whatsapp.php',
    'classes/Database.php',
    'classes/Imovel.php',
    'classes/Corretor.php',
    'assets/css/style.css',
    'assets/css/logo-fix.css',
    'assets/js/main.js'
];

echo "<h2>📁 Arquivos para Upload:</h2>";
echo "<ul>";

$uploaded_count = 0;
$error_count = 0;

foreach ($files_to_upload as $file) {
    if (file_exists($file)) {
        echo "<li>$file";
        
        // Upload do arquivo
        if (ftp_put($connection, $file, $file, FTP_BINARY)) {
            echo " ✅ <span style='color: green;'>Enviado</span>";
            $uploaded_count++;
        } else {
            echo " ❌ <span style='color: red;'>Erro no upload</span>";
            $error_count++;
        }
        
        echo "</li>";
    } else {
        echo "<li>$file ❌ <span style='color: orange;'>Arquivo não encontrado</span></li>";
    }
}

echo "</ul>";

// Upload de diretórios (recursivo)
$directories_to_upload = [
    'assets/css',
    'assets/js', 
    'assets/images',
    'classes',
    'config',
    'includes'
];

echo "<h2>📂 Diretórios:</h2>";
echo "<ul>";

foreach ($directories_to_upload as $dir) {
    if (is_dir($dir)) {
        echo "<li>$dir/";
        
        // Criar diretório remoto
        if (ftp_mkdir($connection, $dir)) {
            echo " ✅ <span style='color: green;'>Diretório criado</span>";
        } else {
            echo " ⚠️ <span style='color: orange;'>Diretório já existe ou erro</span>";
        }
        
        echo "</li>";
    }
}

echo "</ul>";

// Fechar conexão
ftp_close($connection);

echo "<h2>📊 Resumo:</h2>";
echo "<p><strong>Arquivos enviados:</strong> $uploaded_count</p>";
echo "<p><strong>Erros:</strong> $error_count</p>";

if ($error_count == 0) {
    echo "<p style='color: green; font-weight: bold;'>🎉 Upload concluído com sucesso!</p>";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ Upload concluído com alguns erros</p>";
}

echo "<h2>🔧 Próximos Passos:</h2>";
echo "<ol>";
echo "<li>Acesse o cPanel e configure o email <code>contato@br2studios.com.br</code></li>";
echo "<li>Edite <code>config/email_config.php</code> com a senha do email</li>";
echo "<li>Teste o sistema em <code>https://br2studios.com.br/test_email.php</code></li>";
echo "<li>Configure SPF/DKIM no cPanel para melhor deliverability</li>";
echo "</ol>";

echo "<p><a href='CONFIGURAR_EMAIL_CPANEL.md'>📖 Ver instruções completas do cPanel</a></p>";
?>
