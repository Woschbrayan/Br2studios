<?php
require_once __DIR__ . '/classes/Imovel.php';

$imovel = new Imovel();
$regiao = $_GET['regiao'] ?? '';

$regioes = [
    'sao-paulo' => [
        'nome' => 'São Paulo',
        'estado' => 'SP',
        'imagem' => 'assets/images/Mapas/Sao_paulo-SP.png',
        'descricao' => 'Capital financeira do Brasil, centro de oportunidades e investimentos',
        'imoveis' => 15,
        'valorizacao' => 'Alta',
        'destaque' => 'Centro financeiro e empresarial',
        'cidades' => ['São Paulo', 'Campinas', 'Santos', 'São José dos Campos']
    ],
    'rio-janeiro' => [
        'nome' => 'Rio de Janeiro',
        'estado' => 'RJ',
        'imagem' => 'assets/images/Mapas/Rio_De_Janeiro-RJ.png',
        'descricao' => 'Cidade maravilhosa com potencial turístico e residencial',
        'imoveis' => 12,
        'valorizacao' => 'Média-Alta',
        'destaque' => 'Turismo e praias',
        'cidades' => ['Rio de Janeiro', 'Niterói', 'Petrópolis', 'Angra dos Reis']
    ],
    'curitiba' => [
        'nome' => 'Curitiba',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Capital verde com qualidade de vida e desenvolvimento sustentável',
        'imoveis' => 8,
        'valorizacao' => 'Média',
        'destaque' => 'Qualidade de vida',
        'cidades' => ['Curitiba', 'Londrina', 'Maringá', 'Ponta Grossa']
    ],
    'fortaleza' => [
        'nome' => 'Fortaleza',
        'estado' => 'CE',
        'imagem' => 'assets/images/Mapas/Fortaleza-CE.png',
        'descricao' => 'Terra da luz com potencial turístico e crescimento econômico',
        'imoveis' => 6,
        'valorizacao' => 'Média',
        'destaque' => 'Turismo e praias',
        'cidades' => ['Fortaleza', 'Caucaia', 'Maracanaú', 'Sobral']
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
$page_title = 'Regiões - Br2Studios';
$page_css = 'assets/css/regioes.css';
include 'includes/header.php'; 
?>

    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1>Investindo em Todo o Brasil</h1>
                    <p>Descubra as melhores oportunidades de investimento em imóveis em todas as regiões do país</p>
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

    <!-- Market Analysis -->
    <section class="market-analysis">
        <div class="container">
            <div class="section-header">
                <h2>Análise de Mercado por Região</h2>
                <p>Insights estratégicos para suas decisões de investimento</p>
            </div>
            
            <div class="analysis-grid">
                <div class="analysis-card">
                    <div class="analysis-icon">
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <h3>São Paulo</h3>
                    <p>Mercado mais maduro com estabilidade e crescimento consistente. Ideal para investidores conservadores.</p>
                    <div class="analysis-metrics">
                        <div class="metric">
                            <span class="label">Valorização Anual</span>
                            <span class="value">8-12%</span>
                        </div>
                        <div class="metric">
                            <span class="label">Liquidez</span>
                            <span class="value">Alta</span>
                        </div>
                    </div>
                </div>
                
                <div class="analysis-card">
                    <div class="analysis-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Rio de Janeiro</h3>
                    <p>Potencial de alta valorização com foco turístico. Oportunidade para investidores com perfil moderado.</p>
                    <div class="analysis-metrics">
                        <div class="metric">
                            <span class="label">Valorização Anual</span>
                            <span class="value">10-15%</span>
                        </div>
                        <div class="metric">
                            <span class="label">Liquidez</span>
                            <span class="value">Média-Alta</span>
                        </div>
                    </div>
                </div>
                
                <div class="analysis-card">
                    <div class="analysis-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Curitiba</h3>
                    <p>Mercado em crescimento com qualidade de vida. Excelente para investidores que buscam estabilidade.</p>
                    <div class="analysis-metrics">
                        <div class="metric">
                            <span class="label">Valorização Anual</span>
                            <span class="value">6-10%</span>
                        </div>
                        <div class="metric">
                            <span class="label">Liquidez</span>
                            <span class="value">Média</span>
                        </div>
                    </div>
                </div>
                
                <div class="analysis-card">
                    <div class="analysis-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3>Fortaleza</h3>
                    <p>Mercado emergente com alto potencial de crescimento. Ideal para investidores com perfil arrojado.</p>
                    <div class="analysis-metrics">
                        <div class="metric">
                            <span class="label">Valorização Anual</span>
                            <span class="value">12-18%</span>
                        </div>
                        <div class="metric">
                            <span class="label">Liquidez</span>
                            <span class="value">Média</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Investment Strategy -->
    <section class="investment-strategy">
        <div class="container">
            <div class="section-header">
                <h2>Estratégia de Investimento Regional</h2>
                <p>Como diversificar seu portfólio imobiliário estrategicamente</p>
            </div>
            
            <div class="strategy-content">
                <div class="strategy-text">
                    <h3>Diversificação Inteligente</h3>
                    <p>Investir em diferentes regiões do Brasil permite reduzir riscos e maximizar retornos. Cada região tem características únicas que se complementam no seu portfólio.</p>
                    
                    <div class="strategy-points">
                        <div class="point">
                            <i class="fas fa-check-circle"></i>
                            <span>Redução de risco geográfico</span>
                        </div>
                        <div class="point">
                            <i class="fas fa-check-circle"></i>
                            <span>Exposição a diferentes ciclos de mercado</span>
                        </div>
                        <div class="point">
                            <i class="fas fa-check-circle"></i>
                            <span>Oportunidades de valorização variadas</span>
                        </div>
                        <div class="point">
                            <i class="fas fa-check-circle"></i>
                            <span>Proteção contra volatilidade local</span>
                        </div>
                    </div>
                </div>
                
                <div class="strategy-visual">
                    <div class="strategy-chart">
                        <div class="chart-item">
                            <div class="chart-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span>São Paulo</span>
                            <div class="chart-bar" style="height: 80%"></div>
                        </div>
                        <div class="chart-item">
                            <div class="chart-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span>Rio de Janeiro</span>
                            <div class="chart-bar" style="height: 65%"></div>
                        </div>
                        <div class="chart-item">
                            <div class="chart-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span>Curitiba</span>
                            <div class="chart-bar" style="height: 45%"></div>
                        </div>
                        <div class="chart-item">
                            <div class="chart-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <span>Fortaleza</span>
                            <div class="chart-bar" style="height: 35%"></div>
                        </div>
                    </div>
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
                <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Mapa do Brasil">
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/whatsapp.php'; ?>
<?php include 'includes/footer.php'; ?>
