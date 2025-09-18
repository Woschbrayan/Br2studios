<?php
/**
 * Script para atualizar automaticamente a versão dos assets
 * Uso: php scripts/update_cache_version.php [nova_versao]
 * Se não informar versão, incrementa automaticamente
 */

require_once __DIR__ . '/../config/version.php';

function updateVersion($newVersion = null) {
    $versionFile = __DIR__ . '/../config/version.php';
    
    if (!file_exists($versionFile)) {
        echo "❌ Arquivo de versão não encontrado: $versionFile\n";
        return false;
    }
    
    // Ler versão atual
    $currentVersion = getAssetsVersion();
    echo "📋 Versão atual: $currentVersion\n";
    
    // Determinar nova versão
    if ($newVersion) {
        $nextVersion = $newVersion;
    } else {
        // Incrementar automaticamente
        $parts = explode('.', $currentVersion);
        $lastPart = intval(end($parts));
        $lastPart++;
        $parts[count($parts) - 1] = $lastPart;
        $nextVersion = implode('.', $parts);
    }
    
    echo "🚀 Nova versão: $nextVersion\n";
    
    // Ler conteúdo do arquivo
    $content = file_get_contents($versionFile);
    
    // Substituir a versão
    $pattern = '/\$ASSETS_VERSION = \'[^\']+\';/';
    $replacement = "\$ASSETS_VERSION = '$nextVersion';";
    
    $newContent = preg_replace($pattern, $replacement, $content);
    
    if ($newContent === $content) {
        echo "❌ Erro: Não foi possível atualizar a versão no arquivo\n";
        return false;
    }
    
    // Salvar arquivo
    if (file_put_contents($versionFile, $newContent)) {
        echo "✅ Versão atualizada com sucesso!\n";
        echo "📁 Arquivo: $versionFile\n";
        echo "🔄 Todos os arquivos CSS/JS agora usarão a versão $nextVersion\n";
        echo "\n💡 Dica: Limpe o cache do navegador (Ctrl+F5) para ver as mudanças\n";
        return true;
    } else {
        echo "❌ Erro ao salvar arquivo de versão\n";
        return false;
    }
}

// Executar script
if (php_sapi_name() === 'cli') {
    echo "=== ATUALIZADOR DE VERSÃO DE ASSETS ===\n\n";
    
    $newVersion = $argv[1] ?? null;
    
    if ($newVersion && !preg_match('/^\d+\.\d+\.\d+$/', $newVersion)) {
        echo "❌ Formato de versão inválido. Use: X.Y.Z (ex: 1.2.3)\n";
        exit(1);
    }
    
    $success = updateVersion($newVersion);
    exit($success ? 0 : 1);
} else {
    // Se executado via web
    $newVersion = $_GET['version'] ?? null;
    
    if ($newVersion && !preg_match('/^\d+\.\d+\.\d+$/', $newVersion)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Formato de versão inválido']);
        exit;
    }
    
    $success = updateVersion($newVersion);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Versão atualizada com sucesso' : 'Erro ao atualizar versão'
    ]);
}
?>
