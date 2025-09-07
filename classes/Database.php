<?php
/**
 * Classe Database
 * Sistema Br2Studios
 */

// Incluir configurações do banco de dados
require_once __DIR__ . '/../config/database.php';

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->username = DB_USERNAME;
        $this->password = DB_PASSWORD;
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    /**
     * Busca um único registro
     */
    public function fetchOne($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro fetchOne: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca múltiplos registros
     */
    public function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erro fetchAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Insere um registro e retorna o ID
     */
    public function insert($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $this->getConnection()->lastInsertId();
        } catch(PDOException $e) {
            error_log("Erro insert: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza registros e retorna número de linhas afetadas
     */
    public function update($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch(PDOException $e) {
            error_log("Erro update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta registros e retorna número de linhas afetadas
     */
    public function delete($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch(PDOException $e) {
            error_log("Erro delete: " . $e->getMessage());
            return false;
        }
    }
}
?>
