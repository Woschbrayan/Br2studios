<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir configurações
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/CategoriaImovel.php';

$mensagem = '';
$tipo_mensagem = '';

try {
    $categoria = new CategoriaImovel();
} catch (Exception $e) {
    $mensagem = "Erro ao inicializar classes: " . $e->getMessage();
    $tipo_mensagem = 'error';
}

// Processar ações
$action = $_GET['action'] ?? 'listar';

if ($_POST) {
    if ($action === 'novo' || $action === 'editar') {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'icone' => trim($_POST['icone'] ?? 'fas fa-check'),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];
        
        if (empty($dados['nome'])) {
            $mensagem = "Nome da categoria é obrigatório";
            $tipo_mensagem = 'error';
        } else {
            try {
                if ($action === 'editar') {
                    $id = $_POST['id'] ?? '';
                    $resultado = $categoria->atualizar($id, $dados['nome'], $dados['descricao'], $dados['icone'], $dados['ativo']);
                    if ($resultado) {
                        $mensagem = "Categoria atualizada com sucesso!";
                        $tipo_mensagem = 'success';
                        $action = 'listar';
                    }
                } else {
                    $id = $categoria->cadastrar($dados['nome'], $dados['descricao'], $dados['icone']);
                    if ($id) {
                        $mensagem = "Categoria cadastrada com sucesso!";
                        $tipo_mensagem = 'success';
                        $action = 'listar';
                    }
                }
            } catch (Exception $e) {
                $mensagem = "Erro: " . $e->getMessage();
                $tipo_mensagem = 'error';
            }
        }
    } elseif ($action === 'excluir' && isset($_POST['id'])) {
        try {
            $id = $_POST['id'];
            $resultado = $categoria->deletar($id);
                    if ($resultado) {
                $mensagem = "Categoria excluída com sucesso!";
                        $tipo_mensagem = 'success';
            }
        } catch (Exception $e) {
            $mensagem = "Erro ao excluir categoria: " . $e->getMessage();
            $tipo_mensagem = 'error';
        }
        $action = 'listar';
    }
}

// Buscar dados para edição
if ($action === 'editar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $categoria_edicao = $categoria->buscarPorId($id);
    
    if (!$categoria_edicao) {
        $mensagem = "Categoria não encontrada";
        $tipo_mensagem = 'error';
        $action = 'listar';
    }
}

// Buscar categorias para listagem
if ($action === 'listar') {
    try {
        $categorias = $categoria->listarTodas();
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar categorias: " . $e->getMessage();
        $tipo_mensagem = 'error';
        $categorias = [];
    }
}

// Estados brasileiros
$estados = [
    'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
    'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
    'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
    'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
    'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
];

// Definir variáveis para o header
$page_title = 'Gerenciar Categorias - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Gerenciar Categorias</h1>
            <p class="page-subtitle">Cadastre e gerencie as categorias/características dos imóveis</p>
        </div>
        
        <div class="header-actions">
            <?php if ($action === 'listar'): ?>
                <a href="?action=novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nova Categoria
                </a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Voltar
                </a>
            <?php endif; ?>
        </div>
        </div>
        
        <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem === 'success' ? 'success' : 'error'; ?>">
            <i class="fas fa-<?php echo $tipo_mensagem === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
        <?php endif; ?>
        
    <?php if ($action === 'listar'): ?>
        <!-- Lista de Categorias -->
        <div class="table-container">
            <div class="table-header">
                <h3>Categorias Cadastradas</h3>
            </div>
            
            <?php if (!empty($categorias)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ícone</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td>
                                    <i class="<?php echo htmlspecialchars($cat['icone']); ?>" style="font-size: 1.2rem; color: #666;"></i>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($cat['nome']); ?></strong>
                            </td>
                                <td>
                                    <?php if (!empty($cat['descricao'])): ?>
                                        <?php echo htmlspecialchars($cat['descricao']); ?>
                                    <?php else: ?>
                                        <span style="color: #999;">Sem descrição</span>
                                    <?php endif; ?>
                            </td>
                            <td>
                                    <?php if ($cat['ativo']): ?>
                                        <span class="status-badge status-disponivel" style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                            Ativa
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-vendido" style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                            Inativa
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($cat['created_at'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?action=editar&id=<?php echo $cat['id']; ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                            <button type="submit" name="action" value="excluir" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                                        </form>
                                    </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state" style="padding: 40px; text-align: center;">
                    <i class="fas fa-tags" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>Nenhuma categoria cadastrada</p>
                    <a href="?action=novo" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeira Categoria
                    </a>
            </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?php echo $action === 'editar' ? 'Editar Categoria' : 'Cadastrar Nova Categoria'; ?></h2>
            <p>Preencha os dados da <?php echo $action === 'editar' ? 'categoria' : 'nova categoria'; ?></p>
            
            <form method="POST" style="margin-top: 20px;">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $categoria_edicao['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nome" class="form-label">Nome da Categoria *</label>
                    <input type="text" id="nome" name="nome" class="form-control" required
                           value="<?php echo htmlspecialchars($categoria_edicao['nome'] ?? ''); ?>"
                           placeholder="Ex: Academia, Piscina, Churrasqueira...">
                </div>
                
                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control form-textarea" rows="3"
                              placeholder="Descreva brevemente esta característica..."><?php echo htmlspecialchars($categoria_edicao['descricao'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="icone" class="form-label">Ícone da Categoria</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="icone" name="icone" class="form-control" readonly
                               value="<?php echo htmlspecialchars($categoria_edicao['icone'] ?? 'fas fa-check'); ?>"
                               placeholder="Clique no botão para selecionar um ícone">
                        <button type="button" class="btn btn-secondary" onclick="abrirSeletorIcones()">
                            <i class="fas fa-icons"></i>
                            Selecionar Ícone
                        </button>
                    </div>
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Clique no botão para escolher um ícone visualmente
                    </small>
                </div>

                <!-- Modal de Seleção de Ícones -->
                <div id="modalIcones" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
                    <div class="modal-content" style="background-color: white; margin: 5% auto; padding: 20px; border-radius: 10px; width: 90%; max-width: 800px; max-height: 80vh; overflow-y: auto;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 style="margin: 0; color: #333;">
                                <i class="fas fa-icons"></i>
                                Seletor de Ícones
                            </h3>
                            <button type="button" onclick="fecharModalIcones()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <input type="text" id="buscaIcone" placeholder="Buscar ícone..." 
                                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                        </div>
                        
                        <div id="categoriasIcones">
                            <!-- Ícones de Áreas Comuns -->
                            <div class="categoria-icones">
                                <h4 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 5px; margin-bottom: 15px;">
                                    <i class="fas fa-building"></i> Áreas Comuns
                                </h4>
                                <div class="grid-icones">
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-dumbbell')" data-nome="Academia">
                                        <i class="fas fa-dumbbell"></i>
                                        <span>Academia</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-fire')" data-nome="Churrasqueira">
                                        <i class="fas fa-fire"></i>
                                        <span>Churrasqueira</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-swimming-pool')" data-nome="Piscina">
                                        <i class="fas fa-swimming-pool"></i>
                                        <span>Piscina</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-glass-cheers')" data-nome="Salão de Festas">
                                        <i class="fas fa-glass-cheers"></i>
                                        <span>Salão de Festas</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-seedling')" data-nome="Jardim">
                                        <i class="fas fa-seedling"></i>
                                        <span>Jardim</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-child')" data-nome="Playground">
                                        <i class="fas fa-child"></i>
                                        <span>Playground</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-utensils')" data-nome="Espaço Gourmet">
                                        <i class="fas fa-utensils"></i>
                                        <span>Espaço Gourmet</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-tshirt')" data-nome="Lavanderia">
                                        <i class="fas fa-tshirt"></i>
                                        <span>Lavanderia</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-spa')" data-nome="Spa">
                                        <i class="fas fa-spa"></i>
                                        <span>Spa</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-basketball-ball')" data-nome="Quadra Esportiva">
                                        <i class="fas fa-basketball-ball"></i>
                                        <span>Quadra Esportiva</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-gamepad')" data-nome="Sala de Jogos">
                                        <i class="fas fa-gamepad"></i>
                                        <span>Sala de Jogos</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-book')" data-nome="Biblioteca">
                                        <i class="fas fa-book"></i>
                                        <span>Biblioteca</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ícones de Infraestrutura -->
                            <div class="categoria-icones">
                                <h4 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 5px; margin-bottom: 15px;">
                                    <i class="fas fa-cogs"></i> Infraestrutura
                                </h4>
                                <div class="grid-icones">
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-arrow-up')" data-nome="Elevador">
                                        <i class="fas fa-arrow-up"></i>
                                        <span>Elevador</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-user-shield')" data-nome="Portaria 24h">
                                        <i class="fas fa-user-shield"></i>
                                        <span>Portaria 24h</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-shield-alt')" data-nome="Segurança 24h">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Segurança 24h</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-car')" data-nome="Vagas de Garagem">
                                        <i class="fas fa-car"></i>
                                        <span>Vagas de Garagem</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-parking')" data-nome="Estacionamento">
                                        <i class="fas fa-parking"></i>
                                        <span>Estacionamento</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-lock')" data-nome="Condomínio Fechado">
                                        <i class="fas fa-lock"></i>
                                        <span>Condomínio Fechado</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-video')" data-nome="Câmeras de Segurança">
                                        <i class="fas fa-video"></i>
                                        <span>Câmeras de Segurança</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-bell')" data-nome="Alarme">
                                        <i class="fas fa-bell"></i>
                                        <span>Alarme</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-phone')" data-nome="Interfone">
                                        <i class="fas fa-phone"></i>
                                        <span>Interfone</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ícones de Características do Imóvel -->
                            <div class="categoria-icones">
                                <h4 style="color: #2c3e50; border-bottom: 2px solid #f39c12; padding-bottom: 5px; margin-bottom: 15px;">
                                    <i class="fas fa-home"></i> Características do Imóvel
                                </h4>
                                <div class="grid-icones">
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-laptop-house')" data-nome="Home Office">
                                        <i class="fas fa-laptop-house"></i>
                                        <span>Home Office</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-umbrella-beach')" data-nome="Varanda Gourmet">
                                        <i class="fas fa-umbrella-beach"></i>
                                        <span>Varanda Gourmet</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-door-open')" data-nome="Sacada">
                                        <i class="fas fa-door-open"></i>
                                        <span>Sacada</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-paw')" data-nome="Aceita Pets">
                                        <i class="fas fa-paw"></i>
                                        <span>Aceita Pets</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-couch')" data-nome="Mobiliado">
                                        <i class="fas fa-couch"></i>
                                        <span>Mobiliado</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-snowflake')" data-nome="Ar Condicionado">
                                        <i class="fas fa-snowflake"></i>
                                        <span>Ar Condicionado</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-thermometer-half')" data-nome="Aquecimento">
                                        <i class="fas fa-thermometer-half"></i>
                                        <span>Aquecimento</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-wifi')" data-nome="Internet">
                                        <i class="fas fa-wifi"></i>
                                        <span>Internet</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-tv')" data-nome="TV a Cabo">
                                        <i class="fas fa-tv"></i>
                                        <span>TV a Cabo</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ícones de Acessibilidade e Outros -->
                            <div class="categoria-icones">
                                <h4 style="color: #2c3e50; border-bottom: 2px solid #9b59b6; padding-bottom: 5px; margin-bottom: 15px;">
                                    <i class="fas fa-plus-circle"></i> Outros
                                </h4>
                                <div class="grid-icones">
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-wheelchair')" data-nome="Acessibilidade">
                                        <i class="fas fa-wheelchair"></i>
                                        <span>Acessibilidade</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-tree')" data-nome="Área Verde">
                                        <i class="fas fa-tree"></i>
                                        <span>Área Verde</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-check')" data-nome="Check">
                                        <i class="fas fa-check"></i>
                                        <span>Check</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-star')" data-nome="Estrela">
                                        <i class="fas fa-star"></i>
                                        <span>Estrela</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-heart')" data-nome="Coração">
                                        <i class="fas fa-heart"></i>
                                        <span>Coração</span>
                                    </div>
                                    <div class="icone-item" onclick="selecionarIcone('fas fa-thumbs-up')" data-nome="Like">
                                        <i class="fas fa-thumbs-up"></i>
                                        <span>Like</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-top: 20px;">
                            <button type="button" onclick="fecharModalIcones()" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>

                <style>
                .grid-icones {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                    gap: 15px;
                    margin-bottom: 30px;
                }
                
                .icone-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 15px;
                    border: 2px solid #e0e0e0;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    background: white;
                    text-align: center;
                }
                
                .icone-item:hover {
                    border-color: #3498db;
                    background: #e3f2fd;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                
                .icone-item i {
                    font-size: 24px;
                    color: #2c3e50;
                    margin-bottom: 8px;
                }
                
                .icone-item span {
                    font-size: 0.8rem;
                    color: #666;
                    font-weight: 500;
                }
                
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.5);
                }
                
                .modal-content {
                    background-color: white;
                    margin: 5% auto;
                    padding: 20px;
                    border-radius: 10px;
                    width: 90%;
                    max-width: 800px;
                    max-height: 80vh;
                    overflow-y: auto;
                }
                
                .categoria-icones {
                    margin-bottom: 30px;
                }
                
                .categoria-icones h4 {
                    color: #2c3e50;
                    border-bottom: 2px solid #3498db;
                    padding-bottom: 5px;
                    margin-bottom: 15px;
                }
                
                .categoria-icones:nth-child(2) h4 {
                    border-bottom-color: #e74c3c;
                }
                
                .categoria-icones:nth-child(3) h4 {
                    border-bottom-color: #f39c12;
                }
                
                .categoria-icones:nth-child(4) h4 {
                    border-bottom-color: #9b59b6;
                }
                </style>

                <script>
                function abrirSeletorIcones() {
                    document.getElementById('modalIcones').style.display = 'block';
                    document.getElementById('buscaIcone').focus();
                }
                
                function fecharModalIcones() {
                    document.getElementById('modalIcones').style.display = 'none';
                    document.getElementById('buscaIcone').value = '';
                    filtrarIcones();
                }
                
                function selecionarIcone(icone) {
                    document.getElementById('icone').value = icone;
                    fecharModalIcones();
                }
                
                // Filtro de busca
                document.getElementById('buscaIcone').addEventListener('input', function() {
                    filtrarIcones();
                });
                
                function filtrarIcones() {
                    const busca = document.getElementById('buscaIcone').value.toLowerCase();
                    const icones = document.querySelectorAll('.icone-item');
                    
                    icones.forEach(icone => {
                        const nome = icone.getAttribute('data-nome').toLowerCase();
                        if (nome.includes(busca)) {
                            icone.style.display = 'flex';
                        } else {
                            icone.style.display = 'none';
                        }
                    });
                }
                
                // Fechar modal ao clicar fora
                window.onclick = function(event) {
                    const modal = document.getElementById('modalIcones');
                    if (event.target === modal) {
                        fecharModalIcones();
                    }
                }
                </script>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="ativo" value="1" 
                               <?php echo ($categoria_edicao['ativo'] ?? 1) ? 'checked' : ''; ?>>
                        <span>Categoria ativa</span>
                    </label>
                </div>
                
                <div class="form-actions" style="margin-top: 30px; display: flex; gap: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <?php echo $action === 'editar' ? 'Atualizar' : 'Cadastrar'; ?>
                    </button>
                    <a href="?action=listar" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
