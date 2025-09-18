<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';

$imovel = new Imovel();

// Filtros
$filters = [];

// Filtro especial para destaque e valorização (vindo do index.php)
if (isset($_GET['filtro']) && !empty($_GET['filtro'])) {
    if ($_GET['filtro'] == 'destaque') {
        $filters['destaque'] = 1;
    } elseif ($_GET['filtro'] == 'valorizacao') {
        $filters['maior_valorizacao'] = 1;
    }
}

// Filtros de busca
if (isset($_GET['status_construcao']) && !empty($_GET['status_construcao'])) {
    $filters['status_construcao'] = $_GET['status_construcao'];
}
if (isset($_GET['endereco']) && !empty($_GET['endereco'])) {
    $filters['endereco'] = $_GET['endereco'];
}
if (isset($_GET['regiao']) && !empty($_GET['regiao'])) {
    $filters['regiao'] = $_GET['regiao'];
}
if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
    $filters['preco_min'] = floatval($_GET['min_price']);
}
if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $filters['preco_max'] = floatval($_GET['max_price']);
}

// Sempre filtrar apenas imóveis disponíveis
$filters['status'] = 'disponivel';

// Buscar imóveis do banco de dados
try {
    $imoveis_result = $imovel->listarTodos($filters);
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_result) && isset($imoveis_result['imoveis'])) {
        $imoveis = $imoveis_result['imoveis'];
    } else {
        $imoveis = [];
    }
    
    // Buscar total de imóveis disponíveis (sem filtros)
    $totalDisponiveis = $imovel->contarTotal(['status' => 'disponivel']);
    
    // Buscar cidades únicas para o filtro (apenas imóveis disponíveis)
    $filtrosCidades = ['status' => 'disponivel'];
    $todasCidades_result = $imovel->listarTodos($filtrosCidades);
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
    $totalDisponiveis = 0;
}


?>

<?php 
$current_page = 'imoveis';

// Definir título da página baseado no filtro
if (isset($_GET['filtro']) && $_GET['filtro'] == 'destaque') {
    $page_title = 'Imóveis em Destaque - Br2Studios';
    $page_subtitle = 'Imóveis em Destaque';
    $page_description = 'Descubra nossos imóveis em destaque selecionados pelos especialistas';
} elseif (isset($_GET['filtro']) && $_GET['filtro'] == 'valorizacao') {
    $page_title = 'Imóveis de Maior Valorização - Br2Studios';
    $page_subtitle = 'Imóveis de Maior Valorização';
    $page_description = 'Oportunidades com excelente potencial de crescimento e retorno';
} else {
    $page_title = 'Imóveis em Curitiba - Br2Studios';
    $page_subtitle = 'Portfólio de Imóveis';
    $page_description = 'Descubra os melhores imóveis em Curitiba e região metropolitana';
}

$page_css = 'assets/css/imoveis.css';
include 'includes/header.php'; 
?>
<style>
/* ETIQUETAS DOS CARDS - DESKTOP */
.property-labels {
    position: absolute !important;
    top: 15px !important;
    left: 15px !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 8px !important;
    z-index: 15 !important;
    max-width: 350px !important;
    pointer-events: none !important;
    width: auto !important;
    height: auto !important;
    min-height: auto !important;
    overflow: visible !important;
}

.property-badge {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 8px 16px !important;
    font-size: 0.75rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border-radius: 25px !important;
    white-space: nowrap !important;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2) !important;
    backdrop-filter: blur(10px) !important;
    min-height: 32px !important;
    height: auto !important;
}

.badge-destaque {
    background: linear-gradient(135deg, #ff6b35, #ff8e53) !important;
    color: white !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
}

.badge-valorizacao {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    color: white !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
}

.badge-entrega {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    color: white !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
}
/* ESTILOS GERAIS DOS CARDS */
    .property-details {
    display: flex !important
;
    gap: 0px !important;
    margin-bottom: 0px !important;
    flex-wrap: wrap !important;
    min-height: 2rem !important;
}
.property-image {
    position: relative !important;
    height: 340px !important;
    overflow: hidden !important;
}

/* Desktop - Mesmo formato do mobile com mais colunas */
@media (min-width: 769px) {
    .properties-grid {
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)) !important;
        gap: 25px !important;
        padding: 20px 0 !important;
        max-width: 1400px !important;
        margin: 0 auto !important;
        justify-content: center !important;
        align-items: start !important;
    }
    
    .property-card {
        width: 100% !important;
        min-width: 350px !important;
        max-width: 400px !important;
        height: auto !important;
        margin: 0 auto !important;
        border-radius: 20px !important;
        overflow: hidden !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .property-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
    
    .property-image {
        height: 240px !important;
        position: relative !important;
        overflow: hidden !important;
        flex-shrink: 0 !important;
    }
    
    .property-image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.3s ease !important;
    }
    
    .property-card:hover .property-image img {
        transform: scale(1.05) !important;
    }
    
    .property-info {
        padding: 20px !important;
        background: #ffffff !important;
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .property-info h3 {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin-bottom: 12px !important;
        line-height: 1.3 !important;
    }
    
    .property-location-container {
        width: 100% !important;
        margin-bottom: 15px !important;
        text-align: left !important;
    }
    
    .property-location {
        font-size: 0.95rem !important;
        color: #666 !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-align: left !important;
        width: 100% !important;
        justify-content: flex-start !important;
    }
    
    .property-location i {
        color: #666 !important;
        font-size: 14px !important;
        flex-shrink: 0 !important;
    }
    
    /* Valor abaixo da localização no desktop também */
    .property-price {
        position: static !important;
        background: transparent !important;
        color: #1a1a1a !important;
        padding: 0 !important;
        border-radius: 0 !important;
        text-align: left !important;
        z-index: auto !important;
        max-width: none !important;
        min-height: auto !important;
        height: auto !important;
        width: auto !important;
        margin-bottom: 12px !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }
    
    .property-price .price {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        display: block !important;
        margin-bottom: 0 !important;
        line-height: 1.2 !important;
        color: #1a1a1a !important;
        text-shadow: none !important;
    }
    
    .property-details {
        margin-bottom: 18px !important;
        gap: 6px !important;
        flex-wrap: wrap !important;
        display: flex !important;
    }
    
    .property-details span {
        font-size: 0.85rem !important;
        padding: 6px 10px !important;
        background: #f1f3f4 !important;
        border: none !important;
        border-radius: 15px !important;
        color: #5f6368 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        font-weight: 500 !important;
    }
    
    .property-details span i {
        font-size: 12px !important;
        color: #5f6368 !important;
    }
    
    /* ETIQUETAS DESKTOP - SOBRE A IMAGEM */
    .property-labels {
        position: absolute !important;
        top: 10px !important;
        left: 10px !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 5px !important;
        z-index: 15 !important;
        max-width: 200px !important;
        pointer-events: none !important;
        width: auto !important;
        height: auto !important;
        min-height: auto !important;
        overflow: visible !important;
    }
    
    .property-badge {
        font-size: 0.65rem !important;
        padding: 6px 10px !important;
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 10 !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        bottom: auto !important;
        height: auto !important;
        border-radius: 15px !important;
        font-weight: 700 !important;
        min-height: 26px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        backdrop-filter: blur(10px) !important;
    }
    
    .property-badge i {
        font-size: 0.45rem !important;
        margin-right: 2px !important;
    }
    
    .btn-view-property {
        padding: 14px 28px !important;
        font-size: 16px !important;
        border-radius: 12px !important;
        margin-top: 0 !important;
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: white !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
        width: 100% !important;
        text-align: center !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-view-property:hover {
        background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
    }
}

/* Breakpoints específicos para otimizar colunas */
@media (min-width: 1200px) {
    .properties-grid {
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)) !important;
        max-width: 1600px !important;
    }
}

@media (min-width: 1400px) {
    .properties-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
        max-width: 1800px !important;
    }
}

/* Cards Mobile - Menores e Padronizados */
@media (max-width: 768px) {
    .properties-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        padding: 0 15px !important;
        max-width: 100% !important;
        justify-items: center !important;
        align-items: start !important;
    }
    
    .property-card {
        width: 380px !important;
        min-width: 380px !important;
        max-width: 380px !important;
        height: auto !important;
        margin: 0 auto !important;
        border-radius: 20px !important;
        overflow: hidden !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .property-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
    }
    
    .property-image {
        height: 240px !important;
        position: relative !important;
        overflow: hidden !important;
        flex-shrink: 0 !important;
    }
    
    .property-image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.3s ease !important;
    }
    
    .property-card:hover .property-image img {
        transform: scale(1.05) !important;
    }
    
    .property-info {
        padding: 20px !important;
        background: #ffffff !important;
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        text-align: left !important;
    }
    
    .property-info h3 {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin-bottom: 12px !important;
        line-height: 1.3 !important;
    }
    
    .property-location-container {
        width: 100% !important;
        margin-bottom: 15px !important;
        text-align: left !important;
    }
    
    .property-location {
        font-size: 0.95rem !important;
        color: #666 !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-align: left !important;
        width: 100% !important;
        justify-content: flex-start !important;
    }
    
    .property-location i {
        color: #666 !important;
        font-size: 14px !important;
        flex-shrink: 0 !important;
    }
    
    .property-details {
        margin-bottom: 18px !important;
        gap: 6px !important;
        flex-wrap: wrap !important;
        display: flex !important;
    }
    
    .property-details span {
        font-size: 0.85rem !important;
        padding: 6px 10px !important;
        background: #f1f3f4 !important;
        border: none !important;
        border-radius: 15px !important;
        color: #5f6368 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        font-weight: 500 !important;
    }
    
    .property-details span i {
        font-size: 12px !important;
        color: #5f6368 !important;
    }
    
    /* Valor abaixo da localização no mobile */
    .property-price {
        position: static !important;
        background: transparent !important;
        color: #1a1a1a !important;
        padding: 0 !important;
        border-radius: 0 !important;
        text-align: left !important;
        z-index: auto !important;
        max-width: none !important;
        min-height: auto !important;
        height: auto !important;
        width: auto !important;
        margin-bottom: 12px !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }
    
    .property-price .price {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        display: block !important;
        margin-bottom: 0 !important;
        line-height: 1.2 !important;
        color: #1a1a1a !important;
        text-shadow: none !important;
    }
    
    /* ETIQUETAS MOBILE - SOBRE A IMAGEM */
    .property-labels {
        position: absolute !important;
        top: 10px !important;
        left: 10px !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 5px !important;
        z-index: 15 !important;
        max-width: 200px !important;
        pointer-events: none !important;
        width: auto !important;
        height: auto !important;
        min-height: auto !important;
        overflow: visible !important;
    }
    
    .property-badge {
        font-size: 0.65rem !important;
        padding: 6px 10px !important;
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 10 !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        bottom: auto !important;
        height: auto !important;
        border-radius: 15px !important;
        font-weight: 700 !important;
        min-height: 26px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        backdrop-filter: blur(10px) !important;
    }
    
    .property-badge i {
        font-size: 0.45rem !important;
        margin-right: 2px !important;
    }
    
    .btn-view-property {
        padding: 14px 28px !important;
        font-size: 16px !important;
        border-radius: 12px !important;
        margin-top: 0 !important;
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: white !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
        width: 100% !important;
        text-align: center !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-view-property:hover {
        background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
    }
}

@media (max-width: 480px) {
    .properties-grid {
        gap: 15px !important;
        padding: 0 10px !important;
        justify-items: center !important;
    }
    
    .property-card {
        width: 380px !important;
        min-width: 380px !important;
        max-width: 380px !important;
        height: auto !important;
    }
    
    .property-image {
        height: 200px !important;
    }
    
    .property-info {
        padding: 15px !important;
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    .property-info h3 {
        font-size: 1.1rem !important;
        margin-bottom: 8px !important;
    }
    
    .property-location-container {
        width: 100% !important;
        margin-bottom: 10px !important;
        text-align: left !important;
    }
    
    .property-location {
        font-size: 0.85rem !important;
        margin: 0 !important;
        color: #666 !important;
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
        text-align: left !important;
        width: 100% !important;
        justify-content: flex-start !important;
    }
    
    .property-location i {
        color: #666 !important;
        font-size: 12px !important;
        flex-shrink: 0 !important;
    }
    
    .property-details {
        margin-bottom: 12px !important;
        gap: 4px !important;
        display: flex !important;
        flex-wrap: wrap !important;
    }
    
    .property-details span {
        font-size: 0.8rem !important;
        padding: 5px 8px !important;
        background: #f1f3f4 !important;
        border: none !important;
        border-radius: 12px !important;
        color: #5f6368 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 3px !important;
        font-weight: 500 !important;
    }
    
    .property-details span i {
        font-size: 11px !important;
        color: #5f6368 !important;
    }
    
    /* Valor abaixo da localização - mobile pequeno */
    .property-price {
        position: static !important;
        background: transparent !important;
        color: #1a1a1a !important;
        padding: 0 !important;
        border-radius: 0 !important;
        text-align: left !important;
        z-index: auto !important;
        max-width: none !important;
        min-height: auto !important;
        height: auto !important;
        width: auto !important;
        margin-bottom: 10px !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }
    
    .property-price .price {
        font-size: 1.2rem !important;
        line-height: 1.2 !important;
        color: #1a1a1a !important;
        font-weight: 700 !important;
        margin-bottom: 0 !important;
        text-shadow: none !important;
    }
    
    /* ETIQUETAS MOBILE PEQUENO - SOBRE A IMAGEM */
    .property-labels {
        position: absolute !important;
        top: 8px !important;
        left: 8px !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 3px !important;
        z-index: 15 !important;
        max-width: 180px !important;
        pointer-events: none !important;
        width: auto !important;
        height: auto !important;
        min-height: auto !important;
        overflow: visible !important;
    }
    
    .property-badge {
        font-size: 0.6rem !important;
        padding: 5px 8px !important;
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 10 !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        bottom: auto !important;
        height: auto !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        min-height: 24px !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12) !important;
        backdrop-filter: blur(8px) !important;
    }
    
    .property-badge i {
        font-size: 0.4rem !important;
        margin-right: 1px !important;
    }
    
    .btn-view-property {
        padding: 12px 24px !important;
        font-size: 15px !important;
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: white !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
        width: 100% !important;
        text-align: center !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        border-radius: 10px !important;
    }
    
    .btn-view-property:hover {
        background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
    }
}


    .property-badge {
    padding: 2px 4px !important;
    font-size: 0.8rem !important;
    max-width: 130px !important;
    border-radius: 15px !important;
}
.badge-entrega {
    background: linear-gradient(135deg, #c01e1e, #ee2325) !important;
    color: white !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
}
/* CSS duplicado removido - regras consolidadas acima */
.property-price{
height: 80px !important;
}
</style>
    <!-- Page Banner Desktop -->
    <section class="page-banner desktop-only">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1><?php echo $page_subtitle; ?></h1>
                    <p><?php echo $page_description; ?></p>
                    <div class="banner-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($imoveis); ?></span>
                            <span class="stat-label">Imóveis</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($cidades); ?></span>
                            <span class="stat-label">Cidades</span>
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
                <h1><?php echo $page_subtitle; ?></h1>
                <p><?php echo count($imoveis); ?> oportunidades disponíveis</p>
                <div class="quick-stats-mobile">
                    <span class="quick-stat">
                        <i class="fas fa-home"></i>
                        <?php echo count($imoveis); ?> Imóveis
                    </span>
                    <span class="quick-stat">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo count($cidades); ?> Cidades
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
                
                <!-- Filtros Dinâmicos de Categoria -->
                <div class="category-filters">
                    <div class="category-filter-item <?php echo (!isset($_GET['filtro']) || $_GET['filtro'] == '') ? 'active' : ''; ?>">
                        <a href="imoveis.php" class="category-filter-link">
                            <i class="fas fa-home"></i>
                            <span>Todos os Imóveis</span>
                        </a>
                    </div>
                    <div class="category-filter-item <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'destaque') ? 'active' : ''; ?>">
                        <a href="imoveis.php?filtro=destaque" class="category-filter-link">
                            <i class="fas fa-star"></i>
                            <span>Em Destaque</span>
                        </a>
                    </div>
                    <div class="category-filter-item <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'valorizacao') ? 'active' : ''; ?>">
                        <a href="imoveis.php?filtro=valorizacao" class="category-filter-link">
                            <i class="fas fa-chart-line"></i>
                            <span>Maior Valorização</span>
                        </a>
                    </div>
                </div>
                
                <form class="search-form" method="GET">
                    <?php if (isset($_GET['filtro'])): ?>
                        <input type="hidden" name="filtro" value="<?php echo htmlspecialchars($_GET['filtro']); ?>">
                    <?php endif; ?>
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
                            <label for="regiao">Região</label>
                            <select name="regiao" id="regiao" class="filter-select">
                                <option value="">Todas as regiões</option>
                                <option value="sitio-cercado" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'sitio-cercado') ? 'selected' : ''; ?>>Sítio Cercado</option>
                                <option value="agua-verde" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'agua-verde') ? 'selected' : ''; ?>>Água Verde</option>
                                <option value="batel" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'batel') ? 'selected' : ''; ?>>Batel</option>
                                <option value="cabral" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'cabral') ? 'selected' : ''; ?>>Cabral</option>
                                <option value="centro-civico" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'centro-civico') ? 'selected' : ''; ?>>Centro Cívico</option>
                                <option value="juveve" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'juveve') ? 'selected' : ''; ?>>Juvevê</option>
                                <option value="centro" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'centro') ? 'selected' : ''; ?>>Centro</option>
                                <option value="jardim-botanico" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'jardim-botanico') ? 'selected' : ''; ?>>Jardim Botânico</option>
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
            <!-- Filtros de Categoria Mobile -->
            <div class="category-filters-mobile">
                <div class="category-filter-mobile <?php echo (!isset($_GET['filtro']) || $_GET['filtro'] == '') ? 'active' : ''; ?>">
                    <a href="imoveis.php" class="category-filter-link-mobile">
                        <i class="fas fa-home"></i>
                        <span>Todos</span>
                    </a>
                </div>
                <div class="category-filter-mobile <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'destaque') ? 'active' : ''; ?>">
                    <a href="imoveis.php?filtro=destaque" class="category-filter-link-mobile">
                        <i class="fas fa-star"></i>
                        <span>Destaque</span>
                    </a>
                </div>
                <div class="category-filter-mobile <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'valorizacao') ? 'active' : ''; ?>">
                    <a href="imoveis.php?filtro=valorizacao" class="category-filter-link-mobile">
                        <i class="fas fa-chart-line"></i>
                        <span>Valorização</span>
                    </a>
                </div>
            </div>
            
            <div class="filters-mobile-container">
                <button class="filters-toggle-btn" id="filters-toggle">
                    <i class="fas fa-filter"></i>
                    Filtros
                    <span class="filters-count" style="display: none;">0</span>
                </button>
                
                <div class="filters-mobile-panel" id="filters-panel" style="display: none;">
                    <form method="GET" class="filters-form-mobile">
                        <?php if (isset($_GET['filtro'])): ?>
                            <input type="hidden" name="filtro" value="<?php echo htmlspecialchars($_GET['filtro']); ?>">
                        <?php endif; ?>
                        <div class="filter-row-mobile">
                            <label>Status</label>
                            <select name="status_construcao" class="filter-select-mobile">
                                <option value="">Todos</option>
                                <option value="pronto" <?php echo (isset($_GET['status_construcao']) && $_GET['status_construcao'] == 'pronto') ? 'selected' : ''; ?>>Pronto</option>
                                <option value="na_planta" <?php echo (isset($_GET['status_construcao']) && $_GET['status_construcao'] == 'na_planta') ? 'selected' : ''; ?>>Na Planta</option>
                            </select>
                        </div>
                        
                        <div class="filter-row-mobile">
                            <label>Região</label>
                            <select name="regiao" class="filter-select-mobile">
                                <option value="">Todas</option>
                                <option value="sitio-cercado" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'sitio-cercado') ? 'selected' : ''; ?>>Sítio Cercado</option>
                                <option value="agua-verde" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'agua-verde') ? 'selected' : ''; ?>>Água Verde</option>
                                <option value="batel" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'batel') ? 'selected' : ''; ?>>Batel</option>
                                <option value="cabral" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'cabral') ? 'selected' : ''; ?>>Cabral</option>
                                <option value="centro-civico" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'centro-civico') ? 'selected' : ''; ?>>Centro Cívico</option>
                                <option value="juveve" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'juveve') ? 'selected' : ''; ?>>Juvevê</option>
                                <option value="centro" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'centro') ? 'selected' : ''; ?>>Centro</option>
                                <option value="jardim-botanico" <?php echo (isset($_GET['regiao']) && $_GET['regiao'] == 'jardim-botanico') ? 'selected' : ''; ?>>Jardim Botânico</option>
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
           
            <?php if (empty($imoveis)): ?>
                <div class="no-properties-message">
                    <div class="no-properties-content">
                        <i class="fas fa-search"></i>
                        <h3>Nenhum imóvel encontrado</h3>
                        <?php if (!empty($filters) && count($filters) > 1): ?>
                            <p>Não encontramos imóveis com os filtros aplicados. Tente ajustar os critérios de busca ou veja todos os <?php echo $totalDisponiveis; ?> imóveis disponíveis.</p>
                            <div class="no-properties-actions">
                                <a href="imoveis.php" class="btn-view-all">Ver Todos os Imóveis</a>
                                <a href="contato.php" class="btn-secondary">Falar com Corretor</a>
                            </div>
                        <?php else: ?>
                            <p>No momento não temos imóveis disponíveis. Entre em contato conosco para ser notificado sobre novas oportunidades.</p>
                            <a href="contato.php" class="btn-view-all">Falar com Corretor</a>
                        <?php endif; ?>
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
                                
                                <!-- ETIQUETAS NO TOPO ESQUERDO DA IMAGEM -->
                                <div class="property-labels">
                                    <?php if ($imovel_item['destaque']): ?>
                                        <span class="property-badge badge-destaque">
                                            <i class="fas fa-star"></i>
                                            DESTAQUE
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($imovel_item['maior_valorizacao']): ?>
                                        <span class="property-badge badge-valorizacao">
                                            <i class="fas fa-chart-line"></i>
                                            VALORIZAÇÃO
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($imovel_item['ano_entrega'])): ?>
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
                                
                                <div class="property-location-container">
                                    <p class="property-location">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php 
                                        // Usar campo bairro do banco de dados
                                        $bairro = $imovel_item['bairro'] ?? '';
                                        $cidade = $imovel_item['cidade'] ?? '';
                                        $estado = $imovel_item['estado'] ?? '';
                                        
                                        // Se tem bairro, mostrar bairro + cidade + estado
                                        if (!empty($bairro)) {
                                            $localizacao = $bairro . ', ' . $cidade . ', ' . $estado;
                                        } else {
                                            // Fallback: tentar extrair do endereço
                                            $endereco = $imovel_item['endereco'] ?? '';
                                            $regiao = '';
                                            
                                            // Mapear regiões conhecidas
                                            $regioes = [
                                                'sitio-cercado' => 'Sítio Cercado',
                                                'agua-verde' => 'Água Verde', 
                                                'batel' => 'Batel',
                                                'cabral' => 'Cabral',
                                                'centro-civico' => 'Centro Cívico',
                                                'juveve' => 'Juvevê',
                                                'centro' => 'Centro',
                                                'jardim-botanico' => 'Jardim Botânico'
                                            ];
                                            
                                            // Tentar identificar região pelo endereço
                                            foreach ($regioes as $key => $nome) {
                                                if (stripos($endereco, $nome) !== false) {
                                                    $regiao = $nome;
                                                    break;
                                                }
                                            }
                                            
                                            $localizacao = $regiao ? $regiao . ', ' . $cidade . ', ' . $estado : $cidade . ', ' . $estado;
                                        }
                                        
                                        echo htmlspecialchars($localizacao); 
                                        ?>
                                    </p>
                                </div>
                                
                                <!-- Valor do imóvel abaixo da localização no mobile -->
                                <div class="property-price">
                                    <span class="price">R$ <?php echo number_format($imovel_item['preco'], 0, ',', '.'); ?></span>
                                </div>
                                
                                
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
require_once __DIR__ . '/config/version.php';
$version = getAssetsVersion();
?>
<script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
<script>
// Funcionalidade dos filtros melhorada
document.addEventListener('DOMContentLoaded', function() {
    const filtersToggle = document.getElementById('filters-toggle');
    const filtersPanel = document.getElementById('filters-panel');
    const filtersCount = document.querySelector('.filters-count');
    
    // Toggle dos filtros mobile
    if (filtersToggle && filtersPanel) {
        filtersToggle.addEventListener('click', function() {
            const isVisible = filtersPanel.style.display !== 'none';
            filtersPanel.style.display = isVisible ? 'none' : 'block';
            
            // Atualizar ícone
            const icon = this.querySelector('i');
            if (isVisible) {
                icon.className = 'fas fa-filter';
            } else {
                icon.className = 'fas fa-times';
            }
        });
    }
    
    // Contar filtros ativos
    function updateFiltersCount() {
        if (filtersCount) {
            const activeFilters = document.querySelectorAll('select[name="status_construcao"], select[name="regiao"], input[name="min_price"], input[name="max_price"]');
            let count = 0;
            
            activeFilters.forEach(filter => {
                if (filter.value && filter.value !== '') {
                    count++;
                }
            });
            
            if (count > 0) {
                filtersCount.textContent = count;
                filtersCount.style.display = 'inline';
            } else {
                filtersCount.style.display = 'none';
            }
        }
    }
    
    // Atualizar contador quando filtros mudarem
    const filterSelects = document.querySelectorAll('select[name="status_construcao"], select[name="regiao"], input[name="min_price"], input[name="max_price"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', updateFiltersCount);
        select.addEventListener('input', updateFiltersCount);
    });
    
    // Atualizar contador na carga inicial
    updateFiltersCount();
    
    // Auto-submit do formulário mobile quando filtros mudarem
    const mobileForm = document.querySelector('.filters-form-mobile');
    if (mobileForm) {
        const mobileSelects = mobileForm.querySelectorAll('select');
        mobileSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Pequeno delay para melhor UX
                setTimeout(() => {
                    mobileForm.submit();
                }, 300);
            });
        });
    }
    
    // Melhorar funcionalidade dos filtros desktop
    const desktopForm = document.querySelector('.search-form');
    if (desktopForm) {
        // Auto-submit quando filtros mudarem (com delay)
        const desktopSelects = desktopForm.querySelectorAll('select');
        desktopSelects.forEach(select => {
            select.addEventListener('change', function() {
                setTimeout(() => {
                    desktopForm.submit();
                }, 500);
            });
        });
        
        // Validação de preços
        const minPriceInput = desktopForm.querySelector('input[name="min_price"]');
        const maxPriceInput = desktopForm.querySelector('input[name="max_price"]');
        
        if (minPriceInput && maxPriceInput) {
            minPriceInput.addEventListener('blur', function() {
                const minValue = parseFloat(this.value);
                const maxValue = parseFloat(maxPriceInput.value);
                
                if (minValue && maxValue && minValue > maxValue) {
                    alert('O preço mínimo não pode ser maior que o preço máximo.');
                    this.value = '';
                }
            });
            
            maxPriceInput.addEventListener('blur', function() {
                const minValue = parseFloat(minPriceInput.value);
                const maxValue = parseFloat(this.value);
                
                if (minValue && maxValue && maxValue < minValue) {
                    alert('O preço máximo não pode ser menor que o preço mínimo.');
                    this.value = '';
                }
            });
        }
    }
    
    // Melhorar UX dos botões de filtro de categoria
    const categoryFilters = document.querySelectorAll('.category-filter-link, .category-filter-link-mobile');
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            // Adicionar loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
            this.style.pointerEvents = 'none';
            
            // Restaurar após 2 segundos se não redirecionar
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.pointerEvents = 'auto';
            }, 2000);
        });
    });
    
    // Adicionar animação aos cards
    const propertyCards = document.querySelectorAll('.property-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    propertyCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

</script>
