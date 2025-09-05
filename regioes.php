<?php
require_once __DIR__ . '/classes/Imovel.php';

$imovel = new Imovel();
$regiao = $_GET['regiao'] ?? '';

$regioes = [
    'centro' => [
        'nome' => 'Centro',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Região central de Curitiba com infraestrutura completa e fácil acesso',
        'imoveis' => 15,
        'valorizacao' => 'Alta',
        'destaque' => 'Centro histórico e comercial',
        'cidades' => ['Centro', 'Centro Cívico', 'Batel', 'Água Verde']
    ],
    'bairro-alto' => [
        'nome' => 'Bairro Alto',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Região nobre com excelente infraestrutura e valorização constante',
        'imoveis' => 12,
        'valorizacao' => 'Alta',
        'destaque' => 'Região nobre e valorizada',
        'cidades' => ['Bairro Alto', 'Seminário', 'Cristo Rei', 'Juvevê']
    ],
    'agua-verde' => [
        'nome' => 'Água Verde',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro residencial com ótima localização e crescimento imobiliário',
        'imoveis' => 8,
        'valorizacao' => 'Média-Alta',
        'destaque' => 'Residencial e bem localizado',
        'cidades' => ['Água Verde', 'Rebouças', 'Portão', 'Guabirotuba']
    ],
    'regiao-metropolitana' => [
        'nome' => 'Região Metropolitana',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Cidades da região metropolitana com potencial de crescimento',
        'imoveis' => 6,
        'valorizacao' => 'Média',
        'destaque' => 'Crescimento e oportunidades',
        'cidades' => ['São José dos Pinhais', 'Pinhais', 'Colombo', 'Araucária']
    ]
];

// Buscar imóveis reais do banco de dados para cada região
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

$imoveis_regiao = [];
try {
    $imovel = new Imovel();
    
    if ($regiao && isset($regioes[$regiao])) {
        // Buscar imóveis da região selecionada
        $filtros_regiao = [
            'cidade' => $regioes[$regiao]['nome'],
            'status' => 'disponivel'
        ];
        $resultado = $imovel->listarTodos($filtros_regiao);
        if (is_array($resultado) && isset($resultado['imoveis'])) {
            $imoveis_regiao = $resultado['imoveis'];
        }
    }
    
    // Atualizar contadores de imóveis por região baseado nos dados reais
    foreach ($regioes as $key => &$regiao_data) {
        $filtros_contagem = [
            'cidade' => $regiao_data['nome'],
            'status' => 'disponivel'
        ];
        $resultado_contagem = $imovel->listarTodos($filtros_contagem);
        $regiao_data['imoveis'] = (is_array($resultado_contagem) && isset($resultado_contagem['imoveis'])) ? count($resultado_contagem['imoveis']) : 0;
    }
    
} catch (Exception $e) {
    error_log("Erro ao buscar imóveis da região: " . $e->getMessage());
}
?>

<?php 
$current_page = 'regioes';
$page_title = 'Regiões de Curitiba - Br2Studios';
$page_css = 'assets/css/regioes.css';
include 'includes/header.php'; 
?>

    <!-- Page Banner Desktop -->
    <section class="page-banner desktop-only">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1>Investindo em Curitiba</h1>
                    <p>Descubra as melhores oportunidades de investimento em imóveis na capital paranaense e região metropolitana</p>
                    <div class="banner-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($regioes); ?></span>
                            <span class="stat-label">Regiões</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">41</span>
                            <span class="stat-label">Imóveis</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">98%</span>
                            <span class="stat-label">Satisfação</span>
                        </div>
                    </div>
                </div>
                <div class="banner-visual">
                    <div class="banner-map">
                        <img src="assets/images/imoveis/Imovel-5.jpeg" alt="Mapa do Brasil">
                    </div>
                    <div class="banner-badge">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Cobertura Nacional</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Header Mobile Simples -->
    <section class="regioes-header-mobile mobile-only">
        <div class="container">
            <div class="header-mobile-content">
                <h1>Nossas Regiões</h1>
                <p>Investimentos em <?php echo count($regioes); ?> regiões de Curitiba</p>
                <div class="quick-stats-mobile">
                    <span class="quick-stat">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo count($regioes); ?> Regiões
                    </span>
                    <span class="quick-stat">
                        <i class="fas fa-home"></i>
                        41+ Imóveis
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Regions Overview -->
    <section class="regions-overview">
        <div class="container">
            <div class="section-header">
                <h2>Nossas Regiões de Atuação</h2>
                <p>Seleção estratégica de mercados com alto potencial de valorização</p>
            </div>
            
            <div class="regions-grid">
                <?php foreach ($regioes as $key => $regiao_item): ?>
                    <div class="region-card" onclick="exploreRegion('<?php echo $key; ?>', '<?php echo $regiao_item['nome']; ?>')">
                        <div class="region-map">
                            <img src="<?php echo $regiao_item['imagem']; ?>" alt="<?php echo $regiao_item['nome']; ?>">
                            <div class="region-overlay">
                                <div class="region-info">
                                    <h3><?php echo $regiao_item['nome']; ?></h3>
                                    <p><?php echo $regiao_item['descricao']; ?></p>
                                    <div class="region-stats">
                                        <div class="stat">
                                            <i class="fas fa-home"></i>
                                            <span><?php echo $regiao_item['imoveis']; ?> Imóveis</span>
                                        </div>
                                        <div class="stat">
                                            <i class="fas fa-chart-line"></i>
                                            <span><?php echo $regiao_item['valorizacao']; ?> Valorização</span>
                                        </div>
                                    </div>
                                    <div class="region-highlight">
                                        <i class="fas fa-star"></i>
                                        <span><?php echo $regiao_item['destaque']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="region-content">
                            <h4><?php echo $regiao_item['nome']; ?></h4>
                            <p><?php echo $regiao_item['descricao']; ?></p>
                            <div class="region-metrics">
                                <div class="metric">
                                    <span class="metric-value"><?php echo $regiao_item['imoveis']; ?></span>
                                    <span class="metric-label">Imóveis</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-value"><?php echo $regiao_item['valorizacao']; ?></span>
                                    <span class="metric-label">Valorização</span>
                                </div>
                            </div>
                            <button class="btn-explore" onclick="event.stopPropagation(); exploreRegion('<?php echo $key; ?>', '<?php echo $regiao_item['nome']; ?>')">
                                <i class="fas fa-search"></i>
                                Explorar Região
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Regiões Mobile - Cards Simples -->
    <section class="regioes-mobile mobile-only">
        <div class="section-header">
            <h2>Regiões de Curitiba</h2>
            <p>Escolha seu bairro ideal</p>
        </div>
        <div class="regioes-grid-mobile">
            <?php foreach ($regioes as $key => $regiao_item): ?>
                <div class="regiao-card-mobile" onclick="exploreRegion('<?php echo $key; ?>', '<?php echo $regiao_item['nome']; ?>')">
                    <div class="regiao-icon-mobile">
                        <i class="fas fa-<?php 
                            echo $key === 'sao_paulo' ? 'building' : 
                                ($key === 'rio_janeiro' ? 'beach' : 
                                ($key === 'curitiba' ? 'tree' : 'sun')); 
                        ?>"></i>
                    </div>
                    <div class="regiao-info-mobile">
                        <h3><?php echo $regiao_item['nome']; ?></h3>
                        <span class="regiao-state-mobile"><?php echo $regiao_item['estado']; ?></span>
                        <p><?php echo $regiao_item['destaque']; ?></p>
                        <div class="regiao-stats-mobile">
                            <span class="regiao-stat-mobile">
                                <i class="fas fa-home"></i>
                                <?php echo $regiao_item['imoveis']; ?> imóveis
                            </span>
                            <span class="regiao-stat-mobile">
                                <i class="fas fa-chart-line"></i>
                                <?php echo $regiao_item['valorizacao']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="regiao-arrow-mobile">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            <?php endforeach; ?>
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
                <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Mapa do Brasil">
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/whatsapp.php'; ?>
<?php include 'includes/footer.php'; ?>

<?php 
// Versionamento para forçar atualização do cache
require_once __DIR__ . '/config/version.php';
$version = getAssetsVersion();
?>
<script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
