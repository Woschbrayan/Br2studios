<?php
/**
 * Arquivo de Inicialização do Sistema
 * Sistema Br2Studios
 * 
 * Este arquivo deve ser incluído ANTES de session_start() para configurar
 * todas as configurações necessárias
 */

// Configurar limites de sessão ANTES de iniciar a sessão
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);

// Configurar outras configurações de sessão
ini_set('session.use_strict_mode', 1);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // 0 para desenvolvimento, 1 para produção com HTTPS

// Configurar timezone
if (!defined('TIMEZONE_SET')) {
    date_default_timezone_set('America/Sao_Paulo');
    define('TIMEZONE_SET', true);
}

// Configurar encoding
if (!defined('ENCODING_SET')) {
    ini_set('default_charset', 'UTF-8');
    define('ENCODING_SET', true);
}

// Configurar exibição de erros para produção
if (!defined('ERROR_REPORTING_SET')) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
    define('ERROR_REPORTING_SET', true);
}
?>
