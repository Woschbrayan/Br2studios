<?php
/**
 * Classe Regiao - Gerenciamento de Regiões e Cidades
 * Sistema Br2Studios
 */

require_once 'Database.php';

class Regiao {
    private $db;
    private $table = 'regioes';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Cadastra uma nova região
     */
    public function cadastrar($dados) {
        try {
            // Validações básicas
            if (empty($dados['nome']) || empty($dados['estado'])) {
                throw new Exception("Nome da região e estado são obrigatórios");
            }
            
            // Verificar se região já existe
            $existente = $this->buscarPorNomeEstado($dados['nome'], $dados['estado']);
            if ($existente) {
                throw new Exception("Região já cadastrada para este estado");
            }
            
            // Preparar dados para inserção
            $campos = ['nome', 'estado', 'descricao', 'imagem', 'destaque', 'ativo'];
            $valores = [];
            $params = [];
            $campos_inserir = [];
            
            foreach ($campos as $campo) {
                if (isset($dados[$campo]) && !empty($dados[$campo])) {
                    $campos_inserir[] = $campo;
                    $valores[] = ":$campo";
                    $params[":$campo"] = $dados[$campo];
                }
            }
            
            if (empty($valores)) {
                throw new Exception("Dados insuficientes para cadastro");
            }
            
            $sql = "INSERT INTO {$this->table} (" . implode(', ', $campos_inserir) . ") VALUES (" . implode(', ', $valores) . ")";
            
            $id = $this->db->insert($sql, $params);
            
            if ($id) {
                return [
                    'success' => true,
                    'message' => 'Região cadastrada com sucesso!',
                    'id' => $id
                ];
            } else {
                throw new Exception("Erro ao cadastrar região");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualiza dados de uma região
     */
    public function atualizar($id, $dados) {
        try {
            if (empty($id)) {
                throw new Exception("ID da região é obrigatório");
            }
            
            // Verificar se região existe
            $existente = $this->buscarPorId($id);
            if (!$existente) {
                throw new Exception("Região não encontrada");
            }
            
            // Preparar dados para atualização
            $campos = ['nome', 'estado', 'descricao', 'imagem', 'destaque', 'ativo'];
            $sets = [];
            $params = [':id' => $id];
            
            foreach ($campos as $campo) {
                if (isset($dados[$campo])) {
                    $sets[] = "$campo = :$campo";
                    $params[":$campo"] = $dados[$campo];
                }
            }
            
            if (empty($sets)) {
                throw new Exception("Nenhum dado para atualizar");
            }
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";
            
            $rows = $this->db->update($sql, $params);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Região atualizada com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao atualizar região");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Busca região por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            return $this->db->fetchOne($sql, [':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro ao buscar região por ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca região por nome e estado
     */
    public function buscarPorNomeEstado($nome, $estado) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE nome = :nome AND estado = :estado";
            return $this->db->fetchOne($sql, [':nome' => $nome, ':estado' => $estado]);
        } catch (Exception $e) {
            error_log("Erro ao buscar região por nome e estado: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista todas as regiões
     */
    public function listarTodos($filtros = [], $pagina = 1, $limite = 20) {
        try {
            $where = [];
            $params = [];
            
            // Aplicar filtros
            if (isset($filtros['ativo'])) {
                $where[] = "ativo = :ativo";
                $params[':ativo'] = $filtros['ativo'];
            }
            
            if (isset($filtros['destaque'])) {
                $where[] = "destaque = :destaque";
                $params[':destaque'] = $filtros['destaque'];
            }
            
            if (isset($filtros['estado']) && !empty($filtros['estado'])) {
                $where[] = "estado = :estado";
                $params[':estado'] = $filtros['estado'];
            }
            
            $sql = "SELECT * FROM {$this->table}";
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            $sql .= " ORDER BY estado ASC, nome ASC";
            
            // Paginação
            $offset = ($pagina - 1) * $limite;
            $sql .= " LIMIT {$limite} OFFSET {$offset}";
            
            $regioes = $this->db->fetchAll($sql, $params);
            
            // Contar total para paginação
            $sql_count = "SELECT COUNT(*) as total FROM {$this->table}";
            if (!empty($where)) {
                $sql_count .= " WHERE " . implode(' AND ', $where);
            }
            
            $total = $this->db->fetchOne($sql_count, $params);
            $total_registros = $total['total'] ?? 0;
            
            return [
                'regioes' => $regioes,
                'total' => $total_registros,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => ceil($total_registros / $limite)
            ];
            
        } catch (Exception $e) {
            error_log("Erro ao listar regiões: " . $e->getMessage());
            return [
                'regioes' => [],
                'total' => 0,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => 0
            ];
        }
    }
    
    /**
     * Lista regiões por estado
     */
    public function listarPorEstado($estado) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE estado = :estado AND ativo = 1 ORDER BY nome ASC";
            return $this->db->fetchAll($sql, [':estado' => $estado]);
        } catch (Exception $e) {
            error_log("Erro ao listar regiões por estado: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lista regiões em destaque
     */
    public function listarDestaques($limite = 6) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE destaque = 1 AND ativo = 1 ORDER BY estado ASC, nome ASC LIMIT {$limite}";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Erro ao listar regiões em destaque: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lista todos os estados disponíveis
     */
    public function listarEstados() {
        try {
            $sql = "SELECT DISTINCT estado FROM {$this->table} WHERE ativo = 1 ORDER BY estado ASC";
            $resultado = $this->db->fetchAll($sql);
            return array_column($resultado, 'estado');
        } catch (Exception $e) {
            error_log("Erro ao listar estados: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Remove uma região
     */
    public function remover($id) {
        try {
            if (empty($id)) {
                throw new Exception("ID da região é obrigatório");
            }
            
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $rows = $this->db->delete($sql, [':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Região removida com sucesso!'
                ];
            } else {
                throw new Exception("Região não encontrada");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Altera status de destaque
     */
    public function alterarDestaque($id, $destaque) {
        try {
            if (empty($id)) {
                throw new Exception("ID da região é obrigatório");
            }
            
            $sql = "UPDATE {$this->table} SET destaque = :destaque WHERE id = :id";
            $rows = $this->db->update($sql, [':destaque' => $destaque, ':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Status de destaque alterado com sucesso!'
                ];
            } else {
                throw new Exception("Região não encontrada");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Altera status ativo
     */
    public function alterarStatus($id, $ativo) {
        try {
            if (empty($id)) {
                throw new Exception("ID da região é obrigatório");
            }
            
            $sql = "UPDATE {$this->table} SET ativo = :ativo WHERE id = :id";
            $rows = $this->db->update($sql, [':ativo' => $ativo, ':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Status alterado com sucesso!'
                ];
            } else {
                throw new Exception("Região não encontrada");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Conta total de regiões
     */
    public function contarTotal($filtros = []) {
        try {
            $where = [];
            $params = [];
            
            if (isset($filtros['ativo'])) {
                $where[] = "ativo = :ativo";
                $params[':ativo'] = $filtros['ativo'];
            }
            
            if (isset($filtros['destaque'])) {
                $where[] = "destaque = :destaque";
                $params[':destaque'] = $filtros['destaque'];
            }
            
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            
            $resultado = $this->db->fetchOne($sql, $params);
            return $resultado['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("Erro ao contar regiões: " . $e->getMessage());
            return 0;
        }
    }
}
?>
