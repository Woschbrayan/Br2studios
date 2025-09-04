<?php
/**
 * Configurações do Banco de Dados
 * Sistema Br2Studios
 */

// Configurações do MySQL para Hostgator
define('DB_HOST', 'localhost:3308');
define('DB_NAME', 'br2studios');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de log
define('LOG_PATH', '../logs/');
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Configurações de segurança
define('PASSWORD_MIN_LENGTH', 8);
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutos

// Configurações da aplicação
define('APP_NAME', 'Br2Studios');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'https://br2studios.online');
define('ADMIN_EMAIL', 'admin@br2studios.com.br');
?>
