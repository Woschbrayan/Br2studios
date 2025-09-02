<?php
/**
 * Configurações de Limites do PHP para Desenvolvimento
 * Sistema Br2Studios
 * 
 * Este arquivo deve ser incluído no início de scripts que fazem upload
 * para garantir que os limites estejam configurados corretamente
 */

// Configurar limites de upload para desenvolvimento
function configurePHPLimits() {
    // Limites de upload
    ini_set('upload_max_filesize', '50M');
    ini_set('post_max_size', '100M');
    ini_set('max_file_uploads', '20');
    
    // Limites de execução
    ini_set('max_execution_time', 300); // 5 minutos
    ini_set('max_input_time', 300);
    ini_set('memory_limit', '256M');
    
    // Limites de entrada
    ini_set('max_input_vars', 3000);
    
    // Configurações de upload
    ini_set('file_uploads', '1');
    ini_set('allow_url_fopen', '1');
    
    // Configurações de erro para desenvolvimento
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// Configurar limites automaticamente
configurePHPLimits();

// Função para verificar se os limites foram aplicados
function checkPHPLimits() {
    $limits = [
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_file_uploads' => ini_get('max_file_uploads'),
        'max_execution_time' => ini_get('max_execution_time'),
        'memory_limit' => ini_get('memory_limit')
    ];
    
    return $limits;
}

// Função para formatar bytes
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

// Função para exibir status dos limites
function displayPHPLimits() {
    $limits = checkPHPLimits();
    
    echo "<div style='background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; font-family: monospace;'>";
    echo "<h4>📊 Status dos Limites do PHP:</h4>";
    echo "<ul>";
    foreach ($limits as $key => $value) {
        $formatted_value = is_numeric($value) ? formatBytes($value * 1024 * 1024) : $value;
        echo "<li><strong>{$key}:</strong> {$formatted_value}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// Função para validar se os limites são suficientes
function validatePHPLimits() {
    $limits = checkPHPLimits();
    $issues = [];
    
    // Verificar tamanho de upload
    $upload_size = intval($limits['upload_max_filesize']);
    if ($upload_size < 50) {
        $issues[] = "upload_max_filesize muito baixo (atual: {$upload_size}M, recomendado: 50M+)";
    }
    
    // Verificar tamanho de post
    $post_size = intval($limits['post_max_size']);
    if ($post_size < 100) {
        $issues[] = "post_max_size muito baixo (atual: {$post_size}M, recomendado: 100M+)";
    }
    
    // Verificar número de arquivos
    $file_uploads = intval($limits['max_file_uploads']);
    if ($file_uploads < 10) {
        $issues[] = "max_file_uploads muito baixo (atual: {$file_uploads}, recomendado: 10+)";
    }
    
    return $issues;
}

// Função para exibir avisos de configuração
function displayPHPLimitsWarnings() {
    $issues = validatePHPLimits();
    
    if (!empty($issues)) {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h4>⚠️ Avisos de Configuração:</h4>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li>{$issue}</li>";
        }
        echo "</ul>";
        echo "<p><strong>Dica:</strong> Configure estes valores no php.ini ou .htaccess para melhor performance.</p>";
        echo "</div>";
    } else {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h4>✅ Limites do PHP configurados corretamente!</h4>";
        echo "<p>O sistema está configurado para suportar uploads de até 50MB e múltiplas imagens.</p>";
        echo "</div>";
    }
}

// Verificar se é uma requisição de teste
if (isset($_GET['test_limits'])) {
    displayPHPLimits();
    displayPHPLimitsWarnings();
}
?>
