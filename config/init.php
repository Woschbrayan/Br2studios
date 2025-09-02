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

// Configurar exibição de erros para desenvolvimento
if (!defined('ERROR_REPORTING_SET')) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    define('ERROR_REPORTING_SET', true);
}

// Função para verificar se as configurações foram aplicadas
function checkInitConfig() {
    $configs = [
        'session.gc_maxlifetime' => ini_get('session.gc_maxlifetime'),
        'session.cookie_lifetime' => ini_get('session.cookie_lifetime'),
        'default_charset' => ini_get('default_charset'),
        'display_errors' => ini_get('display_errors'),
        'timezone' => date_default_timezone_get()
    ];
    
    return $configs;
}

// Função para exibir status da inicialização
function displayInitStatus() {
    $configs = checkInitConfig();
    
    echo "<div style='background: #e7f3ff; padding: 15px; margin: 10px 0; border-radius: 5px; font-family: monospace;'>";
    echo "<h4>🔧 Status da Inicialização:</h4>";
    echo "<ul>";
    foreach ($configs as $key => $value) {
        echo "<li><strong>{$key}:</strong> {$value}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// Verificar se é uma requisição de teste
if (isset($_GET['test_init'])) {
    displayInitStatus();
}
?>
