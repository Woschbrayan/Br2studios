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
require_once '../classes/Depoimento.php';
require_once '../classes/FileUpload.php';

$mensagem = '';
$tipo_mensagem = '';

try {
    $depoimento = new Depoimento();
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
            'depoimento' => trim($_POST['depoimento'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'avaliacao' => floatval($_POST['avaliacao'] ?? 5.0),
            'cargo' => trim($_POST['cargo'] ?? ''),
            'empresa' => trim($_POST['empresa'] ?? ''),
            'destaque' => isset($_POST['destaque']) ? 1 : 0
        ];
        
        // Processar upload de imagem
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload_result = $file_upload->uploadImage($_FILES['foto'], 'depoimentos');
            
            if ($upload_result['success']) {
                $dados['foto'] = $upload_result['url'];
            } else {
                $mensagem = $upload_result['message'];
                $tipo_mensagem = 'error';
            }
        } elseif ($action === 'editar' && !empty($_POST['foto_atual'])) {
            // Manter foto atual se não foi enviada nova
            $dados['foto'] = $_POST['foto_atual'];
        }
        
        if (empty($mensagem)) { // Só processa se não houve erro no upload
            try {
                if ($action === 'editar') {
                    $id = $_POST['id'] ?? '';
                    $resultado = $depoimento->atualizar($id, $dados);
                } else {
                    $resultado = $depoimento->cadastrar($dados);
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
        // Buscar depoimento para remover foto
        $depoimento_info = $depoimento->buscarPorId($id);
        if ($depoimento_info && !empty($depoimento_info['foto'])) {
            // Remover arquivo físico se existir
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $depoimento_info['foto'];
            if (file_exists($file_path)) {
                $file_upload->removeFile($file_path);
            }
        }
        
        $resultado = $depoimento->remover($id);
        
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
$depoimento_edicao = null;
if ($action === 'editar' && isset($_GET['id'])) {
    try {
        $depoimento_edicao = $depoimento->buscarPorId($_GET['id']);
        if (!$depoimento_edicao) {
            $mensagem = "Depoimento não encontrado";
            $tipo_mensagem = 'error';
            $action = 'listar';
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar depoimento: " . $e->getMessage();
        $tipo_mensagem = 'error';
        $action = 'listar';
    }
}

// Buscar lista de depoimentos
$depoimentos = [];
if ($action === 'listar') {
    try {
        $depoimentos_result = $depoimento->listarTodos();
        if (is_array($depoimentos_result) && isset($depoimentos_result['depoimentos'])) {
            $depoimentos = $depoimentos_result;
        } elseif (is_array($depoimentos_result)) {
            $depoimentos = ['depoimentos' => $depoimentos_result];
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar depoimentos: " . $e->getMessage();
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

// Definir variáveis para o header
$page_title = 'Gerenciar Depoimentos - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Gerenciar Depoimentos</h1>
            <p class="page-subtitle">Cadastre e gerencie os depoimentos dos clientes</p>
        </div>
        
        <div class="header-actions">
            <?php if ($action === 'listar'): ?>
                <a href="?action=novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Depoimento
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
        <!-- Lista de Depoimentos -->
        <div class="table-container">
            <div class="table-header">
                <h3>Depoimentos Cadastrados</h3>
            </div>
            
            <?php if (!empty($depoimentos['depoimentos'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Depoimento</th>
                            <th>Localização</th>
                            <th>Avaliação</th>
                            <th>Destaque</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($depoimentos['depoimentos'] as $depoimento_item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($depoimento_item['foto']): ?>
                                            <img src="<?php echo htmlspecialchars($depoimento_item['foto']); ?>" 
                                                 alt="Cliente" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #666; display: flex; align-items: center; justify-content: center; color: white;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($depoimento_item['nome']); ?></strong>
                                            <?php if (!empty($depoimento_item['cargo'])): ?>
                                                <br><small style="color: #666;"><?php echo htmlspecialchars($depoimento_item['cargo']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        <p style="margin: 0; font-size: 0.9rem; line-height: 1.4;">
                                            <?php echo htmlspecialchars(substr($depoimento_item['depoimento'], 0, 100)); ?>
                                            <?php if (strlen($depoimento_item['depoimento']) > 100): ?>...<?php endif; ?>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $cidade = $depoimento_item['cidade'] ?? '';
                                    $estado = $depoimento_item['estado'] ?? '';
                                    
                                    if (!empty($cidade) && !empty($estado)) {
                                        echo htmlspecialchars($cidade . ' - ' . $estado);
                                    } elseif (!empty($cidade)) {
                                        echo htmlspecialchars($cidade);
                                    } elseif (!empty($estado)) {
                                        echo htmlspecialchars($estado);
                                    } else {
                                        echo '<span style="color: #999;">Não informado</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <span style="color: #ffc107; font-weight: 600;"><?php echo number_format($depoimento_item['avaliacao'], 1); ?></span>
                                        <div style="color: #ffc107;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?php echo $i <= $depoimento_item['avaliacao'] ? '' : '-o'; ?>" style="font-size: 0.8rem;"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($depoimento_item['destaque']): ?>
                                        <span class="destaque-badge" style="background: #ffc107; color: #333; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem;">Destaque</span>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 0.8rem;">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($depoimento_item['data_cadastro'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?action=editar&id=<?php echo $depoimento_item['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=excluir&id=<?php echo $depoimento_item['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja excluir este depoimento?')">
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
                    <i class="fas fa-comments" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>Nenhum depoimento cadastrado</p>
                    <a href="?action=novo" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeiro Depoimento
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?php echo $action === 'editar' ? 'Editar Depoimento' : 'Cadastrar Novo Depoimento'; ?></h2>
            <p>Preencha os dados do <?php echo $action === 'editar' ? 'depoimento' : 'novo depoimento'; ?></p>
            
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $depoimento_edicao['id']; ?>">
                    <input type="hidden" name="foto_atual" value="<?php echo htmlspecialchars($depoimento_edicao['foto'] ?? ''); ?>">
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome do Cliente *</label>
                        <input type="text" id="nome" name="nome" class="form-control" required
                               value="<?php echo htmlspecialchars($depoimento_edicao['nome'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cargo" class="form-label">Cargo/Profissão</label>
                        <input type="text" id="cargo" name="cargo" class="form-control"
                               value="<?php echo htmlspecialchars($depoimento_edicao['cargo'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="empresa" class="form-label">Empresa</label>
                    <input type="text" id="empresa" name="empresa" class="form-control"
                           value="<?php echo htmlspecialchars($depoimento_edicao['empresa'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="depoimento" class="form-label">Depoimento *</label>
                    <textarea id="depoimento" name="depoimento" class="form-control form-textarea" rows="4" required
                              placeholder="Digite o depoimento do cliente..."><?php echo htmlspecialchars($depoimento_edicao['depoimento'] ?? ''); ?></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="form-control"
                               value="<?php echo htmlspecialchars($depoimento_edicao['cidade'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="">Selecione um estado</option>
                            <?php foreach ($estados as $sigla => $nome): ?>
                                <option value="<?php echo $sigla; ?>" 
                                        <?php echo ($depoimento_edicao['estado'] ?? '') === $sigla ? 'selected' : ''; ?>>
                                    <?php echo $nome; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="avaliacao" class="form-label">Avaliação (1-5)</label>
                        <select id="avaliacao" name="avaliacao" class="form-control">
                            <?php for ($i = 1; $i <= 5; $i += 0.5): ?>
                                <option value="<?php echo $i; ?>" 
                                        <?php echo ($depoimento_edicao['avaliacao'] ?? 5.0) == $i ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> <?php echo $i == 1 ? 'estrela' : 'estrelas'; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="foto" class="form-label">Foto do Cliente</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <?php if ($action === 'editar' && !empty($depoimento_edicao['foto'])): ?>
                        <small style="color: #666; margin-top: 5px; display: block;">
                            Foto atual: <?php echo basename($depoimento_edicao['foto']); ?>
                        </small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="destaque" value="1" 
                               <?php echo ($depoimento_edicao['destaque'] ?? 0) ? 'checked' : ''; ?>>
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
