# ✅ **CHECKLIST DE INSTALAÇÃO - BR2Studios**

## 🎯 **Objetivo**
Este checklist garante que todas as etapas de instalação sejam executadas corretamente.

---

## 📋 **PRÉ-INSTALAÇÃO**

### **Ambiente de Desenvolvimento**
- [ ] **PHP 7.4+** instalado e funcionando
- [ ] **MySQL 5.7+** ou MariaDB 10.2+ rodando
- [ ] **Apache/Nginx** configurado
- [ ] **Extensões PHP** ativas: PDO, PDO_MySQL, GD, mbstring
- [ ] **mod_rewrite** habilitado (Apache)

### **Verificação de Extensões**
```bash
php -m | grep -E "(pdo|gd|mbstring)"
# Deve mostrar: gd, mbstring, pdo, pdo_mysql
```

---

## 🚀 **INSTALAÇÃO**

### **1. Download do Projeto**
- [ ] Projeto baixado/clonado
- [ ] Navegação para pasta do projeto
- [ ] Verificação da estrutura de arquivos

### **2. Configuração de Permissões**
```bash
# Linux/Mac
chmod 755 uploads/
chmod 755 uploads/imoveis/
chmod 755 uploads/corretores/
chmod 755 uploads/depoimentos/
chmod 755 uploads/especialistas/
chmod 755 uploads/regioes/

# Windows (XAMPP)
# Clique direito → Propriedades → Segurança → Everyone → Controle Total
```

### **3. Banco de Dados**
- [ ] Banco `br2studios` criado
- [ ] Usuário MySQL criado (recomendado)
- [ ] Permissões concedidas
- [ ] Conexão testada

```sql
CREATE DATABASE br2studios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'br2studios'@'localhost' IDENTIFIED BY 'senha123';
GRANT ALL PRIVILEGES ON br2studios.* TO 'br2studios'@'localhost';
FLUSH PRIVILEGES;
```

### **4. Configuração do Sistema**
- [ ] Arquivo `config/database.php` editado
- [ ] Credenciais do banco configuradas
- [ ] Conexão testada

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'br2studios');
define('DB_USERNAME', 'br2studios');
define('DB_PASSWORD', 'senha123');
```

### **5. Criação das Tabelas**
- [ ] Script `check_database_structure.php` executado
- [ ] Script `verify_tables.php` executado
- [ ] Tabelas criadas com sucesso
- [ ] Estrutura verificada

```bash
php scripts/check_database_structure.php
php scripts/verify_tables.php
```

---

## ✅ **VERIFICAÇÃO**

### **Teste de Conexão**
- [ ] Banco de dados acessível
- [ ] Tabelas existem e estão funcionais
- [ ] Scripts executam sem erros

### **Teste do Site**
- [ ] Página inicial carrega
- [ ] Menu de navegação funcional
- [ ] CSS e JavaScript carregam
- [ ] Imagens exibem corretamente

### **Teste do Painel Admin**
- [ ] Acesso a `/admin`
- [ ] Login com credenciais padrão
- [ ] Dashboard carrega
- [ ] Menu administrativo funcional

### **Teste de Upload**
- [ ] Upload de imagens funciona
- [ ] Thumbnails são gerados
- [ ] Arquivos salvos corretamente
- [ ] Permissões de pasta corretas

---

## 🔧 **CONFIGURAÇÕES AVANÇADAS**

### **PHP (php.ini)**
- [ ] `upload_max_filesize = 10M`
- [ ] `post_max_size = 10M`
- [ ] `max_execution_time = 300`
- [ ] `memory_limit = 256M`

### **Apache (.htaccess)**
- [ ] `mod_rewrite` habilitado
- [ ] Arquivo `.htaccess` presente
- [ ] URLs amigáveis funcionando
- [ ] Redirecionamentos corretos

### **Segurança**
- [ ] Credenciais padrão alteradas
- [ ] Usuário MySQL não é root
- [ ] Permissões de arquivo corretas
- [ ] Logs de erro configurados

---

## 🎯 **PÓS-INSTALAÇÃO**

### **Personalização**
- [ ] Logo da empresa alterado
- [ ] Cores do site personalizadas
- [ ] Conteúdo da empresa atualizado
- [ ] Informações de contato corretas

### **Cadastro de Conteúdo**
- [ ] Primeiro imóvel cadastrado
- [ ] Primeiro corretor cadastrado
- [ ] Regiões atendidas configuradas
- [ ] Depoimentos adicionados

### **Testes Finais**
- [ ] Todas as páginas funcionam
- [ ] Formulários enviam dados
- [ ] Uploads funcionam
- [ ] Responsividade testada
- [ ] Navegadores testados

---

## 🐛 **SOLUÇÃO DE PROBLEMAS**

### **Problemas Comuns**
- [ ] **Conexão com banco**: Verificar credenciais e status MySQL
- [ ] **Permissões**: Verificar chmod e proprietário dos arquivos
- [ ] **Upload**: Verificar configurações PHP e permissões de pasta
- [ ] **Página em branco**: Verificar logs de erro e sintaxe PHP
- [ ] **Erro 500**: Verificar .htaccess e mod_rewrite

### **Comandos de Diagnóstico**
```bash
# Verificar estrutura do banco
php scripts/check_database_structure.php

# Verificar tabelas
php scripts/verify_tables.php

# Verificar sintaxe PHP
php -l index.php

# Verificar logs de erro
tail -f /var/log/apache2/error.log
```

---

## 📞 **SUPORTE**

### **Documentação**
- [ ] README.md lido
- [ ] INSTALACAO.md consultado
- [ ] scripts/README.md revisado
- [ ] Comentários no código analisados

### **Contato para Suporte**
- **Email**: suporte@br2studios.com.br
- **WhatsApp**: (11) 99999-9999
- **Telefone**: (11) 99999-9999

---

## 🎉 **INSTALAÇÃO CONCLUÍDA**

### **Checklist Final**
- [ ] ✅ Sistema funcionando perfeitamente
- [ ] ✅ Site acessível publicamente
- [ ] ✅ Painel admin funcional
- [ ] ✅ Uploads funcionando
- [ ] ✅ Banco de dados operacional
- [ ] ✅ Todas as funcionalidades testadas

### **Próximos Passos**
1. **Cadastrar conteúdo real** (imóveis, corretores, regiões)
2. **Personalizar design** (cores, logo, conteúdo)
3. **Configurar SEO** (meta tags, URLs amigáveis)
4. **Testar em produção** (diferentes dispositivos e navegadores)
5. **Configurar backup** (banco de dados e arquivos)

---

## 📊 **STATUS DA INSTALAÇÃO**

**Data da Instalação**: _______________
**Responsável**: _______________
**Ambiente**: _______________ (Local/Produção/Teste)

**Status Geral**: ⚪ Não iniciado | 🟡 Em andamento | 🟢 Concluído | 🔴 Com problemas

**Observações**: _______________

---

**🎯 Sistema BR2Studios - Instalação verificada e aprovada! 🏠✨**
