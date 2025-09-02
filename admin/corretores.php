<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir configurações
require_once '../config/database.php';
require_once '../config/upload_config.php';
require_once '../config/php_limits.php';
require_once '../classes/Database.php';
require_once '../classes/Corretor.php';
require_once '../classes/FileUpload.php';

$mensagem = '';
$tipo_mensagem = '';

try {
    $corretor = new Corretor();
    $file_upload = new FileUpload();
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
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'creci' => trim($_POST['creci'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? '')
        ];
        
        // Processar upload de imagem
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload_result = $file_upload->uploadImage($_FILES['foto'], 'corretores', true);
            
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
                    $resultado = $corretor->atualizar($id, $dados);
                } else {
                    $resultado = $corretor->cadastrar($dados);
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
        // Buscar corretor para remover foto
        $corretor_info = $corretor->buscarPorId($id);
        if ($corretor_info && !empty($corretor_info['foto'])) {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $corretor_info['foto'];
            $file_upload->removeFile($file_path);
        }
        
        $resultado = $corretor->remover($id);
        
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
$corretor_edicao = null;
if ($action === 'editar' && isset($_GET['id'])) {
    try {
        $corretor_edicao = $corretor->buscarPorId($_GET['id']);
        if (!$corretor_edicao) {
            $mensagem = "Corretor não encontrado";
            $tipo_mensagem = 'error';
            $action = 'listar';
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar corretor: " . $e->getMessage();
        $tipo_mensagem = 'error';
        $action = 'listar';
    }
}

// Buscar lista de corretores
$corretores = [];
if ($action === 'listar') {
    try {
        $corretores_result = $corretor->listarTodos();
        if (is_array($corretores_result) && isset($corretores_result['corretores'])) {
            $corretores = $corretores_result['corretores'];
        } elseif (is_array($corretores_result)) {
            $corretores = $corretores_result;
        }
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar corretores: " . $e->getMessage();
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
$page_title = 'Gerenciar Corretores - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Gerenciar Corretores</h1>
            <p class="page-subtitle">Cadastre e gerencie os corretores da empresa</p>
        </div>
        
        <div class="header-actions">
            <?php if ($action === 'listar'): ?>
                <a href="?action=novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Corretor
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
        <!-- Lista de Corretores -->
        <div class="table-container">
            <div class="table-header">
                <h3>Corretores Cadastrados</h3>
            </div>
            
            <?php if (!empty($corretores)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>CRECI</th>
                            <th>Localização</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($corretores as $corretor_item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($corretor_item['foto'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $corretor_item['foto'])): ?>
                                            <img src="<?php echo htmlspecialchars($corretor_item['foto']); ?>" 
                                                 alt="Foto de <?php echo htmlspecialchars($corretor_item['nome']); ?>" 
                                                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #e9ecef;">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; border: 2px solid #e9ecef;">
                                                <?php echo strtoupper(substr($corretor_item['nome'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($corretor_item['nome']); ?></strong>
                                            <?php if ($corretor_item['foto']): ?>
                                                <br><small style="color: #28a745; font-size: 11px;">
                                                    <i class="fas fa-image"></i> Com foto
                                                </small>
                                            <?php else: ?>
                                                <br><small style="color: #6c757d; font-size: 11px;">
                                                    <i class="fas fa-user"></i> Sem foto
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($corretor_item['email']); ?></td>
                                <td><?php echo htmlspecialchars($corretor_item['telefone'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($corretor_item['creci'] ?: '-'); ?></td>
                                <td>
                                    <?php 
                                    $cidade = $corretor_item['cidade'] ?? '';
                                    $estado = $corretor_item['estado'] ?? '';
                                    
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
                                <td><?php echo date('d/m/Y', strtotime($corretor_item['data_cadastro'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?action=editar&id=<?php echo $corretor_item['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=excluir&id=<?php echo $corretor_item['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja excluir este corretor?')">
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
                    <p>Nenhum corretor cadastrado</p>
                    <a href="?action=novo" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeiro Corretor
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?php echo $action === 'editar' ? 'Editar Corretor' : 'Cadastrar Novo Corretor'; ?></h2>
            <p>Preencha os dados do <?php echo $action === 'editar' ? 'corretor' : 'novo corretor'; ?></p>
            
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $corretor_edicao['id']; ?>">
                    <input type="hidden" name="foto_atual" value="<?php echo htmlspecialchars($corretor_edicao['foto'] ?? ''); ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nome" class="form-label">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" class="form-control" required
                           value="<?php echo htmlspecialchars($corretor_edicao['nome'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">E-mail *</label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="<?php echo htmlspecialchars($corretor_edicao['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" class="form-control"
                           value="<?php echo htmlspecialchars($corretor_edicao['telefone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="creci" class="form-label">CRECI</label>
                    <input type="text" id="creci" name="creci" class="form-control"
                           value="<?php echo htmlspecialchars($corretor_edicao['creci'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="form-control"
                           value="<?php echo htmlspecialchars($corretor_edicao['cidade'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-control">
                        <option value="">Selecione um estado</option>
                        <?php foreach ($estados as $sigla => $nome): ?>
                            <option value="<?php echo $sigla; ?>" 
                                    <?php echo ($corretor_edicao['estado'] ?? '') === $sigla ? 'selected' : ''; ?>>
                                <?php echo $nome; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="foto" class="form-label">Foto de Perfil</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*" onchange="previewImage(this)">
                    
                    <!-- Preview da imagem -->
                    <div id="image-preview" style="margin-top: 15px; display: none;">
                        <img id="preview-img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef;">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()" style="margin-top: 10px;">
                            <i class="fas fa-times"></i> Remover Imagem
                        </button>
                    </div>
                    
                    <!-- Imagem atual (se editando) -->
                    <?php if ($action === 'editar' && !empty($corretor_edicao['foto'])): ?>
                        <div style="margin-top: 15px;">
                            <label class="form-label">Foto Atual:</label>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="<?php echo htmlspecialchars($corretor_edicao['foto']); ?>" 
                                     alt="Foto atual" 
                                     style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover; border: 2px solid #e9ecef;">
                                <div>
                                    <small style="color: #666;">
                                        <strong>Arquivo:</strong> <?php echo basename($corretor_edicao['foto']); ?><br>
                                        <strong>Tamanho:</strong> <?php 
                                            $file_path = $_SERVER['DOCUMENT_ROOT'] . $corretor_edicao['foto'];
                                            if (file_exists($file_path)) {
                                                echo number_format(filesize($file_path) / 1024, 1) . ' KB';
                                            } else {
                                                echo 'Arquivo não encontrado';
                                            }
                                        ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-text">
                        <strong>Formatos aceitos:</strong> JPG, PNG, GIF, WebP<br>
                        <strong>Tamanho máximo:</strong> <?php echo number_format(UPLOAD_MAX_SIZE / 1024 / 1024, 1); ?> MB<br>
                        <strong>Dimensões recomendadas:</strong> 400x400 pixels (quadrada)
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bio" class="form-label">Biografia</label>
                    <textarea id="bio" name="bio" class="form-control form-textarea" rows="4"
                              placeholder="Conte um pouco sobre a experiência e especialidades do corretor..."><?php echo htmlspecialchars($corretor_edicao['bio'] ?? ''); ?></textarea>
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

<!-- JavaScript para preview de imagem -->
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
        
        // Validar tamanho do arquivo
        const file = input.files[0];
        const maxSize = <?php echo UPLOAD_MAX_SIZE; ?>;
        
        if (file.size > maxSize) {
            alert('Arquivo muito grande! Tamanho máximo: ' + (maxSize / 1024 / 1024).toFixed(1) + ' MB');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validar tipo do arquivo
        const allowedTypes = <?php echo json_encode(UPLOAD_ALLOWED_TYPES); ?>;
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            alert('Tipo de arquivo não permitido! Formatos aceitos: ' + allowedTypes.join(', ').toUpperCase());
            input.value = '';
            preview.style.display = 'none';
            return;
        }
    } else {
        preview.style.display = 'none';
    }
}

function removeImage() {
    const input = document.getElementById('foto');
    const preview = document.getElementById('image-preview');
    
    input.value = '';
    preview.style.display = 'none';
}

// Mostrar preview se já existe imagem (edição)
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto');
    const fotoAtual = '<?php echo $action === 'editar' ? ($corretor_edicao['foto'] ?? '') : ''; ?>';
    
    if (fotoAtual && fotoInput) {
        // Simular preview para imagem existente
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (preview && previewImg) {
            previewImg.src = fotoAtual;
            preview.style.display = 'block';
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
