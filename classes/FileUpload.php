<?php
/**
 * Classe FileUpload - Gerenciamento de Upload de Arquivos
 * Sistema Br2Studios
 */

require_once __DIR__ . '/../config/upload_config.php';

class FileUpload {
    private $upload_path;
    private $max_size;
    private $allowed_types;
    private $max_files;
    
    public function __construct($upload_path = '') {
        $this->upload_path = $upload_path ?: UPLOAD_PATH;
        $this->max_size = UPLOAD_MAX_SIZE;
        $this->allowed_types = UPLOAD_ALLOWED_TYPES;
        $this->max_files = UPLOAD_MAX_FILES;
    }
    
    /**
     * Faz upload de uma imagem com otimização
     */
    public function uploadImage($file, $folder = '', $create_thumbnail = true) {
        try {
            // Verificar se o arquivo foi enviado
            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                throw new Exception("Nenhum arquivo foi enviado");
            }
            
            // Verificar erros de upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error_message = $this->getUploadErrorMessage($file['error']);
                throw new Exception("Erro no upload: " . $error_message);
            }
            
            // Verificar tamanho do arquivo
            if ($file['size'] > $this->max_size) {
                throw new Exception("Arquivo muito grande. Tamanho máximo: " . $this->formatBytes($this->max_size));
            }
            
            // Verificar tipo do arquivo
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($file_extension, $this->allowed_types)) {
                throw new Exception("Tipo de arquivo não permitido. Tipos aceitos: " . implode(', ', $this->allowed_types));
            }
            
            // Criar pasta de destino se não existir
            $destination_folder = $this->upload_path . trim($folder, '/') . '/';
            if (!is_dir($destination_folder)) {
                if (!mkdir($destination_folder, 0755, true)) {
                    throw new Exception("Não foi possível criar a pasta de destino");
                }
            }
            
            // Gerar nome único para o arquivo
            $filename = generateSecureFilename($file['name'], $folder);
            $destination_path = $destination_folder . $filename;
            
            // Mover arquivo para pasta de destino
            if (!move_uploaded_file($file['tmp_name'], $destination_path)) {
                throw new Exception("Erro ao mover arquivo para pasta de destino");
            }
            
            // Redimensionar imagem se necessário
            $resized_path = $this->resizeImageIfNeeded($destination_path);
            
            // Criar thumbnail se solicitado
            $thumbnail_path = '';
            if ($create_thumbnail) {
                $thumbnail_path = $this->createThumbnail($destination_path);
            }
            
            // Retornar informações do arquivo
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $destination_path,
                'url' => $this->getPublicUrl($destination_path),
                'size' => $file['size'],
                'type' => $file['type'],
                'resized_path' => $resized_path,
                'thumbnail_path' => $thumbnail_path
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Faz upload de múltiplas imagens com validação aprimorada
     */
    public function uploadMultipleImages($files, $folder = '', $create_thumbnails = true) {
        $results = [];
        $success_count = 0;
        $error_count = 0;
        
        try {
            // Verificar se é um array válido de arquivos
            if (!is_array($files['name']) || empty($files['name'][0])) {
                throw new Exception("Nenhum arquivo foi enviado");
            }
            
            $file_count = count($files['name']);
            
            // Verificar limite de arquivos
            if ($file_count > $this->max_files) {
                throw new Exception("Máximo de " . $this->max_files . " arquivos permitidos por vez");
            }
            
            // Verificar tamanho total dos arquivos
            $total_size = array_sum($files['size']);
            if ($total_size > ($this->max_size * 2)) { // Limite mais flexível para múltiplos arquivos
                throw new Exception("Tamanho total dos arquivos excede o limite permitido");
            }
            
            // Processar cada arquivo
            for ($i = 0; $i < $file_count; $i++) {
                // Pular arquivos vazios
                if (empty($files['name'][$i]) || $files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $result = $this->uploadImage($file, $folder, $create_thumbnails);
                
                if ($result['success']) {
                    $success_count++;
                    $results[] = $result;
                } else {
                    $error_count++;
                    $results[] = [
                        'success' => false,
                        'message' => "Arquivo {$files['name'][$i]}: " . $result['message']
                    ];
                }
            }
            
            // Retornar resultado consolidado
            return [
                'success' => $success_count > 0,
                'message' => "Upload concluído: {$success_count} sucesso(s), {$error_count} erro(s)",
                'files' => $results,
                'urls' => array_map(function($r) { return $r['url'] ?? ''; }, array_filter($results, function($r) { return $r['success']; })),
                'success_count' => $success_count,
                'error_count' => $error_count
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'files' => [],
                'urls' => [],
                'success_count' => 0,
                'error_count' => $file_count ?? 0
            ];
        }
    }
    
    /**
     * Redimensiona imagem se exceder as dimensões máximas
     */
    private function resizeImageIfNeeded($image_path) {
        $image_info = getimagesize($image_path);
        if (!$image_info) {
            return $image_path;
        }
        
        $width = $image_info[0];
        $height = $image_info[1];
        
        // Só redimensionar se exceder os limites
        if ($width <= IMAGE_MAX_WIDTH && $height <= IMAGE_MAX_HEIGHT) {
            return $image_path;
        }
        
        $resized_path = $image_path;
        $resize_result = resizeImage($image_path, $image_path, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT, IMAGE_QUALITY);
        
        return $resize_result ? $image_path : $image_path;
    }
    
    /**
     * Cria thumbnail da imagem
     */
    private function createThumbnail($image_path) {
        $path_info = pathinfo($image_path);
        $thumbnail_path = $path_info['dirname'] . '/thumb_' . $path_info['basename'];
        
        $thumbnail_result = createThumbnail($image_path, $thumbnail_path, IMAGE_QUALITY);
        
        return $thumbnail_result ? $thumbnail_path : '';
    }
    
    /**
     * Remove arquivo do servidor
     */
    public function removeFile($file_path) {
        try {
            if (file_exists($file_path)) {
                if (unlink($file_path)) {
                    // Tentar remover thumbnail se existir
                    $path_info = pathinfo($file_path);
                    $thumbnail_path = $path_info['dirname'] . '/thumb_' . $path_info['basename'];
                    if (file_exists($thumbnail_path)) {
                        unlink($thumbnail_path);
                    }
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Remove múltiplos arquivos
     */
    public function removeMultipleFiles($file_paths) {
        $results = [];
        foreach ($file_paths as $file_path) {
            $results[] = [
                'file' => $file_path,
                'removed' => $this->removeFile($file_path)
            ];
        }
        return $results;
    }
    
    /**
     * Gera nome único para arquivo
     */
    private function generateUniqueFilename($original_name, $destination_folder) {
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $filename = generateSecureFilename($original_name);
        
        $counter = 1;
        $final_filename = $filename;
        
        while (file_exists($destination_folder . $final_filename)) {
            $name_without_ext = pathinfo($filename, PATHINFO_FILENAME);
            $final_filename = $name_without_ext . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $final_filename;
    }
    
    /**
     * Obtém URL pública do arquivo
     */
    private function getPublicUrl($file_path) {
        $relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file_path);
        return str_replace('\\', '/', $relative_path);
    }
    
    /**
     * Formata bytes para exibição
     */
    private function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Obtém mensagem de erro de upload
     */
    private function getUploadErrorMessage($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return "Arquivo excede o tamanho máximo permitido pelo servidor";
            case UPLOAD_ERR_FORM_SIZE:
                return "Arquivo excede o tamanho máximo permitido pelo formulário";
            case UPLOAD_ERR_PARTIAL:
                return "Upload foi interrompido";
            case UPLOAD_ERR_NO_FILE:
                return "Nenhum arquivo foi enviado";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Pasta temporária não encontrada";
            case UPLOAD_ERR_CANT_WRITE:
                return "Erro ao escrever arquivo no disco";
            case UPLOAD_ERR_EXTENSION:
                return "Upload interrompido por extensão";
            default:
                return "Erro desconhecido no upload";
        }
    }
}
?>
