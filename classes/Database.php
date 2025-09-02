<?php
/**
 * Classe Database - Gerenciamento de Conexão com Banco de Dados
 * Sistema Br2Studios
 */

class Database {
    private $host = 'localhost:3308'; // Porta específica do XAMPP
    private $db_name = 'br2studios';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function __construct() {
        $this->host = defined('DB_HOST') ? DB_HOST : 'localhost:3308';
        $this->db_name = defined('DB_NAME') ? DB_NAME : 'br2studios';
        $this->username = defined('DB_USERNAME') ? DB_USERNAME : 'root';
        $this->password = defined('DB_PASSWORD') ? DB_PASSWORD : '';
    }
    
    /**
     * Estabelece conexão com o banco de dados
     */
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch(PDOException $exception) {
                error_log("Erro de conexão: " . $exception->getMessage());
                throw new Exception("Erro ao conectar com o banco de dados");
            }
        }
        
        return $this->conn;
    }
    
    /**
     * Executa uma query preparada
     */
    public function executeQuery($sql, $params = []) {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $exception) {
            $error_details = [
                'sql' => $sql,
                'params' => $params,
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ];
            error_log("Erro na query: " . json_encode($error_details));
            throw new Exception("Erro ao executar consulta no banco de dados: " . $exception->getMessage());
        }
    }
    
    /**
     * Busca um único registro
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Busca múltiplos registros
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Insere um registro e retorna o ID
     */
    public function insert($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $this->getConnection()->lastInsertId();
    }
    
    /**
     * Atualiza registros
     */
    public function update($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Remove registros
     */
    public function delete($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Inicia uma transação
     */
    public function beginTransaction() {
        return $this->getConnection()->beginTransaction();
    }
    
    /**
     * Confirma uma transação
     */
    public function commit() {
        return $this->getConnection()->commit();
    }
    
    /**
     * Reverte uma transação
     */
    public function rollback() {
        return $this->getConnection()->rollback();
    }
    
    /**
     * Verifica se está em uma transação
     */
    public function inTransaction() {
        return $this->getConnection()->inTransaction();
    }
    
    /**
     * Fecha a conexão
     */
    public function closeConnection() {
        $this->conn = null;
    }
    
    /**
     * Escapa strings para evitar SQL injection
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Valida se o email é válido
     */
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Valida se o CPF/CNPJ é válido
     */
    public function validateCpfCnpj($cpfCnpj) {
        $cpfCnpj = preg_replace('/[^0-9]/', '', $cpfCnpj);
        
        if (strlen($cpfCnpj) == 11) {
            return $this->validateCpf($cpfCnpj);
        } elseif (strlen($cpfCnpj) == 14) {
            return $this->validateCnpj($cpfCnpj);
        }
        
        return false;
    }
    
    /**
     * Valida CPF
     */
    private function validateCpf($cpf) {
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Valida CNPJ
     */
    private function validateCnpj($cnpj) {
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
        
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
?>
