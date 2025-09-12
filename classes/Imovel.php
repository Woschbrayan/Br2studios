<?php
/**
 * Classe Imovel - Gerenciamento de Imóveis
 * Sistema Br2Studios
 */

require_once 'Database.php';

class Imovel {
    private $db;
    private $table = 'imoveis';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Cadastra um novo imóvel
     */
    public function cadastrar($dados) {
        try {
            // Validações básicas
            if (empty($dados['titulo']) || empty($dados['tipo'])) {
                throw new Exception("Título e tipo são obrigatórios");
            }
            
            if (empty($dados['endereco']) || empty($dados['cidade']) || empty($dados['estado']) || empty($dados['preco'])) {
                throw new Exception("Endereço, cidade, estado e preço são obrigatórios");
            }
            
            $sql = "INSERT INTO {$this->table} (
                titulo, descricao, preco, area, quartos, 
                banheiros, vagas, endereco, cidade, estado, cep, tipo, status_construcao, ano_entrega, status, 
                imagem_principal, imagens, caracteristicas, destaque, maior_valorizacao
            ) VALUES (
                :titulo, :descricao, :preco, :area, :quartos,
                :banheiros, :vagas, :endereco, :cidade, :estado, :cep, :tipo, :status_construcao, :ano_entrega, :status,
                :imagem_principal, :imagens, :caracteristicas, :destaque, :maior_valorizacao
            )";
            
            $params = [
                ':titulo' => $dados['titulo'],
                ':descricao' => $dados['descricao'] ?? null,
                ':preco' => $dados['preco'],
                ':area' => $dados['area'] ?? null,
                ':quartos' => $dados['quartos'] ?? 0,
                ':banheiros' => $dados['banheiros'] ?? 0,
                ':vagas' => $dados['vagas'] ?? 0,
                ':endereco' => $dados['endereco'],
                ':cidade' => $dados['cidade'],
                ':estado' => $dados['estado'],
                ':cep' => $dados['cep'] ?? null,
                ':tipo' => $dados['tipo'],
                ':status_construcao' => $dados['status_construcao'] ?? 'pronto',
                ':ano_entrega' => $dados['ano_entrega'] ?? null,
                ':status' => $dados['status'] ?? 'disponivel',
                ':imagem_principal' => $dados['imagem_principal'] ?? null,
                ':imagens' => $dados['imagens'] ?? null,
                ':caracteristicas' => $dados['caracteristicas'] ?? null,
                ':destaque' => $dados['destaque'] ?? 0,
                ':maior_valorizacao' => $dados['maior_valorizacao'] ?? 0
            ];
            
            $id = $this->db->insert($sql, $params);
            
            if ($id) {
                // Processar categorias se fornecidas
                if (!empty($dados['categorias']) && is_array($dados['categorias'])) {
                    $this->atualizarCategorias($id, $dados['categorias']);
                }
                
                return [
                    'success' => true,
                    'message' => 'Imóvel cadastrado com sucesso!',
                    'id' => $id
                ];
            } else {
                throw new Exception("Erro ao cadastrar imóvel");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualiza dados de um imóvel
     */
    public function atualizar($id, $dados) {
        try {
            if (empty($id)) {
                throw new Exception("ID do imóvel é obrigatório");
            }
            
            // Verificar se imóvel existe
            $existente = $this->buscarPorId($id);
            if (!$existente) {
                throw new Exception("Imóvel não encontrado");
            }
            
            // Preparar dados para atualização
            $campos = ['titulo', 'descricao', 'preco', 'area', 'quartos', 'banheiros', 'vagas', 'endereco', 'cidade', 'estado', 'cep', 'tipo', 'status_construcao', 'ano_entrega', 'status', 'imagem_principal', 'imagens', 'caracteristicas', 'destaque', 'maior_valorizacao'];
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
            
            $sets[] = "data_atualizacao = CURRENT_TIMESTAMP";
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";
            
            $rows = $this->db->update($sql, $params);
            
            if ($rows > 0) {
                // Processar categorias se fornecidas
                if (isset($dados['categorias'])) {
                    $this->atualizarCategorias($id, $dados['categorias']);
                }
                
                return [
                    'success' => true,
                    'message' => 'Imóvel atualizado com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao atualizar imóvel");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualiza as categorias de um imóvel
     */
    private function atualizarCategorias($imovel_id, $categorias_ids) {
        try {
            require_once __DIR__ . '/CategoriaImovel.php';
            $categoria = new CategoriaImovel();
            return $categoria->atualizarCategoriasImovel($imovel_id, $categorias_ids);
        } catch (Exception $e) {
            error_log("Erro ao atualizar categorias do imóvel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Busca imóvel por ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $imovel = $this->db->fetchOne($sql, [':id' => $id]);
            
            if ($imovel) {
                // Buscar categorias do imóvel (temporariamente desabilitado para debug)
                try {
                    require_once __DIR__ . '/CategoriaImovel.php';
                    $categoria = new CategoriaImovel();
                    $imovel['categorias'] = $categoria->buscarPorImovel($id);
                } catch (Exception $e) {
                    error_log("Erro ao buscar categorias: " . $e->getMessage());
                    $imovel['categorias'] = [];
                }
            }
            
            return $imovel;
        } catch (Exception $e) {
            error_log("Erro ao buscar imóvel por ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lista todos os imóveis
     */
    public function listarTodos($filtros = [], $pagina = 1, $limite = 20) {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];
            $where = [];
            
            // Aplicar filtros
            if (!empty($filtros['titulo'])) {
                $where[] = "titulo LIKE :titulo";
                $params[':titulo'] = '%' . $filtros['titulo'] . '%';
            }
            
            if (!empty($filtros['tipo'])) {
                $where[] = "tipo = :tipo";
                $params[':tipo'] = $filtros['tipo'];
            }
            
            if (!empty($filtros['status_construcao'])) {
                $where[] = "status_construcao = :status_construcao";
                $params[':status_construcao'] = $filtros['status_construcao'];
            }
            
            if (!empty($filtros['status'])) {
                $where[] = "status = :status";
                $params[':status'] = $filtros['status'];
            }
            
            if (!empty($filtros['cidade'])) {
                $where[] = "cidade LIKE :cidade";
                $params[':cidade'] = '%' . $filtros['cidade'] . '%';
            }
            
            if (!empty($filtros['estado'])) {
                $where[] = "estado = :estado";
                $params[':estado'] = $filtros['estado'];
            }
            
            if (!empty($filtros['preco_min'])) {
                $where[] = "preco >= :preco_min";
                $params[':preco_min'] = $filtros['preco_min'];
            }
            
            if (!empty($filtros['preco_max'])) {
                $where[] = "preco <= :preco_max";
                $params[':preco_max'] = $filtros['preco_max'];
            }
            
            if (isset($filtros['destaque'])) {
                $where[] = "destaque = :destaque";
                $params[':destaque'] = $filtros['destaque'];
            }
            
            if (isset($filtros['maior_valorizacao'])) {
                $where[] = "maior_valorizacao = :maior_valorizacao";
                $params[':maior_valorizacao'] = $filtros['maior_valorizacao'];
            }
            
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            
            $sql .= " ORDER BY data_cadastro DESC";
            
            // Paginação
            if ($pagina > 1) {
                $offset = ($pagina - 1) * $limite;
                $sql .= " LIMIT $limite OFFSET $offset";
            } else {
                $sql .= " LIMIT $limite";
            }
            
            $imoveis = $this->db->fetchAll($sql, $params);
            
            // Contar total para paginação
            $sql_count = "SELECT COUNT(*) as total FROM {$this->table}";
            if (!empty($where)) {
                $sql_count .= " WHERE " . implode(' AND ', $where);
            }
            $total = $this->db->fetchOne($sql_count, $params)['total'] ?? 0;
            
            return [
                'imoveis' => $imoveis,
                'total' => $total,
                'pagina' => $pagina,
                'limite' => $limite,
                'total_paginas' => ceil($total / $limite)
            ];
            
        } catch (Exception $e) {
            error_log("Erro ao listar imóveis: " . $e->getMessage());
            return [
                'imoveis' => [],
                'total' => 0,
                'pagina' => 1,
                'limite' => $limite,
                'total_paginas' => 0
            ];
        }
    }
    
    /**
     * Lista imóveis por tipo
     */
    public function listarPorTipo($tipo) {
        return $this->listarTodos(['tipo' => $tipo]);
    }
    
    /**
     * Lista imóveis por status
     */
    public function listarPorStatus($status) {
        return $this->listarTodos(['status' => $status]);
    }
    
    /**
     * Lista imóveis por região
     */
    public function listarPorRegiao($estado, $cidade = null) {
        $filtros = ['estado' => $estado];
        if ($cidade) {
            $filtros['cidade'] = $cidade;
        }
        return $this->listarTodos($filtros);
    }
    
    /**
     * Lista imóveis em destaque
     */
    public function listarDestaques() {
        return $this->listarTodos(['destaque' => 1]);
    }
    
    /**
     * Lista imóveis de maior valorização
     */
    public function listarMaiorValorizacao() {
        return $this->listarTodos(['maior_valorizacao' => 1]);
    }
    
    /**
     * Busca imóveis por texto
     */
    public function buscarPorTexto($texto) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 
                    titulo LIKE :texto OR 
                    descricao LIKE :texto OR 
                    endereco LIKE :texto OR 
                    cidade LIKE :texto OR 
                    estado LIKE :texto";
            
            $params = [':texto' => '%' . $texto . '%'];
            
            return $this->db->fetchAll($sql, $params);
            
        } catch (Exception $e) {
            error_log("Erro na busca por texto: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Remove um imóvel
     */
    public function remover($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $rows = $this->db->delete($sql, [':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Imóvel removido com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao remover imóvel");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Altera status do imóvel
     */
    public function alterarStatus($id, $status) {
        try {
            $statuses = ['disponivel', 'vendido', 'reservado'];
            if (!in_array($status, $statuses)) {
                throw new Exception("Status inválido");
            }
            
            $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
            $rows = $this->db->update($sql, [':status' => $status, ':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Status alterado com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao alterar status");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Altera destaque do imóvel
     */
    public function alterarDestaque($id, $destaque) {
        try {
            $sql = "UPDATE {$this->table} SET destaque = :destaque WHERE id = :id";
            $rows = $this->db->update($sql, [':destaque' => $destaque ? 1 : 0, ':id' => $id]);
            
            if ($rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Destaque alterado com sucesso!'
                ];
            } else {
                throw new Exception("Erro ao alterar destaque");
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Conta total de imóveis
     */
    public function contarTotal($filtros = []) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $params = [];
            $where = [];
            
            // Aplicar filtros
            if (!empty($filtros['titulo'])) {
                $where[] = "titulo LIKE :titulo";
                $params[':titulo'] = '%' . $filtros['titulo'] . '%';
            }
            
            if (!empty($filtros['tipo'])) {
                $where[] = "tipo = :tipo";
                $params[':tipo'] = $filtros['tipo'];
            }
            
            if (!empty($filtros['status_construcao'])) {
                $where[] = "status_construcao = :status_construcao";
                $params[':status_construcao'] = $filtros['status_construcao'];
            }
            
            if (!empty($filtros['status'])) {
                $where[] = "status = :status";
                $params[':status'] = $filtros['status'];
            }
            
            if (!empty($filtros['cidade'])) {
                $where[] = "cidade = :cidade";
                $params[':cidade'] = $filtros['cidade'];
            }
            
            if (!empty($filtros['estado'])) {
                $where[] = "estado = :estado";
                $params[':estado'] = $filtros['estado'];
            }
            
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            
            $result = $this->db->fetchOne($sql, $params);
            return $result['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("Erro ao contar imóveis: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Busca estatísticas dos imóveis
     */
    public function buscarEstatisticas() {
        try {
            $sql = "SELECT 
                        tipo,
                        status,
                        COUNT(*) as total,
                        AVG(preco) as preco_medio,
                        MIN(preco) as preco_min,
                        MAX(preco) as preco_max
                    FROM {$this->table} 
                    GROUP BY tipo, status";
            
            return $this->db->fetchAll($sql);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar estatísticas: " . $e->getMessage());
            return [];
        }
    }
}
?>
