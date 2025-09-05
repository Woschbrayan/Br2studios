<?php
/**
 * Script para atualizar a versão dos assets
 * Uso: php scripts/update_version.php [nova_versao]
 * 
 * Se não fornecida uma versão, incrementa automaticamente a versão patch
 */

require_once __DIR__ . '/../config/version.php';

function updateVersion($newVersion = null) {
    $versionFile = __DIR__ . '/../config/version.php';
    
    if ($newVersion) {
        // Usar versão fornecida
        $version = $newVersion;
    } else {
        // Incrementar automaticamente a versão patch
        global $ASSETS_VERSION;
        $parts = explode('.', $ASSETS_VERSION);
        $parts[2] = (int)$parts[2] + 1; // Incrementa patch version
        $version = implode('.', $parts);
    }
    
    // Ler o arquivo atual
    $content = file_get_contents($versionFile);
    
    // Substituir a versão
    $content = preg_replace(
        '/\$ASSETS_VERSION\s*=\s*[\'"][^\'"]*[\'"];/',
        "\$ASSETS_VERSION = '$version';",
        $content
    );
    
    // Escrever o arquivo atualizado
    if (file_put_contents($versionFile, $content)) {
        echo "✅ Versão atualizada para: $version\n";
        echo "📁 Arquivo: $versionFile\n";
        echo "🚀 Pronto para publicação!\n";
    } else {
        echo "❌ Erro ao atualizar a versão\n";
        exit(1);
    }
}

// Verificar argumentos da linha de comando
$newVersion = isset($argv[1]) ? $argv[1] : null;

// Validar formato da versão se fornecida
if ($newVersion && !preg_match('/^\d+\.\d+\.\d+$/', $newVersion)) {
    echo "❌ Formato de versão inválido. Use: X.Y.Z (ex: 1.0.3)\n";
    exit(1);
}

updateVersion($newVersion);
?>
