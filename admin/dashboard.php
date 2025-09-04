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
require_once '../classes/Corretor.php';
require_once '../classes/Imovel.php';
require_once '../classes/Depoimento.php';
require_once '../classes/Especialista.php';
require_once '../classes/Regiao.php';

$db = new Database();
$corretor = new Corretor();
$imovel = new Imovel();
$depoimento = new Depoimento();
$especialista = new Especialista();
$regiao = new Regiao();

// Buscar estatísticas
try {
    $total_corretores = $corretor->contarTotal();
    $total_imoveis = $imovel->contarTotal();
    $total_depoimentos = $depoimento->contarTotal();
    $total_especialistas = $especialista->contarTotal();
    $total_regioes = $regiao->contarTotal();
    
    // Contar destaque
    $corretores_destaque = $corretor->contarTotal(['destaque' => 1]);
    $imoveis_destaque = $imovel->contarTotal(['destaque' => 1]);
    $depoimentos_destaque = $depoimento->contarTotal(['destaque' => 1]);
    $especialistas_destaque = $especialista->contarTotal(['destaque' => 1]);
    $regioes_destaque = $regiao->contarTotal(['destaque' => 1]);
    
} catch (Exception $e) {
    error_log("Erro ao buscar estatísticas: " . $e->getMessage());
    $total_corretores = $total_imoveis = $total_depoimentos = $total_especialistas = $total_regioes = 0;
    $corretores_destaque = $imoveis_destaque = $depoimentos_destaque = $especialistas_destaque = $regioes_destaque = 0;
}

// Últimos corretores cadastrados
$ultimos_corretores = [];
try {
    $corretores_result = $corretor->listarTodos();
    if (is_array($corretores_result) && isset($corretores_result['corretores'])) {
        $ultimos_corretores = array_slice($corretores_result['corretores'], 0, 5);
    } elseif (is_array($corretores_result)) {
        $ultimos_corretores = array_slice($corretores_result, 0, 5);
    }
} catch (Exception $e) {
    error_log("Erro ao buscar últimos corretores: " . $e->getMessage());
}

// Últimos imóveis cadastrados
$ultimos_imoveis = [];
try {
    $imoveis_result = $imovel->listarTodos();
    if (is_array($imoveis_result) && isset($imoveis_result['imoveis'])) {
        $ultimos_imoveis = array_slice($imoveis_result['imoveis'], 0, 5);
    } elseif (is_array($imoveis_result)) {
        $ultimos_imoveis = array_slice($imoveis_result, 0, 5);
    }
} catch (Exception $e) {
    error_log("Erro ao buscar últimos imóveis: " . $e->getMessage());
}

// Definir variáveis para o header
$page_title = 'Dashboard - Br2Studios Admin';

// Incluir header
include 'includes/header.php';
?>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content">
    <div class="page-header">
        <div class="page-title">
            <h1>Dashboard</h1>
            <p class="page-subtitle">Bem-vindo ao painel administrativo</p>
        </div>
        
        <div class="header-actions">
            <a href="corretores.php?action=novo" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Novo Corretor
            </a>
            <a href="imoveis.php?action=novo" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Novo Imóvel
            </a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_imoveis; ?></h3>
                <p>Total de Imóveis</p>
                <small><?php echo $imoveis_destaque; ?> em destaque</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_corretores; ?></h3>
                <p>Total de Corretores</p>
                <small><?php echo $corretores_destaque; ?> em destaque</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_depoimentos; ?></h3>
                <p>Total de Depoimentos</p>
                <small><?php echo $depoimentos_destaque; ?> em destaque</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_especialistas; ?></h3>
                <p>Total de Especialistas</p>
                <small><?php echo $especialistas_destaque; ?> em destaque</small>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_regioes; ?></h3>
                <p>Total de Regiões</p>
                <small><?php echo $regioes_destaque; ?> em destaque</small>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="recent-activity">
        <div class="activity-grid">
            <!-- Últimos Corretores -->
            <div class="activity-card">
                <div class="card-header">
                    <h3>Últimos Corretores Cadastrados</h3>
                    <a href="corretores.php" class="btn btn-secondary btn-sm">Ver Todos</a>
                </div>
                <div class="content-body">
                    <?php if (!empty($ultimos_corretores)): ?>
                        <?php foreach ($ultimos_corretores as $corretor_item): ?>
                            <div class="list-item">
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($corretor_item['nome'] ?? 'Nome não informado'); ?></h4>
                                    <p>
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
                                            echo 'Localização não informada';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="item-actions">
                                    <a href="corretores.php?action=editar&id=<?php echo $corretor_item['id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-user-tie"></i>
                            <p>Nenhum corretor cadastrado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Últimos Imóveis -->
            <div class="activity-card">
                <div class="card-header">
                    <h3>Últimos Imóveis Cadastrados</h3>
                    <a href="imoveis.php" class="btn btn-secondary btn-sm">Ver Todos</a>
                </div>
                <div class="content-body">
                    <?php if (!empty($ultimos_imoveis)): ?>
                        <?php foreach ($ultimos_imoveis as $imovel_item): ?>
                            <div class="list-item">
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($imovel_item['titulo'] ?? $imovel_item['nome'] ?? 'Título não informado'); ?></h4>
                                    <p>
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
                                            echo 'Localização não informada';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="item-actions">
                                    <a href="imoveis.php?action=editar&id=<?php echo $imovel_item['id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-home"></i>
                            <p>Nenhum imóvel cadastrado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
