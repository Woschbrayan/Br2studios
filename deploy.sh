#!/bin/bash

# Script de Deploy para Produção - Br2Studios
# Este script prepara o projeto para produção

echo "🚀 Iniciando deploy para produção..."

# 1. Verificar se estamos no diretório correto
if [ ! -f "index.php" ]; then
    echo "❌ Erro: Execute este script no diretório raiz do projeto"
    exit 1
fi

echo "✅ Diretório correto identificado"

# 2. Remover arquivos de desenvolvimento
echo "🧹 Removendo arquivos de desenvolvimento..."

# Remover arquivos de teste
find . -name "test_*.php" -delete 2>/dev/null
find . -name "*test*.php" -delete 2>/dev/null

# Remover diretórios de exemplo
rm -rf exemplos/ 2>/dev/null

# Remover arquivos temporários
find . -name "*.tmp" -delete 2>/dev/null
find . -name "*.log" -delete 2>/dev/null

echo "✅ Arquivos de desenvolvimento removidos"

# 3. Verificar permissões
echo "🔐 Configurando permissões..."

# Diretórios de upload precisam ser graváveis
chmod 755 uploads/
chmod 755 uploads/imoveis/
chmod 755 uploads/corretores/
chmod 755 uploads/depoimentos/
chmod 755 uploads/especialistas/
chmod 755 uploads/regioes/

# Arquivos de configuração devem ser seguros
chmod 644 config/*.php
chmod 644 classes/*.php
chmod 644 .htaccess

echo "✅ Permissões configuradas"

# 4. Verificar dependências
echo "📦 Verificando dependências..."

# Verificar se as classes principais existem
required_files=(
    "classes/Database.php"
    "classes/Imovel.php"
    "classes/CategoriaImovel.php"
    "classes/Corretor.php"
    "classes/Depoimento.php"
    "classes/Especialista.php"
    "classes/Regiao.php"
    "classes/FileUpload.php"
)

for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        echo "❌ Arquivo obrigatório não encontrado: $file"
        exit 1
    fi
done

echo "✅ Todas as dependências estão presentes"

# 5. Verificar configuração do banco
echo "🗄️ Verificando configuração do banco..."

if [ ! -f "config/database.php" ]; then
    echo "❌ Arquivo de configuração do banco não encontrado"
    exit 1
fi

echo "✅ Configuração do banco encontrada"

# 6. Criar arquivo de status de produção
echo "📝 Criando arquivo de status..."

cat > production_status.txt << EOF
🚀 Br2Studios - Status de Produção
📅 Data: $(date)
✅ Projeto preparado para produção
✅ Arquivos de teste removidos
✅ Permissões configuradas
✅ Dependências verificadas
✅ Configuração do banco verificada

📋 Checklist de Produção:
- [ ] Configurar variáveis de ambiente
- [ ] Configurar SSL/HTTPS
- [ ] Configurar backup automático
- [ ] Configurar monitoramento
- [ ] Testar funcionalidades principais
- [ ] Verificar performance

🔧 Configurações recomendadas:
- PHP 8.0+ com extensões: mysqli, gd, mbstring
- MySQL 5.7+ ou MariaDB 10.2+
- Apache 2.4+ com mod_rewrite habilitado
- SSL certificado para HTTPS

📞 Suporte: (41) 4141-0093
EOF

echo "✅ Arquivo de status criado: production_status.txt"

# 7. Verificar estrutura final
echo "🏗️ Verificando estrutura final..."

# Contar arquivos PHP
php_files=$(find . -name "*.php" | wc -l)
css_files=$(find . -name "*.css" | wc -l)
js_files=$(find . -name "*.js" | wc -l)

echo "📊 Estatísticas do projeto:"
echo "   - Arquivos PHP: $php_files"
echo "   - Arquivos CSS: $css_files"
echo "   - Arquivos JS: $js_files"

# 8. Mensagem final
echo ""
echo "🎉 DEPLOY CONCLUÍDO COM SUCESSO!"
echo ""
echo "📋 Próximos passos:"
echo "   1. Fazer upload dos arquivos para o servidor"
echo "   2. Configurar banco de dados no servidor"
echo "   3. Configurar domínio e SSL"
echo "   4. Testar todas as funcionalidades"
echo "   5. Configurar backup automático"
echo ""
echo "📁 Arquivos importantes:"
echo "   - production_status.txt (status do deploy)"
echo "   - .htaccess (configurações do servidor)"
echo "   - config/database.php (configuração do banco)"
echo ""
echo "🚀 Projeto pronto para produção!"
