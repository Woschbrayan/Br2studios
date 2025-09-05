<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

$imovel = new Imovel();

// Filtros
$filters = [];
if (isset($_GET['status_construcao']) && !empty($_GET['status_construcao'])) {
    $filters['status_construcao'] = $_GET['status_construcao'];
}
if (isset($_GET['cidade']) && !empty($_GET['cidade'])) {
    $filters['cidade'] = $_GET['cidade'];
}
if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
    $filters['min_price'] = floatval($_GET['min_price']);
}
if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $filters['max_price'] = floatval($_GET['max_price']);
}

// Buscar imóveis do banco de dados
try {
    $imoveis_result = $imovel->listarTodos($filters);
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_result) && isset($imoveis_result['imoveis'])) {
        $imoveis = $imoveis_result['imoveis'];
    } else {
        $imoveis = [];
    }
    
    // Buscar cidades únicas para o filtro
    $todasCidades_result = $imovel->listarTodos();
    if (is_array($todasCidades_result) && isset($todasCidades_result['imoveis'])) {
        $todasCidades = $todasCidades_result['imoveis'];
        $cidades = array_unique(array_column($todasCidades, 'cidade'));
        sort($cidades);
    } else {
        $cidades = [];
    }
    
} catch (Exception $e) {
    // Em caso de erro, usar arrays vazios
    error_log("Erro ao buscar imóveis: " . $e->getMessage());
    $imoveis = [];
    $cidades = [];
}


?>

<?php 
$current_page = 'imoveis';
$page_title = 'Imóveis em Curitiba - Br2Studios';
$page_css = 'assets/css/imoveis.css';
include 'includes/header.php'; 
?>

    <!-- Page Banner Desktop -->
    <section class="page-banner desktop-only">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1>Portfólio de Imóveis</h1>
                    <p>Descubra os melhores imóveis em Curitiba e região metropolitana</p>
                    <div class="banner-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($imoveis); ?>+</span>
                            <span class="stat-label">Imóveis</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">4</span>
                            <span class="stat-label">Regiões</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">98%</span>
                            <span class="stat-label">Aprovação</span>
                        </div>
                    </div>
                </div>
                <div class="banner-visual">
                    <div class="banner-image">
                        <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Studio Premium">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Header Mobile Simples -->
    <section class="imoveis-header-mobile mobile-only">
        <div class="container">
            <div class="header-mobile-content">
                <h1>Nossos Imóveis</h1>
                <p><?php echo count($imoveis); ?> oportunidades disponíveis</p>
                <div class="quick-stats-mobile">
                    <span class="quick-stat">
                        <i class="fas fa-home"></i>
                        <?php echo count($imoveis); ?> Imóveis
                    </span>
                    <span class="quick-stat">
                        <i class="fas fa-map-marker-alt"></i>
                        4 Regiões
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-header">
                    <h2>Encontre seu Investimento</h2>
                    <p>Filtre por status de construção, localização e faixa de preço</p>
                </div>
                
                <form class="search-form" method="GET">
                    <div class="search-filters">
                        <div class="filter-group">
                            <label for="status_construcao">Status de Construção</label>
                            <select name="status_construcao" id="status_construcao" class="filter-select">
                                <option value="">Todos os status</option>
                                <option value="pronto" <?php echo (isset($_GET['status_construcao']) && $_GET['status_construcao'] == 'pronto') ? 'selected' : ''; ?>>Imóvel Pronto</option>
                                <option value="na_planta" <?php echo (isset($_GET['status_construcao']) && $_GET['status_construcao'] == 'na_planta') ? 'selected' : ''; ?>>Na Planta</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="cidade">Cidade</label>
                            <select name="cidade" id="cidade" class="filter-select">
                                <option value="">Todas as cidades</option>
                                <?php foreach ($cidades as $cidade): ?>
                                    <option value="<?php echo htmlspecialchars($cidade); ?>" <?php echo (isset($_GET['cidade']) && $_GET['cidade'] == $cidade) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cidade); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="min_price">Preço Mínimo</label>
                            <input type="number" name="min_price" id="min_price" placeholder="R$ 0" class="filter-input" value="<?php echo $_GET['min_price'] ?? ''; ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label for="max_price">Preço Máximo</label>
                            <input type="number" name="max_price" id="max_price" placeholder="R$ 1.000.000" class="filter-input" value="<?php echo $_GET['max_price'] ?? ''; ?>">
                        </div>
                    </div>
                    
                    <div class="search-actions">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i>
                            Buscar Imóveis
                        </button>
                        <a href="imoveis.php" class="btn-clear">Limpar Filtros</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Filtros Mobile Simplificados -->
    <section class="filters-mobile mobile-only">
        <div class="container">
            <div class="filters-mobile-container">
                <button class="filters-toggle-btn" id="filters-toggle">
                    <i class="fas fa-filter"></i>
                    Filtros
                    <span class="filters-count" style="display: none;">0</span>
                </button>
                
                <div class="filters-mobile-panel" id="filters-panel" style="display: none;">
                    <form method="GET" class="filters-form-mobile">
                        <div class="filter-row-mobile">
                            <label>Status</label>
                            <select name="status_construcao" class="filter-select-mobile">
                                <option value="">Todos</option>
                                <option value="pronto" <?php echo (isset($_GET['status_construcao']) && $_GET['status_construcao'] == 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                                <option value="na_planta" <?php echo (isset($_GET['status_construcao']) && $_GET['status_construcao'] == 'na_planta') ? 'selected' : ''; ?>>Na Planta</option>
                            </select>
                        </div>
                        
                        <div class="filter-row-mobile">
                            <label>Cidade</label>
                            <select name="cidade" class="filter-select-mobile">
                                <option value="">Todas</option>
                                <?php foreach ($cidades as $cidade): ?>
                                    <option value="<?php echo htmlspecialchars($cidade); ?>" <?php echo (isset($_GET['cidade']) && $_GET['cidade'] == $cidade) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cidade); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-actions-mobile">
                            <button type="submit" class="btn-apply-filters">
                                <i class="fas fa-search"></i>
                                Aplicar
                            </button>
                            <a href="imoveis.php" class="btn-clear-filters">
                                <i class="fas fa-times"></i>
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Properties Grid -->
    <section class="properties-section">
        <div class="container">
            <div class="section-header">
                <h2>Imóveis Disponíveis</h2>
                <p>Seleção criteriosa de investimentos com alto potencial de retorno</p>
            </div>
            
            <?php if (empty($imoveis)): ?>
                <div class="no-properties-message">
                    <div class="no-properties-content">
                        <i class="fas fa-search"></i>
                        <h3>Nenhum imóvel encontrado</h3>
                        <p>Tente ajustar os filtros de busca ou entre em contato conosco para mais informações.</p>
                        <a href="contato.php" class="btn-view-all">
                         
                            Falar com Corretor
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="properties-grid">
                    <?php foreach ($imoveis as $imovel_item): ?>
                        <div class="property-card">
                            <div class="property-image">
                                <?php if (!empty($imovel_item['imagem_principal'])): ?>
                                    <img src="<?php echo htmlspecialchars($imovel_item['imagem_principal']); ?>" 
                                         alt="<?php echo htmlspecialchars($imovel_item['titulo']); ?>">
                                <?php else: ?>
                                    <img src="assets/images/imoveis/Imovel-1.jpeg" 
                                         alt="Imóvel sem imagem">
                                <?php endif; ?>
                                
                                <div class="property-labels">
                                    <?php if ($imovel_item['destaque'] == 1 || $imovel_item['destaque'] == '1'): ?>
                                        <span class="property-badge badge-destaque">
                                            <i class="fas fa-star"></i>
                                            DESTAQUE
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($imovel_item['maior_valorizacao'] == 1 || $imovel_item['maior_valorizacao'] == '1'): ?>
                                        <span class="property-badge badge-valorizacao">
                                            <i class="fas fa-chart-line"></i>
                                            VALORIZAÇÃO
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($imovel_item['ano_entrega']) && $imovel_item['ano_entrega'] != '' && $imovel_item['ano_entrega'] != '0'): ?>
                                        <span class="property-badge badge-entrega">
                                            <i class="fas fa-calendar-alt"></i>
                                            ENTREGA <?php echo $imovel_item['ano_entrega']; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="property-price">
                                    <span class="price">R$ <?php echo number_format($imovel_item['preco'], 0, ',', '.'); ?></span>
                                    <?php if (!empty($imovel_item['area']) && $imovel_item['area'] > 0): ?>
                                        <span class="price-per-sqft">R$ <?php echo number_format($imovel_item['preco'] / $imovel_item['area'], 0, ',', '.'); ?>/m²</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="property-info">
                                <h3><?php echo htmlspecialchars($imovel_item['titulo']); ?></h3>
                                <p class="property-location">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    <?php echo htmlspecialchars($imovel_item['cidade'] . ', ' . $imovel_item['estado']); ?>
                                </p>
                                <div class="property-details">
                                    <?php if (!empty($imovel_item['area']) && $imovel_item['area'] > 0): ?>
                                        <span><i class="fas fa-ruler-combined"></i> <?php echo $imovel_item['area']; ?>m²</span>
                                    <?php endif; ?>
                                    <?php if (!empty($imovel_item['quartos'])): ?>
                                        <span><i class="fas fa-bed"></i> <?php echo $imovel_item['quartos']; ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($imovel_item['banheiros'])): ?>
                                        <span><i class="fas fa-bath"></i> <?php echo $imovel_item['banheiros']; ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($imovel_item['vagas'])): ?>
                                        <span><i class="fas fa-car"></i> <?php echo $imovel_item['vagas']; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="produto.php?id=<?php echo $imovel_item['id']; ?>" class="btn-view-property">Ver Detalhes</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Investment Benefits -->
    <section class="benefits-section">
        <div class="container">
            <div class="section-header">
                <h2>Por que Investir em Studios?</h2>
                <p>Vantagens exclusivas deste tipo de investimento</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Alta Rentabilidade</h3>
                    <p>ROI superior comparado a outros tipos de imóveis, com retorno médio de 15% ao ano.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Alta Demanda</h3>
                    <p>Mercado aquecido com procura constante por locação, reduzindo riscos de vacância.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3>Ticket Menor</h3>
                    <p>Entrada mais acessível, permitindo diversificação da carteira imobiliária.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Segurança</h3>
                    <p>Investimento sólido com valorização consistente e proteção contra inflação.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Pronto para Investir?</h2>
                    <p>Entre em contato com nossos especialistas e descubra as melhores oportunidades do mercado</p>
                    <div class="cta-actions">
                        <a href="contato.php" class="btn-primary btn-large">
                            <i class="fas fa-phone"></i>
                            Falar com Especialista
                        </a>
                        <a href="corretores.php" class="btn-secondary btn-large">
                            <i class="fas fa-users"></i>
                            Conhecer Corretores
                        </a>
                    </div>
                </div>
                <div class="cta-visual">
                    <img src="assets/images/imoveis/imovel-3.jpeg" alt="Investimento">
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/whatsapp.php'; ?>
<?php include 'includes/footer.php'; ?>

<?php 
// Versionamento para forçar atualização do cache
$version = '1.0.0';
?>
<script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
