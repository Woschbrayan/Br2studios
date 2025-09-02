<?php
/**
 * Configurações de Upload Otimizadas
 * Sistema Br2Studios
 */

// Configurações de upload otimizadas (só define se não existir)
if (!defined('UPLOAD_MAX_SIZE')) {
    define('UPLOAD_MAX_SIZE', 50 * 1024 * 1024); // 50MB (aumentado)
}
if (!defined('UPLOAD_MAX_FILES')) {
    define('UPLOAD_MAX_FILES', 10); // Máximo de 10 arquivos por vez
}
if (!defined('UPLOAD_ALLOWED_TYPES')) {
    define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}
if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', __DIR__ . '/../uploads/');
}
if (!defined('UPLOAD_IMOVEIS_PATH')) {
    define('UPLOAD_IMOVEIS_PATH', __DIR__ . '/../uploads/imoveis/');
}
if (!defined('UPLOAD_CORRETORES_PATH')) {
    define('UPLOAD_CORRETORES_PATH', __DIR__ . '/../uploads/corretores/');
}

// Configurações de imagem
if (!defined('IMAGE_MAX_WIDTH')) {
    define('IMAGE_MAX_WIDTH', 1920);
}
if (!defined('IMAGE_MAX_HEIGHT')) {
    define('IMAGE_MAX_HEIGHT', 1080);
}
if (!defined('IMAGE_QUALITY')) {
    define('IMAGE_QUALITY', 85);
}
if (!defined('THUMBNAIL_WIDTH')) {
    define('THUMBNAIL_WIDTH', 300);
}
if (!defined('THUMBNAIL_HEIGHT')) {
    define('THUMBNAIL_HEIGHT', 200);
}

// Configurações de segurança
if (!defined('UPLOAD_SECURE_NAMES')) {
    define('UPLOAD_SECURE_NAMES', true);
}
if (!defined('UPLOAD_OVERWRITE')) {
    define('UPLOAD_OVERWRITE', false);
}

// Função para configurar limites do PHP
function configureUploadLimits() {
    // Aumentar limites de upload
    ini_set('upload_max_filesize', '50M');
    ini_set('post_max_size', '100M');
    ini_set('max_file_uploads', '20');
    ini_set('max_execution_time', 300); // 5 minutos
    ini_set('memory_limit', '256M');
    
    // Configurações de entrada
    ini_set('max_input_vars', 3000);
    ini_set('max_input_time', 300);
    
    // Configurações de upload
    ini_set('file_uploads', '1');
    ini_set('allow_url_fopen', '1');
}

// Função para configurar limites de sessão (deve ser chamada ANTES de session_start())
function configureSessionLimits() {
    // Configurações de sessão para uploads grandes
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 3600);
}

// Função para criar diretórios de upload se não existirem
function createUploadDirectories() {
    $directories = [
        UPLOAD_PATH,
        UPLOAD_IMOVEIS_PATH,
        UPLOAD_CORRETORES_PATH
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Função para validar tipo de arquivo
function isValidImageType($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, UPLOAD_ALLOWED_TYPES);
}

// Função para gerar nome seguro de arquivo
function generateSecureFilename($original_name, $prefix = '') {
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $timestamp = time();
    $random = bin2hex(random_bytes(8));
    
    if ($prefix) {
        return $prefix . '_' . $timestamp . '_' . $random . '.' . $extension;
    }
    
    return $timestamp . '_' . $random . '.' . $extension;
}

// Função para redimensionar imagem
function resizeImage($source_path, $destination_path, $max_width, $max_height, $quality = 85) {
    if (!file_exists($source_path)) {
        return false;
    }
    
    $image_info = getimagesize($source_path);
    if (!$image_info) {
        return false;
    }
    
    $width = $image_info[0];
    $height = $image_info[1];
    $type = $image_info[2];
    
    // Calcular novas dimensões mantendo proporção
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = round($width * $ratio);
    $new_height = round($height * $ratio);
    
    // Criar nova imagem
    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    // Carregar imagem original baseada no tipo
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source_path);
            // Preservar transparência PNG
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($source_path);
            break;
        default:
            return false;
    }
    
    // Redimensionar
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Salvar nova imagem
    $result = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($new_image, $destination_path, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($new_image, $destination_path, round((100 - $quality) / 11.111111));
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($new_image, $destination_path);
            break;
    }
    
    // Limpar memória
    imagedestroy($source_image);
    imagedestroy($new_image);
    
    return $result;
}

// Função para criar thumbnail
function createThumbnail($source_path, $destination_path, $quality = 85) {
    return resizeImage($source_path, $destination_path, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, $quality);
}

// Configurar limites automaticamente (exceto sessão)
configureUploadLimits();
createUploadDirectories();
?>
