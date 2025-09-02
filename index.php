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
                    <h2>Investimentos Seguros em Todo o Brasil</h2>
                    <p>Portfólio selecionado de empreendimentos em regiões estratégicas, garantindo liquidez e segurança ao investidor.</p>
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
                    <span class="stat-number">12</span>
                    <span class="stat-label">Estados</span>
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

   

    <!-- Featured Properties Section -->
    <section class="featured-properties">
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
                                        <span class="label-status">À VENDA</span>
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
                                        <span class="property-type"><?php echo strtoupper($imovel_item['tipo']); ?></span>
                                    </div>
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
                                    <span class="label-status">À VENDA</span>
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
                                    <span class="property-type">STUDIO</span>
                                </div>
                                <a href="#" class="btn-view-property">Ver Detalhes</a>
                            </div>
                        </div>
                        
                        <div class="property-card">
                            <div class="property-image">
                                <img src="assets/images/imoveis/imovel-2.jpeg" alt="Studio com Vista para o Mar - Rio de Janeiro">
                                <div class="property-labels">
                                    <span class="label-featured">DESTAQUE</span>
                                    <span class="label-status">À VENDA</span>
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
                                    <span class="property-type">STUDIO</span>
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

    <!-- Cities Section -->
    <section class="cities-section">
        <div class="container">
            <div class="section-header">
                <h2>Investindo em Todo o Brasil</h2>
                <p>Descubra as melhores oportunidades em diferentes regiões do país</p>
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

    <!-- Property Types Section -->
    <section class="property-types">
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
 <!-- Features Section -->
 <section class="features">
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
    <!-- Meet Our Agents Section -->
    <section class="meet-agents">
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

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>O que nossos clientes dizem</h2>
                <p>Depoimentos reais de investidores que confiaram na Br2Studios</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="quote-icon">"</div>
                    <p class="testimonial-text">A Br2Studios transformou meu investimento em um negócio lucrativo. O studio que comprei valorizou 40% em apenas 2 anos!</p>
                    <div class="testimonial-author">
                        <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Carlos Mendes - Investidor">
                        <div class="author-info">
                            <h4>Carlos Mendes</h4>
                            <p>Investidor</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="quote-icon">"</div>
                    <p class="testimonial-text">Equipe profissional e transparente. Consegui encontrar o imóvel perfeito para investimento com toda a assessoria necessária.</p>
                    <div class="testimonial-author">
                        <img src="assets/images/imoveis/imovel-2.jpeg" alt="Ana Paula Costa - Investidora">
                        <div class="author-info">
                            <h4>Ana Paula Costa</h4>
                            <p>Investidora</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="quote-icon">"</div>
                    <p class="testimonial-text">Excelente retorno sobre investimento. A Br2Studios realmente entende do mercado e oferece as melhores oportunidades.</p>
                    <div class="testimonial-author">
                        <img src="assets/images/imoveis/imovel-3.jpeg" alt="Roberto Almeida - Empresário">
                        <div class="author-info">
                            <h4>Roberto Almeida</h4>
                            <p>Empresário</p>
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
                <h2>Pronto para começar seu investimento?</h2>
                <p>Entre em contato conosco e descubra as melhores oportunidades do mercado imobiliário</p>
                <div class="cta-actions">
                    <a href="contato.php" class="btn-primary">Falar com Especialista</a>
                    <a href="imoveis.php" class="btn-secondary">Ver Imóveis</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="partners">
        <div class="container">
            <div class="section-header">
                <h2>Nossos Parceiros</h2>
                <p>Empresas que confiam na nossa expertise</p>
            </div>
            
            <div class="partners-grid">
                <div class="partner-logo">
                    <h3>WILDRIDGE</h3>
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="partner-logo">
                    <h3>HILLSTROM REAL ESTATE</h3>
                    <i class="fas fa-tree"></i>
                </div>
                <div class="partner-logo">
                    <h3>HORIZON HOMES</h3>
                    <i class="fas fa-home"></i>
                </div>
                <div class="partner-logo">
                    <h3>HOME Real Estate</h3>
                    <p>SEU PROFISSIONAL IMOBILIÁRIO</p>
                    <i class="fas fa-building"></i>
                </div>
                <div class="partner-logo">
                    <h3>CHARLES BENTLEY</h3>
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Button -->
    <div class="whatsapp-button">
        <a href="https://wa.me/554141410093?text=Olá! Gostaria de saber mais sobre os imóveis da Br2Imóveis" target="_blank">
            <i class="fas fa-whatsapp"></i>
        </a>
    </div>

<?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>
