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
require_once '../classes/Especialista.php';
require_once '../classes/FileUpload.php';

$mensagem = '';
$tipo_mensagem = '';

try {
    $especialista = new Especialista();
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
            'especialidade' => trim($_POST['especialidade'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'experiencia' => trim($_POST['experiencia'] ?? ''),
            'formacao' => trim($_POST['formacao'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'destaque' => isset($_POST['destaque']) ? 1 : 0
        ];
        
        // Processar upload de imagem
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload_result = $file_upload->uploadImage($_FILES['foto'], 'especialistas');
            
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
                    $resultado = $especialista->atualizar($id, $dados);
                } else {
                    $resultado = $especialista->cadastrar($dados);
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
        // Buscar especialista para remover foto
        $especialista_info = $especialista->buscarPorId($id);
        if ($especialista_info && !empty($especialista_info['foto'])) {
            // Remover arquivo físico se existir
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $especialista_info['foto'];
            if (file_exists($file_path)) {
                $file_upload->removeFile($file_path);
            }
        }
        
        $resultado = $especialista->remover($id);
        
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
$especialista_edicao = null;
if ($action === 'editar' && isset($_GET['id'])) {
    try {
        $especialista_edicao = $especialista->buscarPorId($_GET['id']);
        if (!$especialista_edicao) {
            $mensagem = "Especialista não encontrado";
            $tipo_mensagem = 'error';
            $action = 'listar';
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar especialista: " . $e->getMessage();
        $tipo_mensagem = 'error';
        $action = 'listar';
    }
}

// Buscar lista de especialistas
$especialistas = [];
if ($action === 'listar') {
    try {
        $especialistas_result = $especialista->listarTodos();
        if (is_array($especialistas_result) && isset($especialistas_result['especialistas'])) {
            $especialistas = $especialistas_result;
        } elseif (is_array($especialistas_result)) {
            $especialistas = ['especialistas' => $especialistas_result];
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar especialistas: " . $e->getMessage();
        $tipo_mensagem = 'error';
    }
}

// Especialidades comuns
$especialidades = [
    'Direito Imobiliário', 'Engenharia Civil', 'Arquitetura', 'Urbanismo',
    'Financiamento Imobiliário', 'Avaliação Imobiliária', 'Gestão de Condomínios',
    'Regularização Fundiária', 'Consultoria Imobiliária', 'Marketing Imobiliário'
];

// Definir variáveis para o header
$page_title = 'Gerenciar Especialistas - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Gerenciar Especialistas</h1>
            <p class="page-subtitle">Cadastre e gerencie os especialistas da empresa</p>
        </div>
        
        <div class="header-actions">
            <?php if ($action === 'listar'): ?>
                <a href="?action=novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Especialista
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
        <!-- Lista de Especialistas -->
        <div class="table-container">
            <div class="table-header">
                <h3>Especialistas Cadastrados</h3>
            </div>
            
            <?php if (!empty($especialistas['especialistas'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Especialista</th>
                            <th>Especialidade</th>
                            <th>Experiência</th>
                            <th>Contato</th>
                            <th>Destaque</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($especialistas['especialistas'] as $especialista_item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($especialista_item['foto']): ?>
                                            <img src="<?php echo htmlspecialchars($especialista_item['foto']); ?>" 
                                                 alt="Especialista" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #666; display: flex; align-items: center; justify-content: center; color: white;">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($especialista_item['nome']); ?></strong>
                                            <?php if (!empty($especialista_item['formacao'])): ?>
                                                <br><small style="color: #666;"><?php echo htmlspecialchars($especialista_item['formacao']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="background: #f8f9fa; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; color: #666;">
                                        <?php echo htmlspecialchars($especialista_item['especialidade']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="max-width: 200px;">
                                        <p style="margin: 0; font-size: 0.9rem; line-height: 1.4;">
                                            <?php echo htmlspecialchars(substr($especialista_item['experiencia'], 0, 80)); ?>
                                            <?php if (strlen($especialista_item['experiencia']) > 80): ?>...<?php endif; ?>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.9rem;">
                                        <?php if (!empty($especialista_item['email'])): ?>
                                            <div><i class="fas fa-envelope" style="color: #666; margin-right: 5px;"></i><?php echo htmlspecialchars($especialista_item['email']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($especialista_item['telefone'])): ?>
                                            <div><i class="fas fa-phone" style="color: #666; margin-right: 5px;"></i><?php echo htmlspecialchars($especialista_item['telefone']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($especialista_item['destaque']): ?>
                                        <span class="destaque-badge" style="background: #ffc107; color: #333; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem;">Destaque</span>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 0.8rem;">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($especialista_item['data_cadastro'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?action=editar&id=<?php echo $especialista_item['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=excluir&id=<?php echo $especialista_item['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja excluir este especialista?')">
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
                    <i class="fas fa-user-tie" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>Nenhum especialista cadastrado</p>
                    <a href="?action=novo" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeiro Especialista
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?php echo $action === 'editar' ? 'Editar Especialista' : 'Cadastrar Novo Especialista'; ?></h2>
            <p>Preencha os dados do <?php echo $action === 'editar' ? 'especialista' : 'novo especialista'; ?></p>
            
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $especialista_edicao['id']; ?>">
                    <input type="hidden" name="foto_atual" value="<?php echo htmlspecialchars($especialista_edicao['foto'] ?? ''); ?>">
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" class="form-control" required
                               value="<?php echo htmlspecialchars($especialista_edicao['nome'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="especialidade" class="form-label">Especialidade *</label>
                        <select id="especialidade" name="especialidade" class="form-control" required>
                            <option value="">Selecione a especialidade</option>
                            <?php foreach ($especialidades as $esp): ?>
                                <option value="<?php echo $esp; ?>" 
                                        <?php echo ($especialista_edicao['especialidade'] ?? '') === $esp ? 'selected' : ''; ?>>
                                    <?php echo $esp; ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="outro" <?php echo !in_array($especialista_edicao['especialidade'] ?? '', $especialidades) ? 'selected' : ''; ?>>Outro</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="formacao" class="form-label">Formação Acadêmica</label>
                    <input type="text" id="formacao" name="formacao" class="form-control"
                           value="<?php echo htmlspecialchars($especialista_edicao['formacao'] ?? ''); ?>"
                           placeholder="Ex: Bacharel em Direito, Engenheiro Civil...">
                </div>
                
                <div class="form-group">
                    <label for="experiencia" class="form-label">Experiência Profissional *</label>
                    <textarea id="experiencia" name="experiencia" class="form-control form-textarea" rows="3" required
                              placeholder="Descreva a experiência profissional..."><?php echo htmlspecialchars($especialista_edicao['experiencia'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição/Perfil</label>
                    <textarea id="descricao" name="descricao" class="form-control form-textarea" rows="3"
                              placeholder="Descreva o perfil e habilidades..."><?php echo htmlspecialchars($especialista_edicao['descricao'] ?? ''); ?></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" class="form-control"
                               value="<?php echo htmlspecialchars($especialista_edicao['telefone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?php echo htmlspecialchars($especialista_edicao['email'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="linkedin" class="form-label">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin" class="form-control"
                           value="<?php echo htmlspecialchars($especialista_edicao['linkedin'] ?? ''); ?>"
                           placeholder="https://linkedin.com/in/...">
                </div>
                
                <div class="form-group">
                    <label for="foto" class="form-label">Foto do Especialista</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <?php if ($action === 'editar' && !empty($especialista_edicao['foto'])): ?>
                        <small style="color: #666; margin-top: 5px; display: block;">
                            Foto atual: <?php echo basename($especialista_edicao['foto']); ?>
                        </small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="destaque" value="1" 
                               <?php echo ($especialista_edicao['destaque'] ?? 0) ? 'checked' : ''; ?>>
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
