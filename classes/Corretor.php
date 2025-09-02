<?php
/**
 * Classe Corretor - Gerenciamento de Corretores
 * Sistema Br2Studios
 */

require_once 'Database.php';

class Corretor {
    private $db;
    private $table = 'corretores';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Cadastra um novo corretor
     */
    public function cadastrar($dados) {
        try {
            // Validações básicas
            if (empty($dados['nome']) || empty($dados['email'])) {
                throw new Exception("Nome e email são obrigatórios");
            }
            
            if (!$this->db->validateEmail($dados['email'])) {
                throw new Exception("Email inválido");
            }
            
            // Verificar se email já existe
            $existente = $this->buscarPorEmail($dados['email']);
            if ($existente) {
                throw new Exception("Email já cadastrado");
            }
            
            // Preparar dados para inserção
            $campos = ['nome', 'email', 'telefone', 'creci', 'foto', 'bio'];
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
                    'message' => 'Corretor cadastrado com sucesso!',
                    'id' => $id
                ];
            } else {
                throw new Exception("Erro ao cadastrar corretor");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualiza dados de um corretor
     */
    public function atualizar($id, $dados) {
        try {
            if (empty($id)) {
                throw new Exception("ID do corretor é obrigatório");
            }
            
            // Verificar se corretor existe
            $existente = $this->buscarPorId($id);
            if (!$existente) {
                throw new Exception("Corretor não encontrado");
            }
            
            // Verificar se email já existe em outro corretor
            if (isset($dados['email']) && $dados['email'] !== $existente['email']) {
                $outro = $this->buscarPorEmail($dados['email']);
                if ($outro && $outro['id'] != $id) {
                    throw new Exception("Email já cadastrado para outro corretor");
                }
            }
            
            // Preparar dados para atualização
            $campos = ['nome', 'email', 'telefone', 'creci', 'foto', 'bio'];
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
                    'message' => 'Corretor atualizado com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao atualizar corretor");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Busca corretor por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            return $this->db->fetchOne($sql, [':id' => $id]);
        } catch (Exception $e) {
            error_log("Erro ao buscar corretor por ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca corretor por email
     */
    public function buscarPorEmail($email) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = :email";
            return $this->db->fetchOne($sql, [':email' => $email]);
        } catch (Exception $e) {
            error_log("Erro ao buscar corretor por email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca corretor por CPF/CNPJ
     */
    public function buscarPorCpfCnpj($cpfCnpj) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE cpf_cnpj = :cpf_cnpj";
            return $this->db->fetchOne($sql, [':cpf_cnpj' => $cpfCnpj]);
        } catch (Exception $e) {
            error_log("Erro ao buscar corretor por CPF/CNPJ: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista todos os corretores
     */
    public function listarTodos($filtros = [], $pagina = 1, $limite = 20) {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];
            $where = [];
            
            // Aplicar filtros
            if (!empty($filtros['nome'])) {
                $where[] = "nome LIKE :nome";
                $params[':nome'] = '%' . $filtros['nome'] . '%';
            }
            
            if (!empty($filtros['cidade'])) {
                $where[] = "cidade LIKE :cidade";
                $params[':cidade'] = '%' . $filtros['cidade'] . '%';
            }
            
            if (!empty($filtros['estado'])) {
                $where[] = "estado = :estado";
                $params[':estado'] = $filtros['estado'];
            }
            
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            
            $sql .= " ORDER BY nome ASC";
            
            // Paginação
            if ($pagina > 1) {
                $offset = ($pagina - 1) * $limite;
                $sql .= " LIMIT $limite OFFSET $offset";
            } else {
                $sql .= " LIMIT $limite";
            }
            
            return $this->db->fetchAll($sql, $params);
            
        } catch (Exception $e) {
            error_log("Erro ao listar corretores: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lista corretores ativos (todos, já que não há coluna status)
     */
    public function listarAtivos() {
        return $this->listarTodos();
    }
    
    /**
     * Altera status do corretor (não aplicável - tabela não tem status)
     */
    public function alterarStatus($id, $status) {
        return [
            'success' => false,
            'message' => 'Funcionalidade não disponível - tabela não possui coluna status'
        ];
    }
    
    /**
     * Remove um corretor
     */
    public function remover($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $rows = $this->db->delete($sql, [':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Corretor removido com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao remover corretor");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Busca corretores por região (sem status)
     */
    public function buscarPorRegiao($estado, $cidade = null) {
        $sql = "SELECT * FROM {$this->table} WHERE estado = :estado";
        $params = [':estado' => $estado];
        
        if ($cidade) {
            $sql .= " AND cidade = :cidade";
            $params[':cidade'] = $cidade;
        }
        
        $sql .= " ORDER BY nome ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Conta total de corretores
     */
    public function contarTotal($status = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $params = [];
            
            // Como não há coluna status, ignoramos o parâmetro
            // mas mantemos a compatibilidade com o código existente
            
            $result = $this->db->fetchOne($sql, $params);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Erro ao contar corretores: " . $e->getMessage());
            return 0;
        }
    }
}
?>
