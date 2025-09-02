# 🚀 **INSTALAÇÃO RÁPIDA - BR2Studios**

## ⚡ **Instalação em 5 Minutos**

### **1️⃣ Pré-requisitos**
```bash
✅ PHP 7.4+ instalado
✅ MySQL 5.7+ rodando
✅ Apache/Nginx configurado
✅ Extensões PHP: PDO, PDO_MySQL, GD, mbstring
```

### **2️⃣ Download e Configuração**
```bash
# Baixe o projeto
git clone [url-do-repositorio]
cd br2studios

# Configure permissões (Linux/Mac)
chmod 755 uploads/
chmod 755 uploads/imoveis/
chmod 755 uploads/corretores/
chmod 755 uploads/depoimentos/
chmod 755 uploads/especialistas/
chmod 755 uploads/regioes/

# Windows (XAMPP): Clique direito → Propriedades → Segurança → Everyone → Controle Total
```

### **3️⃣ Banco de Dados**
```sql
# Acesse o MySQL
mysql -u root -p

# Crie o banco
CREATE DATABASE br2studios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Crie usuário (recomendado)
CREATE USER 'br2studios'@'localhost' IDENTIFIED BY 'senha123';
GRANT ALL PRIVILEGES ON br2studios.* TO 'br2studios'@'localhost';
FLUSH PRIVILEGES;

# Saia
EXIT;
```

### **4️⃣ Configuração do Sistema**
```php
# Edite config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'br2studios');
define('DB_USERNAME', 'br2studios');  // ou 'root'
define('DB_PASSWORD', 'senha123');    // ou sua senha
```

### **5️⃣ Criação das Tabelas**
```bash
# Execute os scripts
php scripts/check_database_structure.php
php scripts/verify_tables.php
```

### **6️⃣ Acesso ao Sistema**
```
🌐 Site: http://localhost/br2studios
🔐 Admin: http://localhost/br2studios/admin
📧 Login: admin@br2studios.com.br
🔑 Senha: admin123
```

---

## 🔧 **Configurações Adicionais**

### **Permissões de Upload (Linux/Mac)**
```bash
# Definir proprietário correto
sudo chown -R www-data:www-data uploads/

# Verificar permissões
ls -la uploads/
# Deve mostrar: drwxr-xr-x
```

### **Configuração PHP (php.ini)**
```ini
; Aumentar limites de upload
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M

; Habilitar extensões
extension=pdo
extension=pdo_mysql
extension=gd
extension=mbstring
```

### **Verificar Extensões PHP**
```bash
# Verificar se as extensões estão ativas
php -m | grep -E "(pdo|gd|mbstring)"

# Deve mostrar:
# gd
# mbstring
# pdo
# pdo_mysql
```

---

## 🐛 **Solução de Problemas Comuns**

### **❌ Erro: "Could not connect to database"**
```bash
# Verificar se MySQL está rodando
sudo systemctl status mysql

# Verificar credenciais
php scripts/verify_tables.php
```

### **❌ Erro: "Permission denied" no upload**
```bash
# Verificar permissões
ls -la uploads/

# Corrigir permissões
chmod 755 uploads/
chmod 755 uploads/imoveis/
```

### **❌ Página em branco**
```bash
# Verificar logs de erro
tail -f /var/log/apache2/error.log

# Verificar sintaxe PHP
php -l index.php
```

### **❌ Erro 500 (Internal Server Error)**
```bash
# Verificar arquivo .htaccess
cat .htaccess

# Verificar se mod_rewrite está ativo
apache2ctl -M | grep rewrite
```

---

## ✅ **Verificação da Instalação**

### **Teste 1: Conexão com Banco**
```bash
php scripts/verify_tables.php
# Deve mostrar: "✅ Tabelas verificadas com sucesso!"
```

### **Teste 2: Acesso ao Site**
```
Acesse: http://localhost/br2studios
Deve mostrar: Página inicial com menu e conteúdo
```

### **Teste 3: Painel Admin**
```
Acesse: http://localhost/br2studios/admin
Login: admin@br2studios.com.br
Senha: admin123
Deve mostrar: Dashboard administrativo
```

### **Teste 4: Upload de Imagens**
```
1. Acesse admin/imoveis.php
2. Clique em "Adicionar Imóvel"
3. Faça upload de uma imagem
4. Deve funcionar sem erros
```

---

## 🎯 **Próximos Passos**

### **1. Personalizar o Site**
- Editar `index.php` para alterar conteúdo
- Modificar `assets/css/style.css` para cores
- Alterar logo em `assets/images/logo/`

### **2. Cadastrar Conteúdo**
- Adicionar imóveis via painel admin
- Cadastrar corretores
- Configurar regiões atendidas
- Adicionar depoimentos

### **3. Configurar SEO**
- Editar meta tags nas páginas
- Configurar URLs amigáveis
- Adicionar sitemap.xml

---

## 📞 **Suporte Rápido**

### **🆘 Problemas Urgentes**
- **Email**: suporte@br2studios.com.br
- **WhatsApp**: (11) 99999-9999
- **Telefone**: (11) 99999-9999

### **📚 Documentação Completa**
- **README.md** - Documentação completa
- **scripts/README.md** - Guia dos scripts
- **GitHub Issues** - Reportar bugs

---

## 🎉 **Parabéns!**

Seu sistema BR2Studios está funcionando perfeitamente!

**🚀 Próximas ações:**
1. Cadastre seus primeiros imóveis
2. Adicione corretores da equipe
3. Configure as regiões atendidas
4. Personalize o design do site
5. Teste todas as funcionalidades

**💡 Dica:** Use o dashboard administrativo para acompanhar o desempenho!

---

**Sistema BR2Studios - Instalado com sucesso! 🏠✨**
