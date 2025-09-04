<?php

class CategoriaImovel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Cadastrar uma nova categoria
     */
    public function cadastrar($nome, $descricao = '', $icone = 'fas fa-check') {
        try {
            // Verificar se já existe uma categoria com este nome
            $sql_check = "SELECT id FROM categorias_imoveis WHERE nome = :nome AND ativo = 1";
            $existing = $this->db->fetchOne($sql_check, [':nome' => $nome]);
            
            if ($existing) {
                throw new Exception("Já existe uma categoria com o nome '$nome'. Escolha um nome diferente.");
            }
            
            $sql = "INSERT INTO categorias_imoveis (nome, descricao, icone) VALUES (:nome, :descricao, :icone)";
            $params = [
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':icone' => $icone
            ];
            
            return $this->db->insert($sql, $params);
        } catch (Exception $e) {
            error_log("Erro ao cadastrar categoria: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Atualizar uma categoria existente
     */
    public function atualizar($id, $nome, $descricao = '', $icone = 'fas fa-check', $ativo = true) {
        try {
            $sql = "UPDATE categorias_imoveis SET nome = :nome, descricao = :descricao, icone = :icone, ativo = :ativo WHERE id = :id";
            $params = [
                ':id' => $id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':icone' => $icone,
                ':ativo' => $ativo
            ];
            
            return $this->db->update($sql, $params);
        } catch (Exception $e) {
            error_log("Erro ao atualizar categoria: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Buscar categoria por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM categorias_imoveis WHERE id = :id";
            return $this->db->fetchOne($sql, [':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro ao buscar categoria por ID: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Listar todas as categorias ativas
     */
    public function listarTodas($ativo = true) {
        try {
            $sql = "SELECT * FROM categorias_imoveis WHERE ativo = :ativo ORDER BY nome ASC";
            return $this->db->fetchAll($sql, [':ativo' => $ativo]);
        } catch (Exception $e) {
            error_log("Erro ao listar categorias: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Buscar categorias de um imóvel específico
     */
    public function buscarPorImovel($imovel_id) {
        try {
            $sql = "SELECT c.* FROM categorias_imoveis c 
                    INNER JOIN imovel_categorias ic ON c.id = ic.categoria_id 
                    WHERE ic.imovel_id = :imovel_id AND c.ativo = 1 
                    ORDER BY c.nome ASC";
            
            return $this->db->fetchAll($sql, [':imovel_id' => $imovel_id]);
        } catch (Exception $e) {
            error_log("Erro ao buscar categorias do imóvel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Adicionar categoria a um imóvel
     */
    public function adicionarAoImovel($imovel_id, $categoria_id) {
        try {
            $sql = "INSERT INTO imovel_categorias (imovel_id, categoria_id) VALUES (:imovel_id, :categoria_id)";
            $params = [
                ':imovel_id' => $imovel_id,
                ':categoria_id' => $categoria_id
            ];
            
            return $this->db->insert($sql, $params);
        } catch (Exception $e) {
            error_log("Erro ao adicionar categoria ao imóvel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Remover categoria de um imóvel
     */
    public function removerDoImovel($imovel_id, $categoria_id) {
        try {
            $sql = "DELETE FROM imovel_categorias WHERE imovel_id = :imovel_id AND categoria_id = :categoria_id";
            $params = [
                ':imovel_id' => $imovel_id,
                ':categoria_id' => $categoria_id
            ];
            
            return $this->db->executeQuery($sql, $params);
        } catch (Exception $e) {
            error_log("Erro ao remover categoria do imóvel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Atualizar todas as categorias de um imóvel
     */
    public function atualizarCategoriasImovel($imovel_id, $categorias_ids) {
        try {
            // Primeiro remove todas as categorias atuais
            $sql_remove = "DELETE FROM imovel_categorias WHERE imovel_id = :imovel_id";
            $this->db->executeQuery($sql_remove, [':imovel_id' => $imovel_id]);
            
            // Depois adiciona as novas categorias
            if (!empty($categorias_ids)) {
                foreach ($categorias_ids as $categoria_id) {
                    $this->adicionarAoImovel($imovel_id, $categoria_id);
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Erro ao atualizar categorias do imóvel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Deletar uma categoria (soft delete)
     */
    public function deletar($id) {
        try {
            $sql = "UPDATE categorias_imoveis SET ativo = 0 WHERE id = :id";
            return $this->db->update($sql, [':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro ao deletar categoria: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Buscar categorias por nome (para autocomplete)
     */
    public function buscarPorNome($nome) {
        try {
            $sql = "SELECT * FROM categorias_imoveis WHERE nome LIKE :nome AND ativo = 1 ORDER BY nome ASC LIMIT 10";
            return $this->db->fetchAll($sql, [':nome' => '%' . $nome . '%']);
        } catch (Exception $e) {
            error_log("Erro ao buscar categorias por nome: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sugerir nome único para categoria
     */
    public function sugerirNomeUnico($nome_base) {
        try {
            $nome_sugerido = $nome_base;
            $contador = 1;
            
            while (true) {
                $sql = "SELECT id FROM categorias_imoveis WHERE nome = :nome AND ativo = 1";
                $existing = $this->db->fetchOne($sql, [':nome' => $nome_sugerido]);
                
                if (!$existing) {
                    return $nome_sugerido;
                }
                
                $contador++;
                $nome_sugerido = $nome_base . ' ' . $contador;
                
                // Evitar loop infinito
                if ($contador > 100) {
                    return $nome_base . ' - ' . date('Y-m-d H:i:s');
                }
            }
        } catch (Exception $e) {
            error_log("Erro ao sugerir nome único: " . $e->getMessage());
            return $nome_base . ' - ' . date('Y-m-d H:i:s');
        }
    }
}
