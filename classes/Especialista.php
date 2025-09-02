<?php
/**
 * Classe Especialista - Gerenciamento de Especialistas da Empresa
 * Sistema Br2Studios
 */

require_once 'Database.php';

class Especialista {
    private $db;
    private $table = 'especialistas';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Cadastra um novo especialista
     */
    public function cadastrar($dados) {
        try {
            // Validações básicas
            if (empty($dados['nome']) || empty($dados['cargo'])) {
                throw new Exception("Nome e cargo são obrigatórios");
            }
            
            // Preparar dados para inserção
            $campos = ['nome', 'cargo', 'especialidade', 'experiencia', 'bio', 'foto', 'email', 'telefone', 'linkedin', 'destaque'];
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
                    'message' => 'Especialista cadastrado com sucesso!',
                    'id' => $id
                ];
            } else {
                throw new Exception("Erro ao cadastrar especialista");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualiza dados de um especialista
     */
    public function atualizar($id, $dados) {
        try {
            if (empty($id)) {
                throw new Exception("ID do especialista é obrigatório");
            }
            
            // Verificar se especialista existe
            $existente = $this->buscarPorId($id);
            if (!$existente) {
                throw new Exception("Especialista não encontrado");
            }
            
            // Preparar dados para atualização
            $campos = ['nome', 'cargo', 'especialidade', 'experiencia', 'bio', 'foto', 'email', 'telefone', 'linkedin', 'destaque'];
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
                    'message' => 'Especialista atualizado com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao atualizar especialista");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Busca especialista por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            return $this->db->fetchOne($sql, [':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro ao buscar especialista por ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista todos os especialistas
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
            
            if (isset($filtros['especialidade']) && !empty($filtros['especialidade'])) {
                $where[] = "especialidade = :especialidade";
                $params[':especialidade'] = $filtros['especialidade'];
            }
            
            $sql = "SELECT * FROM {$this->table}";
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            $sql .= " ORDER BY destaque DESC, nome ASC";
            
            // Paginação
            $offset = ($pagina - 1) * $limite;
            $sql .= " LIMIT {$limite} OFFSET {$offset}";
            
            $especialistas = $this->db->fetchAll($sql, $params);
            
            // Contar total para paginação
            $sql_count = "SELECT COUNT(*) as total FROM {$this->table}";
            if (!empty($where)) {
                $sql_count .= " WHERE " . implode(' AND ', $where);
            }
            
            $total = $this->db->fetchOne($sql_count, $params);
            $total_registros = $total['total'] ?? 0;
            
            return [
                'especialistas' => $especialistas,
                'total' => $total_registros,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => ceil($total_registros / $limite)
            ];
            
        } catch (Exception $e) {
            error_log("Erro ao listar especialistas: " . $e->getMessage());
            return [
                'especialistas' => [],
                'total' => 0,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => 0
            ];
        }
    }
    
    /**
     * Lista especialistas em destaque
     */
    public function listarDestaques($limite = 6) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE destaque = 1 ORDER BY nome ASC LIMIT {$limite}";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Erro ao listar especialistas em destaque: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Remove um especialista
     */
    public function remover($id) {
        try {
            if (empty($id)) {
                throw new Exception("ID do especialista é obrigatório");
            }
            
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $rows = $this->db->delete($sql, [':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Especialista removido com sucesso!'
                ];
            } else {
                throw new Exception("Especialista não encontrado");
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
                throw new Exception("ID do especialista é obrigatório");
            }
            
            $sql = "UPDATE {$this->table} SET destaque = :destaque WHERE id = :id";
            $rows = $this->db->update($sql, [':destaque' => $destaque, ':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Status de destaque alterado com sucesso!'
                ];
            } else {
                throw new Exception("Especialista não encontrado");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Conta total de especialistas
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
            error_log("Erro ao contar especialistas: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lista especialidades únicas
     */
    public function listarEspecialidades() {
        try {
            $sql = "SELECT DISTINCT especialidade FROM {$this->table} WHERE especialidade IS NOT NULL AND especialidade != '' ORDER BY especialidade ASC";
            $resultado = $this->db->fetchAll($sql);
            return array_column($resultado, 'especialidade');
        } catch (Exception $e) {
            error_log("Erro ao listar especialidades: " . $e->getMessage());
            return [];
        }
    }
}
?>
