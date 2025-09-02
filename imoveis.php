<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

$imovel = new Imovel();

// Filtros
$filters = [];
if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
    $filters['tipo'] = $_GET['tipo'];
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
$page_title = 'Imóveis - Br2Studios';
$page_css = 'assets/css/imoveis.css';
include 'includes/header.php'; 
?>

    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1>Portfólio de Imóveis</h1>
                    <p>Descubra os melhores imóveis em todo o Brasil</p>
                    <div class="banner-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($imoveis); ?>+</span>
                            <span class="stat-label">Imóveis</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">6</span>
                            <span class="stat-label">Estados</span>
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

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-header">
                    <h2>Encontre seu Investimento</h2>
                    <p>Filtre por tipo, localização e faixa de preço</p>
                </div>
                
                <form class="search-form" method="GET">
                    <div class="search-filters">
                        <div class="filter-group">
                            <label for="tipo">Tipo de Imóvel</label>
                            <select name="tipo" id="tipo" class="filter-select">
                                <option value="">Todos os tipos</option>
                                <option value="studio" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'studio') ? 'selected' : ''; ?>>Studio</option>
                                <option value="apartamento" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'apartamento') ? 'selected' : ''; ?>>Apartamento</option>
                                <option value="casa" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'casa') ? 'selected' : ''; ?>>Casa</option>
                                <option value="comercial" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'comercial') ? 'selected' : ''; ?>>Comercial</option>
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

    <!-- Properties Grid -->
    <section class="properties-section">
        <div class="container">
            <div class="section-header">
                <h2>Imóveis Disponíveis</h2>
                <p>Seleção criteriosa de investimentos com alto potencial de retorno</p>
            </div>
            
            <?php if (empty($imoveis)): ?>
                <div class="no-results" style="text-align: center; padding: 80px 20px;">
                    <i class="fas fa-search" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 style="font-size: 1.8rem; color: #666; margin-bottom: 15px;">Nenhum imóvel encontrado</h3>
                    <p style="color: #999; margin-bottom: 30px;">Tente ajustar os filtros de busca ou entre em contato conosco.</p>
                    <a href="contato.php" class="btn-primary" style="display: inline-flex; padding: 15px 30px;">
                        <i class="fas fa-phone"></i>
                        Falar com Corretor
                    </a>
                </div>
            <?php else: ?>
                <div class="properties-grid">
                    <?php foreach ($imoveis as $imovel_item): ?>
                        <div class="property-card">
                            <div class="property-image">
                                <?php 
                                $imagem = isset($imovel_item['imagem_principal']) && !empty($imovel_item['imagem_principal']) 
                                    ? $imovel_item['imagem_principal'] 
                                    : 'assets/images/imoveis/Imovel-1.jpeg'; // Imagem padrão
                                ?>
                                <img src="<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($imovel_item['titulo']); ?>">
                                <?php if (isset($imovel_item['destaque']) && $imovel_item['destaque']): ?>
                                    <div class="property-badge">
                                        <i class="fas fa-star"></i>
                                        <span>Destaque</span>
                                    </div>
                                <?php endif; ?>
                                <div class="property-price">
                                    <span class="price">R$ <?php echo number_format($imovel_item['preco'], 0, ',', '.'); ?></span>
                                    <span class="price-per-sqft">R$ <?php echo number_format($imovel_item['preco'] / $imovel_item['area'], 0, ',', '.'); ?>/m²</span>
                                </div>
                            </div>
                            
                            <div class="property-content">
                                <div class="property-header">
                                    <h3><?php echo htmlspecialchars($imovel_item['titulo']); ?></h3>
                                    <div class="property-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($imovel_item['cidade']); ?>, <?php echo htmlspecialchars($imovel_item['estado']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="property-details">
                                    <div class="detail-item">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span><?php echo $imovel_item['area']; ?>m²</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-bed"></i>
                                        <span><?php echo $imovel_item['quartos']; ?> quarto</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-bath"></i>
                                        <span><?php echo $imovel_item['banheiros']; ?> banheiro</span>
                                    </div>
                                </div>
                                
                                <div class="property-type">
                                    <span class="type-tag"><?php echo ucfirst(htmlspecialchars($imovel_item['tipo'])); ?></span>
                                </div>
                                
                                <div class="property-actions">
                                    <a href="produto.php?id=<?php echo $imovel_item['id']; ?>" class="btn-primary">
                                        <i class="fas fa-info-circle"></i>
                                        Ver Detalhes
                                    </a>
                                    <a href="https://wa.me/554141410093?text=Olá! Gostaria de saber mais sobre o imóvel <?php echo urlencode($imovel_item['titulo']); ?>" class="btn-whatsapp" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                        WhatsApp
                                    </a>
                                </div>
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
