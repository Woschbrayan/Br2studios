<?php
// Incluir configurações antes de iniciar a sessão
require_once '../config/php_limits.php';
require_once '../config/database.php';

session_start();

// Verificar se está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/Database.php';
require_once '../classes/Regiao.php';
require_once '../classes/FileUpload.php';

$mensagem = '';
$tipo_mensagem = '';

try {
    $regiao = new Regiao();
    $file_upload = new FileUpload('../uploads/');
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
            'estado' => trim($_POST['estado'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'caracteristicas' => trim($_POST['caracteristicas'] ?? ''),
            'vantagens' => trim($_POST['vantagens'] ?? ''),
            'status' => trim($_POST['status'] ?? 'ativo'),
            'destaque' => isset($_POST['destaque']) ? 1 : 0
        ];
        
        // Processar upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $upload_result = $file_upload->uploadImage($_FILES['imagem'], 'regioes');
            
            if ($upload_result['success']) {
                $dados['imagem'] = $upload_result['url'];
            } else {
                $mensagem = $upload_result['message'];
                $tipo_mensagem = 'error';
            }
        } elseif ($action === 'editar' && !empty($_POST['imagem_atual'])) {
            // Manter imagem atual se não foi enviada nova
            $dados['imagem'] = $_POST['imagem_atual'];
        }
        
        if (empty($mensagem)) { // Só processa se não houve erro no upload
            try {
                if ($action === 'editar') {
                    $id = $_POST['id'] ?? '';
                    $resultado = $regiao->atualizar($id, $dados);
                } else {
                    $resultado = $regiao->cadastrar($dados);
                }
                
                if ($resultado['success']) {
                    $mensagem = $resultado['message'];
                    $tipo_mensagem = 'success';
                    $action = 'listar';
                } else {
                    $mensagem = $resultado['message'];
                    $tipo_mensagem = 'error';
                }
            } catch (Exception $e) {
                $mensagem = "Erro no cadastro/edição: " . $e->getMessage();
                $tipo_mensagem = 'error';
            }
        }
    }
}

if ($action === 'excluir' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Buscar região para remover imagem
        $regiao_info = $regiao->buscarPorId($id);
        if ($regiao_info && !empty($regiao_info['imagem'])) {
            // Remover arquivo físico se existir
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $regiao_info['imagem'];
            if (file_exists($file_path)) {
                $file_upload->removeFile($file_path);
            }
        }
        
        $resultado = $regiao->remover($id);
        
        if ($resultado['success']) {
            $mensagem = $resultado['message'];
            $tipo_mensagem = 'success';
        } else {
            $mensagem = $resultado['message'];
            $tipo_mensagem = 'error';
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao excluir: " . $e->getMessage();
        $tipo_mensagem = 'error';
    }
}

// Buscar dados para edição
$regiao_edicao = null;
if ($action === 'editar' && isset($_GET['id'])) {
    try {
        $regiao_edicao = $regiao->buscarPorId($_GET['id']);
        if (!$regiao_edicao) {
            $mensagem = "Região não encontrada";
            $tipo_mensagem = 'error';
            $action = 'listar';
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar região: " . $e->getMessage();
        $tipo_mensagem = 'error';
        $action = 'listar';
    }
}

// Buscar lista de regiões
$regioes = [];
if ($action === 'listar') {
    try {
        $regioes_result = $regiao->listarTodos();
        if (is_array($regioes_result) && isset($regioes_result['regioes'])) {
            $regioes = $regioes_result;
        } elseif (is_array($regioes_result)) {
            $regioes = ['regioes' => $regioes_result];
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar regiões: " . $e->getMessage();
        $tipo_mensagem = 'error';
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

// Status
$status_options = ['ativo' => 'Ativo', 'inativo' => 'Inativo'];

// Definir variáveis para o header
$page_title = 'Gerenciar Regiões - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Gerenciar Regiões</h1>
            <p class="page-subtitle">Cadastre e gerencie as regiões de atuação</p>
        </div>
        
        <div class="header-actions">
            <?php if ($action === 'listar'): ?>
                <a href="?action=novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nova Região
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
        <!-- Lista de Regiões -->
        <div class="table-container">
            <div class="table-header">
                <h3>Regiões Cadastradas</h3>
            </div>
            
            <?php if (!empty($regioes['regioes'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Região</th>
                            <th>Estado</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Destaque</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($regioes['regioes'] as $regiao_item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($regiao_item['imagem']): ?>
                                            <img src="<?php echo htmlspecialchars($regiao_item['imagem']); ?>" 
                                                 alt="Região" style="width: 60px; height: 40px; border-radius: 8px; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 60px; height: 40px; border-radius: 8px; background: #666; display: flex; align-items: center; justify-content: center; color: white;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($regiao_item['nome']); ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="background: #f8f9fa; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; color: #666;">
                                        <?php echo htmlspecialchars($estados[$regiao_item['estado']] ?? $regiao_item['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        <p style="margin: 0; font-size: 0.9rem; line-height: 1.4;">
                                            <?php echo htmlspecialchars(substr($regiao_item['descricao'], 0, 100)); ?>
                                            <?php if (strlen($regiao_item['descricao']) > 100): ?>...<?php endif; ?>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $regiao_item['status']; ?>" 
                                          style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                        <?php echo ucfirst($regiao_item['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($regiao_item['destaque']): ?>
                                        <span class="destaque-badge" style="background: #ffc107; color: #333; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem;">Destaque</span>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 0.8rem;">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($regiao_item['data_cadastro'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?action=editar&id=<?php echo $regiao_item['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=excluir&id=<?php echo $regiao_item['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja excluir esta região?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state" style="padding: 40px; text-align: center;">
                    <i class="fas fa-map-marker-alt" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>Nenhuma região cadastrada</p>
                    <a href="?action=novo" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeira Região
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?php echo $action === 'editar' ? 'Editar Região' : 'Cadastrar Nova Região'; ?></h2>
            <p>Preencha os dados da <?php echo $action === 'editar' ? 'região' : 'nova região'; ?></p>
            
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $regiao_edicao['id']; ?>">
                    <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($regiao_edicao['imagem'] ?? ''); ?>">
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome da Região *</label>
                        <input type="text" id="nome" name="nome" class="form-control" required
                               value="<?php echo htmlspecialchars($regiao_edicao['nome'] ?? ''); ?>"
                               placeholder="Ex: Zona Sul, Centro, Barra da Tijuca...">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado" class="form-label">Estado *</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="">Selecione um estado</option>
                            <?php foreach ($estados as $sigla => $nome): ?>
                                <option value="<?php echo $sigla; ?>" 
                                        <?php echo ($regiao_edicao['estado'] ?? '') === $sigla ? 'selected' : ''; ?>>
                                    <?php echo $nome; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição da Região *</label>
                    <textarea id="descricao" name="descricao" class="form-control form-textarea" rows="3" required
                              placeholder="Descreva a região, localização, características gerais..."><?php echo htmlspecialchars($regiao_edicao['descricao'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="caracteristicas" class="form-label">Características</label>
                    <textarea id="caracteristicas" name="caracteristicas" class="form-control form-textarea" rows="3"
                              placeholder="Liste as principais características da região..."><?php echo htmlspecialchars($regiao_edicao['caracteristicas'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="vantagens" class="form-label">Vantagens</label>
                    <textarea id="vantagens" name="vantagens" class="form-control form-textarea" rows="3"
                              placeholder="Descreva as vantagens de investir nesta região..."><?php echo htmlspecialchars($regiao_edicao['vantagens'] ?? ''); ?></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <?php foreach ($status_options as $value => $label): ?>
                                <option value="<?php echo $value; ?>" 
                                        <?php echo ($regiao_edicao['status'] ?? 'ativo') === $value ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="imagem" class="form-label">Imagem da Região</label>
                        <input type="file" id="imagem" name="imagem" class="form-control" accept="image/*">
                        <?php if ($action === 'editar' && !empty($regiao_edicao['imagem'])): ?>
                            <small style="color: #666; margin-top: 5px; display: block;">
                                Imagem atual: <?php echo basename($regiao_edicao['imagem']); ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="destaque" value="1" 
                               <?php echo ($regiao_edicao['destaque'] ?? 0) ? 'checked' : ''; ?>>
                        <span>Marcar como destaque</span>
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
