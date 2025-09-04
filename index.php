<?php 
// Buscar imóveis em destaque do banco de dados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

$imoveis_destaque = [];

try {
    $imovel = new Imovel();
    
    // Buscar imóveis em destaque (status = 'disponivel' e destaque = 1)
    $filtros_destaque = ['destaque' => 1, 'status' => 'disponivel'];
    $imoveis_destaque_result = $imovel->listarTodos($filtros_destaque);
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_destaque_result) && isset($imoveis_destaque_result['imoveis'])) {
        $imoveis_destaque = $imoveis_destaque_result['imoveis'];
    } else {
        $imoveis_destaque = [];
    }
    
    // Limitar a 4 imóveis para a seção de destaque
    $imoveis_destaque = array_slice($imoveis_destaque, 0, 4);
    
} catch (Exception $e) {
    error_log("Erro ao buscar imóveis em destaque: " . $e->getMessage());
    $imoveis_destaque = [];
}

$current_page = 'home';
$page_title = 'Br2Imóveis - Imóveis de Qualidade em Todo o Brasil';
$page_css = 'assets/css/home-sections.css';
include 'includes/header.php'; 
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-slider">
            <div class="slide active">
                <img src="assets/images/hero-3.jpg" alt="Br2Studios - Investimentos Imobiliários">
            </div>
            <div class="slide">
                <img src="assets/images/hero-4.jpg" alt="Equipe Br2Studios">
            </div>
            <div class="slide">
                <img src="assets/images/hero-3.jpg" alt="Studios de Alta Rentabilidade">
            </div>
            <div class="slide">
                <img src="assets/images/hero-4.jpg" alt="Investimentos Seguros">
            </div>
            <div class="slide">
                <img src="assets/images/hero-5.jpg" alt="Especialistas no Nicho">
            </div>
        </div>
        
        <div class="hero-overlay"></div>
        
        <div class="hero-content">
            <h1 class="hero-title">Br2Studios</h1>
            <p class="hero-subtitle">Transformando sonhos em investimentos lucrativos</p>
            
            <div class="hero-slides">
                <div class="hero-slide active">
                    <h2>Valorização Acelerada</h2>
                    <p>Projetos em construção oferecem excelente potencial de valorização até a entrega, maximizando os ganhos do investidor.</p>
                </div>
                <div class="hero-slide">
                    <h2>Investimentos Seguros em Curitiba</h2>
                    <p>Portfólio selecionado de empreendimentos na região metropolitana de Curitiba, garantindo liquidez e segurança ao investidor.</p>
                </div>
                <div class="hero-slide">
                    <h2>Studios de Alta Rentabilidade</h2>
                    <p>Especialistas em identificar imóveis compactos com o melhor potencial de valorização e retorno financeiro.</p>
                </div>
                <div class="hero-slide">
                    <h2>Especialistas no Nicho</h2>
                    <p>Equipe focada exclusivamente em studios, com conhecimento profundo do mercado e análise criteriosa dos melhores empreendimentos.</p>
                </div>
                <div class="hero-slide">
                    <h2>Segurança Jurídica e Financeira</h2>
                    <p>Imóveis 100% regularizados, com assessoria completa em toda a negociação, garantindo transparência e segurança na aquisição.</p>
                </div>
            </div>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Imóveis Vendidos</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">4</span>
                    <span class="stat-label">Regiões</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">98%</span>
                    <span class="stat-label">Clientes Satisfeitos</span>
                </div>
            </div>
        </div>
        
        <!-- Slider Indicators -->
         <div class="slider-indicators" style="visibility: hidden;">
            <span class="indicator active" data-slide="0"></span>
            <span class="indicator" data-slide="1"></span>
            <span class="indicator" data-slide="2"></span>
            <span class="indicator" data-slide="3"></span>
            <span class="indicator" data-slide="4"></span>
        </div> 
    </section>

    <!-- Properties Mobile - Carousel Limpo -->
    <section class="properties-mobile mobile-only">
        <div class="section-header">
            <h2>Imóveis em Destaque</h2>
            <p>Deslize para ver nossas oportunidades</p>
        </div>
        <div class="properties-carousel-mobile">
            <?php if (!empty($imoveis_destaque)): ?>
                <?php foreach ($imoveis_destaque as $imovel): ?>
                    <div class="property-card-mobile">
                        <div class="property-image-mobile" style="background-image: url('<?php echo $imovel['imagem_principal'] ?: 'assets/images/imoveis/Imovel-1.jpeg'; ?>');">
                            <div class="property-badge-mobile">DESTAQUE</div>
                            <?php if (!empty($imovel['ano_entrega'])): ?>
                                <div class="property-delivery-mobile" style="position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">
                                    ENTREGA <?php echo $imovel['ano_entrega']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="property-info-mobile">
                            <h3 class="property-title-mobile"><?php echo htmlspecialchars($imovel['titulo']); ?></h3>
                            <div class="property-location-mobile">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($imovel['cidade'] . ', ' . $imovel['estado']); ?>
                            </div>
                            <div class="property-price-mobile">R$ <?php echo number_format($imovel['preco'], 0, ',', '.'); ?></div>
                            <div class="property-features-mobile">
                                <?php if (!empty($imovel['area'])): ?>
                                    <span class="property-feature-mobile"><i class="fas fa-ruler"></i> <?php echo $imovel['area']; ?>m²</span>
                                <?php endif; ?>
                                <?php if (!empty($imovel['quartos'])): ?>
                                    <span class="property-feature-mobile"><i class="fas fa-bed"></i> <?php echo $imovel['quartos']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($imovel['banheiros'])): ?>
                                    <span class="property-feature-mobile"><i class="fas fa-bath"></i> <?php echo $imovel['banheiros']; ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="produto.php?id=<?php echo $imovel['id']; ?>" class="property-action-mobile">Ver Detalhes</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Exemplo se não houver dados -->
                <div class="property-card-mobile">
                    <div class="property-image-mobile" style="background-image: url('assets/images/imoveis/Imovel-1.jpeg');">
                        <div class="property-badge-mobile">DESTAQUE</div>
                    </div>
                    <div class="property-info-mobile">
                        <h3 class="property-title-mobile">Studio Moderno Centro</h3>
                        <div class="property-location-mobile">
                            <i class="fas fa-map-marker-alt"></i>
                            São Paulo, SP
                        </div>
                        <div class="property-price-mobile">R$ 180.000</div>
                        <div class="property-features-mobile">
                            <span class="property-feature-mobile"><i class="fas fa-ruler"></i> 35m²</span>
                            <span class="property-feature-mobile"><i class="fas fa-bed"></i> 1</span>
                            <span class="property-feature-mobile"><i class="fas fa-bath"></i> 1</span>
                        </div>
                        <a href="produto.php" class="property-action-mobile">Ver Detalhes</a>
                    </div>
                </div>
                <div class="property-card-mobile">
                    <div class="property-image-mobile" style="background-image: url('assets/images/imoveis/imovel-2.jpeg');">
                        <div class="property-badge-mobile">DESTAQUE</div>
                    </div>
                    <div class="property-info-mobile">
                        <h3 class="property-title-mobile">Studio Vista Mar</h3>
                        <div class="property-location-mobile">
                            <i class="fas fa-map-marker-alt"></i>
                            Rio de Janeiro, RJ
                        </div>
                        <div class="property-price-mobile">R$ 250.000</div>
                        <div class="property-features-mobile">
                            <span class="property-feature-mobile"><i class="fas fa-ruler"></i> 42m²</span>
                            <span class="property-feature-mobile"><i class="fas fa-bed"></i> 1</span>
                            <span class="property-feature-mobile"><i class="fas fa-bath"></i> 1</span>
                        </div>
                        <a href="produto.php" class="property-action-mobile">Ver Detalhes</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="imoveis.php" class="btn-primary btn-large">Ver Todos os Imóveis</a>
        </div>
    </section>

    <!-- Features Section Mobile - Limpa e Organizada -->
    <section class="features-mobile mobile-only">
        <div class="section-header">
            <h2>Por que escolher a Br2Studios?</h2>
            <p>Especialistas em investimentos imobiliários</p>
        </div>
        <div class="features-grid-mobile">
            <div class="feature-card-mobile">
                <div class="feature-icon-mobile">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Investimentos Seguros</h3>
                <p>Portfólio selecionado em regiões estratégicas com alta liquidez</p>
            </div>
            <div class="feature-card-mobile">
                <div class="feature-icon-mobile">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Alta Rentabilidade</h3>
                <p>Consultoria especializada para maximizar seus rendimentos</p>
            </div>
            <div class="feature-card-mobile">
                <div class="feature-icon-mobile">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Valorização Acelerada</h3>
                <p>Projetos com excelente potencial de valorização</p>
            </div>
        </div>
    </section>

    <!-- Featured Properties Section Desktop -->
    <section class="featured-properties desktop-only">
        <div class="container">
            <div class="section-header">
                <h2>Imóveis em Destaque</h2>
                <p>Descubra as melhores oportunidades de investimento selecionadas pelos nossos especialistas</p>
            </div>
            
            <div class="properties-carousel">
                <div class="properties-grid">
                    <?php if (!empty($imoveis_destaque)): ?>
                        <?php foreach ($imoveis_destaque as $imovel_item): ?>
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
                                        <?php if ($imovel_item['destaque']): ?>
                                            <span class="label-featured">DESTAQUE</span>
                                        <?php endif; ?>
                                        <?php if (!empty($imovel_item['ano_entrega'])): ?>
                                            <span class="label-delivery" style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">
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
                                    
                                    <?php if (!empty($imovel_item['ano_entrega'])): ?>
                                        <div class="property-delivery">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Entrega <?php echo $imovel_item['ano_entrega']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <a href="produto.php?id=<?php echo $imovel_item['id']; ?>" class="btn-view-property">Ver Detalhes</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Imóveis de exemplo caso não haja dados no banco -->
                        <div class="property-card">
                            <div class="property-image">
                                <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Studio Moderno no Centro - São Paulo">
                                <div class="property-labels">
                                    <span class="label-featured">DESTAQUE</span>
                                </div>
                                <div class="property-price">
                                    <span class="price">R$ 180.000</span>
                                    <span class="price-per-sqft">R$ 5.070/m²</span>
                                </div>
                            </div>
                            <div class="property-info">
                                <h3>Studio Moderno no Centro</h3>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> São Paulo, SP</p>
                                <div class="property-details">
                                    <span><i class="fas fa-ruler-combined"></i> 35m²</span>
                                    <span><i class="fas fa-bed"></i> 1</span>
                                    <span><i class="fas fa-bath"></i> 1</span>
                                    <span><i class="fas fa-car"></i> 1</span>
                                </div>
                                
                                <div class="property-delivery">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Entrega 2025</span>
                                </div>
                                <a href="#" class="btn-view-property">Ver Detalhes</a>
                            </div>
                        </div>
                        
                        <div class="property-card">
                            <div class="property-image">
                                <img src="assets/images/imoveis/imovel-2.jpeg" alt="Studio com Vista para o Mar - Rio de Janeiro">
                                <div class="property-labels">
                                    <span class="label-featured">DESTAQUE</span>
                                </div>
                                <div class="property-price">
                                    <span class="price">R$ 250.000</span>
                                    <span class="price-per-sqft">R$ 5.952/m²</span>
                                </div>
                            </div>
                            <div class="property-info">
                                <h3>Studio com Vista para o Mar</h3>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> Rio de Janeiro, RJ</p>
                                <div class="property-details">
                                    <span><i class="fas fa-ruler-combined"></i> 42m²</span>
                                    <span><i class="fas fa-bed"></i> 1</span>
                                    <span><i class="fas fa-bath"></i> 1</span>
                                    <span><i class="fas fa-car"></i> 1</span>
                                </div>
                                
                                <div class="property-delivery">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Entrega 2025</span>
                                </div>
                                <a href="#" class="btn-view-property">Ver Detalhes</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="section-footer">
                <a href="imoveis.php" class="btn-view-all">Ver Todos os Imóveis</a>
            </div>
        </div>
    </section>


    <!-- Cities Carousel - Mobile e Desktop -->
    <section class="cities-mobile">
        <div class="section-header">
            <h2>Regiões de Curitiba</h2>
            <p>Investimentos na capital paranaense</p>
        </div>
        
        <div class="cities-carousel-wrapper">
            <div class="cities-track">
                <!-- Primeira linha de cidades -->
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Centro</h3>
                        <p>Região central</p>
                        <span class="city-count">15 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Bairro Alto</h3>
                        <p>Região nobre</p>
                        <span class="city-count">12 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Água Verde</h3>
                        <p>Residencial</p>
                        <span class="city-count">8 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Região Metropolitana</h3>
                        <p>Crescimento</p>
                        <span class="city-count">6 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Batel</h3>
                        <p>Comercial</p>
                        <span class="city-count">10 Imóveis</span>
                    </div>
                </div>
                
                <!-- Segunda linha de cidades (duplicada para loop infinito) -->
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Centro</h3>
                        <p>Região central</p>
                        <span class="city-count">15 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Bairro Alto</h3>
                        <p>Região nobre</p>
                        <span class="city-count">12 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Água Verde</h3>
                        <p>Residencial</p>
                        <span class="city-count">8 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Região Metropolitana</h3>
                        <p>Crescimento</p>
                        <span class="city-count">6 Imóveis</span>
                    </div>
                </div>
                
                <div class="city-slide">
                    <div class="city-card-mobile">
                        <h3>Batel</h3>
                        <p>Comercial</p>
                        <span class="city-count">10 Imóveis</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="regioes.php" class="btn-secondary btn-large">Explorar Regiões</a>
        </div>
    </section>

    <!-- Cities Section Desktop -->
    <section class="cities-section desktop-only">
        <div class="container">
            <div class="section-header">
                <h2>Investindo em Curitiba e Região</h2>
                <p>Descubra as melhores oportunidades na região metropolitana de Curitiba</p>
            </div>
            
            <div class="cities-grid">
                <div class="city-card">
                    <div class="city-image">
                        <img src="assets/images/Mapas/Sao_paulo-SP.png" alt="São Paulo - Capital Financeira">
                        <div class="city-overlay">
                            <h3>São Paulo</h3>
                            <p>Capital financeira</p>
                            <span class="properties-count">3 Imóveis</span>
                        </div>
                    </div>
                </div>
                
                <div class="city-card">
                    <div class="city-image">
                        <img src="assets/images/Mapas/Rio_De_Janeiro-RJ.png" alt="Rio de Janeiro - Cidade Maravilhosa">
                        <div class="city-overlay">
                            <h3>Rio de Janeiro</h3>
                            <p>Cidade maravilhosa</p>
                            <span class="properties-count">1 Imóvel</span>
                        </div>
                    </div>
                </div>
                
                <div class="city-card">
                    <div class="city-image">
                        <img src="assets/images/Mapas/Curitiba-PR.png" alt="Curitiba - Capital Verde">
                        <div class="city-overlay">
                            <h3>Curitiba</h3>
                            <p>Capital verde</p>
                            <span class="properties-count">1 Imóvel</span>
                        </div>
                    </div>
                </div>
                
                <div class="city-card">
                    <div class="city-image">
                        <img src="assets/images/Mapas/Fortaleza-CE.png" alt="Fortaleza - Terra da Luz">
                        <div class="city-overlay">
                            <h3>Fortaleza</h3>
                            <p>Terra da luz</p>
                            <span class="properties-count">1 Imóvel</span>
                        </div>
                    </div>
                </div>
            
            </div>
            
            <div class="section-footer">
                <a href="regioes.php" class="btn-view-all">Explorar Todas as Regiões</a>
            </div>
        </div>
    </section>

    <!-- Property Types Section Desktop -->
    <section class="property-types desktop-only">
        <div class="container">
            <div class="section-header">
                <h2>Tipos de Imóveis</h2>
                <p>Especialistas em diferentes categorias para atender todas as necessidades</p>
            </div>
            
            <div class="types-grid">
                <div class="type-card">
                    <div class="type-image">
                        <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Studio - Compactos e Funcionais">
                        <div class="type-overlay">
                            <span class="properties-count">12 Imóveis</span>
                            <h3>Studio</h3>
                            <p>Compactos e funcionais</p>
                            <a href="#" class="btn-more">Ver Imóveis</a>
                        </div>
                    </div>
                </div>
                
                <div class="type-card">
                    <div class="type-image">
                        <img src="assets/images/imoveis/imovel-2.jpeg" alt="Apartamento - Conforto e Praticidade">
                        <div class="type-overlay">
                            <span class="properties-count">8 Imóveis</span>
                            <h3>Apartamento</h3>
                            <p>Conforto e praticidade</p>
                            <a href="#" class="btn-more">Ver Imóveis</a>
                        </div>
                    </div>
                </div>
                
                <div class="type-card">
                    <div class="type-image">
                        <img src="assets/images/imoveis/imovel-3.jpeg" alt="Casa - Espaço e Privacidade">
                        <div class="type-overlay">
                            <span class="properties-count">5 Imóveis</span>
                            <h3>Casa</h3>
                            <p>Espaço e privacidade</p>
                            <a href="#" class="btn-more">Ver Imóveis</a>
                        </div>
                    </div>
                </div>
                
                <div class="type-card">
                    <div class="type-image">
                        <img src="assets/images/imoveis/imovel-4.jpeg" alt="Comercial - Para seu Negócio">
                        <div class="type-overlay">
                            <span class="properties-count">3 Imóveis</span>
                            <h3>Comercial</h3>
                            <p>Para seu negócio</p>
                            <a href="#" class="btn-more">Ver Imóveis</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
     <!-- Features Section Desktop -->
    <section class="features desktop-only">
        <div class="container">
            <div class="section-header">
                <h2>Por que escolher a Br2Studios?</h2>
                <p>Somos especialistas em transformar investimentos imobiliários em oportunidades de crescimento financeiro</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Investimentos Seguros</h3>
                    <p class="feature-description">
                        Portfólio selecionado de empreendimentos em regiões estratégicas, garantindo liquidez 
                        e segurança para seus investimentos imobiliários.
                    </p>
                    <div class="feature-link">
                        <a href="#">Saiba mais <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Gestão Focada no Investidor</h3>
                    <p class="feature-description">
                        Consultoria especializada para otimizar seus rendimentos e garantir eficiência em todo o 
                        processo de investimento e gestão de propriedades.
                    </p>
                    <div class="feature-link">
                        <a href="#">Saiba mais <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="feature-title">Valorização Acelerada</h3>
                    <p class="feature-description">
                        Projetos em construção oferecem excelente potencial de valorização até a entrega, 
                        maximizando o retorno sobre seu investimento.
                    </p>
                    <div class="feature-link">
                        <a href="#">Saiba mais <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Agents Mobile - Cards Simples -->
    <section class="agents-mobile mobile-only">
        <div class="section-header">
            <h2>Nossa Equipe</h2>
            <p>Especialistas em investimentos</p>
        </div>
        <div class="agents-grid-mobile">
            <div class="agent-card-mobile">
                <div class="agent-avatar-mobile">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>João Silva</h3>
                <div class="agent-role-mobile">Especialista em Investimentos</div>
                <div class="agent-stats-mobile">
                    <div class="agent-stat-mobile">
                        <span class="agent-stat-number">150+</span>
                        <span>Vendas</span>
                    </div>
                    <div class="agent-stat-mobile">
                        <span class="agent-stat-number">4.9</span>
                        <span>Avaliação</span>
                    </div>
                </div>
                <div class="agent-contact-mobile">
                    <a href="https://wa.me/554141410093" class="btn-whatsapp-mobile" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
            <div class="agent-card-mobile">
                <div class="agent-avatar-mobile">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Maria Santos</h3>
                <div class="agent-role-mobile">Consultora de Mercado</div>
                <div class="agent-stats-mobile">
                    <div class="agent-stat-mobile">
                        <span class="agent-stat-number">120+</span>
                        <span>Vendas</span>
                    </div>
                    <div class="agent-stat-mobile">
                        <span class="agent-stat-number">4.8</span>
                        <span>Avaliação</span>
                    </div>
                </div>
                <div class="agent-contact-mobile">
                    <a href="https://wa.me/554141410093" class="btn-whatsapp-mobile" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="corretores.php" class="btn-secondary btn-large">Ver Todos os Corretores</a>
        </div>
    </section>

    <!-- Meet Our Agents Section Desktop -->
    <section class="meet-agents desktop-only">
        <div class="container">
            <div class="section-header">
                <h2>Conheça Nossos Especialistas</h2>
                <p>Equipe qualificada para orientar seus investimentos imobiliários</p>
            </div>
            
            <div class="agents-grid">
                <div class="agent-card">
                    <div class="agent-image">
                        <img src="assets/images/imoveis/Imovel-1.jpeg" alt="João Silva - Especialista em Investimentos">
                        <div class="agent-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <h3>João Silva</h3>
                    <p class="agent-title">Especialista em Investimentos</p>
                    <p class="agent-description">Mais de 10 anos de experiência no mercado imobiliário, especializado em studios e investimentos de alto retorno.</p>
                    <div class="agent-stats">
                        <span><i class="fas fa-home"></i> 150+ Vendas</span>
                        <span><i class="fas fa-star"></i> 4.9/5</span>
                    </div>
                    <a href="corretores.php" class="btn-view-profile">Ver Perfil</a>
                </div>
                
                <div class="agent-card">
                    <div class="agent-image">
                        <img src="assets/images/imoveis/imovel-2.jpeg" alt="Maria Santos - Consultora de Mercado">
                        <div class="agent-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <h3>Maria Santos</h3>
                    <p class="agent-title">Consultora de Mercado</p>
                    <p class="agent-description">Especialista em análise de mercado e identificação de oportunidades de investimento em diferentes regiões.</p>
                    <div class="agent-stats">
                        <span><i class="fas fa-home"></i> 120+ Vendas</span>
                        <span><i class="fas fa-star"></i> 4.8/5</span>
                    </div>
                    <a href="corretores.php" class="btn-view-profile">Ver Perfil</a>
                </div>
                
                <div class="agent-card">
                    <div class="agent-image">
                        <img src="assets/images/imoveis/imovel-3.jpeg" alt="Pedro Oliveira - Analista de Investimentos">
                        <div class="agent-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <h3>Pedro Oliveira</h3>
                    <p class="agent-title">Analista de Investimentos</p>
                    <p class="agent-description">Focado em análise de rentabilidade e potencial de valorização de imóveis em todo o Brasil.</p>
                    <div class="agent-stats">
                        <span><i class="fas fa-home"></i> 95+ Vendas</span>
                        <span><i class="fas fa-star"></i> 4.9/5</span>
                    </div>
                    <a href="corretores.php" class="btn-view-profile">Ver Perfil</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Mobile - Slider -->
    <section class="testimonials-mobile mobile-only">
        <div class="section-header">
            <h2>Depoimentos</h2>
            <p>O que nossos clientes dizem</p>
        </div>
        <div class="testimonials-slider-mobile">
            <div class="testimonials-track-mobile">
                <div class="testimonial-slide-mobile active">
                    <div class="testimonial-content-mobile">
                        <div class="testimonial-avatar-mobile">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="testimonial-rating-mobile">
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                        </div>
                        <div class="testimonial-text-mobile">"Investir em Curitiba com a Br2Studios foi a melhor decisão. Meu studio valorizou 35% em 2 anos!"</div>
                        <div class="testimonial-author-mobile">Carlos Mendes</div>
                        <div class="testimonial-role-mobile">Investidor - Água Verde</div>
                    </div>
                </div>
                
                <div class="testimonial-slide-mobile">
                    <div class="testimonial-content-mobile">
                        <div class="testimonial-avatar-mobile">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="testimonial-rating-mobile">
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                        </div>
                        <div class="testimonial-text-mobile">"Equipe profissional em Curitiba. Encontrei o imóvel perfeito no Centro com excelente assessoria."</div>
                        <div class="testimonial-author-mobile">Ana Paula Costa</div>
                        <div class="testimonial-role-mobile">Investidora - Centro</div>
                    </div>
                </div>
                
                <div class="testimonial-slide-mobile">
                    <div class="testimonial-content-mobile">
                        <div class="testimonial-avatar-mobile">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="testimonial-rating-mobile">
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                            <i class="fas fa-star star-mobile"></i>
                        </div>
                        <div class="testimonial-text-mobile">"Excelente retorno no Bairro Alto. A Br2Studios conhece muito bem o mercado de Curitiba."</div>
                        <div class="testimonial-author-mobile">Roberto Almeida</div>
                        <div class="testimonial-role-mobile">Empresário - Bairro Alto</div>
                    </div>
                </div>
            </div>
            
            <div class="testimonials-controls-mobile">
                <button class="testimonial-prev-mobile">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="testimonials-dots-mobile">
                    <span class="testimonial-dot-mobile active" data-slide="0"></span>
                    <span class="testimonial-dot-mobile" data-slide="1"></span>
                    <span class="testimonial-dot-mobile" data-slide="2"></span>
                </div>
                <button class="testimonial-next-mobile">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials Section Desktop - Carrossel Melhorado -->
    <section class="testimonials-desktop desktop-only">
        <div class="container">
            <div class="section-header">
                <h2>O que nossos clientes dizem</h2>
                <p>Depoimentos reais de investidores que confiaram na Br2Studios</p>
            </div>
            
            <div class="testimonials-carousel-desktop">
                <div class="testimonials-wrapper">
                    <div class="testimonials-track">
                        <!-- Depoimento 1 -->
                        <div class="testimonial-slide active">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="quote-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-text">
                                        "Investir em Curitiba com a Br2Studios foi a melhor decisão que tomei. 
                                        Meu studio no Água Verde valorizou 35% em apenas 2 anos! A equipe é 
                                        extremamente profissional e transparente."
                                    </p>
                                    <div class="testimonial-author">
                                        <div class="author-avatar">
                                            <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Carlos Mendes">
                                        </div>
                                        <div class="author-info">
                                            <h4>Carlos Mendes</h4>
                                            <p>Investidor - Água Verde</p>
                                            <span class="author-location">Curitiba, PR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Depoimento 2 -->
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="quote-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-text">
                                        "Equipe profissional e transparente. Consegui encontrar o imóvel perfeito 
                                        no Centro com toda a assessoria necessária. Recomendo para quem busca 
                                        investimentos seguros e rentáveis."
                                    </p>
                                    <div class="testimonial-author">
                                        <div class="author-avatar">
                                            <img src="assets/images/imoveis/imovel-2.jpeg" alt="Ana Paula Costa">
                                        </div>
                                        <div class="author-info">
                                            <h4>Ana Paula Costa</h4>
                                            <p>Investidora - Centro</p>
                                            <span class="author-location">Curitiba, PR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Depoimento 3 -->
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="quote-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-text">
                                        "Excelente retorno no Bairro Alto. A Br2Studios realmente entende do 
                                        mercado imobiliário de Curitiba e oferece as melhores oportunidades. 
                                        Superou todas as minhas expectativas!"
                                    </p>
                                    <div class="testimonial-author">
                                        <div class="author-avatar">
                                            <img src="assets/images/imoveis/imovel-3.jpeg" alt="Roberto Almeida">
                                        </div>
                                        <div class="author-info">
                                            <h4>Roberto Almeida</h4>
                                            <p>Empresário - Bairro Alto</p>
                                            <span class="author-location">Curitiba, PR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Depoimento 4 -->
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="quote-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <div class="testimonial-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-text">
                                        "Atendimento excepcional desde o primeiro contato. A Br2Studios me ajudou 
                                        a encontrar o investimento perfeito na região metropolitana. 
                                        Retorno garantido e processo muito transparente."
                                    </p>
                                    <div class="testimonial-author">
                                        <div class="author-avatar">
                                            <img src="assets/images/imoveis/imovel-4.jpeg" alt="Mariana Silva">
                                        </div>
                                        <div class="author-info">
                                            <h4>Mariana Silva</h4>
                                            <p>Investidora - Região Metropolitana</p>
                                            <span class="author-location">São José dos Pinhais, PR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Controles do Carrossel -->
                <div class="carousel-controls">
                    <div class="carousel-dots">
                        <span class="dot active" data-slide="0"></span>
                        <span class="dot" data-slide="1"></span>
                        <span class="dot" data-slide="2"></span>
                        <span class="dot" data-slide="3"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para começar seu investimento?</h2>
                <p>Entre em contato conosco e descubra as melhores oportunidades do mercado imobiliário</p>
                <div class="cta-actions">
                    <a href="contato.php" class="btn-primary">Falar com Especialista</a>
                    <a href="imoveis.php" class="btn-secondary">Ver Imóveis</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section - Carrossel Automático -->
    <section class="partners-carousel">
        <div class="container">
            <div class="section-header">
                <h2>Nossos Parceiros</h2>
                <p>Empresas que confiam na nossa expertise</p>
            </div>
            
            <div class="partners-carousel-wrapper">
                <div class="partners-track">
                    <!-- Primeira linha de parceiros -->
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h3>WILDRIDGE</h3>
                            <p>Desenvolvimento Sustentável</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-tree"></i>
                            </div>
                            <h3>HILLSTROM REAL ESTATE</h3>
                            <p>Consultoria Imobiliária</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h3>HORIZON HOMES</h3>
                            <p>Construção Residencial</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>HOME Real Estate</h3>
                            <p>SEU PROFISSIONAL IMOBILIÁRIO</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <h3>CHARLES BENTLEY</h3>
                            <p>Investimentos de Luxo</p>
                        </div>
                    </div>
                    
                    <!-- Segunda linha de parceiros (duplicada para loop infinito) -->
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h3>WILDRIDGE</h3>
                            <p>Desenvolvimento Sustentável</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-tree"></i>
                            </div>
                            <h3>HILLSTROM REAL ESTATE</h3>
                            <p>Consultoria Imobiliária</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h3>HORIZON HOMES</h3>
                            <p>Construção Residencial</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>HOME Real Estate</h3>
                            <p>SEU PROFISSIONAL IMOBILIÁRIO</p>
                        </div>
                    </div>
                    
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <div class="partner-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <h3>CHARLES BENTLEY</h3>
                            <p>Investimentos de Luxo</p>
                        </div>
                    </div>
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
    <script src="assets/js/main.js?v=<?php echo $version; ?>"></script>
    <script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
</body>
</html>
