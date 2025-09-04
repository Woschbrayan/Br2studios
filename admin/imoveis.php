<?php
// Incluir configurações antes de iniciar a sessão
require_once '../config/php_limits.php';
require_once '../config/database.php';
require_once '../config/upload_config.php';

session_start();

// Verificar se está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../classes/Database.php';
require_once '../classes/Imovel.php';
require_once '../classes/FileUpload.php';
require_once '../classes/CategoriaImovel.php';

$mensagem = '';
$tipo_mensagem = '';

try {
    $imovel = new Imovel();
    $file_upload = new FileUpload();
    $categoria = new CategoriaImovel();
} catch (Exception $e) {
    $mensagem = "Erro ao inicializar classes: " . $e->getMessage();
    $tipo_mensagem = 'error';
}

// Processar ações
$action = $_GET['action'] ?? 'listar';

// Processar AJAX para sugestão de nome único
if ($action === 'ajax' && $_POST && isset($_POST['action']) && $_POST['action'] === 'sugerir_nome') {
    header('Content-Type: application/json');
    
    try {
        $nome_base = trim($_POST['nome'] ?? '');
        
        if (empty($nome_base)) {
            throw new Exception("Nome base é obrigatório");
        }
        
        $nome_sugerido = $categoria->sugerirNomeUnico($nome_base);
        
        echo json_encode([
            'success' => true,
            'nome_sugerido' => $nome_sugerido
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// Processar AJAX para criação de categorias
if ($action === 'ajax' && $_POST && isset($_POST['action']) && $_POST['action'] === 'criar_categoria') {
    header('Content-Type: application/json');
    
    try {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $icone = trim($_POST['icone'] ?? 'fas fa-check');
        
        if (empty($nome)) {
            throw new Exception("Nome da categoria é obrigatório");
        }
        
        if (!$categoria) {
            throw new Exception("Classe CategoriaImovel não foi inicializada");
        }
        
        $categoria_id = $categoria->cadastrar($nome, $descricao, $icone);
        
        if ($categoria_id) {
            $nova_categoria = $categoria->buscarPorId($categoria_id);
            
            echo json_encode([
                'success' => true,
                'message' => 'Categoria criada com sucesso!',
                'categoria' => $nova_categoria
            ]);
        } else {
            throw new Exception("Erro ao criar categoria - ID não retornado");
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

if ($_POST) {
    if ($action === 'novo' || $action === 'editar') {
        $dados = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'preco' => floatval(str_replace(',', '.', $_POST['preco'] ?? 0)),
            'area' => floatval($_POST['area'] ?? 0),
            'quartos' => intval($_POST['quartos'] ?? 0),
            'banheiros' => intval($_POST['banheiros'] ?? 0),
            'vagas' => intval($_POST['vagas'] ?? 0),
            'endereco' => trim($_POST['endereco'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'cep' => trim($_POST['cep'] ?? ''),
            'tipo' => trim($_POST['tipo'] ?? ''),
            'status_construcao' => trim($_POST['status_construcao'] ?? 'pronto'),
            'ano_entrega' => !empty($_POST['ano_entrega']) ? intval($_POST['ano_entrega']) : null,
            'status' => trim($_POST['status'] ?? 'disponivel'),
            'caracteristicas' => trim($_POST['caracteristicas'] ?? ''),
            'destaque' => isset($_POST['destaque']) ? 1 : 0,
            'categorias' => isset($_POST['categorias']) ? $_POST['categorias'] : []
        ];
        
        // Processar upload de imagem principal
        if (isset($_FILES['imagem_principal']) && $_FILES['imagem_principal']['error'] === UPLOAD_ERR_OK) {
            $upload_result = $file_upload->uploadImage($_FILES['imagem_principal'], 'imoveis', true);
            
            if ($upload_result['success']) {
                $dados['imagem_principal'] = $upload_result['url'];
            } else {
                $mensagem = $upload_result['message'];
                $tipo_mensagem = 'error';
            }
        } elseif ($action === 'editar' && !empty($_POST['imagem_principal_atual'])) {
            // Manter imagem atual se não foi enviada nova
            $dados['imagem_principal'] = $_POST['imagem_principal_atual'];
        }
        
        // Processar upload de múltiplas imagens
        if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
            $upload_result = $file_upload->uploadMultipleImages($_FILES['imagens'], 'imoveis', true);
            
            if ($upload_result['success']) {
                // Combinar imagens existentes com novas (se editando)
                $imagens_existentes = [];
                if ($action === 'editar' && !empty($_POST['imagens_atual'])) {
                    $imagens_existentes = explode(',', $_POST['imagens_atual']);
                }
                
                $todas_imagens = array_merge($imagens_existentes, $upload_result['urls']);
                $dados['imagens'] = implode(',', array_filter($todas_imagens));
                
                $mensagem .= " " . $upload_result['message'];
            } else {
                $mensagem = $upload_result['message'];
                $tipo_mensagem = 'error';
            }
        } elseif ($action === 'editar' && !empty($_POST['imagens_atual'])) {
            // Manter imagens atuais se não foram enviadas novas
            $dados['imagens'] = $_POST['imagens_atual'];
        }
        
        // Processar imagens removidas
        if (isset($_POST['imagens_removidas']) && !empty($_POST['imagens_removidas'])) {
            $imagens_removidas = json_decode($_POST['imagens_removidas'], true);
            if (is_array($imagens_removidas)) {
                foreach ($imagens_removidas as $imagem_path) {
                    if (!empty($imagem_path)) {
                        $file_path = $_SERVER['DOCUMENT_ROOT'] . $imagem_path;
                        $file_upload->removeFile($file_path);
                    }
                }
                
                // Remover das imagens atuais
                if ($action === 'editar' && !empty($dados['imagens'])) {
                    $imagens_atuais = explode(',', $dados['imagens']);
                    $imagens_atuais = array_filter($imagens_atuais, function($img) use ($imagens_removidas) {
                        return !in_array($img, $imagens_removidas);
                    });
                    $dados['imagens'] = implode(',', $imagens_atuais);
                }
            }
        }
        
        if (empty($mensagem) || strpos($mensagem, 'Upload concluído') !== false) { // Só processa se não houve erro no upload
            try {
                if ($action === 'editar') {
                    $id = $_POST['id'] ?? '';
                    $resultado = $imovel->atualizar($id, $dados);
                } else {
                    $resultado = $imovel->cadastrar($dados);
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
        // Buscar imóvel para remover imagens
        $imovel_info = $imovel->buscarPorId($id);
        if ($imovel_info) {
            // Remover imagem principal
            if (!empty($imovel_info['imagem_principal'])) {
                $file_path = $_SERVER['DOCUMENT_ROOT'] . $imovel_info['imagem_principal'];
                $file_upload->removeFile($file_path);
            }
            
            // Remover múltiplas imagens
            if (!empty($imovel_info['imagens'])) {
                $imagens = explode(',', $imovel_info['imagens']);
                foreach ($imagens as $imagem) {
                    if (!empty($imagem)) {
                        $file_path = $_SERVER['DOCUMENT_ROOT'] . $imagem;
                        $file_upload->removeFile($file_path);
                    }
                }
            }
        }
        
        $resultado = $imovel->remover($id);
        
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
$imovel_edicao = null;
if ($action === 'editar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $imovel_edicao = $imovel->buscarPorId($id);
    
    if (!$imovel_edicao) {
        $mensagem = "Imóvel não encontrado";
        $tipo_mensagem = 'error';
        $action = 'listar';
    }
}

// Buscar categorias disponíveis
try {
    $categorias_disponiveis = $categoria->listarTodas(true);
} catch (Exception $e) {
    $categorias_disponiveis = [];
    error_log("Erro ao buscar categorias: " . $e->getMessage());
}

// Buscar imóveis para listagem
if ($action === 'listar') {
    try {
        $imoveis = $imovel->listarTodos();
    } catch (Exception $e) {
        $mensagem = "Erro ao buscar imóveis: " . $e->getMessage();
        $tipo_mensagem = 'error';
        $imoveis = ['imoveis' => []];
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

// Tipos de imóvel
$tipos = ['studio', 'apartamento', 'casa', 'cobertura', 'loft', 'flat', 'kitnet'];

// Status
$status_options = ['disponivel' => 'Disponível', 'vendido' => 'Vendido', 'reservado' => 'Reservado'];

// Definir variáveis para o header
$page_title = 'Gerenciar Imóveis - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Gerenciar Imóveis</h1>
            <p class="page-subtitle">Cadastre e gerencie os imóveis da empresa</p>
        </div>
        
        <div class="header-actions">
            <?php if ($action === 'listar'): ?>
                <a href="?action=novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Imóvel
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
        <!-- Lista de Imóveis -->
        <div class="table-container">
            <div class="table-header">
                <h3>Imóveis Cadastrados</h3>
            </div>
            
            <?php if (!empty($imoveis['imoveis'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Imóvel</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                            <th>Localização</th>
                            <th>Construção</th>
                            <th>Categorias</th>
                            <th>Status</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($imoveis['imoveis'] as $imovel_item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($imovel_item['imagem_principal']): ?>
                                            <img src="<?php echo htmlspecialchars($imovel_item['imagem_principal']); ?>" 
                                                 alt="Imóvel" style="width: 50px; height: 40px; border-radius: 8px; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 40px; border-radius: 8px; background: #666; display: flex; align-items: center; justify-content: center; color: white;">
                                                <i class="fas fa-home"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($imovel_item['titulo']); ?></strong>
                                            <?php if ($imovel_item['destaque']): ?>
                                                <span class="destaque-badge" style="background: #ffc107; color: #333; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 8px;">Destaque</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars(ucfirst($imovel_item['tipo'])); ?></td>
                                <td>R$ <?php echo number_format($imovel_item['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $cidade = $imovel_item['cidade'] ?? '';
                                    $estado = $imovel_item['estado'] ?? '';
                                    
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
                                    <?php 
                                    $status_construcao = $imovel_item['status_construcao'] ?? 'pronto';
                                    $ano_entrega = $imovel_item['ano_entrega'] ?? null;
                                    
                                    $status_labels = [
                                        'pronto' => 'Pronto',
                                        'em_construcao' => 'Em Construção',
                                        'na_planta' => 'Na Planta'
                                    ];
                                    
                                    $status_colors = [
                                        'pronto' => '#28a745',
                                        'em_construcao' => '#ffc107',
                                        'na_planta' => '#17a2b8'
                                    ];
                                    ?>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span style="background: <?php echo $status_colors[$status_construcao]; ?>; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; text-align: center;">
                                            <?php echo $status_labels[$status_construcao]; ?>
                                        </span>
                                        <?php if ($ano_entrega): ?>
                                            <small style="color: #666; font-size: 0.7rem; text-align: center;">
                                                Entrega: <?php echo $ano_entrega; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    // Buscar categorias do imóvel para exibição
                                    $categorias_imovel = [];
                                    try {
                                        $categorias_imovel = $categoria->buscarPorImovel($imovel_item['id']);
                                    } catch (Exception $e) {
                                        // Ignorar erro silenciosamente
                                    }
                                    
                                    if (!empty($categorias_imovel)): ?>
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 200px;">
                                            <?php foreach (array_slice($categorias_imovel, 0, 3) as $cat): ?>
                                                <span style="background: #e3f2fd; color: #1976d2; padding: 2px 6px; border-radius: 12px; font-size: 0.7rem; white-space: nowrap;">
                                                    <i class="<?php echo htmlspecialchars($cat['icone']); ?>" style="margin-right: 4px;"></i>
                                                    <?php echo htmlspecialchars($cat['nome']); ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if (count($categorias_imovel) > 3): ?>
                                                <span style="background: #f5f5f5; color: #666; padding: 2px 6px; border-radius: 12px; font-size: 0.7rem;">
                                                    +<?php echo count($categorias_imovel) - 3; ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 0.8rem;">Nenhuma categoria</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $imovel_item['status']; ?>" 
                                          style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                        <?php echo ucfirst($imovel_item['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($imovel_item['data_cadastro'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?action=editar&id=<?php echo $imovel_item['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=excluir&id=<?php echo $imovel_item['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja excluir este imóvel?')">
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
                    <i class="fas fa-home" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>Nenhum imóvel cadastrado</p>
                    <a href="?action=novo" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeiro Imóvel
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Formulário de Cadastro/Edição -->
        <div class="form-container">
            <h2><?php echo $action === 'editar' ? 'Editar Imóvel' : 'Cadastrar Novo Imóvel'; ?></h2>
            <p>Preencha os dados do <?php echo $action === 'editar' ? 'imóvel' : 'novo imóvel'; ?></p>
            
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;" id="imovelForm">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $imovel_edicao['id']; ?>">
                    <input type="hidden" name="imagem_principal_atual" value="<?php echo htmlspecialchars($imovel_edicao['imagem_principal'] ?? ''); ?>">
                    <input type="hidden" name="imagens_atual" value="<?php echo htmlspecialchars($imovel_edicao['imagens'] ?? ''); ?>">
                <?php endif; ?>
                
                <!-- Seção de Imagens - PRIMEIRA -->
                <div class="form-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 2px solid #e9ecef;">
                    <h3 style="color: #495057; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-images" style="color: #007bff;"></i>
                        Imagens do Imóvel
                    </h3>
                    
                    <!-- Upload de Imagem Principal -->
                    <div class="form-group">
                        <label for="imagem_principal" class="form-label">Imagem Principal *</label>
                        <div class="image-upload-container" style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; background: white;">
                            <input type="file" id="imagem_principal" name="imagem_principal" class="form-control" accept="image/*" style="display: none;" onchange="previewMainImage(this)">
                            <div id="main-image-preview" style="display: none;">
                                <img id="main-preview-img" style="max-width: 200px; max-height: 150px; border-radius: 8px; margin-bottom: 10px;">
                                <div>
                                    <button type="button" onclick="removeMainImage()" class="btn btn-danger btn-sm" style="margin-right: 10px;">
                                        <i class="fas fa-trash"></i> Remover
                                    </button>
                                    <button type="button" onclick="document.getElementById('imagem_principal').click()" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Alterar
                                    </button>
                                </div>
                            </div>
                            <div id="main-image-placeholder">
                                <i class="fas fa-camera" style="font-size: 2rem; color: #6c757d; margin-bottom: 10px;"></i>
                                <p style="color: #6c757d; margin: 0;">Clique para selecionar a imagem principal</p>
                                <button type="button" onclick="document.getElementById('imagem_principal').click()" class="btn btn-outline-primary" style="margin-top: 10px;">
                                    <i class="fas fa-upload"></i> Selecionar Imagem
                                </button>
                            </div>
                        </div>
                        <?php if ($action === 'editar' && !empty($imovel_edicao['imagem_principal'])): ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showMainImagePreview('<?php echo htmlspecialchars($imovel_edicao['imagem_principal']); ?>');
                                });
                            </script>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Upload de Múltiplas Imagens -->
                    <div class="form-group">
                        <label for="imagens" class="form-label">Imagens Adicionais</label>
                        <div class="multiple-images-container">
                            <input type="file" id="imagens" name="imagens[]" class="form-control" accept="image/*" multiple style="display: none;" onchange="previewMultipleImages(this)">
                            <div class="image-upload-area" style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 20px; text-align: center; background: white; margin-bottom: 15px;">
                                <i class="fas fa-images" style="font-size: 2rem; color: #6c757d; margin-bottom: 10px;"></i>
                                <p style="color: #6c757d; margin: 0;">Clique para selecionar múltiplas imagens</p>
                                <button type="button" onclick="document.getElementById('imagens').click()" class="btn btn-outline-primary" style="margin-top: 10px;">
                                    <i class="fas fa-upload"></i> Selecionar Imagens
                                </button>
                            </div>
                            
                            <!-- Preview das imagens múltiplas -->
                            <div id="multiple-images-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                                <?php if ($action === 'editar' && !empty($imovel_edicao['imagens'])): ?>
                                    <?php 
                                    $imagens_existentes = explode(',', $imovel_edicao['imagens']);
                                    foreach ($imagens_existentes as $index => $imagem): 
                                        if (!empty($imagem)):
                                    ?>
                                        <div class="image-item" style="position: relative; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            <img src="<?php echo htmlspecialchars($imagem); ?>" style="width: 100%; height: 120px; object-fit: cover;">
                                            <button type="button" onclick="removeExistingImage(this, '<?php echo htmlspecialchars($imagem); ?>')" 
                                                    class="btn btn-danger btn-sm" 
                                                    style="position: absolute; top: 5px; right: 5px; padding: 5px 8px; border-radius: 50%;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Seção de Informações Básicas -->
                <div class="form-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 2px solid #e9ecef;">
                    <h3 style="color: #495057; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-info-circle" style="color: #28a745;"></i>
                        Informações Básicas
                    </h3>
                
                    <div class="form-group">
                        <label for="titulo" class="form-label">Título do Imóvel *</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" required
                               value="<?php echo htmlspecialchars($imovel_edicao['titulo'] ?? ''); ?>">
                    </div>
                
                    <div class="form-group">
                        <label for="descricao" class="form-label">Descrição *</label>
                        <textarea id="descricao" name="descricao" class="form-control form-textarea" rows="4" required
                                  placeholder="Descreva detalhes do imóvel..."><?php echo htmlspecialchars($imovel_edicao['descricao'] ?? ''); ?></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="preco" class="form-label">Preço (R$) *</label>
                            <input type="number" id="preco" name="preco" class="form-control" step="1" min="0" required
                                   value="<?php echo htmlspecialchars($imovel_edicao['preco'] ?? ''); ?>"
                                   placeholder="Ex: 280000">
                        </div>
                        
                        <div class="form-group">
                            <label for="area" class="form-label">Área (m²)</label>
                            <input type="number" id="area" name="area" class="form-control" step="0.01" min="0"
                                   value="<?php echo htmlspecialchars($imovel_edicao['area'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="quartos" class="form-label">Quartos</label>
                            <input type="number" id="quartos" name="quartos" class="form-control" min="0"
                                   value="<?php echo htmlspecialchars($imovel_edicao['quartos'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="banheiros" class="form-label">Banheiros</label>
                            <input type="number" id="banheiros" name="banheiros" class="form-control" min="0"
                                   value="<?php echo htmlspecialchars($imovel_edicao['banheiros'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="vagas" class="form-label">Vagas</label>
                            <input type="number" id="vagas" name="vagas" class="form-control" min="0"
                                   value="<?php echo htmlspecialchars($imovel_edicao['vagas'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Seção de Localização -->
                <div class="form-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 2px solid #e9ecef;">
                    <h3 style="color: #495057; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-map-marker-alt" style="color: #dc3545;"></i>
                        Localização
                    </h3>
                    
                    <div class="form-group">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="form-control"
                               value="<?php echo htmlspecialchars($imovel_edicao['endereco'] ?? ''); ?>">
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" id="cidade" name="cidade" class="form-control"
                                   value="<?php echo htmlspecialchars($imovel_edicao['cidade'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-control">
                                <option value="">Selecione um estado</option>
                                <?php foreach ($estados as $sigla => $nome): ?>
                                    <option value="<?php echo $sigla; ?>" 
                                            <?php echo ($imovel_edicao['estado'] ?? '') === $sigla ? 'selected' : ''; ?>>
                                        <?php echo $nome; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" id="cep" name="cep" class="form-control"
                                   value="<?php echo htmlspecialchars($imovel_edicao['cep'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Seção de Tipo e Status -->
                <div class="form-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 2px solid #e9ecef;">
                    <h3 style="color: #495057; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-cog" style="color: #6f42c1;"></i>
                        Configurações
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="tipo" class="form-label">Tipo de Imóvel</label>
                            <select id="tipo" name="tipo" class="form-control">
                                <option value="">Selecione o tipo</option>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo; ?>" 
                                            <?php echo ($imovel_edicao['tipo'] ?? '') === $tipo ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($tipo); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control">
                                <?php foreach ($status_options as $value => $label): ?>
                                    <option value="<?php echo $value; ?>" 
                                            <?php echo ($imovel_edicao['status'] ?? 'disponivel') === $value ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="status_construcao" class="form-label">Status da Construção</label>
                            <select id="status_construcao" name="status_construcao" class="form-control">
                                <option value="pronto" <?php echo ($imovel_edicao['status_construcao'] ?? 'pronto') === 'pronto' ? 'selected' : ''; ?>>Pronto</option>
                                <option value="em_construcao" <?php echo ($imovel_edicao['status_construcao'] ?? '') === 'em_construcao' ? 'selected' : ''; ?>>Em Construção</option>
                                <option value="na_planta" <?php echo ($imovel_edicao['status_construcao'] ?? '') === 'na_planta' ? 'selected' : ''; ?>>Na Planta</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="ano_entrega" class="form-label">Ano de Entrega</label>
                            <input type="number" id="ano_entrega" name="ano_entrega" class="form-control" 
                                   min="2024" max="2030" 
                                   value="<?php echo htmlspecialchars($imovel_edicao['ano_entrega'] ?? ''); ?>"
                                   placeholder="Ex: 2025">
                            <small style="color: #666; margin-top: 5px; display: block;">
                                Ano previsto para entrega do imóvel (opcional)
                            </small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="caracteristicas" class="form-label">Características</label>
                        <textarea id="caracteristicas" name="caracteristicas" class="form-control form-textarea" rows="3"
                                  placeholder="Liste as características do imóvel..."><?php echo htmlspecialchars($imovel_edicao['caracteristicas'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <!-- Seção de Categorias com Criação Inline -->
                <div class="form-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 2px solid #e9ecef;">
                    <h3 style="color: #495057; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-tags" style="color: #fd7e14;"></i>
                        Categorias e Características
                    </h3>
                    
                    <!-- Criação de Nova Categoria -->
                    <div class="new-category-form" style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #dee2e6;">
                        <h4 style="color: #495057; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-plus-circle" style="color: #28a745;"></i>
                            Criar Nova Categoria
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="nova_categoria_nome" class="form-label">Nome da Categoria</label>
                                <input type="text" id="nova_categoria_nome" class="form-control" placeholder="Ex: Piscina, Academia, etc." oninput="verificarNomeExistente(this.value)">
                                <div id="nome-suggestions" style="display: none; margin-top: 5px; padding: 10px; background: #f8f9fa; border-radius: 4px; border: 1px solid #dee2e6;">
                                    <small style="color: #6c757d;">Categorias similares existentes:</small>
                                    <div id="suggestions-list" style="margin-top: 5px;"></div>
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="nova_categoria_descricao" class="form-label">Descrição</label>
                                <input type="text" id="nova_categoria_descricao" class="form-control" placeholder="Descrição opcional">
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="nova_categoria_icone" class="form-label">Ícone</label>
                                <select id="nova_categoria_icone" class="form-control">
                                    <option value="fas fa-swimming-pool">🏊 Piscina</option>
                                    <option value="fas fa-dumbbell">💪 Academia</option>
                                    <option value="fas fa-car">🚗 Garagem</option>
                                    <option value="fas fa-tree">🌳 Jardim</option>
                                    <option value="fas fa-wifi">📶 Wi-Fi</option>
                                    <option value="fas fa-shield-alt">🔒 Segurança</option>
                                    <option value="fas fa-fire">🔥 Churrasqueira</option>
                                    <option value="fas fa-gamepad">🎮 Lazer</option>
                                    <option value="fas fa-utensils">🍽️ Cozinha</option>
                                    <option value="fas fa-bed">🛏️ Quartos</option>
                                    <option value="fas fa-bath">🛁 Banheiros</option>
                                    <option value="fas fa-home">🏠 Casa</option>
                                    <option value="fas fa-building">🏢 Apartamento</option>
                                    <option value="fas fa-star">⭐ Destaque</option>
                                    <option value="fas fa-check">✅ Disponível</option>
                                </select>
                            </div>
                            
                            <div>
                                <button type="button" onclick="criarNovaCategoria()" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Criar
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lista de Categorias Disponíveis -->
                    <div class="categorias-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: white;">
                        <h4 style="color: #495057; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-list" style="color: #6c757d;"></i>
                            Categorias Disponíveis
                        </h4>
                        
                        <?php if (!empty($categorias_disponiveis)): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;">
                                <?php 
                                $categorias_atuais = [];
                                if ($action === 'editar' && !empty($imovel_edicao['categorias'])) {
                                    foreach ($imovel_edicao['categorias'] as $cat) {
                                        $categorias_atuais[] = $cat['id'];
                                    }
                                }
                                ?>
                                <?php foreach ($categorias_disponiveis as $cat): ?>
                                    <label class="category-item" style="display: flex; align-items: center; gap: 10px; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent; <?php echo in_array($cat['id'], $categorias_atuais) ? 'background: #e3f2fd; border-color: #2196f3;' : 'background: #f8f9fa;'; ?>" 
                                           onmouseover="this.style.background='#e9ecef'" 
                                           onmouseout="this.style.background='<?php echo in_array($cat['id'], $categorias_atuais) ? '#e3f2fd' : '#f8f9fa'; ?>'">
                                        <input type="checkbox" name="categorias[]" value="<?php echo $cat['id']; ?>" 
                                               <?php echo in_array($cat['id'], $categorias_atuais) ? 'checked' : ''; ?>
                                               style="margin: 0; transform: scale(1.2);" 
                                               onchange="toggleCategorySelection(this)">
                                        <i class="<?php echo htmlspecialchars($cat['icone']); ?>" style="color: #666; width: 20px; font-size: 1.1rem;"></i>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 500; font-size: 0.9rem;"><?php echo htmlspecialchars($cat['nome']); ?></div>
                                            <?php if (!empty($cat['descricao'])): ?>
                                                <div style="font-size: 0.8rem; color: #6c757d; margin-top: 2px;"><?php echo htmlspecialchars($cat['descricao']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: #6c757d;">
                                <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 15px;"></i>
                                <p>Nenhuma categoria disponível. Crie uma nova categoria acima!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <small style="color: #666; margin-top: 10px; display: block;">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Dica:</strong> Você pode criar novas categorias ou selecionar as existentes. As categorias selecionadas aparecerão no imóvel.
                    </small>
                </div>
                
                <!-- Seção Final -->
                <div class="form-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 2px solid #e9ecef;">
                    <h3 style="color: #495057; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-star" style="color: #ffc107;"></i>
                        Configurações Finais
                    </h3>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px; padding: 15px; background: white; border-radius: 8px; border: 1px solid #dee2e6;">
                            <input type="checkbox" name="destaque" value="1" 
                                   <?php echo ($imovel_edicao['destaque'] ?? 0) ? 'checked' : ''; ?>
                                   style="transform: scale(1.2);">
                            <div>
                                <div style="font-weight: 500;">Marcar como destaque</div>
                                <small style="color: #6c757d;">Este imóvel aparecerá em destaque na página inicial</small>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="form-actions" style="margin-top: 30px; display: flex; gap: 15px; justify-content: center; padding: 20px; background: #f8f9fa; border-radius: 12px;">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i>
                        <?php echo $action === 'editar' ? 'Atualizar Imóvel' : 'Cadastrar Imóvel'; ?>
                    </button>
                    <a href="?action=listar" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

<script>
// Variáveis globais para gerenciar imagens
let removedImages = [];
let newCategories = [];

// Função para preview da imagem principal
function previewMainImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            showMainImagePreview(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function showMainImagePreview(src) {
    document.getElementById('main-image-preview').style.display = 'block';
    document.getElementById('main-image-placeholder').style.display = 'none';
    document.getElementById('main-preview-img').src = src;
}

function removeMainImage() {
    document.getElementById('imagem_principal').value = '';
    document.getElementById('main-image-preview').style.display = 'none';
    document.getElementById('main-image-placeholder').style.display = 'block';
}

// Função para preview de múltiplas imagens
function previewMultipleImages(input) {
    const preview = document.getElementById('multiple-images-preview');
    
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageItem = document.createElement('div');
                imageItem.className = 'image-item';
                imageItem.style.cssText = 'position: relative; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);';
                
                imageItem.innerHTML = `
                    <img src="${e.target.result}" style="width: 100%; height: 120px; object-fit: cover;">
                    <button type="button" onclick="removeNewImage(this)" 
                            class="btn btn-danger btn-sm" 
                            style="position: absolute; top: 5px; right: 5px; padding: 5px 8px; border-radius: 50%;">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                preview.appendChild(imageItem);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeNewImage(button) {
    button.parentElement.remove();
}

function removeExistingImage(button, imagePath) {
    removedImages.push(imagePath);
    button.parentElement.remove();
}

// Função para criar nova categoria
function criarNovaCategoria() {
    const nome = document.getElementById('nova_categoria_nome').value.trim();
    const descricao = document.getElementById('nova_categoria_descricao').value.trim();
    const icone = document.getElementById('nova_categoria_icone').value;
    
    if (!nome) {
        alert('Por favor, digite o nome da categoria.');
        return;
    }
    
    // Mostrar loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Criando...';
    btn.disabled = true;
    
    // Criar categoria via AJAX
    const formData = new FormData();
    formData.append('action', 'criar_categoria');
    formData.append('nome', nome);
    formData.append('descricao', descricao);
    formData.append('icone', icone);
    
    fetch('?action=ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Adicionar à lista de categorias
            adicionarCategoriaNaLista(data.categoria);
            
            // Limpar formulário
            document.getElementById('nova_categoria_nome').value = '';
            document.getElementById('nova_categoria_descricao').value = '';
            
            // Adicionar aos checkboxes selecionados
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'categorias[]';
            checkbox.value = data.categoria.id;
            checkbox.checked = true;
            checkbox.style.cssText = 'margin: 0; transform: scale(1.2);';
            checkbox.onchange = function() { toggleCategorySelection(this); };
            
            // Adicionar ao formulário principal
            document.getElementById('imovelForm').appendChild(checkbox);
            
            alert('Categoria criada e selecionada com sucesso!');
        } else {
            let errorMessage = data.message;
            
            // Se for erro de categoria duplicada, oferecer sugestão automática
            if (data.message.includes('Já existe uma categoria')) {
                errorMessage += '\n\n💡 Deseja que eu sugira um nome único?';
                
                if (confirm(errorMessage + '\n\nClique em "OK" para obter uma sugestão automática.')) {
                    sugerirNomeUnico(nome);
                }
            } else {
                alert(errorMessage);
            }
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Erro de conexão ao criar categoria. Tente novamente.');
    })
    .finally(() => {
        // Restaurar botão
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function adicionarCategoriaNaLista(categoria) {
    let container = document.querySelector('.categorias-container .grid');
    if (!container) {
        // Criar container se não existir
        const categoriasContainer = document.querySelector('.categorias-container');
        const grid = document.createElement('div');
        grid.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;';
        categoriasContainer.appendChild(grid);
        container = grid;
    }
    
    const label = document.createElement('label');
    label.className = 'category-item';
    label.style.cssText = 'display: flex; align-items: center; gap: 10px; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid #2196f3; background: #e3f2fd;';
    label.onmouseover = function() { this.style.background = '#e9ecef'; };
    label.onmouseout = function() { this.style.background = '#e3f2fd'; };
    
    label.innerHTML = `
        <input type="checkbox" name="categorias[]" value="${categoria.id}" checked style="margin: 0; transform: scale(1.2);" onchange="toggleCategorySelection(this)">
        <i class="${categoria.icone}" style="color: #666; width: 20px; font-size: 1.1rem;"></i>
        <div style="flex: 1;">
            <div style="font-weight: 500; font-size: 0.9rem;">${categoria.nome}</div>
            ${categoria.descricao ? `<div style="font-size: 0.8rem; color: #6c757d; margin-top: 2px;">${categoria.descricao}</div>` : ''}
        </div>
    `;
    
    container.appendChild(label);
}

function toggleCategorySelection(checkbox) {
    const label = checkbox.closest('label');
    if (checkbox.checked) {
        label.style.background = '#e3f2fd';
        label.style.borderColor = '#2196f3';
    } else {
        label.style.background = '#f8f9fa';
        label.style.borderColor = 'transparent';
    }
}

// Função para verificar nomes existentes
function verificarNomeExistente(nome) {
    if (nome.length < 2) {
        document.getElementById('nome-suggestions').style.display = 'none';
        return;
    }
    
    // Buscar categorias similares nas existentes
    const categoriasExistentes = <?php echo json_encode($categorias_disponiveis); ?>;
    const similares = categoriasExistentes.filter(cat => 
        cat.nome.toLowerCase().includes(nome.toLowerCase())
    );
    
    const suggestionsDiv = document.getElementById('nome-suggestions');
    const suggestionsList = document.getElementById('suggestions-list');
    
    if (similares.length > 0) {
        suggestionsList.innerHTML = '';
        similares.forEach(cat => {
            const div = document.createElement('div');
            div.style.cssText = 'padding: 5px; background: white; margin: 2px 0; border-radius: 4px; border: 1px solid #e9ecef; cursor: pointer;';
            div.innerHTML = `<i class="${cat.icone}"></i> ${cat.nome}`;
            div.onclick = () => {
                document.getElementById('nova_categoria_nome').value = cat.nome;
                suggestionsDiv.style.display = 'none';
            };
            suggestionsList.appendChild(div);
        });
        suggestionsDiv.style.display = 'block';
    } else {
        suggestionsDiv.style.display = 'none';
    }
}

// Função para sugerir nome único
function sugerirNomeUnico(nomeBase) {
    // Sugestão simples no frontend primeiro
    let nomeSugerido = nomeBase + ' 2';
    
    // Tentar obter sugestão do servidor
    const formData = new FormData();
    formData.append('action', 'sugerir_nome');
    formData.append('nome', nomeBase);
    
    fetch('?action=ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            nomeSugerido = data.nome_sugerido;
        }
        
        if (confirm(`Nome sugerido: "${nomeSugerido}"\n\nDeseja usar este nome para criar a categoria?`)) {
            // Preencher o campo com o nome sugerido
            document.getElementById('nova_categoria_nome').value = nomeSugerido;
            
            // Tentar criar a categoria novamente
            criarNovaCategoria();
        }
    })
    .catch(error => {
        console.error('Erro ao obter sugestão do servidor:', error);
        
        // Usar sugestão local como fallback
        if (confirm(`Nome sugerido: "${nomeSugerido}"\n\nDeseja usar este nome para criar a categoria?`)) {
            document.getElementById('nova_categoria_nome').value = nomeSugerido;
            criarNovaCategoria();
        }
    });
}

// Adicionar imagens removidas ao formulário
document.getElementById('imovelForm').addEventListener('submit', function() {
    // Adicionar campo hidden com imagens removidas
    if (removedImages.length > 0) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'imagens_removidas';
        hiddenInput.value = JSON.stringify(removedImages);
        this.appendChild(hiddenInput);
    }
});
</script>

<?php include 'includes/footer.php'; ?>
