# Sistema de Versionamento de Assets

Este sistema centraliza o controle de versão dos arquivos CSS e JavaScript para evitar problemas de cache no navegador.

## Como Funciona

- **Arquivo central**: `config/version.php` - contém a versão atual
- **Todos os arquivos PHP** carregam a versão deste arquivo central
- **Query string** é adicionada automaticamente aos assets: `?v=1.0.2`

## Arquivos Atualizados

✅ **CSS Files** (via `includes/header.php`):
- `assets/css/style.css`
- `assets/css/mobile-enhancements.css`
- `assets/css/mobile-creative.css`
- `assets/css/dark-theme-fixes.css`
- `assets/css/scroll-fix.css`
- `assets/css/hero-mobile-fixes.css`
- Arquivos específicos de página (ex: `home-sections.css`)

✅ **JavaScript Files** (via `includes/footer.php` e páginas específicas):
- `assets/js/main.js`
- `assets/js/mobile-creative.js`
- `assets/js/produto.js`

## Como Atualizar a Versão

### Opção 1: Script Automático (Recomendado)
```bash
# Incrementa automaticamente a versão patch (1.0.2 → 1.0.3)
php scripts/update_version.php

# Define uma versão específica
php scripts/update_version.php 1.1.0
```

### Opção 2: Manual
Edite o arquivo `config/version.php` e altere a variável `$ASSETS_VERSION`:
```php
$ASSETS_VERSION = '1.0.3'; // Nova versão
```

## Quando Atualizar

🔄 **SEMPRE atualize a versão quando:**
- Modificar arquivos CSS
- Modificar arquivos JavaScript
- Fizer alterações visuais ou funcionais
- Publicar uma nova versão do site

## Benefícios

- ✅ **Cache busting automático** - navegadores baixam versões atualizadas
- ✅ **Controle centralizado** - uma única versão para todo o site
- ✅ **Facilidade de manutenção** - script para atualização rápida
- ✅ **Consistência** - todos os assets usam a mesma versão

## Estrutura dos Arquivos

```
config/
├── version.php          # Versão centralizada
scripts/
├── update_version.php   # Script de atualização
includes/
├── header.php           # Carrega CSS com versão
└── footer.php           # Carrega JS com versão
```

## Exemplo de Uso

```php
// Em qualquer arquivo PHP
require_once __DIR__ . '/config/version.php';
$version = getAssetsVersion();

// Resultado: assets/css/style.css?v=1.0.2
echo "assets/css/style.css?v=" . $version;
```
