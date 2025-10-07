<?php 
// Buscar dados do imóvel do banco de dados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';
require_once __DIR__ . '/classes/Corretor.php';

$imovel_data = null;
$imovel_id = $_GET['id'] ?? null;

if ($imovel_id) {
    try {
        $imovel = new Imovel();
        $imovel_data = $imovel->buscarPorId($imovel_id);
        
        if (!$imovel_data) {
            // Se não encontrou o imóvel, redirecionar para página de imóveis
            header('Location: imoveis.php');
            exit;
        }
        
        // Buscar corretor responsável (usar primeiro corretor disponível se não há corretor_id)
        $corretor = new Corretor();
        $corretor_data = null;
        if (!empty($imovel_data['corretor_id'])) {
            $corretor_data = $corretor->buscarPorId($imovel_data['corretor_id']);
        } else {
            // Se não há corretor específico, buscar o primeiro corretor ativo
            $corretores_disponiveis = $corretor->listarTodos();
            if (!empty($corretores_disponiveis) && is_array($corretores_disponiveis)) {
                $corretor_data = $corretores_disponiveis[0];
            }
        }
        
        // Definir título e CSS baseado no imóvel
        $page_title = htmlspecialchars($imovel_data['titulo']) . ' - Investimento | Br2Studios';
        $page_css = 'assets/css/produto.css';
        
    } catch (Exception $e) {
        error_log("Erro ao buscar imóvel: " . $e->getMessage());
        // Em caso de erro, usar dados padrão
        $imovel_data = null;
    }
}

// Se não há dados do imóvel, usar dados padrão
if (!$imovel_data) {
    $page_title = 'Studio Premium - Investimento com 25% de Valorização | Br2Studios';
    $page_css = 'assets/css/produto.css';
}

$current_page = 'produto';
include 'includes/header.php'; 
?>
<style>
    .product-hero-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
        position: relative;
        z-index: 2;
        min-height: auto;
        margin-top: 5%;
    }
    
    /* Forçar valores específicos para desktop */
    @media (min-width: 769px) {
        .product-hero {
            padding: 100px 0 80px !important;
            min-height: auto !important;
        }
        
        .product-info h1 {
            font-size: 2.8rem !important;
            margin-bottom: 20px !important;
        }
        
        .product-subtitle {
            font-size: 1.1rem !important;
            margin-bottom: 25px !important;
        }
        
        .product-highlights {
            gap: 20px !important;
            margin-bottom: 30px !important;
        }
        
        .product-price {
            padding: 25px !important;
            margin-bottom: 30px !important;
        }
        
        .price-value {
            font-size: 2.5rem !important;
        }
        
        .main-image img {
            height: 400px !important;
        }
        
        .btn-primary,
        .btn-whatsapp {
            padding: 16px 28px !important;
            font-size: 1rem !important;
        }
    }
    
    /* Estilos do Mapa */
    .map-container {
        position: relative;
        margin-top: 20px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        background: #f8f9fa;
    }
    
    .map-container iframe {
        width: 100%;
        height: 300px;
        border: none;
        border-radius: 15px;
    }
    
    .map-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 300px;
    }
    
    .map-info h4 {
        margin: 0 0 8px 0;
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    
    .map-info p {
        margin: 0 0 10px 0;
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }
    
    .btn-map-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #dc2626;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-map-link:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    
    .btn-map-link i {
        font-size: 0.8rem;
    }
    
    /* Responsividade do mapa */
    @media (max-width: 768px) {
        .map-container iframe {
            height: 250px;
        }
        
        .map-overlay {
            position: static;
            margin-top: 15px;
            max-width: none;
            background: #ffffff;
        }
        .product-title-mobile {
        display: block !important;
        font-size: 2.2rem;
        text-align: center !important;
        margin: 0 auto 25px auto;
        color: var(--text-primary);
        font-weight: 700;
        line-height: 1.2;
        width: 100%;
        padding: 0 20px;
        word-wrap: break-word;
        max-width: 100%;
        margin-top: 17%;
    }
    .location-section {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    padding: 25px;
    margin-bottom: 30px;
    margin-top: -15%;
}
    }
</style>
    <!-- Hero Product Section -->
    <section class="product-hero">
        <div class="container">
            <div class="product-hero-content">
                <!-- Título separado para reordenação no mobile -->
                <?php if ($imovel_data): ?>
                    <h1 class="product-title-mobile"><?php echo htmlspecialchars($imovel_data['titulo']); ?></h1>
                <?php else: ?>
                    <h1 class="product-title-mobile">Studio Premium em São Paulo</h1>
                <?php endif; ?>
                
                <div class="product-info">
                    <?php if ($imovel_data): ?>
                        <h1 class="product-title-desktop"><?php echo htmlspecialchars($imovel_data['titulo']); ?></h1>
                        <p class="product-subtitle"><?php echo htmlspecialchars($imovel_data['descricao'] ?: 'Investimento com excelente potencial de valorização'); ?></p>
                        
                        <div class="product-highlights">
                            <div class="highlight-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Excelente Valorização</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo $imovel_data['status'] === 'disponivel' ? 'Disponível' : ucfirst($imovel_data['status']); ?></span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($imovel_data['cidade'] . ' - ' . $imovel_data['estado']); ?></span>
                            </div>
                            <?php if (!empty($imovel_data['tipo'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-home"></i>
                                <span><?php echo htmlspecialchars(ucfirst($imovel_data['tipo'])); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($imovel_data['area'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span><?php echo $imovel_data['area']; ?>m²</span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($imovel_data['quartos'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-bed"></i>
                                <span><?php echo $imovel_data['quartos']; ?> quarto<?php echo $imovel_data['quartos'] > 1 ? 's' : ''; ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($imovel_data['banheiros'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-bath"></i>
                                <span><?php echo $imovel_data['banheiros']; ?> banheiro<?php echo $imovel_data['banheiros'] > 1 ? 's' : ''; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-price">
                            <div class="price-main">
                                <span class="price-label">Preço</span>
                                <span class="price-value">R$ <?php echo number_format($imovel_data['preco'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="price-details">
                                <?php if (!empty($imovel_data['area']) && $imovel_data['area'] > 0): ?>
                                    <span class="price-per-sqm">R$ <?php echo number_format($imovel_data['preco'] / $imovel_data['area'], 0, ',', '.'); ?>/m²</span>
                                <?php endif; ?>
                                <span class="price-financing">Financiamento disponível</span>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <a href="https://wa.me/554188049999?text=Olá! Gostaria de saber mais sobre <?php echo urlencode($imovel_data['titulo']); ?>" target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                Falar no WhatsApp
                            </a>
                        </div>
                    <?php else: ?>
                        <h1 class="product-title-desktop">Studio Premium em São Paulo</h1>
                        <p class="product-subtitle">Investimento com potencial de 25% de valorização em 18 meses</p>
                        
                        <div class="product-highlights">
                            <div class="highlight-item">
                                <i class="fas fa-chart-line"></i>
                                <span>25% de Valorização</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-clock"></i>
                                <span>18 Meses</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Vila Madalena</span>
                            </div>
                        </div>
                        
                        <div class="product-price">
                            <div class="price-main">
                                <span class="price-label">Preço</span>
                                <span class="price-value">R$ 280.000</span>
                            </div>
                            <div class="price-details">
                                <span class="price-per-sqm">R$ 7.000/m²</span>
                                <span class="price-financing">Financiamento a partir de R$ 56.000</span>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <a href="#contato-rapido" class="btn-primary">QUERO INVESTIR AGORA</a>
                            <a href="https://wa.me/554188049999?text=Olá! Gostaria de saber mais sobre o Studio Premium em São Paulo" target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                Falar no WhatsApp
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-gallery">
                    <?php if ($imovel_data && !empty($imovel_data['imagem_principal'])): ?>
                        <div class="main-image">
                            <img src="<?php echo htmlspecialchars($imovel_data['imagem_principal']); ?>" 
                                 alt="<?php echo htmlspecialchars($imovel_data['titulo']); ?>">
                            <?php if ($imovel_data['destaque']): ?>
                                <div class="image-overlay">
                                    <span class="overlay-text">DESTAQUE</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($imovel_data['imagens'])): ?>
                            <div class="thumbnail-grid">
                                <?php 
                                $imagens = explode(',', $imovel_data['imagens']);
                                foreach ($imagens as $index => $imagem): 
                                    $imagem = trim($imagem);
                                    if (!empty($imagem)):
                                ?>
                                    <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="<?php echo htmlspecialchars($imagem); ?>" 
                                             alt="<?php echo htmlspecialchars($imovel_data['titulo'] . ' - Vista ' . ($index + 1)); ?>">
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="main-image">
                            <img src="assets/images/imoveis/studio-1.jpg.svg" alt="Studio Premium - Vista Principal">
                            <div class="image-overlay">
                                <span class="overlay-text">DESTAQUE</span>
                            </div>
                        </div>
                        <div class="thumbnail-grid">
                            <div class="thumbnail active">
                                <img src="assets/images/imoveis/studio-1.jpg.svg" alt="Studio Premium - Vista 1">
                            </div>
                            <div class="thumbnail">
                                <img src="assets/images/imoveis/studio-2.jpg.svg" alt="Studio Premium - Vista 2">
                            </div>
                            <div class="thumbnail">
                                <img src="assets/images/imoveis/studio-3.jpg.svg" alt="Studio Premium - Vista 3">
                            </div>
                            <div class="thumbnail">
                                <img src="assets/images/imoveis/studio-4.jpg.svg" alt="Studio Premium - Vista 4">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Descrição Mobile (aparece após a galeria) -->
                <div class="mobile-description-section">
                    <?php if ($imovel_data): ?>
                        <div class="mobile-description">
                            <p><?php echo htmlspecialchars($imovel_data['descricao'] ?: 'Investimento com excelente potencial de valorização'); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="mobile-description">
                            <p>Investimento com potencial de 25% de valorização em 18 meses</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Características Mobile (aparece após a descrição) -->
                <div class="mobile-highlights-section">
                    <?php if ($imovel_data): ?>
                        <div class="product-highlights">
                            <div class="highlight-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Excelente Valorização</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo $imovel_data['status'] === 'disponivel' ? 'Disponível' : ucfirst($imovel_data['status']); ?></span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($imovel_data['cidade'] . ' - ' . $imovel_data['estado']); ?></span>
                            </div>
                            <?php if (!empty($imovel_data['tipo'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-home"></i>
                                <span><?php echo htmlspecialchars(ucfirst($imovel_data['tipo'])); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($imovel_data['area'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span><?php echo $imovel_data['area']; ?>m²</span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($imovel_data['quartos'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-bed"></i>
                                <span><?php echo $imovel_data['quartos']; ?> quarto<?php echo $imovel_data['quartos'] > 1 ? 's' : ''; ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($imovel_data['banheiros'])): ?>
                            <div class="highlight-item">
                                <i class="fas fa-bath"></i>
                                <span><?php echo $imovel_data['banheiros']; ?> banheiro<?php echo $imovel_data['banheiros'] > 1 ? 's' : ''; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="product-highlights">
                            <div class="highlight-item">
                                <i class="fas fa-chart-line"></i>
                                <span>25% de Valorização</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-clock"></i>
                                <span>18 Meses</span>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Vila Madalena</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Preço Mobile (aparece após as características) -->
                <div class="mobile-price-section">
                    <?php if ($imovel_data): ?>
                        <div class="product-price">
                            <div class="price-main">
                                <span class="price-label">Preço</span>
                                <span class="price-value">R$ <?php echo number_format($imovel_data['preco'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="price-details">
                                <?php if (!empty($imovel_data['area']) && $imovel_data['area'] > 0): ?>
                                    <span class="price-per-sqm">R$ <?php echo number_format($imovel_data['preco'] / $imovel_data['area'], 0, ',', '.'); ?>/m²</span>
                                <?php endif; ?>
                                <span class="price-financing">Financiamento disponível</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="product-price">
                            <div class="price-main">
                                <span class="price-label">Preço</span>
                                <span class="price-value">R$ 280.000</span>
                            </div>
                            <div class="price-details">
                                <span class="price-per-sqm">R$ 7.000/m²</span>
                                <span class="price-financing">Financiamento a partir de R$ 56.000</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Ações Mobile (aparece por último) -->
                <div class="mobile-actions-section">
                    <?php if ($imovel_data): ?>
                        <div class="product-actions">
                            <a href="#contato-rapido" class="btn-primary">QUERO INVESTIR AGORA</a>
                            <a href="https://wa.me/554188049999?text=Olá! Gostaria de saber mais sobre <?php echo urlencode($imovel_data['titulo']); ?>" target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                Falar no WhatsApp
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="product-actions">
                            <a href="#contato-rapido" class="btn-primary">QUERO INVESTIR AGORA</a>
                            <a href="https://wa.me/554188049999?text=Olá! Gostaria de saber mais sobre o Studio Premium em São Paulo" target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                Falar no WhatsApp
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Property Details -->
    <section class="property-details">
        <div class="container">
            <div class="details-grid">
                <div class="details-main">
                    <div class="details-header">
                        <div class="header-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="header-content">
                            <h2>Detalhes do Imóvel</h2>
                            <p>Informações técnicas e características principais</p>
                        </div>
                    </div>
                    
                    <!-- Características -->
                    <div class="details-section main-features">
                        <h3>Características</h3>
                        
                        <div class="features-simple">
                            <?php if ($imovel_data): ?>
                                <?php if (!empty($imovel_data['area'])): ?>
                                <div class="feature-simple">
                                    <i class="fas fa-ruler-combined"></i>
                                    <span><?php echo number_format($imovel_data['area'], 0, ',', '.'); ?>m²</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['quartos'])): ?>
                                <div class="feature-simple">
                                    <i class="fas fa-bed"></i>
                                    <span><?php echo $imovel_data['quartos']; ?> quarto<?php echo $imovel_data['quartos'] > 1 ? 's' : ''; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['banheiros'])): ?>
                                <div class="feature-simple">
                                    <i class="fas fa-bath"></i>
                                    <span><?php echo $imovel_data['banheiros']; ?> banheiro<?php echo $imovel_data['banheiros'] > 1 ? 's' : ''; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['vagas'])): ?>
                                <div class="feature-simple">
                                    <i class="fas fa-car"></i>
                                    <span><?php echo $imovel_data['vagas']; ?> vaga<?php echo $imovel_data['vagas'] > 1 ? 's' : ''; ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['ano_entrega'])): ?>
                                <div class="feature-simple">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Entrega <?php echo $imovel_data['ano_entrega']; ?></span>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="feature-simple">
                                    <i class="fas fa-ruler-combined"></i>
                                    <span>40m²</span>
                                </div>
                                <div class="feature-simple">
                                    <i class="fas fa-bed"></i>
                                    <span>1 quarto</span>
                                </div>
                                <div class="feature-simple">
                                    <i class="fas fa-bath"></i>
                                    <span>1 banheiro</span>
                                </div>
                                <div class="feature-simple">
                                    <i class="fas fa-car"></i>
                                    <span>1 vaga</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Características Detalhadas -->
                    <?php if ($imovel_data && !empty($imovel_data['caracteristicas'])): ?>
                    <div class="details-section characteristics-section">
                        <h3>Características Detalhadas</h3>
                        
                        <div class="characteristics-content">
                            <?php 
                            // Converter quebras de linha para HTML
                            $caracteristicas_formatadas = nl2br(htmlspecialchars($imovel_data['caracteristicas']));
                            echo $caracteristicas_formatadas;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Amenidades -->
                    <?php if ($imovel_data && !empty($imovel_data['categorias']) && is_array($imovel_data['categorias'])): ?>
                    <div class="details-section amenities-section">
                        <h3>Amenidades</h3>
                        
                        <div class="amenities-simple">
                            <?php foreach ($imovel_data['categorias'] as $categoria): ?>
                                <div class="amenity-simple">
                                    <i class="fas fa-check"></i>
                                    <span><?php echo htmlspecialchars($categoria['nome']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Descrição Completa -->
                    <?php if ($imovel_data && !empty($imovel_data['descricao'])): ?>
                    <div class="details-section description-section">
                        <div class="section-header">
                            <h3><i class="fas fa-file-alt"></i> Descrição</h3>
                          
                        </div>
                       
                    </div>
                    <?php endif; ?>
                    
                    <!-- Localização -->
                    <div class="details-section location-section">
                        <h3>Localização</h3>
                        
                        <div class="location-simple">
                            <?php if ($imovel_data): ?>
                                <?php if (!empty($imovel_data['endereco'])): ?>
                                <div class="location-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($imovel_data['endereco']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['cidade']) && !empty($imovel_data['estado'])): ?>
                                <div class="location-item">
                                    <i class="fas fa-city"></i>
                                    <span><?php echo htmlspecialchars($imovel_data['cidade'] . ' - ' . $imovel_data['estado']); ?></span>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="location-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Vila Madalena, São Paulo - SP</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Mapa do Google Maps -->
                        <div class="map-container">
                            <?php 
                            // Preparar endereço para o Google Maps
                            if ($imovel_data) {
                                $endereco_maps = '';
                                if (!empty($imovel_data['endereco'])) {
                                    $endereco_maps .= $imovel_data['endereco'];
                                }
                                if (!empty($imovel_data['cidade'])) {
                                    $endereco_maps .= ($endereco_maps ? ', ' : '') . $imovel_data['cidade'];
                                }
                                if (!empty($imovel_data['estado'])) {
                                    $endereco_maps .= ($endereco_maps ? ', ' : '') . $imovel_data['estado'];
                                }
                                if (!empty($imovel_data['cep'])) {
                                    $endereco_maps .= ($endereco_maps ? ', ' : '') . $imovel_data['cep'];
                                }
                                $endereco_maps = $endereco_maps ?: 'Curitiba, PR';
                                $titulo_maps = $imovel_data['titulo'];
                            } else {
                                $endereco_maps = 'Vila Madalena, São Paulo, SP';
                                $titulo_maps = 'Studio Premium em São Paulo';
                            }
                            
                            // URL simples do Google Maps
                            $endereco_url = urlencode($endereco_maps);
                            $maps_url = "https://maps.google.com/maps?q=" . $endereco_url . "&t=&z=15&ie=UTF8&iwloc=&output=embed";
                            ?>
                            
                            <iframe 
                                src="<?php echo $maps_url; ?>" 
                                width="100%" 
                                height="300" 
                                style="border:0; border-radius: 15px;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Localização do Imóvel">
                            </iframe>
                            
                            <div class="map-overlay">
                                <div class="map-info">
                                    <h4>Localização do Imóvel</h4>
                                    <p><?php echo htmlspecialchars($endereco_maps); ?></p>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $endereco_url; ?>" 
                                       target="_blank" 
                                       class="btn-map-link">
                                        <i class="fas fa-external-link-alt"></i>
                                        Abrir no Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
              
            </div>
        </div>
    </section>

    <!-- Investment Benefits -->
    <section class="investment-benefits">
        <div class="container">
            <div class="section-header">
                <h2>Por que investir neste imóvel?</h2>
                <p>Descubra as vantagens exclusivas deste investimento</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Alta Valorização</h3>
                    <p>Localização privilegiada com histórico de valorização acima da média do mercado imobiliário.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3>Rentabilidade Garantida</h3>
                    <p>Aluguel estimado superior à prestação do financiamento, gerando lucro mensal consistente.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Localização Estratégica</h3>
                    <p>Próximo ao metrô, com shoppings, hospitais e excelente infraestrutura urbana.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Segurança Jurídica</h3>
                    <p>Imóvel 100% regularizado com documentação em dia e assessoria completa da Br2Studios.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Entrega Rápida</h3>
                    <p>Entrega programada permitindo começar a gerar renda rapidamente.</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Suporte Completo</h3>
                    <p>Assessoria em todo o processo: compra, financiamento, aluguel e gestão do imóvel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Theme Toggle Button -->
    <div class="theme-toggle">
        <button id="theme-toggle-btn" class="theme-toggle-btn">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <?php include 'includes/whatsapp.php'; ?>
    <?php include 'includes/footer.php'; ?>

    <?php 
    // Versionamento para forçar atualização do cache
    require_once __DIR__ . '/config/version.php';
    $version = getAssetsVersion();
    ?>
    <script src="assets/js/main.js?v=<?php echo $version; ?>"></script>
    <script src="assets/js/produto.js?v=<?php echo $version; ?>"></script>
</body>
</html>
