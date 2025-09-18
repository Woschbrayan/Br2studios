<?php
/**
 * Configuração de Versionamento para Assets
 * Este arquivo centraliza o controle de versão dos arquivos CSS e JS
 * para evitar problemas de cache no navegador
 */

// Versão atual dos assets
// Incremente este valor sempre que fizer alterações nos arquivos CSS/JS
$ASSETS_VERSION = '1.2.1';

// Função para obter a versão atual
function getAssetsVersion() {
    global $ASSETS_VERSION;
    return $ASSETS_VERSION;
}

// Função para gerar query string de versão
function getVersionQuery() {
    return '?v=' . getAssetsVersion();
}
?>
