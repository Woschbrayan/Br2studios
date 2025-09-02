# 📁 Scripts do Sistema BR2Studios

## 🎯 **Scripts Mantidos (Essenciais)**

### **Estrutura do Banco de Dados:**
- `create_tables.sql` - Cria as tabelas principais do sistema
- `create_new_tables.sql` - Cria tabelas adicionais se necessário
- `create_vender_imovel_tables.sql` - Cria tabelas para sistema de venda

### **Verificação e Manutenção:**
- `check_database_structure.php` - Verifica a estrutura atual do banco
- `verify_tables.php` - Verifica se todas as tabelas existem
- `execute_sql.php` - Executa comandos SQL personalizados

## 🗑️ **Scripts Removidos (Limpeza Realizada)**

### **Scripts de Teste Removidos:**
- `execute_test_data.php` - Populava banco com dados de teste
- `generate_test_images.php` - Gerava imagens de teste
- `test_dashboard.php` - Testava funcionalidades do dashboard
- `check_database.php` - Verificava e insería dados de exemplo
- `seed_data.php` - Insería dados de exemplo no banco
- `setup_database.php` - Configuração inicial do banco
- `create_admin_user.php` - Criação de usuário admin
- `fix_admin_user.php` - Correção de usuário admin
- `fix_corretores_destaque.php` - Correção de campo destaque
- `fix_regioes_table.php` - Correção de tabela regiões
- `recreate_regioes_table.php` - Recriação de tabela regiões

### **Arquivos SQL de Teste Removidos:**
- `populate_test_data.sql` - Dados de teste
- `populate_test_data_adapted.sql` - Dados de teste adaptados

### **Arquivos de Teste da Pasta Admin Removidos:**
- `test_corretores_images.php` - Teste de imagens de corretores
- `test_upload.php` - Teste de upload
- `test_debug.php` - Teste de debug

### **Arquivos de Teste da Raiz Removidos:**
- `test-dark-theme.html` - Teste de tema escuro
- `test_connection.php` - Teste de conexão

## 💾 **Economia de Espaço**

**Total de arquivos removidos:** 22 arquivos
**Espaço liberado:** Aproximadamente 150KB+ de código de teste

## ⚠️ **Importante**

- Os scripts mantidos são **essenciais** para o funcionamento do sistema
- **NÃO DELETE** os scripts restantes sem verificar suas dependências
- Use `execute_sql.php` para executar comandos SQL personalizados quando necessário
- Use `verify_tables.php` para verificar a integridade do banco

## 🔧 **Uso dos Scripts Mantidos**

### **Para verificar a estrutura do banco:**
```bash
php scripts/check_database_structure.php
```

### **Para verificar tabelas:**
```bash
php scripts/verify_tables.php
```

### **Para executar SQL personalizado:**
```bash
php scripts/execute_sql.php
```

---

**Sistema limpo e otimizado! 🚀**
