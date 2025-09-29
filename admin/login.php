<?php
// Incluir configurações antes de iniciar a sessão
require_once '../config/php_limits.php';
require_once '../config/database.php';

session_start();

// Ativar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log de tentativas de login
function logLoginAttempt($email, $success, $message) {
    $log_file = '../logs/login_attempts.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $log_entry = "[$timestamp] IP: $ip | Email: $email | Success: " . ($success ? 'YES' : 'NO') . " | Message: $message\n";
    
    if (!is_dir('../logs')) {
        mkdir('../logs', 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Se já estiver logado, redireciona para o dashboard
if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
$sucesso = '';
$debug_info = '';

// Processar login
if ($_POST) {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    logLoginAttempt($email, false, 'Tentativa de login iniciada');
    
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
        logLoginAttempt($email, false, 'Campos vazios');
    } else {
        try {
            // Conexão direta com PDO para debug
            $host = DB_HOST;
            $username = DB_USERNAME;
            $password = DB_PASSWORD;
            $database = DB_NAME;
            
            $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $debug_info .= "✓ Conectado ao banco com sucesso\n";
            
            // Buscar usuário
            $sql = "SELECT id, nome, email, senha, nivel, status FROM usuarios_admin WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                $debug_info .= "✓ Usuário encontrado: " . $usuario['nome'] . "\n";
                $debug_info .= "✓ Hash da senha: " . substr($usuario['senha'], 0, 20) . "...\n";
                $debug_info .= "✓ Status: " . $usuario['status'] . "\n";
                
                // Verificar senha
                if (password_verify($senha, $usuario['senha'])) {
                    $debug_info .= "✓ Senha válida\n";
                    
                    if ($usuario['status'] === 'ativo') {
                        // Login bem-sucedido
                        $_SESSION['admin_logado'] = true;
                        $_SESSION['admin_id'] = $usuario['id'];
                        $_SESSION['admin_nome'] = $usuario['nome'];
                        $_SESSION['admin_email'] = $usuario['email'];
                        $_SESSION['admin_nivel'] = $usuario['nivel'];
                        
                        // Atualiza último login
                        $sql_update = "UPDATE usuarios_admin SET ultimo_login = CURRENT_TIMESTAMP WHERE id = :id";
                        $stmt_update = $pdo->prepare($sql_update);
                        $stmt_update->execute([':id' => $usuario['id']]);
                        
                        logLoginAttempt($email, true, 'Login bem-sucedido');
                        
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        $erro = 'Usuário inativo. Entre em contato com o administrador.';
                        logLoginAttempt($email, false, 'Usuário inativo');
                    }
                } else {
                    $erro = 'Email ou senha incorretos.';
                    $debug_info .= "✗ Senha inválida\n";
                    $debug_info .= "✓ Senha digitada: " . $senha . "\n";
                    logLoginAttempt($email, false, 'Senha inválida');
                }
            } else {
                $erro = 'Email ou senha incorretos.';
                $debug_info .= "✗ Usuário não encontrado\n";
                logLoginAttempt($email, false, 'Usuário não encontrado');
            }
            
        } catch (PDOException $e) {
            $erro = 'Erro de conexão com o banco de dados.';
            $debug_info .= "✗ Erro PDO: " . $e->getMessage() . "\n";
            logLoginAttempt($email, false, 'Erro PDO: ' . $e->getMessage());
            error_log("Erro no login: " . $e->getMessage());
        } catch (Exception $e) {
            $erro = 'Erro interno. Tente novamente.';
            $debug_info .= "✗ Erro geral: " . $e->getMessage() . "\n";
            logLoginAttempt($email, false, 'Erro geral: ' . $e->getMessage());
            error_log("Erro no login: " . $e->getMessage());
        }
    }
}

// Verificar se existe usuário admin
try {
    $host = DB_HOST;
    $username = DB_USERNAME;
    $password = DB_PASSWORD;
    $database = DB_NAME;
    
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios_admin");
    $total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    if ($total_usuarios == 0) {
        $sucesso = 'Nenhum usuário admin encontrado. Execute o script de configuração primeiro.';
    }
    
} catch (Exception $e) {
    $erro = 'Não foi possível conectar ao banco de dados.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NNQ3SZFB');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - Br2Studios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.5;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 50px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .logo-icon {
            font-size: 2.5rem;
            color: #ffffff;
            background: linear-gradient(135deg, #666666, #999999);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .login-subtitle {
            color: #cccccc;
            font-size: 1rem;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            color: #ffffff;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #666666;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(102, 102, 102, 0.2);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.1rem;
        }
        
        .input-icon input {
            padding-left: 50px;
        }
        
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #666666, #999999);
            color: #ffffff;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #51cf66;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .debug-info {
            background: rgba(0, 0, 0, 0.3);
            color: #cccccc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 0.8rem;
            white-space: pre-line;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .login-footer {
            text-align: center;
            color: #999999;
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: #cccccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-footer a:hover {
            color: #ffffff;
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }
        
        .floating-element {
            position: absolute;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.3);
            font-size: 1rem;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            .logo-text {
                font-size: 1.5rem;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NNQ3SZFB"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="floating-elements">
        <div class="floating-element">
            <i class="fas fa-building"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-home"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-building"></i>
                </div>
                <span class="logo-text">Br2Studios</span>
            </div>
            <p class="login-subtitle">Painel Administrativo</p>
        </div>
        
        <?php if ($erro): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($sucesso); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($debug_info): ?>
            <div class="debug-info">
                <strong>Debug Info:</strong>
                <?php echo htmlspecialchars($debug_info); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="admin@br2studios.com.br" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="senha" name="senha" placeholder="admin123" required>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Entrar
            </button>
        </form>
        
        <div class="login-footer">
            <p>© 2024 Br2Studios. Todos os direitos reservados.</p>
            <p><a href="../index.php">← Voltar ao site</a></p>
            <p><a href="../scripts/fix_admin_user.php">🔧 Recriar Usuário Admin</a></p>
        </div>
    </div>
</body>
</html>
