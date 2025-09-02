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

    <!-- Hero Product Section -->
    <section class="product-hero">
        <div class="container">
            <div class="product-hero-content">
                <div class="product-info">
                    <?php if ($imovel_data): ?>
                        <h1><?php echo htmlspecialchars($imovel_data['titulo']); ?></h1>
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
                            <a href="#contato-rapido" class="btn-primary">QUERO INVESTIR AGORA</a>
                            <a href="https://wa.me/554141410093?text=Olá! Gostaria de saber mais sobre <?php echo urlencode($imovel_data['titulo']); ?>" target="_blank" class="btn-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                Falar no WhatsApp
                            </a>
                        </div>
                    <?php else: ?>
                        <h1>Studio Premium em São Paulo</h1>
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
                            <a href="https://wa.me/554141410093?text=Olá! Gostaria de saber mais sobre o Studio Premium em São Paulo" target="_blank" class="btn-whatsapp">
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
                    
                    <!-- Características Principais -->
                    <div class="details-section main-features">
                        <div class="section-header">
                            <h3><i class="fas fa-home"></i> Características Principais</h3>
                            <p>Dados técnicos e estruturais do imóvel</p>
                        </div>
                        
                        <div class="features-container">
                            <div class="features-grid">
                                <?php if ($imovel_data): ?>
                                    <?php if (!empty($imovel_data['area'])): ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-ruler-combined"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value"><?php echo number_format($imovel_data['area'], 0, ',', '.'); ?>m²</span>
                                            <span class="feature-label">Área Total</span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($imovel_data['quartos'])): ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-bed"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value"><?php echo $imovel_data['quartos']; ?></span>
                                            <span class="feature-label">Quarto<?php echo $imovel_data['quartos'] > 1 ? 's' : ''; ?></span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($imovel_data['banheiros'])): ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-bath"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value"><?php echo $imovel_data['banheiros']; ?></span>
                                            <span class="feature-label">Banheiro<?php echo $imovel_data['banheiros'] > 1 ? 's' : ''; ?></span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($imovel_data['vagas'])): ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-car"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value"><?php echo $imovel_data['vagas']; ?></span>
                                            <span class="feature-label">Vaga<?php echo $imovel_data['vagas'] > 1 ? 's' : ''; ?></span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($imovel_data['tipo'])): ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value"><?php echo ucfirst($imovel_data['tipo']); ?></span>
                                            <span class="feature-label">Tipo</span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($imovel_data['status'])): ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value"><?php echo ucfirst($imovel_data['status']); ?></span>
                                            <span class="feature-label">Status</span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-ruler-combined"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value">40m²</span>
                                            <span class="feature-label">Área Total</span>
                                        </div>
                                    </div>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-bed"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value">1</span>
                                            <span class="feature-label">Quarto</span>
                                        </div>
                                    </div>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-bath"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value">1</span>
                                            <span class="feature-label">Banheiro</span>
                                        </div>
                                    </div>
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="fas fa-car"></i>
                                        </div>
                                        <div class="feature-content">
                                            <span class="feature-value">1</span>
                                            <span class="feature-label">Vaga</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Características e Amenidades -->
                    <?php if ($imovel_data && !empty($imovel_data['categorias']) && is_array($imovel_data['categorias'])): ?>
                    <div class="details-section amenities-section">
                        <div class="section-header">
                            <h3><i class="fas fa-star"></i> Características e Amenidades</h3>
                            <p>Descubra todas as comodidades que este imóvel oferece</p>
                        </div>
                        
                        <div class="amenities-container">
                            <div class="amenities-grid">
                                <?php 
                                // Organizar categorias por grupos
                                $grupos = [
                                    'areas_comuns' => ['Academia', 'Piscina', 'Churrasqueira', 'Salão de Festas', 'Jardim', 'Playground', 'Espaço Gourmet', 'Lavanderia', 'Spa', 'Quadra Esportiva', 'Sala de Jogos', 'Biblioteca'],
                                    'infraestrutura' => ['Elevador', 'Portaria 24h', 'Segurança 24h', 'Vagas de Garagem', 'Estacionamento', 'Condomínio Fechado', 'Câmeras de Segurança', 'Alarme', 'Interfone'],
                                    'caracteristicas' => ['Home Office', 'Varanda Gourmet', 'Sacada', 'Aceita Pets', 'Mobiliado', 'Ar Condicionado', 'Aquecimento', 'Internet', 'TV a Cabo'],
                                    'outros' => ['Acessibilidade', 'Área Verde']
                                ];
                                
                                $categorias_organizadas = [];
                                foreach ($grupos as $grupo => $nomes) {
                                    $categorias_organizadas[$grupo] = [];
                                    foreach ($imovel_data['categorias'] as $categoria) {
                                        if (in_array($categoria['nome'], $nomes)) {
                                            $categorias_organizadas[$grupo][] = $categoria;
                                        }
                                    }
                                }
                                
                                // Adicionar categorias não classificadas ao grupo "outros"
                                foreach ($imovel_data['categorias'] as $categoria) {
                                    $encontrada = false;
                                    foreach ($grupos as $grupo => $nomes) {
                                        if (in_array($categoria['nome'], $nomes)) {
                                            $encontrada = true;
                                            break;
                                        }
                                    }
                                    if (!$encontrada) {
                                        $categorias_organizadas['outros'][] = $categoria;
                                    }
                                }
                                
                                $grupo_titulos = [
                                    'areas_comuns' => ['Áreas Comuns', 'fas fa-building', '#3498db'],
                                    'infraestrutura' => ['Infraestrutura', 'fas fa-cogs', '#e74c3c'],
                                    'caracteristicas' => ['Características', 'fas fa-home', '#f39c12'],
                                    'outros' => ['Outros', 'fas fa-plus-circle', '#9b59b6']
                                ];
                                
                                foreach ($categorias_organizadas as $grupo => $categorias):
                                    if (!empty($categorias)):
                                        $grupo_info = $grupo_titulos[$grupo];
                                ?>
                                    <div class="amenity-group" data-group="<?php echo $grupo; ?>">
                                        <div class="group-header" style="border-left-color: <?php echo $grupo_info[2]; ?>;">
                                            <div class="group-icon" style="background: <?php echo $grupo_info[2]; ?>;">
                                                <i class="<?php echo $grupo_info[1]; ?>"></i>
                                            </div>
                                            <div class="group-title">
                                                <h4><?php echo $grupo_info[0]; ?></h4>
                                                <span class="group-count"><?php echo count($categorias); ?> item<?php echo count($categorias) > 1 ? 's' : ''; ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="group-items">
                                            <?php foreach ($categorias as $categoria): ?>
                                                <div class="amenity-item" title="<?php echo htmlspecialchars($categoria['descricao'] ?: $categoria['nome']); ?>">
                                                    <div class="amenity-icon" style="background: <?php echo $grupo_info[2]; ?>;">
                                                        <i class="<?php echo htmlspecialchars($categoria['icone']); ?>"></i>
                                                    </div>
                                                    <div class="amenity-info">
                                                        <span class="amenity-name"><?php echo htmlspecialchars($categoria['nome']); ?></span>
                                                        <?php if (!empty($categoria['descricao'])): ?>
                                                            <small class="amenity-desc"><?php echo htmlspecialchars($categoria['descricao']); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="amenity-badge">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Descrição Completa -->
                    <?php if ($imovel_data && !empty($imovel_data['descricao'])): ?>
                    <div class="details-section description-section">
                        <div class="section-header">
                            <h3><i class="fas fa-file-alt"></i> Descrição</h3>
                            <p>Informações detalhadas sobre o imóvel</p>
                        </div>
                        
                        <div class="description-content">
                            <p><?php echo nl2br(htmlspecialchars($imovel_data['descricao'])); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Localização e Acessos -->
                    <div class="details-section location-section">
                        <div class="section-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Localização e Acessos</h3>
                            <p>Informações sobre a localização e facilidades próximas</p>
                        </div>
                        
                        <div class="location-container">
                            <!-- Endereço Principal -->
                            <?php if ($imovel_data): ?>
                            <div class="location-main-info">
                                <?php if (!empty($imovel_data['endereco'])): ?>
                                <div class="location-main-card">
                                    <div class="location-main-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="location-main-content">
                                        <span class="location-main-label">Endereço</span>
                                        <span class="location-main-value"><?php echo htmlspecialchars($imovel_data['endereco']); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['cidade']) && !empty($imovel_data['estado'])): ?>
                                <div class="location-main-card">
                                    <div class="location-main-icon">
                                        <i class="fas fa-city"></i>
                                    </div>
                                    <div class="location-main-content">
                                        <span class="location-main-label">Localização</span>
                                        <span class="location-main-value"><?php echo htmlspecialchars($imovel_data['cidade'] . ' - ' . $imovel_data['estado']); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($imovel_data['cep'])): ?>
                                <div class="location-main-card">
                                    <div class="location-main-icon">
                                        <i class="fas fa-mail-bulk"></i>
                                    </div>
                                    <div class="location-main-content">
                                        <span class="location-main-label">CEP</span>
                                        <span class="location-main-value"><?php echo htmlspecialchars($imovel_data['cep']); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Facilidades Próximas -->
                            <div class="location-facilities">
                                <h4 class="facilities-title">
                                    <i class="fas fa-star"></i>
                                    Facilidades Próximas
                                </h4>
                                
                                <div class="facilities-grid">
                                    <div class="facility-card">
                                        <div class="facility-icon">
                                            <i class="fas fa-subway"></i>
                                        </div>
                                        <div class="facility-content">
                                            <span class="facility-label">Transporte</span>
                                            <span class="facility-value">Metrô e ônibus próximos</span>
                                        </div>
                                    </div>
                                    
                                    <div class="facility-card">
                                        <div class="facility-icon">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div class="facility-content">
                                            <span class="facility-label">Comércio</span>
                                            <span class="facility-value">Shopping e serviços próximos</span>
                                        </div>
                                    </div>
                                    
                                    <div class="facility-card">
                                        <div class="facility-icon">
                                            <i class="fas fa-hospital"></i>
                                        </div>
                                        <div class="facility-content">
                                            <span class="facility-label">Saúde</span>
                                            <span class="facility-value">Hospitais e clínicas na região</span>
                                        </div>
                                    </div>
                                    
                                    <div class="facility-card">
                                        <div class="facility-icon">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                        <div class="facility-content">
                                            <span class="facility-label">Gastronomia</span>
                                            <span class="facility-value">Restaurantes e bares próximos</span>
                                        </div>
                                    </div>
                                    
                                    <div class="facility-card">
                                        <div class="facility-icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div class="facility-content">
                                            <span class="facility-label">Educação</span>
                                            <span class="facility-value">Escolas e universidades na região</span>
                                        </div>
                                    </div>
                                    
                                    <div class="facility-card">
                                        <div class="facility-icon">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <div class="facility-content">
                                            <span class="facility-label">Lazer</span>
                                            <span class="facility-value">Parques e áreas de recreação</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="details-sidebar">
                    <!-- Ações Rápidas -->
                    <div class="sidebar-section actions-section">
                        <div class="section-header">
                            <h3><i class="fas fa-bolt"></i> Entre em Contato</h3>
                            <p>Fale conosco para mais informações</p>
                        </div>
                        
                        <div class="actions-grid">
                            <a href="#contato-rapido" class="action-btn primary">
                                <i class="fas fa-envelope"></i>
                                <span>Solicitar Informações</span>
                            </a>
                            
                            <a href="https://wa.me/554141410093?text=Olá! Gostaria de saber mais sobre <?php echo urlencode($imovel_data['titulo'] ?? 'este imóvel'); ?>" target="_blank" class="action-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                <span>Falar no WhatsApp</span>
                            </a>
                            
                            <a href="tel:554141410093" class="action-btn phone">
                                <i class="fas fa-phone"></i>
                                <span>Ligar Agora</span>
                            </a>
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

    <!-- Final CTA -->
    <section class="final-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para fazer seu investimento?</h2>
                <p>Entre em contato agora e garanta este imóvel com condições especiais</p>
                <div class="cta-actions">
                    <a href="#contato-rapido" class="btn-primary">QUERO INVESTIR AGORA</a>
                                    <a href="https://wa.me/554141410093?text=Olá! Gostaria de saber mais sobre <?php echo urlencode($imovel_data ? $imovel_data['titulo'] : 'o Studio Premium'); ?>" target="_blank" class="btn-whatsapp">
                    <i class="fab fa-whatsapp"></i>
                    Falar no WhatsApp
                </a>
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

    <script src="assets/js/main.js"></script>
    <script src="assets/js/produto.js"></script>
</body>
</html>
