<?php
/**
 * Configuração de Limites do PHP para Br2Studios
 * 
 * Este arquivo configura os limites necessários para o funcionamento
 * adequado do sistema de uploads e processamento de imagens
 */

// Configurar limites de upload
function configurePHPLimits() {
    // Limites de upload de arquivos
    ini_set('upload_max_filesize', '50M');
    ini_set('post_max_size', '100M');
    ini_set('max_file_uploads', '20');
    
    // Limites de execução
    ini_set('max_execution_time', '300');
    ini_set('max_input_time', '300');
    ini_set('memory_limit', '256M');
    
    // Configurações de sessão (apenas se a sessão não estiver ativa)
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.gc_maxlifetime', '3600');
        ini_set('session.cookie_lifetime', '3600');
    }
    
    // Configurações de erro para produção
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

// Configurar limites automaticamente
configurePHPLimits();
?>
