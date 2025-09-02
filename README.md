# 🏠 BR2Studios - Sistema Imobiliário Completo

Sistema web profissional para imobiliária Br2Studios, desenvolvido em PHP com banco de dados MySQL, painel administrativo completo e frontend moderno e responsivo.

## 📋 **Índice Rápido**

- [🚀 Instalação Rápida](#-instalação-rápida)
- [⚙️ Configuração Detalhada](#️-configuração-detalhada)
- [🏗️ Estrutura do Sistema](#️-estrutura-do-sistema)
- [🔧 Funcionalidades](#-funcionalidades)
- [📱 Frontend](#-frontend)
- [🔒 Painel Administrativo](#-painel-administrativo)
- [🐛 Solução de Problemas](#-solução-de-problemas)
- [📚 Documentação Técnica](#-documentação-técnica)

---

## 🚀 **Instalação Rápida**

### **1. Pré-requisitos**
```bash
✅ PHP 7.4+ (recomendado 8.0+)
✅ MySQL 5.7+ ou MariaDB 10.2+
✅ Apache/Nginx com mod_rewrite
✅ Extensões PHP: PDO, PDO_MySQL, GD, mbstring
```

### **2. Download e Configuração**
```bash
# Clone o projeto
git clone [url-do-repositorio]
cd br2studios

# Configure permissões
chmod 755 uploads/
chmod 755 uploads/imoveis/
chmod 755 uploads/corretores/
chmod 755 uploads/depoimentos/
chmod 755 uploads/especialistas/
chmod 755 uploads/regioes/
```

### **3. Banco de Dados**
```bash
# Crie o banco
mysql -u root -p
CREATE DATABASE br2studios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Execute os scripts de criação
php scripts/check_database_structure.php
php scripts/verify_tables.php
```

### **4. Configuração**
```php
# Edite config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'br2studios');
define('DB_USERNAME', 'seu_usuario');
define('DB_PASSWORD', 'sua_senha');
```

### **5. Acesso**
```
🌐 Site: http://localhost/br2studios
🔐 Admin: http://localhost/br2studios/admin
📧 Login: admin@br2studios.com.br
🔑 Senha: admin123
```

---

## ⚙️ **Configuração Detalhada**

### **Passo 1: Preparar o Ambiente**

#### **XAMPP/WAMP (Windows/Mac)**
```bash
# Baixe e instale XAMPP
# Inicie Apache e MySQL
# Coloque o projeto em htdocs/
```

#### **LAMP (Linux)**
```bash
# Instalar Apache, MySQL, PHP
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-gd php-mbstring

# Habilitar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### **Passo 2: Configurar Banco de Dados**

#### **Criar Usuário MySQL (Recomendado)**
```sql
CREATE USER 'br2studios'@'localhost' IDENTIFIED BY 'senha_segura_123';
GRANT ALL PRIVILEGES ON br2studios.* TO 'br2studios'@'localhost';
FLUSH PRIVILEGES;
```

#### **Executar Scripts de Criação**
```bash
# Verificar estrutura
php scripts/check_database_structure.php

# Verificar tabelas
php scripts/verify_tables.php

# Se necessário, executar SQL personalizado
php scripts/execute_sql.php
```

### **Passo 3: Configurar Uploads**

#### **Permissões de Diretórios**
```bash
# Linux/Mac
sudo chown -R www-data:www-data uploads/
sudo chmod -R 755 uploads/

# Windows (XAMPP)
# Clique direito → Propriedades → Segurança → Editar → Adicionar → Everyone → Controle Total
```

#### **Configuração PHP (php.ini)**
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
```

### **Passo 4: Configurar .htaccess**

#### **Verificar se está funcionando:**
```bash
# Teste se o mod_rewrite está ativo
# Acesse: http://localhost/br2studios/test_connection.php
# Deve mostrar "✅ Conectado com sucesso!"
```

---

## 🏗️ **Estrutura do Sistema**

### **📁 Estrutura de Pastas**
```
br2studios/
├── 📁 admin/                 # Painel administrativo
│   ├── 📄 dashboard.php      # Dashboard principal
│   ├── 📄 corretores.php     # Gerenciar corretores
│   ├── 📄 imoveis.php        # Gerenciar imóveis
│   ├── 📄 especialistas.php  # Gerenciar especialistas
│   ├── 📄 depoimentos.php    # Gerenciar depoimentos
│   ├── 📄 regioes.php        # Gerenciar regiões
│   └── 📁 includes/          # Componentes admin
├── 📁 assets/                # Recursos estáticos
│   ├── 📁 css/               # Estilos CSS
│   ├── 📁 js/                # JavaScript
│   └── 📁 images/            # Imagens do site
├── 📁 classes/               # Classes PHP
│   ├── 📄 Database.php       # Conexão com banco
│   ├── 📄 Imovel.php         # Gerenciar imóveis
│   ├── 📄 Corretor.php       # Gerenciar corretores
│   └── 📄 FileUpload.php     # Upload de arquivos
├── 📁 config/                # Configurações
│   ├── 📄 database.php       # Configuração do banco
│   ├── 📄 init.php           # Inicialização
│   └── 📄 upload_config.php  # Configuração de uploads
├── 📁 uploads/               # Arquivos enviados
│   ├── 📁 imoveis/           # Imagens de imóveis
│   ├── 📁 corretores/        # Fotos de corretores
│   └── 📁 depoimentos/       # Imagens de depoimentos
├── 📁 scripts/               # Scripts de manutenção
├── 📄 index.php              # Página inicial
├── 📄 imoveis.php            # Listagem de imóveis
├── 📄 corretores.php         # Listagem de corretores
├── 📄 produto.php            # Detalhes do imóvel
├── 📄 regioes.php            # Página de regiões
├── 📄 sobre.php              # Sobre a empresa
├── 📄 contato.php            # Página de contato
└── 📄 .htaccess              # Configurações Apache
```

### **🗄️ Estrutura do Banco de Dados**

#### **Tabelas Principais:**
- `usuarios_admin` - Usuários do painel administrativo
- `corretores` - Cadastro de corretores
- `imoveis` - Cadastro de imóveis
- `imagens_imoveis` - Imagens dos imóveis
- `regioes` - Regiões atendidas
- `depoimentos` - Depoimentos de clientes
- `especialistas` - Especialistas da empresa
- `contatos` - Mensagens de contato
- `newsletter` - Inscritos na newsletter
- `logs_acesso` - Logs de acesso

---

## 🔧 **Funcionalidades**

### **🌐 Frontend (Site Público)**

#### **Página Inicial (index.php)**
- ✅ Header fixo com navegação responsiva
- ✅ Hero section com slider automático
- ✅ Seção de características da empresa
- ✅ Seção de imóveis em destaque
- ✅ Seção de corretores em destaque
- ✅ Footer com informações de contato

#### **Listagem de Imóveis (imoveis.php)**
- ✅ Grid responsivo de imóveis
- ✅ Filtros por tipo, cidade, preço
- ✅ Sistema de paginação
- ✅ Busca por texto
- ✅ Ordenação por preço, data, destaque

#### **Detalhes do Imóvel (produto.php)**
- ✅ Galeria de imagens
- ✅ Informações detalhadas
- ✅ Características do imóvel
- ✅ Formulário de contato
- ✅ Imóveis relacionados
- ✅ Compartilhamento social

#### **Corretores (corretores.php)**
- ✅ Lista de corretores
- ✅ Perfil detalhado
- ✅ Imóveis do corretor
- ✅ Formulário de contato

#### **Regiões (regioes.php)**
- ✅ Mapa das regiões atendidas
- ✅ Imóveis por região
- ✅ Informações da região

### **🔐 Painel Administrativo (admin/)**

#### **Dashboard (dashboard.php)**
- ✅ Estatísticas gerais
- ✅ Gráficos de performance
- ✅ Últimas atividades
- ✅ Ações rápidas

#### **Gerenciamento de Imóveis**
- ✅ CRUD completo de imóveis
- ✅ Upload múltiplo de imagens
- ✅ Sistema de destaque
- ✅ Status de disponibilidade
- ✅ Relacionamento com corretores

#### **Gerenciamento de Corretores**
- ✅ Cadastro de corretores
- ✅ Upload de foto
- ✅ Sistema de destaque
- ✅ Relacionamento com imóveis

#### **Gerenciamento de Conteúdo**
- ✅ Depoimentos de clientes
- ✅ Especialistas da empresa
- ✅ Regiões atendidas
- ✅ Configurações do site

---

## 📱 **Frontend**

### **🎨 Design System**

#### **Cores Principais:**
- **Preto**: #000000 (fundo principal)
- **Branco**: #ffffff (texto principal)
- **Azul**: #0066cc (accent color)
- **Cinza**: #cccccc (texto secundário)
- **Verde**: #28a745 (sucesso)
- **Vermelho**: #dc3545 (erro)

#### **Tipografia:**
- **Títulos**: Roboto, sans-serif
- **Corpo**: Open Sans, sans-serif
- **Tamanhos**: 14px (base), 16px (desktop), 12px (mobile)

### **📱 Responsividade**

#### **Breakpoints:**
- **Mobile**: 320px - 767px
- **Tablet**: 768px - 1199px
- **Desktop**: 1200px+

#### **Características:**
- ✅ Layout fluido e adaptativo
- ✅ Menu mobile com hamburger
- ✅ Imagens responsivas
- ✅ Grid flexível
- ✅ Touch-friendly

### **⚡ Performance**

#### **Otimizações:**
- ✅ CSS e JS minificados
- ✅ Imagens otimizadas
- ✅ Lazy loading de imagens
- ✅ Cache de navegador
- ✅ Compressão GZIP

---

## 🔒 **Painel Administrativo**

### **👤 Sistema de Usuários**

#### **Níveis de Acesso:**
- **Admin**: Acesso total ao sistema
- **Gerente**: Gerenciar conteúdo e usuários
- **Operador**: Gerenciar conteúdo básico

#### **Segurança:**
- ✅ Login com email e senha
- ✅ Hash seguro de senhas
- ✅ Sessões seguras
- ✅ Logs de acesso
- ✅ Timeout de sessão

### **📊 Dashboard**

#### **Métricas Principais:**
- Total de imóveis
- Total de corretores
- Imóveis em destaque
- Contatos recebidos
- Visitas ao site

#### **Gráficos:**
- Vendas por mês
- Imóveis por região
- Performance dos corretores
- Visitas por página

### **📝 Gerenciamento de Conteúdo**

#### **Imóveis:**
- ✅ Cadastro completo
- ✅ Upload de imagens
- ✅ Sistema de destaque
- ✅ Status de disponibilidade
- ✅ Relacionamentos

#### **Corretores:**
- ✅ Perfil completo
- ✅ Foto profissional
- ✅ Especialidades
- ✅ Imóveis associados

---

## 🐛 **Solução de Problemas**

### **❌ Problemas Comuns**

#### **1. Erro de Conexão com Banco**
```bash
# Verificar se MySQL está rodando
sudo systemctl status mysql

# Verificar credenciais em config/database.php
# Testar conexão manual
php scripts/verify_tables.php
```

#### **2. Erro de Upload de Imagens**
```bash
# Verificar permissões
chmod 755 uploads/
chmod 755 uploads/imoveis/

# Verificar configuração PHP
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

#### **3. Página em Branco (White Screen)**
```bash
# Verificar logs de erro
tail -f /var/log/apache2/error.log

# Habilitar exibição de erros em config/init.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

#### **4. Erro 500 (Internal Server Error)**
```bash
# Verificar sintaxe PHP
php -l index.php

# Verificar permissões de arquivos
ls -la *.php
```

### **🔧 Comandos de Diagnóstico**

#### **Verificar Estrutura do Banco:**
```bash
php scripts/check_database_structure.php
```

#### **Verificar Tabelas:**
```bash
php scripts/verify_tables.php
```

#### **Testar Conexão:**
```bash
php scripts/execute_sql.php
```

---

## 📚 **Documentação Técnica**

### **🔌 APIs e Endpoints**

#### **Frontend:**
- `GET /` - Página inicial
- `GET /imoveis` - Listagem de imóveis
- `GET /produto?id={id}` - Detalhes do imóvel
- `GET /corretores` - Lista de corretores
- `GET /regioes` - Regiões atendidas

#### **Admin:**
- `POST /admin/login` - Login administrativo
- `GET /admin/dashboard` - Dashboard
- `CRUD /admin/imoveis` - Gerenciar imóveis
- `CRUD /admin/corretores` - Gerenciar corretores

### **🗄️ Banco de Dados**

#### **Configuração:**
```php
// config/database.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'br2studios');
define('DB_USERNAME', 'usuario');
define('DB_PASSWORD', 'senha');
define('DB_CHARSET', 'utf8mb4');
```

#### **Conexão:**
```php
// classes/Database.php
class Database {
    public static function getConnection() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        return new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}
```

### **📁 Estrutura de Classes**

#### **Classe Imovel:**
```php
class Imovel {
    public function create($data) { /* ... */ }
    public function read($id) { /* ... */ }
    public function update($id, $data) { /* ... */ }
    public function delete($id) { /* ... */ }
    public function search($filters) { /* ... */ }
}
```

#### **Classe FileUpload:**
```php
class FileUpload {
    public function uploadImage($file, $directory) { /* ... */ }
    public function createThumbnail($source, $destination) { /* ... */ }
    public function validateFile($file) { /* ... */ }
}
```

---

## 🚀 **Deploy em Produção**

### **🌐 Servidor Web**

#### **Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Segurança
<Files "*.php">
    Order Allow,Deny
    Allow from all
</Files>

# Cache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
</IfModule>
```

#### **Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}
```

### **🔒 Segurança**

#### **Configurações Recomendadas:**
```php
// config/init.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// Headers de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
```

---

## 📞 **Suporte e Contato**

### **🆘 Suporte Técnico**
- **Email**: suporte@br2studios.com.br
- **Telefone**: (11) 99999-9999
- **WhatsApp**: (11) 99999-9999
- **Horário**: Segunda a Sexta, 9h às 18h

### **📧 Reportar Bugs**
- **GitHub Issues**: [Link do repositório]
- **Email**: bugs@br2studios.com.br
- **Formulário**: [Link do formulário]

### **💡 Sugestões e Melhorias**
- **Email**: sugestoes@br2studios.com.br
- **Formulário**: [Link do formulário]

---

## 📄 **Licença e Direitos**

### **© Copyright**
- **Empresa**: BR2Studios
- **Ano**: 2024
- **Desenvolvedor**: [Nome do desenvolvedor]
- **Versão**: 2.0.0

### **🔒 Direitos Reservados**
Este software é propriedade da BR2Studios. Todos os direitos reservados.
É proibida a reprodução, distribuição ou modificação sem autorização expressa.

---

## 🎯 **Próximas Atualizações**

### **🆕 Versão 2.1.0 (Próxima)**
- [ ] Sistema de notificações push
- [ ] Chat em tempo real
- [ ] API REST completa
- [ ] Sistema de favoritos
- [ ] Relatórios avançados

### **🆕 Versão 2.2.0 (Futura)**
- [ ] App mobile nativo
- [ ] Integração com WhatsApp Business
- [ ] Sistema de leads avançado
- [ ] Analytics em tempo real
- [ ] Integração com CRM

---

## ✨ **Conclusão**

O sistema BR2Studios está configurado e funcionando perfeitamente! 

**🎉 Funcionalidades ativas:**
- ✅ Site responsivo e moderno
- ✅ Painel administrativo completo
- ✅ Sistema de upload de imagens
- ✅ Banco de dados otimizado
- ✅ Segurança implementada
- ✅ Performance otimizada

**🚀 Para começar:**
1. Acesse o painel admin
2. Cadastre seus primeiros imóveis
3. Adicione corretores
4. Configure as regiões
5. Personalize o conteúdo

**💡 Dica:** Use o dashboard para acompanhar o desempenho do seu site!

---

**Sistema BR2Studios - Transformando sonhos em realidade! 🏠✨**
# Br2studios
