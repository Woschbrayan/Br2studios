# Sistema de Versionamento de Cache

## 📋 Visão Geral

Este sistema evita problemas de cache do navegador quando você faz atualizações nos arquivos CSS e JavaScript. Todos os arquivos são carregados com um parâmetro de versão que força o navegador a baixar a versão mais recente.

## 🔧 Como Funciona

### Arquivo de Configuração
- **Arquivo**: `config/version.php`
- **Variável**: `$ASSETS_VERSION`
- **Formato**: `X.Y.Z` (ex: 1.2.1)

### Aplicação Automática
Todos os arquivos CSS e JS são carregados com o parâmetro `?v=VERSÃO`:
```html
<link rel="stylesheet" href="assets/css/style.css?v=1.2.1">
<script src="assets/js/main.js?v=1.2.1"></script>
```

## 🚀 Como Atualizar a Versão

### Método 1: Script Automático (Recomendado)
```bash
# Incrementar automaticamente (1.2.1 → 1.2.2)
php scripts/update_cache_version.php

# Definir versão específica
php scripts/update_cache_version.php 2.0.0
```

### Método 2: Manual
1. Abra `config/version.php`
2. Altere o valor de `$ASSETS_VERSION`
3. Salve o arquivo

### Método 3: Via Web (se disponível)
```
GET /scripts/update_cache_version.php?version=1.2.2
```

## 📁 Arquivos Afetados

### CSS (Header)
- `assets/css/style.css`
- `assets/css/mobile-enhancements.css`
- `assets/css/mobile-creative.css`
- `assets/css/dark-theme-fixes.css`
- `assets/css/scroll-fix.css`
- `assets/css/hero-mobile-fixes.css`
- `assets/css/spacing-fixes.css`
- `assets/css/section-overrides.css`
- `assets/css/corretores-alignment-fix.css`
- `assets/css/mobile-responsiveness-fixes.css`
- `assets/css/features-mobile-fix.css`
- `assets/css/hero-spacing-fix.css`
- `assets/css/section-spacing-mobile.css`
- `assets/css/property-cards-improvements.css`
- `assets/css/imoveis-cards-fix.css`
- `assets/css/imoveis-layout-fix.css`
- `assets/css/dynamic-filters.css`
- `assets/css/regioes-carousel.css`
- `assets/css/sobre-fixes.css`
- `assets/css/logo-fix.css`
- E outros CSS específicos de páginas

### JavaScript (Footer)
- `assets/js/main.js`
- E outros JS específicos de páginas

## 🔄 Quando Atualizar

**SEMPRE atualize a versão quando:**
- ✅ Modificar qualquer arquivo CSS
- ✅ Modificar qualquer arquivo JavaScript
- ✅ Adicionar novos arquivos CSS/JS
- ✅ Fazer alterações visuais importantes
- ✅ Corrigir bugs de layout ou funcionalidade

**NÃO precisa atualizar quando:**
- ❌ Modificar apenas arquivos PHP
- ❌ Alterar conteúdo de texto
- ❌ Modificar imagens
- ❌ Atualizar banco de dados

## 💡 Dicas Importantes

### Para Desenvolvedores
1. **Sempre atualize a versão** após fazer mudanças em CSS/JS
2. **Use o script automático** para evitar erros de digitação
3. **Teste em diferentes navegadores** após atualizar
4. **Informe os usuários** para limparem o cache (Ctrl+F5)

### Para Usuários Finais
- Se não vir as mudanças, pressione **Ctrl+F5** (Windows) ou **Cmd+Shift+R** (Mac)
- Ou abra o navegador em modo incógnito/privado

## 🛠️ Estrutura Técnica

### Arquivos Principais
```
config/version.php          # Configuração central
scripts/update_cache_version.php  # Script de atualização
includes/header.php          # Carrega CSS com versão
includes/footer.php          # Carrega JS com versão
```

### Funções Disponíveis
```php
getAssetsVersion()          # Retorna versão atual
getVersionQuery()           # Retorna ?v=VERSÃO
```

## 📊 Histórico de Versões

- **1.2.1** - Implementação do campo maior_valorizacao
- **1.2.0** - Melhorias no sistema de mapas
- **1.1.6** - Correções de layout mobile
- **1.1.5** - Ajustes de responsividade
- **1.1.4** - Melhorias nos cards de imóveis

## ⚠️ Troubleshooting

### Problema: Mudanças não aparecem
**Solução**: 
1. Verifique se a versão foi atualizada
2. Limpe o cache do navegador (Ctrl+F5)
3. Verifique se o arquivo foi salvo corretamente

### Problema: Erro no script de atualização
**Solução**:
1. Verifique permissões do arquivo `config/version.php`
2. Execute com permissões adequadas
3. Verifique se o PHP está funcionando

### Problema: Versão não está sendo aplicada
**Solução**:
1. Verifique se `includes/header.php` e `includes/footer.php` estão incluindo o versionamento
2. Verifique se não há cache no servidor
3. Confirme que os arquivos estão sendo servidos corretamente
