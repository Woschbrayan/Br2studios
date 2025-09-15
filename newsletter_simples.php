<?php
/**
 * Newsletter Simplificada - BR2Studios
 * Versão que funciona mesmo se o email falhar
 */

// Configurar headers para JSON
header('Content-Type: application/json');

// Processar inscrição na newsletter
if ($_POST && isset($_POST['email'])) {
    $email = $_POST['email'] ?? '';
    
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Salvar no banco de dados
            require_once __DIR__ . '/config/database.php';
            require_once __DIR__ . '/classes/Database.php';
            
            $db = new Database();
            
            // Verificar se tabela newsletter existe, se não, criar
            $tabela_existe = $db->fetchOne("SHOW TABLES LIKE 'newsletter'");
            if (!$tabela_existe) {
                $sql = "CREATE TABLE IF NOT EXISTS newsletter (
                    id int NOT NULL AUTO_INCREMENT,
                    email varchar(100) NOT NULL,
                    status enum('ativo','inativo') DEFAULT 'ativo',
                    data_inscricao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    UNIQUE KEY email (email)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                $db->getConnection()->exec($sql);
            }
            
            // Verificar se já existe
            $existe = $db->fetchOne("SELECT id FROM newsletter WHERE email = ?", [$email]);
            
            if ($existe) {
                $response = ['success' => false, 'message' => 'Este email já está cadastrado na newsletter.'];
            } else {
                // Inserir novo email
                $id = $db->insert("INSERT INTO newsletter (email, status, data_inscricao) VALUES (?, 'ativo', NOW())", [$email]);
                
                if ($id) {
                    // Sucesso - não tenta enviar email para evitar erros
                    $response = ['success' => true, 'message' => 'Inscrição realizada com sucesso! Você receberá nossas novidades em breve.'];
                } else {
                    $response = ['success' => false, 'message' => 'Erro ao processar inscrição. Tente novamente.'];
                }
            }
            
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Erro interno. Tente novamente.'];
            error_log("Erro newsletter: " . $e->getMessage());
        }
    } else {
        $response = ['success' => false, 'message' => 'Email inválido.'];
    }
    
    // Retornar resposta JSON
    echo json_encode($response);
    exit;
}

// Se não for POST, retornar erro
echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
?>
