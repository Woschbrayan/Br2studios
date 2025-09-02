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

if ($_POST) {
    if ($action === 'novo' || $action === 'editar') {
        $dados = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'preco' => floatval($_POST['preco'] ?? 0),
            'area' => floatval($_POST['area'] ?? 0),
            'quartos' => intval($_POST['quartos'] ?? 0),
            'banheiros' => intval($_POST['banheiros'] ?? 0),
            'vagas' => intval($_POST['vagas'] ?? 0),
            'endereco' => trim($_POST['endereco'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'cep' => trim($_POST['cep'] ?? ''),
            'tipo' => trim($_POST['tipo'] ?? ''),
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
            
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                <?php if ($action === 'editar'): ?>
                    <input type="hidden" name="id" value="<?php echo $imovel_edicao['id']; ?>">
                    <input type="hidden" name="imagem_principal_atual" value="<?php echo htmlspecialchars($imovel_edicao['imagem_principal'] ?? ''); ?>">
                    <input type="hidden" name="imagens_atual" value="<?php echo htmlspecialchars($imovel_edicao['imagens'] ?? ''); ?>">
                <?php endif; ?>
                
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
                        <input type="number" id="preco" name="preco" class="form-control" step="0.01" min="0" required
                               value="<?php echo htmlspecialchars($imovel_edicao['preco'] ?? ''); ?>">
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
                
                <div class="form-group">
                    <label for="imagem_principal" class="form-label">Imagem Principal</label>
                    <input type="file" id="imagem_principal" name="imagem_principal" class="form-control" accept="image/*">
                    <?php if ($action === 'editar' && !empty($imovel_edicao['imagem_principal'])): ?>
                        <small style="color: #666; margin-top: 5px; display: block;">
                            Imagem atual: <?php echo basename($imovel_edicao['imagem_principal']); ?>
                        </small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="imagens" class="form-label">Imagens Adicionais (múltiplas)</label>
                    <input type="file" id="imagens" name="imagens[]" class="form-control" accept="image/*" multiple>
                    <?php if ($action === 'editar' && !empty($imovel_edicao['imagens'])): ?>
                        <small style="color: #666; margin-top: 5px; display: block;">
                            Imagens atuais: <?php echo count(explode(',', $imovel_edicao['imagens'])); ?> arquivo(s)
                        </small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="caracteristicas" class="form-label">Características</label>
                    <textarea id="caracteristicas" name="caracteristicas" class="form-control form-textarea" rows="3"
                              placeholder="Liste as características do imóvel..."><?php echo htmlspecialchars($imovel_edicao['caracteristicas'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="categorias" class="form-label">Categorias/Características do Imóvel</label>
                    <div class="categorias-container" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9;">
                        <?php if (!empty($categorias_disponiveis)): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                                <?php 
                                $categorias_atuais = [];
                                if ($action === 'editar' && !empty($imovel_edicao['categorias'])) {
                                    foreach ($imovel_edicao['categorias'] as $cat) {
                                        $categorias_atuais[] = $cat['id'];
                                    }
                                }
                                ?>
                                <?php foreach ($categorias_disponiveis as $cat): ?>
                                    <label style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 6px; cursor: pointer; transition: background 0.2s; <?php echo in_array($cat['id'], $categorias_atuais) ? 'background: #e3f2fd; border: 1px solid #2196f3;' : ''; ?>">
                                        <input type="checkbox" name="categorias[]" value="<?php echo $cat['id']; ?>" 
                                               <?php echo in_array($cat['id'], $categorias_atuais) ? 'checked' : ''; ?>
                                               style="margin: 0;">
                                        <i class="<?php echo htmlspecialchars($cat['icone']); ?>" style="color: #666; width: 16px;"></i>
                                        <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($cat['nome']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #666; text-align: center; margin: 20px 0;">
                                <i class="fas fa-info-circle"></i>
                                Nenhuma categoria disponível. 
                                <a href="categorias.php" style="color: #007bff; text-decoration: none;">
                                    Cadastre categorias primeiro
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                    <small style="color: #666; margin-top: 5px; display: block;">
                        Selecione as características que este imóvel possui. Você pode selecionar múltiplas opções.
                    </small>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="destaque" value="1" 
                               <?php echo ($imovel_edicao['destaque'] ?? 0) ? 'checked' : ''; ?>>
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
