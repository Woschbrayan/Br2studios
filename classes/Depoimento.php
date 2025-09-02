<?php
/**
 * Classe Depoimento - Gerenciamento de Depoimentos de Clientes
 * Sistema Br2Studios
 */

require_once 'Database.php';

class Depoimento {
    private $db;
    private $table = 'depoimentos';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Cadastra um novo depoimento
     */
    public function cadastrar($dados) {
        try {
            // Validações básicas
            if (empty($dados['nome']) || empty($dados['depoimento'])) {
                throw new Exception("Nome e depoimento são obrigatórios");
            }
            
            // Preparar dados para inserção
            $campos = ['nome', 'depoimento', 'cidade', 'estado', 'avaliacao', 'foto', 'cargo', 'empresa', 'destaque'];
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
                    'message' => 'Depoimento cadastrado com sucesso!',
                    'id' => $id
                ];
            } else {
                throw new Exception("Erro ao cadastrar depoimento");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualiza dados de um depoimento
     */
    public function atualizar($id, $dados) {
        try {
            if (empty($id)) {
                throw new Exception("ID do depoimento é obrigatório");
            }
            
            // Verificar se depoimento existe
            $existente = $this->buscarPorId($id);
            if (!$existente) {
                throw new Exception("Depoimento não encontrado");
            }
            
            // Preparar dados para atualização
            $campos = ['nome', 'depoimento', 'cidade', 'estado', 'avaliacao', 'foto', 'cargo', 'empresa', 'destaque'];
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
                    'message' => 'Depoimento atualizado com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao atualizar depoimento");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Busca depoimento por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            return $this->db->fetchOne($sql, [':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro ao buscar depoimento por ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista todos os depoimentos
     */
    public function listarTodos($filtros = [], $pagina = 1, $limite = 20) {
        try {
            $where = [];
            $params = [];
            
            // Aplicar filtros
            if (isset($filtros['destaque'])) {
                $where[] = "destaque = :destaque";
                $params[':destaque'] = $filtros['destaque'];
            }
            
            if (isset($filtros['estado']) && !empty($filtros['estado'])) {
                $where[] = "estado = :estado";
                $params[':estado'] = $filtros['estado'];
            }
            
            if (isset($filtros['cidade']) && !empty($filtros['cidade'])) {
                $where[] = "cidade = :cidade";
                $params[':cidade'] = $filtros['cidade'];
            }
            
            $sql = "SELECT * FROM {$this->table}";
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            $sql .= " ORDER BY destaque DESC, data_cadastro DESC";
            
            // Paginação
            $offset = ($pagina - 1) * $limite;
            $sql .= " LIMIT {$limite} OFFSET {$offset}";
            
            $depoimentos = $this->db->fetchAll($sql, $params);
            
            // Contar total para paginação
            $sql_count = "SELECT COUNT(*) as total FROM {$this->table}";
            if (!empty($where)) {
                $sql_count .= " WHERE " . implode(' AND ', $where);
            }
            
            $total = $this->db->fetchOne($sql_count, $params);
            $total_registros = $total['total'] ?? 0;
            
            return [
                'depoimentos' => $depoimentos,
                'total' => $total_registros,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => ceil($total_registros / $limite)
            ];
            
        } catch (Exception $e) {
            error_log("Erro ao listar depoimentos: " . $e->getMessage());
            return [
                'depoimentos' => [],
                'total' => 0,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => 0
            ];
        }
    }
    
    /**
     * Lista depoimentos em destaque
     */
    public function listarDestaques($limite = 6) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE destaque = 1 ORDER BY data_cadastro DESC LIMIT {$limite}";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Erro ao listar depoimentos em destaque: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Remove um depoimento
     */
    public function remover($id) {
        try {
            if (empty($id)) {
                throw new Exception("ID do depoimento é obrigatório");
            }
            
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $rows = $this->db->delete($sql, [':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Depoimento removido com sucesso!'
                ];
            } else {
                throw new Exception("Depoimento não encontrado");
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
                throw new Exception("ID do depoimento é obrigatório");
            }
            
            $sql = "UPDATE {$this->table} SET destaque = :destaque WHERE id = :id";
            $rows = $this->db->update($sql, [':destaque' => $destaque, ':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Status de destaque alterado com sucesso!'
                ];
            } else {
                throw new Exception("Depoimento não encontrado");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Conta total de depoimentos
     */
    public function contarTotal($filtros = []) {
        try {
            $where = [];
            $params = [];
            
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
            error_log("Erro ao contar depoimentos: " . $e->getMessage());
            return 0;
        }
    }
}
?>
