<?php 
// Buscar imóveis em destaque do banco de dados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';
require_once __DIR__ . '/classes/Corretor.php';
require_once __DIR__ . '/classes/Depoimento.php';

$imoveis_destaque = [];
$imoveis_valorizacao = [];
$corretores_destaque = [];
$depoimentos_destaque = [];

try {
    $imovel = new Imovel();
    
    // Buscar imóveis em destaque (status = 'disponivel' e destaque = 1)
    $filtros_destaque = ['destaque' => 1, 'status' => 'disponivel'];
    $imoveis_destaque_result = $imovel->listarTodos($filtros_destaque, 1, 20);
    
    // Debug: Log do resultado
    error_log("Debug - Resultado busca destaque: " . print_r($imoveis_destaque_result, true));
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_destaque_result) && isset($imoveis_destaque_result['imoveis'])) {
        $imoveis_destaque = $imoveis_destaque_result['imoveis'];
        error_log("Debug - Imóveis em destaque encontrados: " . count($imoveis_destaque));
    } else {
        $imoveis_destaque = [];
        error_log("Debug - Nenhum imóvel em destaque encontrado");
    }
    
    // Se não encontrou imóveis em destaque, buscar imóveis disponíveis
    if (empty($imoveis_destaque)) {
        error_log("Debug - Buscando imóveis disponíveis como fallback para destaque");
        $filtros_fallback = ['status' => 'disponivel'];
        $imoveis_fallback_result = $imovel->listarTodos($filtros_fallback, 1, 4);
        
        if (is_array($imoveis_fallback_result) && isset($imoveis_fallback_result['imoveis'])) {
            $imoveis_destaque = $imoveis_fallback_result['imoveis'];
            error_log("Debug - Imóveis fallback encontrados: " . count($imoveis_destaque));
        }
    }
    
    // Limitar a 4 imóveis para a seção de destaque
    $imoveis_destaque = array_slice($imoveis_destaque, 0, 4);
    
    // Buscar imóveis de maior valorização (status = 'disponivel' e maior_valorizacao = 1)
    $filtros_valorizacao = ['maior_valorizacao' => 1, 'status' => 'disponivel'];
    $imoveis_valorizacao_result = $imovel->listarTodos($filtros_valorizacao, 1, 20);
    
    // Debug: Log do resultado
    error_log("Debug - Resultado busca valorização: " . print_r($imoveis_valorizacao_result, true));
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_valorizacao_result) && isset($imoveis_valorizacao_result['imoveis'])) {
        $imoveis_valorizacao = $imoveis_valorizacao_result['imoveis'];
        error_log("Debug - Imóveis de valorização encontrados: " . count($imoveis_valorizacao));
    } else {
        $imoveis_valorizacao = [];
        error_log("Debug - Nenhum imóvel de valorização encontrado");
    }
    
    // Se não encontrou imóveis de valorização, buscar imóveis disponíveis (diferentes dos de destaque)
    if (empty($imoveis_valorizacao)) {
        error_log("Debug - Buscando imóveis disponíveis como fallback para valorização");
        $filtros_fallback = ['status' => 'disponivel'];
        $imoveis_fallback_result = $imovel->listarTodos($filtros_fallback, 1, 8);
        
        if (is_array($imoveis_fallback_result) && isset($imoveis_fallback_result['imoveis'])) {
            $imoveis_fallback = $imoveis_fallback_result['imoveis'];
            
            // Filtrar para não repetir os imóveis de destaque
            $ids_destaque = array_column($imoveis_destaque, 'id');
            $imoveis_valorizacao = array_filter($imoveis_fallback, function($imovel) use ($ids_destaque) {
                return !in_array($imovel['id'], $ids_destaque);
            });
            
            error_log("Debug - Imóveis fallback para valorização encontrados: " . count($imoveis_valorizacao));
        }
    }
    
    // Limitar a 4 imóveis para a seção de valorização
    $imoveis_valorizacao = array_slice($imoveis_valorizacao, 0, 4);
    
    // Buscar corretores para a seção "Conheça Nossos Especialistas"
    $corretor = new Corretor();
    $corretores_result = $corretor->listarTodos();
    
    if (is_array($corretores_result)) {
        $corretores_destaque = array_slice($corretores_result, 0, 3); // Limitar a 3 corretores
    } else {
        $corretores_destaque = [];
$depoimentos_destaque = [];
    }
    // Buscar depoimentos em destaque
    $depoimento = new Depoimento();
    $depoimentos_result = $depoimento->listarDestaques(10); // Buscar até 10 depoimentos em destaque
    
    if (is_array($depoimentos_result)) {
        $depoimentos_destaque = $depoimentos_result;
    } else {
        $depoimentos_destaque = [];
    }    
} catch (Exception $e) {
    error_log("Erro ao buscar imóveis: " . $e->getMessage());
    $imoveis_destaque = [];
    $imoveis_valorizacao = [];
    $corretores_destaque = [];
$depoimentos_destaque = [];
}

$current_page = 'home';
$page_title = 'Br2Imóveis - Imóveis de Qualidade em Todo o Brasil';
$page_css = 'assets/css/home-sections.css';
include 'includes/header.php';
?>
<?php 
?>
<style>
    /* Hero Content - Desktop */
    .hero-content {
        position: absolute;
        top: 20%;
        left: 10%;
        transform: translateY(-50%);
        text-align: left;
        color: white;
        z-index: 2;
        width: 100%;
        max-width: 700px;
        padding: 0px 24px;
    }
    
    /* Melhorar proporção da hero no desktop */
    @media (min-width: 769px) {
        .hero-title {
            font-size: 3.8rem !important;
            margin-bottom: 25px !important;
            line-height: 1.1 !important;
        }
        
        .hero-subtitle {
            font-size: 1.4rem !important;
            margin-bottom: 35px !important;
            line-height: 1.4 !important;
        }
        
        .hero-slide h2 {
            font-size: 1.5rem !important;
            margin-bottom: 15px !important;
        }
        
        .hero-slide p {
            font-size: 1.1rem !important;
            line-height: 1.6 !important;
        }
        
        .hero-stats {
            gap: 35px !important;
            margin-top: 35px !important;
        }
        
        .stat-number {
            font-size: 2.5rem !important;
        }
        
        .stat-label {
            font-size: 1rem !important;
        }
        
    }
    .section-header h2 {
    font-size: 1.8rem !important;
    font-weight: 700 !important;
    margin-bottom: var(--title-margin-bottom) !important;
    line-height: 1.2 !important;
    color: #333333 !important;
}
    /* Hero Mobile Responsive */
    @media (max-width: 768px) {
   
        .hero-title {
            font-size: 2.8rem !important;
            margin-bottom: 15px !important;
            line-height: 1.1 !important;
        }
        
        .hero-subtitle {
            font-size: 1.2rem !important;
            margin-bottom: 20px !important;
            max-width: 100% !important;
        }
        
        .hero-slides {
            margin-bottom: 25px !important;
        }
        
        .hero-slide h2 {
            font-size: 1.2rem !important;
            margin-bottom: 8px !important;
        }
        
        .hero-slide p {
            font-size: 0.9rem !important;
            max-width: 100% !important;
            line-height: 1.4 !important;
        }
        
        /* Diminuir espaçamento entre hero e imóveis em destaque */
        .properties-mobile {
            margin-top: 20px !important;
            padding-top: 20px !important;
        }
        
        .section-header h2 {
            margin-bottom: 8px !important;
        }
        
        .section-header p {
            margin-top: 8px !important;
        }
        
        .hero-stats {
            gap: 20px !important;
            margin-top: 20px !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
        }
        
        .stat-item {
            min-width: 80px !important;
        }
        
        .stat-number {
            font-size: 1.8rem !important;
        }
        
        .stat-label {
            font-size: 0.8rem !important;
        }
    }
    
    @media (max-width: 480px) {

        
        .hero-content {
            top: 54% !important;
            left: 3% !important;
            max-width: 94% !important;
            transform: translateY(-50%) !important;
            padding: 0 15px !important;
        }
        
        .hero-title {
            font-size: 2.2rem !important;
            margin-bottom: 12px !important;
            line-height: 1.1 !important;
        }
        
        .hero-subtitle {
            font-size: 1.1rem !important;
            margin-bottom: 18px !important;
        }
        
        .hero-slides {
            margin-bottom: 20px !important;
        }
        
        .hero-slide h2 {
            font-size: 1.1rem !important;
            margin-bottom: 6px !important;
        }
        
        .hero-slide p {
            font-size: 0.85rem !important;
            line-height: 1.3 !important;
        }
        
        .hero-stats {
            flex-direction: row !important;
            gap: 15px !important;
            margin-top: 15px !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
        }
        
        .stat-item {
            min-width: 70px !important;
        }
        
        .stat-number {
            font-size: 1.5rem !important;
        }
        
        .stat-label {
            font-size: 0.7rem !important;
        }
    }
    
    /* Agent Avatar Fallback */
    .agent-avatar-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        border-radius: 50%;
    }
    
    /* Correção de Alinhamento dos Cards de Agentes */
    .agents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        align-items: stretch;
    }
    
    .agent-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 450px;
    }
    
    .agent-image {
        flex-shrink: 0;
        margin-bottom: 20px;
    }
    
    .agent-card h3 {
        margin-bottom: 10px;
        min-height: 2.5rem;
    }
    
    .agent-title {
        margin-bottom: 15px;
        min-height: 1.5rem;
    }
    
    .agent-description {
        flex-grow: 1;
        margin-bottom: 20px;
        min-height: 4rem;
    }
    
    .agent-stats {
        margin-bottom: 20px;
        min-height: 2rem;
    }
    
    .btn-view-profile {
        margin-top: auto;
        min-height: 44px;
    }
    
    /* Padronizar cards de imóveis */
    .property-card-mobile {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .property-image-mobile {
        height: 200px !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        flex-shrink: 0;
    }
    
    .property-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .property-image {
        height: 250px !important;
        overflow: hidden;
    }
    
    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    /* Botões vermelhos premium refinados */
    .property-action-mobile,
    .btn-view-property {
        background: linear-gradient(135deg, #dc2626 0%, #b91c3c 50%, #991b1b 100%) !important;
        border: 2px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        box-shadow: 
            0 8px 25px rgba(220, 38, 38, 0.4),
            0 4px 15px rgba(185, 28, 60, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            inset 0 -1px 0 rgba(0, 0, 0, 0.1) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative !important;
        overflow: hidden !important;
        border-radius: 12px !important;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        text-shadow: 
            0 1px 3px rgba(0, 0, 0, 0.4),
            0 0 8px rgba(255, 255, 255, 0.1) !important;
        padding: 12px 24px !important;
        min-height: 48px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        outline: none !important;
    }
    
    .property-action-mobile:hover,
    .btn-view-property:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c3c 100%) !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
        box-shadow: 
            0 12px 35px rgba(220, 38, 38, 0.6),
            0 6px 20px rgba(185, 28, 60, 0.5),
            inset 0 1px 0 rgba(255, 255, 255, 0.3),
            inset 0 -1px 0 rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-3px) scale(1.02) !important;
        text-shadow: 
            0 1px 3px rgba(0, 0, 0, 0.5),
            0 0 12px rgba(255, 255, 255, 0.2) !important;
    }
    
    .property-action-mobile:active,
    .btn-view-property:active {
        transform: translateY(-1px) scale(0.98) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        box-shadow: 
            0 4px 15px rgba(220, 38, 38, 0.4),
            0 2px 8px rgba(185, 28, 60, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            inset 0 -1px 0 rgba(0, 0, 0, 0.2) !important;
    }
    
    /* Efeito de brilho deslizante */
    .property-action-mobile::before,
    .btn-view-property::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.4), 
            rgba(255, 255, 255, 0.6),
            rgba(255, 255, 255, 0.4),
            transparent
        );
        transition: left 0.6s ease;
        z-index: 1;
    }
    
    .property-action-mobile:hover::before,
    .btn-view-property:hover::before {
        left: 100%;
    }
    
    /* Efeito de ondas pulsantes */
    .property-action-mobile::after,
    .btn-view-property::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        animation: ripple 2s infinite;
        z-index: 0;
    }
    
    /* Keyframes para pulsação premium */
    @keyframes pulse-premium {
        0% {
            box-shadow: 
                0 8px 25px rgba(220, 38, 38, 0.4),
                0 4px 15px rgba(185, 28, 60, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: scale(1);
        }
        25% {
            box-shadow: 
                0 10px 30px rgba(220, 38, 38, 0.5),
                0 5px 18px rgba(185, 28, 60, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transform: scale(1.01);
        }
        50% {
            box-shadow: 
                0 12px 35px rgba(220, 38, 38, 0.6),
                0 6px 20px rgba(185, 28, 60, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }
        75% {
            box-shadow: 
                0 10px 30px rgba(220, 38, 38, 0.5),
                0 5px 18px rgba(185, 28, 60, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transform: scale(1.01);
        }
        100% {
            box-shadow: 
                0 8px 25px rgba(220, 38, 38, 0.4),
                0 4px 15px rgba(185, 28, 60, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: scale(1);
        }
    }
    
    /* Keyframes para efeito ripple */
    @keyframes ripple {
        0% {
            width: 0;
            height: 0;
            opacity: 1;
        }
        50% {
            width: 20px;
            height: 20px;
            opacity: 0.7;
        }
        100% {
            width: 40px;
            height: 40px;
            opacity: 0;
        }
    }
    
    /* Aplicar animações */
    .property-action-mobile,
    .btn-view-property {
        animation: pulse-premium 3s ease-in-out infinite !important;
    }
    
    .property-action-mobile:hover,
    .btn-view-property:hover {
        animation: none !important;
    }
    
    /* Refinamentos adicionais para botões */
    .property-action-mobile:focus,
    .btn-view-property:focus {
        outline: 2px solid rgba(255, 255, 255, 0.3) !important;
        outline-offset: 2px !important;
    }
    
    /* Responsividade para mobile */
    @media (max-width: 768px) {
        .property-action-mobile,
        .btn-view-property {
            font-size: 13px !important;
            padding: 10px 20px !important;
            min-height: 44px !important;
            letter-spacing: 0.3px !important;
        }
    }
    
    /* Melhorar espaçamento das seções */
    .section-header h2 {
        margin-bottom: 10px !important;
    }
    
    .section-header p {
        margin-top: 10px !important;
        margin-bottom: 0 !important;
    }
    
    
    
    
    
        
    /* CTA Section - Apenas espaçamentos e alinhamento */
    .cta-section {
        text-align: center;
    }
    
    .cta-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .cta-content h2 {
        margin-bottom: 15px;
    }
    
    .cta-content p {
        margin-bottom: 20px;
    }
    
    .cta-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
        align-items: center;
        margin-top: 0;
    }
    
    @media (max-width: 768px) {
        .cta-actions {
            flex-direction: column;
            gap: 15px;
        }
    }
    
    /* REGIÕES - SEÇÃO CENTRALIZADA */
    .regioes-curitiba {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    
    .regioes-curitiba .section-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .regioes-curitiba .section-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 40px auto;
        padding: 0 20px;
    }
    
    .regioes-curitiba .section-header h2 {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .regioes-curitiba .section-header p {
        text-align: center;
        margin: 0 auto;
    }
    
    .regioes-curitiba .section-footer {
        text-align: center;
        margin: 40px auto 0 auto;
        max-width: 800px;
        padding: 0 20px;
    }
    
    .regioes-curitiba .btn-view-all {
        display: inline-block;
        margin: 0 auto;
    }
    
    /* REGIÕES - CARROSSEL CENTRALIZADO */
    .regioes-carousel-wrapper {
        position: relative;
        overflow: hidden;
        margin: 40px auto 0 auto;
        width: 100%;
        max-width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .regioes-track {
        display: flex;
        animation: scroll-regioes 35s linear infinite;
        gap: 30px;
        align-items: center;
        width: calc(200% + 30px);
        justify-content: center;
        margin: 0 auto;
    }
    
    .regiao-slide {
        flex: 0 0 auto;
        min-width: 200px;
    }
    
    .regiao-card {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        transition: all 0.4s ease;
        border: 2px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        flex-direction: column;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .regiao-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #dc2626;
    }
    
    .regiao-icon {
        width: 60px;
        height: 60px;
        background: #f8f9fa !important;
        border: 2px solid #e9ecef !important;
        color: #495057 !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(34, 34, 34, 0.3);
    }
    
    .regiao-content h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    
    .regiao-content p {
        font-size: 0.9rem;
        color: #666;
        margin: 0 0 15px 0;
        font-weight: 500;
    }
    
    .regiao-link {
        display: inline-block;
        background: linear-gradient(135deg, #dc2626, #b91c3c);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }
    
    .regiao-link:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        color: white;
        text-decoration: none;
    }
    
    /* ANIMAÇÃO QUE FUNCIONA */
    @keyframes scroll-regioes {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    /* Pausar no hover */
    .regioes-carousel-wrapper:hover .regioes-track {
        animation-play-state: paused;
    }
    
    /* DESKTOP - Grid 2 Colunas CENTRALIZADO */
    .regioes-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
        margin: 50px auto 0 auto;
        max-width: 1000px;
        width: 100%;
        padding: 0 20px;
        justify-content: center;
        align-items: center;
        justify-items: center;
        place-items: center;
    }
    
    .regioes-grid .regiao-card {
        padding: 40px 30px;
        min-height: 280px;
        text-align: center;
        border-radius: 20px;
        transition: all 0.4s ease;
        border: 2px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        position: relative;
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .regioes-grid .regiao-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #dc2626;
    }
    
    .regioes-grid .regiao-icon {
        width: 90px;
        height: 90px;
        background: #f8f9fa !important;
        border: 2px solid #e9ecef !important;
        color: #495057 !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 36px;
        box-shadow: 0 6px 20px rgba(34, 34, 34, 0.4);
        flex-shrink: 0;
    }
    
    .regioes-grid .regiao-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
    }
    
    .regioes-grid .regiao-content h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.3;
        text-align: center;
    }
    
    .regioes-grid .regiao-content p {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.6;
        margin: 0 0 30px 0;
        font-weight: 500;
        text-align: center;
        flex: 1;
    }
    
    .regioes-grid .regiao-link {
        display: inline-block;
        background: linear-gradient(135deg, #dc2626, #b91c3c);
        color: white;
        padding: 14px 28px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        text-align: center;
        align-self: center;
        margin-top: auto;
    }
    
    .regioes-grid .regiao-link:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        color: white;
        text-decoration: none;
    }
    
    /* MOBILE - CARROSSEL CENTRALIZADO */
    @media (max-width: 768px) {
        .regioes-carousel-wrapper {
            margin: 30px auto 0 auto;
            padding: 0 15px;
            max-width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .regioes-track {
            gap: 20px !important;
            animation: scroll-regioes 30s linear infinite !important;
            align-items: stretch !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }
        
        .regiao-slide {
            min-width: 260px !important;
            flex: 0 0 260px !important;
            display: flex !important;
        }
        
        .regiao-card {
            padding: 25px 20px !important;
            min-height: 220px !important;
            max-width: 260px !important;
            width: 260px !important;
            border-radius: 20px !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: center !important;
            text-align: center !important;
        }
        
        .regiao-icon {
            width: 60px !important;
            height: 60px !important;
            font-size: 24px !important;
            margin-bottom: 15px !important;
            flex-shrink: 0 !important;
            background: #f8f9fa !important;
            border: 2px solid #e9ecef !important;
            color: #495057 !important;
        }
        
        .regiao-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            width: 100% !important;
        }
        
        .regiao-content h3 {
            font-size: 1.3rem !important;
            margin-bottom: 12px !important;
            text-align: center !important;
        }
        
        .regiao-content p {
            font-size: 0.9rem !important;
            line-height: 1.5 !important;
            margin-bottom: 20px !important;
            text-align: center !important;
            flex: 1 !important;
        }
        
        .regiao-link {
            padding: 10px 20px !important;
            font-size: 0.9rem !important;
            border-radius: 25px !important;
            margin-top: auto !important;
            align-self: center !important;
        }
    }
    
    @media (max-width: 480px) {
        .regioes-carousel-wrapper {
            padding: 0 10px;
            margin: 25px auto 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .regioes-track {
            gap: 15px !important;
            animation: scroll-regioes 25s linear infinite !important;
            align-items: stretch !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }
        
        .regiao-slide {
            min-width: 240px !important;
            flex: 0 0 240px !important;
            display: flex !important;
        }
        
        .regiao-card {
            padding: 20px 15px !important;
            min-height: 200px !important;
            max-width: 240px !important;
            width: 240px !important;
            border-radius: 18px !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: center !important;
            text-align: center !important;
        }
        
        .regiao-icon {
            width: 55px !important;
            height: 55px !important;
            font-size: 22px !important;
            margin-bottom: 12px !important;
            flex-shrink: 0 !important;
            background: #f8f9fa !important;
            border: 2px solid #e9ecef !important;
            color: #495057 !important;
        }
        
        .regiao-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            width: 100% !important;
        }
        
        .regiao-content h3 {
            font-size: 1.2rem !important;
            margin-bottom: 10px !important;
            text-align: center !important;
        }
        
        .regiao-content p {
            font-size: 0.85rem !important;
            line-height: 1.4 !important;
            margin-bottom: 15px !important;
            text-align: center !important;
            flex: 1 !important;
        }
        
        .regiao-link {
            padding: 8px 16px !important;
            font-size: 0.85rem !important;
            border-radius: 20px !important;
            margin-top: auto !important;
            align-self: center !important;
        }
    }
    
    /* Parceiros - Apenas logos sem cards */
    .partner-logo {
        background: none !important;
        border: none !important;
        padding: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        min-height: auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .partner-logo:hover {
        transform: none !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }
    
    .partner-logo img {
        max-height: 120px !important;
        width: auto !important;
        border: none !important;
        background: none !important;
        padding: 0 !important;
        filter: none !important;
        transition: transform 0.3s ease !important;
    }
    
    .partner-logo:hover img {
        transform: scale(1.1) !important;
        filter: none !important;
    }
    
    .partner-slide {
        min-width: 180px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .partners-track {
        gap: 50px !important;
        align-items: center !important;
    }
    
    @media (max-width: 768px) {
        .partner-logo img {
            max-height: 90px !important;
        }
        
        .partner-slide {
            min-width: 150px !important;
        }
        
        .partners-track {
            gap: 40px !important;
        }
    }
    <?php 
// Buscar imóveis em destaque do banco de dados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';
require_once __DIR__ . '/classes/Corretor.php';
require_once __DIR__ . '/classes/Depoimento.php';

$imoveis_destaque = [];
$imoveis_valorizacao = [];
$corretores_destaque = [];
$depoimentos_destaque = [];

try {
    $imovel = new Imovel();
    
    // Buscar imóveis em destaque (status = 'disponivel' e destaque = 1)
    $filtros_destaque = ['destaque' => 1, 'status' => 'disponivel'];
    $imoveis_destaque_result = $imovel->listarTodos($filtros_destaque, 1, 20);
    
    // Debug: Log do resultado
    error_log("Debug - Resultado busca destaque: " . print_r($imoveis_destaque_result, true));
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_destaque_result) && isset($imoveis_destaque_result['imoveis'])) {
        $imoveis_destaque = $imoveis_destaque_result['imoveis'];
        error_log("Debug - Imóveis em destaque encontrados: " . count($imoveis_destaque));
    } else {
        $imoveis_destaque = [];
        error_log("Debug - Nenhum imóvel em destaque encontrado");
    }
    
    // Se não encontrou imóveis em destaque, buscar imóveis disponíveis
    if (empty($imoveis_destaque)) {
        error_log("Debug - Buscando imóveis disponíveis como fallback para destaque");
        $filtros_fallback = ['status' => 'disponivel'];
        $imoveis_fallback_result = $imovel->listarTodos($filtros_fallback, 1, 4);
        
        if (is_array($imoveis_fallback_result) && isset($imoveis_fallback_result['imoveis'])) {
            $imoveis_destaque = $imoveis_fallback_result['imoveis'];
            error_log("Debug - Imóveis fallback encontrados: " . count($imoveis_destaque));
        }
    }
    
    // Limitar a 4 imóveis para a seção de destaque
    $imoveis_destaque = array_slice($imoveis_destaque, 0, 4);
    
    // Buscar imóveis de maior valorização (status = 'disponivel' e maior_valorizacao = 1)
    $filtros_valorizacao = ['maior_valorizacao' => 1, 'status' => 'disponivel'];
    $imoveis_valorizacao_result = $imovel->listarTodos($filtros_valorizacao, 1, 20);
    
    // Debug: Log do resultado
    error_log("Debug - Resultado busca valorização: " . print_r($imoveis_valorizacao_result, true));
    
    // A classe retorna um array com chave 'imoveis'
    if (is_array($imoveis_valorizacao_result) && isset($imoveis_valorizacao_result['imoveis'])) {
        $imoveis_valorizacao = $imoveis_valorizacao_result['imoveis'];
        error_log("Debug - Imóveis de valorização encontrados: " . count($imoveis_valorizacao));
    } else {
        $imoveis_valorizacao = [];
        error_log("Debug - Nenhum imóvel de valorização encontrado");
    }
    
    // Se não encontrou imóveis de valorização, buscar imóveis disponíveis (diferentes dos de destaque)
    if (empty($imoveis_valorizacao)) {
        error_log("Debug - Buscando imóveis disponíveis como fallback para valorização");
        $filtros_fallback = ['status' => 'disponivel'];
        $imoveis_fallback_result = $imovel->listarTodos($filtros_fallback, 1, 8);
        
        if (is_array($imoveis_fallback_result) && isset($imoveis_fallback_result['imoveis'])) {
            $imoveis_fallback = $imoveis_fallback_result['imoveis'];
            
            // Filtrar para não repetir os imóveis de destaque
            $ids_destaque = array_column($imoveis_destaque, 'id');
            $imoveis_valorizacao = array_filter($imoveis_fallback, function($imovel) use ($ids_destaque) {
                return !in_array($imovel['id'], $ids_destaque);
            });
            
            error_log("Debug - Imóveis fallback para valorização encontrados: " . count($imoveis_valorizacao));
        }
    }
    
    // Limitar a 4 imóveis para a seção de valorização
    $imoveis_valorizacao = array_slice($imoveis_valorizacao, 0, 4);
    
    // Buscar corretores para a seção "Conheça Nossos Especialistas"
    $corretor = new Corretor();
    $corretores_result = $corretor->listarTodos();
    
    if (is_array($corretores_result)) {
        $corretores_destaque = array_slice($corretores_result, 0, 3); // Limitar a 3 corretores
    } else {
        $corretores_destaque = [];
$depoimentos_destaque = [];
    }
    
} catch (Exception $e) {
    error_log("Erro ao buscar imóveis: " . $e->getMessage());
    $imoveis_destaque = [];
    $imoveis_valorizacao = [];
    $corretores_destaque = [];
$depoimentos_destaque = [];
}

$current_page = 'home';
$page_title = 'Br2Imóveis - Imóveis de Qualidade em Todo o Brasil';
$page_css = 'assets/css/home-sections.css';
include 'includes/header.php';
?>
<?php 
?>
<style>
    /* Hero Content - Desktop */
    .hero-content {
        position: absolute;
        top: 20%;
        left: 10%;
        transform: translateY(-50%);
        text-align: left;
        color: white;
        z-index: 2;
        width: 100%;
        max-width: 700px;
        padding: 0px 24px;
    }
    
    /* Melhorar proporção da hero no desktop */
    @media (min-width: 769px) {
        .hero-title {
            font-size: 3.8rem !important;
            margin-bottom: 25px !important;
            line-height: 1.1 !important;
        }
        
        .hero-subtitle {
            font-size: 1.4rem !important;
            margin-bottom: 35px !important;
            line-height: 1.4 !important;
        }
        
        .hero-slide h2 {
            font-size: 1.5rem !important;
            margin-bottom: 15px !important;
        }
        
        .hero-slide p {
            font-size: 1.1rem !important;
            line-height: 1.6 !important;
        }
        
        .hero-stats {
            gap: 35px !important;
            margin-top: 35px !important;
        }
        
        .stat-number {
            font-size: 2.5rem !important;
        }
        
        .stat-label {
            font-size: 1rem !important;
        }
        
    }
    .section-header h2 {
    font-size: 1.8rem !important;
    font-weight: 700 !important;
    margin-bottom: var(--title-margin-bottom) !important;
    line-height: 1.2 !important;
    color: #333333 !important;
}
    /* Hero Mobile Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.8rem !important;
            margin-bottom: 15px !important;
            line-height: 1.1 !important;
        }
        
        .hero-subtitle {
            font-size: 1.2rem !important;
            margin-bottom: 20px !important;
            max-width: 100% !important;
        }
        
        .hero-slides {
            margin-bottom: 25px !important;
        }
        
        .hero-slide h2 {
            font-size: 1.2rem !important;
            margin-bottom: 8px !important;
        }
        
        .hero-slide p {
            font-size: 0.9rem !important;
            max-width: 100% !important;
            line-height: 1.4 !important;
        }
        
        /* Diminuir espaçamento entre hero e imóveis em destaque */
        .properties-mobile {
            margin-top: 20px !important;
            padding-top: 20px !important;
        }
        
        .section-header h2 {
            margin-bottom: 8px !important;
        }
        
        .section-header p {
            margin-top: 8px !important;
        }
        
        .hero-stats {
            gap: 20px !important;
            margin-top: 20px !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
        }
        
        .stat-item {
            min-width: 80px !important;
        }
        
        .stat-number {
            font-size: 1.8rem !important;
        }
        
        .stat-label {
            font-size: 0.8rem !important;
        }
    }
    
    @media (max-width: 480px) {

    
        .hero-title {
            font-size: 2.2rem !important;
            margin-bottom: 12px !important;
            line-height: 1.1 !important;
        }
        
        .hero-subtitle {
            font-size: 1.1rem !important;
            margin-bottom: 18px !important;
        }
        
        .hero-slides {
            margin-bottom: 20px !important;
        }
        
        .hero-slide h2 {
            font-size: 1.1rem !important;
            margin-bottom: 6px !important;
        }
        
        .hero-slide p {
            font-size: 0.85rem !important;
            line-height: 1.3 !important;
        }
        
        .hero-stats {
            flex-direction: row !important;
            gap: 15px !important;
            margin-top: 15px !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
        }
        
        .stat-item {
            min-width: 70px !important;
        }
        
        .stat-number {
            font-size: 1.5rem !important;
        }
        
        .stat-label {
            font-size: 0.7rem !important;
        }
    }
    
    /* Agent Avatar Fallback */
    .agent-avatar-fallback {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        border-radius: 50%;
    }
    
    /* Correção de Alinhamento dos Cards de Agentes */
    .agents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        align-items: stretch;
    }
    
    .agent-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 450px;
    }
    
    .agent-image {
        flex-shrink: 0;
        margin-bottom: 20px;
    }
    
    .agent-card h3 {
        margin-bottom: 10px;
        min-height: 2.5rem;
    }
    
    .agent-title {
        margin-bottom: 15px;
        min-height: 1.5rem;
    }
    
    .agent-description {
        flex-grow: 1;
        margin-bottom: 20px;
        min-height: 4rem;
    }
    
    .agent-stats {
        margin-bottom: 20px;
        min-height: 2rem;
    }
    
    .btn-view-profile {
        margin-top: auto;
        min-height: 44px;
    }
    
    /* Padronizar cards de imóveis */
    .property-card-mobile {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .property-image-mobile {
        height: 200px !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        flex-shrink: 0;
    }
    
    .property-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .property-image {
        height: 250px !important;
        overflow: hidden;
    }
    
    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    /* Botões vermelhos premium refinados */
    .property-action-mobile,
    .btn-view-property {
        background: linear-gradient(135deg, #dc2626 0%, #b91c3c 50%, #991b1b 100%) !important;
        border: 2px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        box-shadow: 
            0 8px 25px rgba(220, 38, 38, 0.4),
            0 4px 15px rgba(185, 28, 60, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2),
            inset 0 -1px 0 rgba(0, 0, 0, 0.1) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        position: relative !important;
        overflow: hidden !important;
        border-radius: 12px !important;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        text-shadow: 
            0 1px 3px rgba(0, 0, 0, 0.4),
            0 0 8px rgba(255, 255, 255, 0.1) !important;
        padding: 12px 24px !important;
        min-height: 48px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        outline: none !important;
    }
    
    .property-action-mobile:hover,
    .btn-view-property:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c3c 100%) !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
        box-shadow: 
            0 12px 35px rgba(220, 38, 38, 0.6),
            0 6px 20px rgba(185, 28, 60, 0.5),
            inset 0 1px 0 rgba(255, 255, 255, 0.3),
            inset 0 -1px 0 rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-3px) scale(1.02) !important;
        text-shadow: 
            0 1px 3px rgba(0, 0, 0, 0.5),
            0 0 12px rgba(255, 255, 255, 0.2) !important;
    }
    
    .property-action-mobile:active,
    .btn-view-property:active {
        transform: translateY(-1px) scale(0.98) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        box-shadow: 
            0 4px 15px rgba(220, 38, 38, 0.4),
            0 2px 8px rgba(185, 28, 60, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            inset 0 -1px 0 rgba(0, 0, 0, 0.2) !important;
    }
    
    /* Efeito de brilho deslizante */
    .property-action-mobile::before,
    .btn-view-property::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.4), 
            rgba(255, 255, 255, 0.6),
            rgba(255, 255, 255, 0.4),
            transparent
        );
        transition: left 0.6s ease;
        z-index: 1;
    }
    
    .property-action-mobile:hover::before,
    .btn-view-property:hover::before {
        left: 100%;
    }
    
    /* Efeito de ondas pulsantes */
    .property-action-mobile::after,
    .btn-view-property::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        animation: ripple 2s infinite;
        z-index: 0;
    }
    
    /* Keyframes para pulsação premium */
    @keyframes pulse-premium {
        0% {
            box-shadow: 
                0 8px 25px rgba(220, 38, 38, 0.4),
                0 4px 15px rgba(185, 28, 60, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: scale(1);
        }
        25% {
            box-shadow: 
                0 10px 30px rgba(220, 38, 38, 0.5),
                0 5px 18px rgba(185, 28, 60, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transform: scale(1.01);
        }
        50% {
            box-shadow: 
                0 12px 35px rgba(220, 38, 38, 0.6),
                0 6px 20px rgba(185, 28, 60, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }
        75% {
            box-shadow: 
                0 10px 30px rgba(220, 38, 38, 0.5),
                0 5px 18px rgba(185, 28, 60, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
            transform: scale(1.01);
        }
        100% {
            box-shadow: 
                0 8px 25px rgba(220, 38, 38, 0.4),
                0 4px 15px rgba(185, 28, 60, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: scale(1);
        }
    }
    
    /* Keyframes para efeito ripple */
    @keyframes ripple {
        0% {
            width: 0;
            height: 0;
            opacity: 1;
        }
        50% {
            width: 20px;
            height: 20px;
            opacity: 0.7;
        }
        100% {
            width: 40px;
            height: 40px;
            opacity: 0;
        }
    }
    
    /* Aplicar animações */
    .property-action-mobile,
    .btn-view-property {
        animation: pulse-premium 3s ease-in-out infinite !important;
    }
    
    .property-action-mobile:hover,
    .btn-view-property:hover {
        animation: none !important;
    }
    
    /* Refinamentos adicionais para botões */
    .property-action-mobile:focus,
    .btn-view-property:focus {
        outline: 2px solid rgba(255, 255, 255, 0.3) !important;
        outline-offset: 2px !important;
    }
    
    /* Responsividade para mobile */
    @media (max-width: 768px) {
        .property-action-mobile,
        .btn-view-property {
            font-size: 13px !important;
            padding: 10px 20px !important;
            min-height: 44px !important;
            letter-spacing: 0.3px !important;
        }
    }
    
    /* Melhorar espaçamento das seções */
    .section-header h2 {
        margin-bottom: 10px !important;
    }
    
    .section-header p {
        margin-top: 10px !important;
        margin-bottom: 0 !important;
    }
    
    
    
    /* DEPOIMENTOS DESKTOP - APENAS PRIMEIRO CARD CENTRALIZADO */
    .testimonials-desktop {
        padding: 80px 0 !important;
        background: #f8f9fa !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }
    
    /* Esconder depoimentos desktop no mobile */
    @media (max-width: 768px) {
        .testimonials-desktop {
            display: none !important;
        }
    }
    
    .testimonials-desktop .section-container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 20px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }
    
    .testimonials-desktop .section-header {
        text-align: center !important;
        margin-bottom: 50px !important;
        width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .testimonials-desktop .section-header h2 {
        font-size: 2.5rem !important;
        font-weight: 700 !important;
        color: #333 !important;
        margin-bottom: 15px !important;
        text-align: center !important;
    }
    
    .testimonials-desktop .section-header p {
        font-size: 1.1rem !important;
        color: #666 !important;
        max-width: 600px !important;
        margin: 0 auto !important;
        text-align: center !important;
    }
    
    .testimonials-wrapper-desktop {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        max-width: 1000px !important;
        margin: 0 auto !important;
    }
    
    
    
    .testimonial-content-desktop {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    
    .quote-icon-desktop {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #dc2626, #b91c3c);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 28px;
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
    }
    
    .testimonial-rating-desktop {
        margin-bottom: 25px;
    }
    
    .testimonial-rating-desktop i {
        color: #ffc107;
        font-size: 20px;
        margin: 0 2px;
    }
    
    .testimonial-text-desktop {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 35px;
        font-style: italic;
        position: relative;
    }
    
    .testimonial-text-desktop::before {
        content: '"';
        font-size: 80px;
        color: #dc2626;
        position: absolute;
        top: -30px;
        left: -20px;
        font-family: serif;
        opacity: 0.1;
        line-height: 1;
    }
    
    .testimonial-text-desktop::after {
        content: '"';
        font-size: 80px;
        color: #dc2626;
        position: absolute;
        bottom: -40px;
        right: -20px;
        font-family: serif;
        opacity: 0.1;
        line-height: 1;
    }
    
    .testimonial-author-desktop {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    
    .author-avatar-desktop {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
    }
    
    .author-info-desktop h4 {
        font-weight: 700;
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 5px;
    }
    
    .author-info-desktop p {
        font-size: 1.1rem;
        color: #666;
        font-weight: 500;
        margin: 0 0 5px 0;
    }
    
    .author-location-desktop {
        font-size: 0.9rem;
        color: #999;
        font-weight: 400;
    }
    
    .carousel-controls {
        display: none !important;
    }
    
    
    
    /* CTA Section - Apenas espaçamentos e alinhamento */
    .cta-section {
        text-align: center;
    }
    
    .cta-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .cta-content h2 {
        margin-bottom: 15px;
    }
    
    .cta-content p {
        margin-bottom: 20px;
    }
    
    .cta-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
        align-items: center;
        margin-top: 0;
    }
    
    @media (max-width: 768px) {
        .cta-actions {
            flex-direction: column;
            gap: 15px;
        }

        .testimonials-container-mobile {
    text-align: center;
    justify-content: center;
    display: flex;
}
    }
    
    /* REGIÕES - SEÇÃO CENTRALIZADA */
    .regioes-curitiba {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    
    .regioes-curitiba .section-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .regioes-curitiba .section-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 40px auto;
        padding: 0 20px;
    }
    
    .regioes-curitiba .section-header h2 {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .regioes-curitiba .section-header p {
        text-align: center;
        margin: 0 auto;
    }
    
    .regioes-curitiba .section-footer {
        text-align: center;
        margin: 40px auto 0 auto;
        max-width: 800px;
        padding: 0 20px;
    }
    
    .regioes-curitiba .btn-view-all {
        display: inline-block;
        margin: 0 auto;
    }
    
    /* REGIÕES - CARROSSEL CENTRALIZADO */
    .regioes-carousel-wrapper {
        position: relative;
        overflow: hidden;
        margin: 40px auto 0 auto;
        width: 100%;
        max-width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .regioes-track {
        display: flex;
        animation: scroll-regioes 35s linear infinite;
        gap: 30px;
        align-items: center;
        width: calc(200% + 30px);
        justify-content: center;
        margin: 0 auto;
    }
    
    .regiao-slide {
        flex: 0 0 auto;
        min-width: 200px;
    }
    
    .regiao-card {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        transition: all 0.4s ease;
        border: 2px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        flex-direction: column;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .regiao-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #dc2626;
    }
    
    .regiao-icon {
        width: 60px;
        height: 60px;
        background: #f8f9fa !important;
        border: 2px solid #e9ecef !important;
        color: #495057 !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(34, 34, 34, 0.3);
    }
    
    .regiao-content h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    
    .regiao-content p {
        font-size: 0.9rem;
        color: #666;
        margin: 0 0 15px 0;
        font-weight: 500;
    }
    
    .regiao-link {
        display: inline-block;
        background: linear-gradient(135deg, #dc2626, #b91c3c);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }
    
    .regiao-link:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        color: white;
        text-decoration: none;
    }
    
    /* ANIMAÇÃO QUE FUNCIONA */
    @keyframes scroll-regioes {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    /* Pausar no hover */
    .regioes-carousel-wrapper:hover .regioes-track {
        animation-play-state: paused;
    }
    
    /* DESKTOP - Grid 2 Colunas CENTRALIZADO */
    .regioes-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
        margin: 50px auto 0 auto;
        max-width: 1000px;
        width: 100%;
        padding: 0 20px;
        justify-content: center;
        align-items: center;
        justify-items: center;
        place-items: center;
    }
    
    .regioes-grid .regiao-card {
        padding: 40px 30px;
        min-height: 280px;
        text-align: center;
        border-radius: 20px;
        transition: all 0.4s ease;
        border: 2px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        position: relative;
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
    }
    
    .regioes-grid .regiao-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #dc2626;
    }
    
    .regioes-grid .regiao-icon {
        width: 90px;
        height: 90px;
        background: #f8f9fa !important;
        border: 2px solid #e9ecef !important;
        color: #495057 !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 36px;
        box-shadow: 0 6px 20px rgba(34, 34, 34, 0.4);
        flex-shrink: 0;
    }
    
    .regioes-grid .regiao-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
    }
    
    .regioes-grid .regiao-content h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.3;
        text-align: center;
    }
    
    .regioes-grid .regiao-content p {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.6;
        margin: 0 0 30px 0;
        font-weight: 500;
        text-align: center;
        flex: 1;
    }
    
    .regioes-grid .regiao-link {
        display: inline-block;
        background: linear-gradient(135deg, #dc2626, #b91c3c);
        color: white;
        padding: 14px 28px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        text-align: center;
        align-self: center;
        margin-top: auto;
    }
    
    .regioes-grid .regiao-link:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        color: white;
        text-decoration: none;
    }
    
    /* MOBILE - CARROSSEL CENTRALIZADO */
    @media (max-width: 768px) {
        .regioes-carousel-wrapper {
            margin: 30px auto 0 auto;
            padding: 0 15px;
            max-width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .regioes-track {
            gap: 20px !important;
            animation: scroll-regioes 30s linear infinite !important;
            align-items: stretch !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }
        
        .regiao-slide {
            min-width: 260px !important;
            flex: 0 0 260px !important;
            display: flex !important;
        }
        
        .regiao-card {
            padding: 25px 20px !important;
            min-height: 220px !important;
            max-width: 260px !important;
            width: 260px !important;
            border-radius: 20px !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: center !important;
            text-align: center !important;
        }
        
        .regiao-icon {
            width: 60px !important;
            height: 60px !important;
            font-size: 24px !important;
            margin-bottom: 15px !important;
            flex-shrink: 0 !important;
            background: #f8f9fa !important;
            border: 2px solid #e9ecef !important;
            color: #495057 !important;
        }
        
        .regiao-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            width: 100% !important;
        }
        
        .regiao-content h3 {
            font-size: 1.3rem !important;
            margin-bottom: 12px !important;
            text-align: center !important;
        }
        
        .regiao-content p {
            font-size: 0.9rem !important;
            line-height: 1.5 !important;
            margin-bottom: 20px !important;
            text-align: center !important;
            flex: 1 !important;
        }
        
        .regiao-link {
            padding: 10px 20px !important;
            font-size: 0.9rem !important;
            border-radius: 25px !important;
            margin-top: auto !important;
            align-self: center !important;
        }
    }
    
    @media (max-width: 480px) {
        .regioes-carousel-wrapper {
            padding: 0 10px;
            margin: 25px auto 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .regioes-track {
            gap: 15px !important;
            animation: scroll-regioes 25s linear infinite !important;
            align-items: stretch !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }
        
        .regiao-slide {
            min-width: 240px !important;
            flex: 0 0 240px !important;
            display: flex !important;
        }
        
        .regiao-card {
            padding: 20px 15px !important;
            min-height: 200px !important;
            max-width: 240px !important;
            width: 240px !important;
            border-radius: 18px !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: center !important;
            text-align: center !important;
        }
        
        .regiao-icon {
            width: 55px !important;
            height: 55px !important;
            font-size: 22px !important;
            margin-bottom: 12px !important;
            flex-shrink: 0 !important;
            background: #f8f9fa !important;
            border: 2px solid #e9ecef !important;
            color: #495057 !important;
        }
        
        .regiao-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            width: 100% !important;
        }
        
        .regiao-content h3 {
            font-size: 1.2rem !important;
            margin-bottom: 10px !important;
            text-align: center !important;
        }
        
        .regiao-content p {
            font-size: 0.85rem !important;
            line-height: 1.4 !important;
            margin-bottom: 15px !important;
            text-align: center !important;
            flex: 1 !important;
        }
        
        .regiao-link {
            padding: 8px 16px !important;
            font-size: 0.85rem !important;
            border-radius: 20px !important;
            margin-top: auto !important;
            align-self: center !important;
        }
    }
    
    /* Parceiros - Apenas logos sem cards */
    .partner-logo {
        background: none !important;
        border: none !important;
        padding: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        min-height: auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .partner-logo:hover {
        transform: none !important;
        box-shadow: none !important;
        border-color: transparent !important;
    }
    
    .partner-logo img {
        max-height: 120px !important;
        width: auto !important;
        border: none !important;
        background: none !important;
        padding: 0 !important;
        filter: none !important;
        transition: transform 0.3s ease !important;
    }
    
    .partner-logo:hover img {
        transform: scale(1.1) !important;
        filter: none !important;
    }
    
    .partner-slide {
        min-width: 180px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .partners-track {
        gap: 50px !important;
        align-items: center !important;
    }
    
    @media (max-width: 768px) {
        .partner-logo img {
            max-height: 90px !important;
        }
        
        .partner-slide {
            min-width: 150px !important;
        }
        
        .partners-track {
            gap: 40px !important;
        }
    }
    
    @media (max-width: 480px) {
        .partner-logo img {
            max-height: 70px !important;
        }
        
        .partner-slide {
            min-width: 120px !important;
        }
        
        .partners-track {
            gap: 30px !important;
        }
    }
    @media (max-width: 768px) {
    .cta-content {
        flex-direction: column !important;
        gap: 10px !important;
        text-align: center !important;
    }
}
@media (max-width: 480px) {
    .regioes-carousel-wrapper {
        padding: 20px 15px;
    }
    .regiao-link {
    display: inline-block;
    background: linear-gradient(135deg, #dc2626, #b91c3c);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
}
.partner-logo img {
    max-height: 99px !important;
}
}

/* Botões "Ver Todos" seguem padrão preto e branco */
.btn-primary.btn-large {
    background: transparent !important;
    color: #333333 !important;
    border: 3px solid #333333 !important;
    box-shadow: none !important;
    text-shadow: none !important;
    font-weight: 700 !important;
    font-size: 16px !important;
    letter-spacing: 0.8px !important;
    text-transform: uppercase !important;
    padding: 18px 40px !important;
    border-radius: 50px !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
    animation: none !important;
}

.btn-primary.btn-large::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: #333333;
    transition: left 0.3s ease;
    z-index: -1;
}

.btn-primary.btn-large:hover::before {
    left: 0;
}

.btn-primary.btn-large:hover {
    color: white !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    animation: none !important;
}

.regioes-curitiba .section-header {
    text-align: center;
    max-width: 800px;
    margin: 40px auto 40px auto;
    padding: 0 20px;
}

/* Melhorias para os cards das regiões */
.regioes-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 30px !important;
    margin: 50px auto 0 auto !important;
    max-width: 1200px !important;
    width: 100% !important;
    padding: 0 20px !important;
}

.regiao-card {
    background: white !important;
    border-radius: 20px !important;
    padding: 40px 30px !important;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1) !important;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    text-align: center !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
    overflow: hidden !important;
    min-height: 320px !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
}

.regiao-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(135deg, #dc2626, #b91c3c);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.regiao-card:hover::before {
    transform: scaleX(1);
}

.regiao-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
}

.regiao-icon {
    width: 80px !important;
    height: 80px !important;
    background: #f8f9fa !important;
    border: 2px solid #e9ecef !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto 25px !important;
    color: #495057 !important;
    font-size: 32px !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    transition: all 0.3s ease !important;
}

.regiao-card:hover .regiao-icon {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    background: #ffffff !important;
    border-color: #dee2e6 !important;
}

.regiao-content h3 {
    font-size: 1.8rem !important;
    font-weight: 700 !important;
    color: #333 !important;
    margin-bottom: 15px !important;
    line-height: 1.3 !important;
}

.regiao-content p {
    font-size: 1rem !important;
    color: #666 !important;
    line-height: 1.6 !important;
    margin-bottom: 25px !important;
    font-weight: 500 !important;
    flex: 1 !important;
}

.regiao-link {
    display: inline-block !important;
    background: linear-gradient(135deg, #dc2626, #b91c3c) !important;
    color: white !important;
    padding: 16px 32px !important;
    border-radius: 50px !important;
    text-decoration: none !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3) !important;
    position: relative !important;
    overflow: hidden !important;
}

.regiao-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s ease;
}

.regiao-link:hover::before {
    left: 100%;
}

.regiao-link:hover {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 35px rgba(220, 38, 38, 0.4) !important;
    color: white !important;
    text-decoration: none !important;
}

/* Responsividade para tablets */
@media (max-width: 1024px) {
    .regioes-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 25px !important;
    }
    
    .regiao-card {
        min-height: 300px !important;
        padding: 35px 25px !important;
    }
    
    .regiao-icon {
        width: 80px !important;
        height: 80px !important;
        font-size: 32px !important;
        background: #f8f9fa !important;
        color: #495057 !important;
        border: 2px solid #e9ecef !important;
    }
    
    .regiao-content h3 {
        font-size: 1.6rem !important;
    }
}

/* Responsividade para mobile */
@media (max-width: 768px) {
    .regioes-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 20px !important;
        padding: 0 15px !important;
    }
    
    .regiao-card {
        min-height: 280px !important;
        padding: 30px 20px !important;
    }
    
    .regiao-icon {
        width: 80px !important;
        height: 80px !important;
        font-size: 32px !important;
        margin-bottom: 20px !important;
        background: #f8f9fa !important;
        color: #495057 !important;
        border: 2px solid #e9ecef !important;
    }
    
    .regiao-content h3 {
        font-size: 1.4rem !important;
        margin-bottom: 12px !important;
    }
    
    .regiao-content p {
        font-size: 0.9rem !important;
        margin-bottom: 20px !important;
    }
    
    .regiao-link {
        padding: 14px 28px !important;
        font-size: 13px !important;
    }
}

@media (max-width: 480px) {
    .regioes-grid {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
    }
    
    .regiao-card {
        min-height: 260px !important;
        padding: 25px 15px !important;
    }
    
    .regiao-icon {
        width: 80px !important;
        height: 80px !important;
        font-size: 32px !important;
        margin-bottom: 18px !important;
        background: #f8f9fa !important;
        color: #495057 !important;
        border: 2px solid #e9ecef !important;
    }
    
    .regiao-content h3 {
        font-size: 1.3rem !important;
    }
    
    .regiao-content p {
        font-size: 0.85rem !important;
    }
}

/* Classes de responsividade */

.mobile-only {
    display: none !important;
}

@media (max-width: 768px) {
    .desktop-only {
        display: none !important;
    }
    
    .mobile-only {
        display: block !important;
    }
    
    /* Garantir que os ícones do carousel mobile tenham o padrão correto */
    .regioes-carousel .regiao-icon {
        background: #f8f9fa !important;
        border: 2px solid #e9ecef !important;
        color: #495057 !important;
    }
}
.regioes-carousel .regiao-icon i {
    font-size: 1.8rem;
    color: #4b4b4b;
    z-index: 2;
    position: relative;
}

.testimonial-text-mobile::before {
    content: '"';
    font-size: 40px;
    color:rgba(220, 38, 38, 0);
    position: absolute;
    top: -10px;
    left: -8px;
    font-family: serif;
}
.testimonial-text-mobile::after {
    content: '"';
    font-size: 40px;
    color:rgba(220, 38, 38, 0);
    position: absolute;
    top: -10px;
    left: -8px;
    font-family: serif;
}

/* Botão "Falar com Especialista" VERMELHO */
.cta-section .btn-primary,
.cta-section a.btn-primary,
.cta-actions .btn-primary,
.cta-actions a.btn-primary {
    background: #dc2626 !important;
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    color: white !important;
    border: none !important;
    padding: 18px 35px !important;
    border-radius: 12px !important;
    text-decoration: none !important;
    font-weight: 700 !important;
    font-size: 18px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 10px !important;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
    transition: all 0.3s ease !important;
}

.cta-section .btn-primary:hover,
.cta-section a.btn-primary:hover,
.cta-actions .btn-primary:hover,
.cta-actions a.btn-primary:hover {
    background: #b91c1c !important;
    background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5) !important;
    color: white !important;
    text-decoration: none !important;
}
@media (max-width: 480px) {
    .hero {
        height: 100vh !important;
        min-height: 568px !important;
    }
}
</style>
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
            <div class="hero-logo">
                <img src="assets/images/logo/logoBLACK (1).png" alt="Br2Studios" class="hero-logo-image">
            </div>
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
                    <span class="stat-number">+500</span>
                    <span class="stat-label">Imóveis Vendidos</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">8</span>
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
        <div class="section-container">
            <div class="section-header">
                <h2>Imóveis em Destaque</h2>
                <p>Deslize para ver nossas oportunidades</p>
            </div>
        <div class="properties-carousel-mobile">
            <?php if (!empty($imoveis_destaque)): ?>
                <?php foreach ($imoveis_destaque as $imovel): ?>
                    <div class="property-card-mobile">
                        <div class="property-image-mobile" style="background-image: url('<?php echo $imovel['imagem_principal'] ?: 'assets/images/imoveis/Imovel-1.jpeg'; ?>');">
                            <div class="property-badges-mobile">
                                <?php if ($imovel['destaque']): ?>
                                    <div class="property-badge-mobile badge-destaque-mobile">
                                        <i class="fas fa-star"></i>
                                        DESTAQUE
                                    </div>
                                <?php endif; ?>
                                <?php if ($imovel['maior_valorizacao']): ?>
                                    <div class="property-badge-mobile badge-valorizacao-mobile">
                                        <i class="fas fa-home"></i>
                                        VALORIZAÇÃO
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($imovel['ano_entrega'])): ?>
                                    <div class="property-badge-mobile badge-entrega-mobile">
                                        <i class="fas fa-calendar-alt"></i>
                                        ENTREGA <?php echo $imovel['ano_entrega']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
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
            <a href="imoveis.php?filtro=destaque" class="btn-primary btn-large">Ver Todos os Imóveis em Destaque</a>
        </div>
    </section>

    <!-- Features Section Mobile - Limpa e Organizada -->
    <section class="features-mobile mobile-only">
        <div class="section-container">
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

    <!-- Imóveis de Maior Valorização Mobile -->
    <section class="properties-mobile valorizacao-mobile mobile-only">
        <div class="section-container">
            <div class="section-header">
                <h2>Maior Valorização</h2>
                <p>Oportunidades com excelente potencial</p>
            </div>
        <div class="properties-carousel-mobile">
            <?php if (!empty($imoveis_valorizacao)): ?>
                <?php foreach ($imoveis_valorizacao as $imovel_valorizacao): ?>
                    <div class="property-card-mobile valorizacao-card-mobile">
                        <div class="property-image-mobile" style="background-image: url('<?php echo $imovel_valorizacao['imagem_principal'] ?: 'assets/images/imoveis/Imovel-1.jpeg'; ?>');">
                            <div class="property-badges-mobile">
                                <?php if ($imovel_valorizacao['destaque']): ?>
                                    <div class="property-badge-mobile badge-destaque-mobile">
                                        <i class="fas fa-star"></i>
                                        DESTAQUE
                                    </div>
                                <?php endif; ?>
                                <?php if ($imovel_valorizacao['maior_valorizacao']): ?>
                                    <div class="property-badge-mobile badge-valorizacao-mobile">
                                        <i class="fas fa-home"></i>
                                        VALORIZAÇÃO
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($imovel_valorizacao['ano_entrega'])): ?>
                                    <div class="property-badge-mobile badge-entrega-mobile">
                                        <i class="fas fa-calendar-alt"></i>
                                        ENTREGA <?php echo $imovel_valorizacao['ano_entrega']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="property-info-mobile">
                            <h3 class="property-title-mobile"><?php echo htmlspecialchars($imovel_valorizacao['titulo']); ?></h3>
                            <div class="property-location-mobile">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($imovel_valorizacao['cidade'] . ', ' . $imovel_valorizacao['estado']); ?>
                            </div>
                            <div class="property-price-mobile">R$ <?php echo number_format($imovel_valorizacao['preco'], 0, ',', '.'); ?></div>
                            <div class="property-features-mobile">
                                <?php if (!empty($imovel_valorizacao['area'])): ?>
                                    <span class="property-feature-mobile"><i class="fas fa-ruler"></i> <?php echo $imovel_valorizacao['area']; ?>m²</span>
                                <?php endif; ?>
                                <?php if (!empty($imovel_valorizacao['quartos'])): ?>
                                    <span class="property-feature-mobile"><i class="fas fa-bed"></i> <?php echo $imovel_valorizacao['quartos']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($imovel_valorizacao['banheiros'])): ?>
                                    <span class="property-feature-mobile"><i class="fas fa-bath"></i> <?php echo $imovel_valorizacao['banheiros']; ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="produto.php?id=<?php echo $imovel_valorizacao['id']; ?>" class="property-action-mobile">Ver Detalhes</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Exemplo se não houver dados -->
                <div class="property-card-mobile valorizacao-card-mobile">
                    <div class="property-image-mobile" style="background-image: url('assets/images/imoveis/Imovel-1.jpeg');">
                        <div class="property-badge-mobile valorizacao-badge">VALORIZAÇÃO</div>
                    </div>
                    <div class="property-info-mobile">
                        <h3 class="property-title-mobile">Studio de Alto Potencial</h3>
                        <div class="property-location-mobile">
                            <i class="fas fa-map-marker-alt"></i>
                            São Paulo, SP
                        </div>
                        <div class="property-price-mobile">R$ 220.000</div>
                        <div class="property-features-mobile">
                            <span class="property-feature-mobile"><i class="fas fa-ruler"></i> 40m²</span>
                            <span class="property-feature-mobile"><i class="fas fa-bed"></i> 1</span>
                            <span class="property-feature-mobile"><i class="fas fa-bath"></i> 1</span>
                        </div>
                        <a href="produto.php" class="property-action-mobile">Ver Detalhes</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="imoveis.php?filtro=valorizacao" class="btn-primary btn-large">Ver Todos os Imóveis de Maior Valorização</a>
        </div>
    </section>

    <!-- Featured Properties Section Desktop -->
    <section class="featured-properties desktop-only">
        <div class="section-container">
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
                                            <span class="property-badge badge-destaque">
                                                <i class="fas fa-star"></i>
                                                DESTAQUE
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($imovel_item['maior_valorizacao']): ?>
                                            <span class="property-badge badge-valorizacao">
                                                <i class="fas fa-home"></i>
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
                <a href="imoveis.php?filtro=destaque" class="btn-view-all">Ver Todos os Imóveis em Destaque</a>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="promo-video">
        <div class="section-container">
            <div class="section-header">
                <h2>Conheça a Br2Studios</h2>
                <p>Assista ao nosso vídeo institucional</p>
            </div>
            <div class="video-card">
                <div class="video-layout">
                    <div class="video-pane">
                        <div class="video-embed">
                            <iframe 
                                src="https://www.youtube.com/embed/or4N3AI8OoY?rel=0&modestbranding=1&color=white"
                                title="Br2Studios - Vídeo institucional"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                loading="lazy"></iframe>
                        </div>
                    </div>
                    <aside class="video-info">
                        <h3>Investir com estratégia, crescer com segurança</h3>
                        <p>Veja em 60 segundos como a Br2Studios seleciona oportunidades imobiliárias com alto potencial de valorização e renda.</p>
                        <ul class="video-highlights">
                            <li><i class="fas fa-check"></i> Curadoria em regiões estratégicas</li>
                            <li><i class="fas fa-check"></i> Análise de risco e performance</li>
                            <li><i class="fas fa-check"></i> Acompanhamento do investimento</li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section Desktop -->
    <section class="features desktop-only">
        <div class="section-container">
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
                 
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="feature-title">Gestão Focada no Investidor</h3>
                    <p class="feature-description">
                        Consultoria especializada para otimizar seus rendimentos e garantir eficiência em todo o 
                        processo de investimento e gestão de propriedades.
                    </p>
            
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
                
                </div>
            </div>
        </div>
    </section>

    <!-- Imóveis de Maior Valorização Section Desktop -->
    <section class="featured-properties valorizacao-properties desktop-only">
        <div class="section-container">
            <div class="section-header">
                <h2>Imóveis de Maior Valorização</h2>
                <p>Oportunidades com excelente potencial de crescimento e retorno sobre investimento</p>
            </div>
            
            <div class="properties-carousel">
                <div class="properties-grid">
                    <?php if (!empty($imoveis_valorizacao)): ?>
                        <?php foreach ($imoveis_valorizacao as $imovel_valorizacao): ?>
                            <div class="property-card valorizacao-card">
                                <div class="property-image">
                                    <?php if (!empty($imovel_valorizacao['imagem_principal'])): ?>
                                        <img src="<?php echo htmlspecialchars($imovel_valorizacao['imagem_principal']); ?>" 
                                             alt="<?php echo htmlspecialchars($imovel_valorizacao['titulo']); ?>">
                                    <?php else: ?>
                                        <img src="assets/images/imoveis/Imovel-1.jpeg" 
                                             alt="Imóvel sem imagem">
                                    <?php endif; ?>
                                    
                                    <div class="property-labels">
                                        <?php if ($imovel_valorizacao['destaque']): ?>
                                            <span class="property-badge badge-destaque">
                                                <i class="fas fa-star"></i>
                                                DESTAQUE
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($imovel_valorizacao['maior_valorizacao']): ?>
                                            <span class="property-badge badge-valorizacao">
                                                <i class="fas fa-home"></i>
                                                VALORIZAÇÃO
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($imovel_valorizacao['ano_entrega'])): ?>
                                            <span class="property-badge badge-entrega">
                                                <i class="fas fa-calendar-alt"></i>
                                                ENTREGA <?php echo $imovel_valorizacao['ano_entrega']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="property-price">
                                        <span class="price">R$ <?php echo number_format($imovel_valorizacao['preco'], 0, ',', '.'); ?></span>
                                        <?php if (!empty($imovel_valorizacao['area']) && $imovel_valorizacao['area'] > 0): ?>
                                            <span class="price-per-sqft">R$ <?php echo number_format($imovel_valorizacao['preco'] / $imovel_valorizacao['area'], 0, ',', '.'); ?>/m²</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="property-info">
                                    <h3><?php echo htmlspecialchars($imovel_valorizacao['titulo']); ?></h3>
                                    <p class="property-location">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php echo htmlspecialchars($imovel_valorizacao['cidade'] . ', ' . $imovel_valorizacao['estado']); ?>
                                    </p>
                                    <div class="property-details">
                                        <?php if (!empty($imovel_valorizacao['area']) && $imovel_valorizacao['area'] > 0): ?>
                                            <span><i class="fas fa-ruler-combined"></i> <?php echo $imovel_valorizacao['area']; ?>m²</span>
                                        <?php endif; ?>
                                        <?php if (!empty($imovel_valorizacao['quartos'])): ?>
                                            <span><i class="fas fa-bed"></i> <?php echo $imovel_valorizacao['quartos']; ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($imovel_valorizacao['banheiros'])): ?>
                                            <span><i class="fas fa-bath"></i> <?php echo $imovel_valorizacao['banheiros']; ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($imovel_valorizacao['vagas'])): ?>
                                            <span><i class="fas fa-car"></i> <?php echo $imovel_valorizacao['vagas']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($imovel_valorizacao['ano_entrega'])): ?>
                                        <div class="property-delivery">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Entrega <?php echo $imovel_valorizacao['ano_entrega']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <a href="produto.php?id=<?php echo $imovel_valorizacao['id']; ?>" class="btn-view-property">Ver Detalhes</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Imóveis de exemplo caso não haja dados no banco -->
                        <div class="property-card valorizacao-card">
                            <div class="property-image">
                                <img src="assets/images/imoveis/Imovel-1.jpeg" alt="Studio de Alto Potencial - São Paulo">
                                <div class="property-labels">
                                    <span class="label-valorizacao">MAIOR VALORIZAÇÃO</span>
                                </div>
                                <div class="property-price">
                                    <span class="price">R$ 220.000</span>
                                    <span class="price-per-sqft">R$ 5.500/m²</span>
                                </div>
                            </div>
                            <div class="property-info">
                                <h3>Studio de Alto Potencial</h3>
                                <p class="property-location"><i class="fas fa-map-marker-alt"></i> São Paulo, SP</p>
                                <div class="property-details">
                                    <span><i class="fas fa-ruler-combined"></i> 40m²</span>
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
                <a href="imoveis.php?filtro=valorizacao" class="btn-view-all">Ver Todos os Imóveis de Maior Valorização</a>
            </div>
        </div>
    </section>


    <!-- Regiões de Curitiba - Cards em 2 Colunas -->
    <section class="regioes-curitiba">
        <div class="section-container">
            <div class="section-header">
                <h2>Nossos Bairros de Atuação</h2>
                <p>Especialistas em investimentos imobiliários em Curitiba - Conheça os bairros onde atuamos</p>
            </div>
            
            <!-- Desktop Grid - 3 Colunas -->
            <div class="regioes-grid desktop-only" style="grid-template-columns: repeat(3, 1fr);">
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Sítio Cercado</h3>
                        <p>Bairro com excelente infraestrutura e crescimento imobiliário</p>
                        <a href="regioes.php?regiao=sitio-cercado" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Água Verde</h3>
                        <p>Bairro residencial com ótima localização e crescimento imobiliário</p>
                        <a href="regioes.php?regiao=agua-verde" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Batel</h3>
                        <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                        <a href="regioes.php?regiao=batel" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Cabral</h3>
                        <p>Bairro tradicional com boa infraestrutura e potencial de valorização</p>
                        <a href="regioes.php?regiao=cabral" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Centro Cívico</h3>
                        <p>Região administrativa com infraestrutura completa e fácil acesso</p>
                        <a href="regioes.php?regiao=centro-civico" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Juvevê</h3>
                        <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                        <a href="regioes.php?regiao=juveve" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-city"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Centro</h3>
                        <p>Região central com infraestrutura completa e fácil acesso</p>
                        <a href="regioes.php?regiao=centro" class="regiao-link">Explorar Região</a>
                    </div>
                </div>
                
                <div class="regiao-card">
                    <div class="regiao-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="regiao-content">
                        <h3>Jardim Botânico</h3>
                        <p>Bairro verde com natureza preservada e excelente qualidade de vida</p>
                        <a href="regioes.php?regiao=jardim-botanico" class="regiao-link">Explorar Região</a>
                    </div>
                </div>            </div>
            
            <!-- Mobile Carousel Automático - Estilo Parceiros -->
            <div class="regioes-carousel mobile-only">
                <div class="regioes-carousel-wrapper">
                    <div class="regioes-track">
                        <!-- Primeira linha de regiões -->
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Sítio Cercado</h3>
                                    <p>Bairro com excelente infraestrutura e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=sitio-cercado" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Água Verde</h3>
                                    <p>Bairro residencial com ótima localização e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=agua-verde" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Água Verde</h3>
                                    <p>Bairro residencial com ótima localização e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=agua-verde" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Batel</h3>
                                    <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                                    <a href="regioes.php?regiao=batel" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Segunda linha de regiões (duplicada para loop infinito) -->
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Sítio Cercado</h3>
                                    <p>Bairro com excelente infraestrutura e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=sitio-cercado" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Água Verde</h3>
                                    <p>Bairro residencial com ótima localização e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=agua-verde" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Água Verde</h3>
                                    <p>Bairro residencial com ótima localização e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=agua-verde" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Batel</h3>
                                    <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                                    <a href="regioes.php?regiao=batel" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Cabral</h3>
                                    <p>Bairro tradicional com boa infraestrutura e potencial de valorização</p>
                                    <a href="regioes.php?regiao=cabral" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Centro Cívico</h3>
                                    <p>Região administrativa com infraestrutura completa e fácil acesso</p>
                                    <a href="regioes.php?regiao=centro-civico" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Juvevê</h3>
                                    <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                                    <a href="regioes.php?regiao=juveve" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Centro</h3>
                                    <p>Região central com infraestrutura completa e fácil acesso</p>
                                    <a href="regioes.php?regiao=centro" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Jardim Botânico</h3>
                                    <p>Bairro verde com natureza preservada e excelente qualidade de vida</p>
                                    <a href="regioes.php?regiao=jardim-botanico" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Duplicação para scroll infinito -->
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Sítio Cercado</h3>
                                    <p>Bairro com excelente infraestrutura e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=sitio-cercado" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Água Verde</h3>
                                    <p>Bairro residencial com ótima localização e crescimento imobiliário</p>
                                    <a href="regioes.php?regiao=agua-verde" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Batel</h3>
                                    <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                                    <a href="regioes.php?regiao=batel" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Cabral</h3>
                                    <p>Bairro tradicional com boa infraestrutura e potencial de valorização</p>
                                    <a href="regioes.php?regiao=cabral" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Centro Cívico</h3>
                                    <p>Região administrativa com infraestrutura completa e fácil acesso</p>
                                    <a href="regioes.php?regiao=centro-civico" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Juvevê</h3>
                                    <p>Bairro nobre com excelente qualidade de vida e valorização</p>
                                    <a href="regioes.php?regiao=juveve" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Centro</h3>
                                    <p>Região central com infraestrutura completa e fácil acesso</p>
                                    <a href="regioes.php?regiao=centro" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="regiao-slide">
                            <div class="regiao-card">
                                <div class="regiao-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="regiao-content">
                                    <h3>Jardim Botânico</h3>
                                    <p>Bairro verde com natureza preservada e excelente qualidade de vida</p>
                                    <a href="regioes.php?regiao=jardim-botanico" class="regiao-link">Explorar Região</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section-footer">
                <a href="regioes.php" class="btn-view-all">Explorar Todas as Regiões</a>
            </div>
        </div>
    </section>

    <!-- Meet Our Agents Section Desktop -->
    <section class="meet-agents desktop-only">
        <div class="section-container">
            <div class="section-header">
                <h2>Conheça Nossos Especialistas</h2>
                <p>Equipe qualificada para orientar seus investimentos imobiliários</p>
            </div>
            
            <div class="agents-grid">
                <?php if (!empty($corretores_destaque)): ?>
                    <?php foreach ($corretores_destaque as $corretor_item): ?>
                        <div class="agent-card">
                            <div class="agent-image">
                                <?php if (!empty($corretor_item['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($corretor_item['foto']); ?>" 
                                         alt="<?php echo htmlspecialchars($corretor_item['nome']); ?> - Especialista em Investimentos"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="agent-avatar-fallback" style="display: none;">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="agent-avatar-fallback">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="agent-social">
                                    <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $corretor_item['telefone']); ?>?text=Olá <?php echo urlencode($corretor_item['nome']); ?>! Vi seu perfil no site da Br2Studios e gostaria de conversar sobre imóveis." target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="mailto:<?php echo htmlspecialchars($corretor_item['email']); ?>">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                            <h3><?php echo htmlspecialchars($corretor_item['nome']); ?></h3>
                            <p class="agent-title">Especialista em Investimentos</p>
                            <p class="agent-description">
                                <?php if (!empty($corretor_item['bio'])): ?>
                                    <?php echo htmlspecialchars($corretor_item['bio']); ?>
                                <?php else: ?>
                                    Especialista em investimentos imobiliários com experiência no mercado de studios e alto retorno.
                                <?php endif; ?>
                            </p>
                            <div class="agent-stats">
                                <span><i class="fas fa-certificate"></i> CRECI: <?php echo htmlspecialchars($corretor_item['creci'] ?: 'Em processo'); ?></span>
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($corretor_item['cidade'] . ', ' . $corretor_item['estado']); ?></span>
                            </div>
                            <a href="corretores.php" class="btn-view-profile">Ver Perfil</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback se não houver corretores no banco -->
                    <div class="agent-card">
                        <div class="agent-image">
                            <div class="agent-avatar-fallback">
                                <i class="fas fa-user-tie"></i>
                            </div>
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
                            <div class="agent-avatar-fallback">
                                <i class="fas fa-user-tie"></i>
                            </div>
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
                            <div class="agent-avatar-fallback">
                                <i class="fas fa-user-tie"></i>
                            </div>
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
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Seção de Depoimentos - DESKTOP E MOBILE SEPARADOS -->
    <?php
    // Buscar depoimentos de forma independente para evitar conflitos
    $testimonials_data = [];
    try {
        $testimonial_obj = new Depoimento();
        $testimonials_result = $testimonial_obj->listarDestaques(10);
        if (is_array($testimonials_result)) {
            $testimonials_data = $testimonials_result;
        }
    } catch (Exception $e) {
        error_log("Erro ao buscar depoimentos: " . $e->getMessage());
        $testimonials_data = [];
    }
    ?>
    
    <!-- VERSÃO DESKTOP -->
    <section class="testimonials-desktop" style="padding: 100px 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); text-align: center; position: relative; overflow: hidden;">
        <!-- Background decorativo -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: linear-gradient(45deg, rgba(220, 38, 38, 0.05), rgba(220, 38, 38, 0.1)); border-radius: 50%; z-index: 1;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: linear-gradient(45deg, rgba(220, 38, 38, 0.03), rgba(220, 38, 38, 0.08)); border-radius: 50%; z-index: 1;"></div>
        
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 30px; position: relative; z-index: 2;">
            <!-- Header desktop -->
            <div style="margin-bottom: 80px;">
                <h2 style="font-size: 3.2rem; font-weight: 800; color: #1a1a1a; margin-bottom: 20px; letter-spacing: -0.02em; line-height: 1.1;">
                    O que nossos <span style="color: #dc2626;">clientes</span> dizem
                </h2>
                <p style="font-size: 1.3rem; color: #6b7280; margin-bottom: 0; font-weight: 400; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                    Depoimentos reais de investidores que confiaram na Br2Studios e transformaram seus sonhos em realidade
                </p>
            </div>
            
            <!-- Carrossel desktop -->
            <div id="testimonials-desktop-container" style="position: relative; overflow: hidden; max-width: 900px; margin: 0 auto; border-radius: 30px;">
                <div id="testimonials-desktop-track" style="display: flex; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);">
                    <?php if (!empty($testimonials_data)): ?>
                        <?php foreach ($testimonials_data as $idx => $testimonial): ?>
                            <div class="testimonial-desktop-item" style="min-width: 100%; padding: 0 25px; box-sizing: border-box;">
                                <div style="background: white; border-radius: 30px; padding: 60px 50px; box-shadow: 0 25px 60px rgba(0,0,0,0.08), 0 10px 30px rgba(0,0,0,0.05); text-align: center; position: relative; border: 1px solid rgba(220, 38, 38, 0.1); min-height: 450px; display: flex; flex-direction: column; justify-content: center;">
                                    <!-- Aspas decorativas -->
                                    <div style="font-size: 120px; color: #dc2626; position: absolute; top: 30px; left: 40px; opacity: 0.08; font-family: serif; line-height: 1;">"</div>
                                    
                                    <!-- Avaliação com estrelas -->
                                    <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 35px; color: #fbbf24;">
                                        <?php for ($star = 1; $star <= 5; $star++): ?>
                                            <i class="fas fa-star<?php echo $star <= $testimonial['avaliacao'] ? '' : '-o'; ?>" style="font-size: 24px; filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.3));"></i>
                                        <?php endfor; ?>
                                    </div>
                                    
                                    <!-- Texto do depoimento -->
                                    <div style="font-size: 1.4rem; line-height: 1.8; color: #374151; margin-bottom: 45px; font-style: italic; position: relative; z-index: 2; font-weight: 400;">
                                        "<?php echo htmlspecialchars($testimonial['depoimento']); ?>"
                                    </div>
                                    
                                    <!-- Informações do autor -->
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 25px; margin-top: auto;">
                                        <!-- Avatar -->
                                        <div style="width: 85px; height: 85px; border-radius: 50%; background: linear-gradient(135deg, #dc2626, #b91c1c); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; border: 4px solid white; box-shadow: 0 8px 25px rgba(220, 38, 38, 0.25); overflow: hidden; position: relative;">
                                            <?php if (!empty($testimonial['foto'])): ?>
                                                <img src="<?php echo htmlspecialchars($testimonial['foto']); ?>" alt="<?php echo htmlspecialchars($testimonial['nome']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <i class="fas fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Dados do autor -->
                                        <div style="text-align: left;">
                                            <h4 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 8px; letter-spacing: -0.01em;"><?php echo htmlspecialchars($testimonial['nome']); ?></h4>
                                            <p style="font-size: 1.1rem; color: #6b7280; margin-bottom: 6px; font-weight: 500;"><?php echo htmlspecialchars($testimonial['cargo']); ?></p>
                                            <span style="font-size: 1rem; color: #9ca3af; font-weight: 400; display: flex; align-items: center; gap: 5px;">
                                                <i class="fas fa-map-marker-alt" style="font-size: 12px;"></i>
                                                <?php echo htmlspecialchars($testimonial['cidade']); ?>, <?php echo htmlspecialchars($testimonial['estado']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <?php if (count($testimonials_data) > 1): ?>
                    <!-- Controles desktop -->
                    <div style="display: flex; justify-content: center; align-items: center; gap: 40px; margin-top: 60px;">
                        <button id="testimonials-desktop-prev" style="width: 70px; height: 70px; border-radius: 50%; background: white; border: 3px solid #dc2626; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-size: 24px; box-shadow: 0 8px 25px rgba(220, 38, 38, 0.15); position: relative; overflow: hidden;">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div id="testimonials-desktop-dots" style="display: flex; gap: 15px;">
                            <?php foreach ($testimonials_data as $idx => $testimonial): ?>
                                <span class="testimonials-desktop-dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide="<?php echo $idx; ?>" style="width: 16px; height: 16px; border-radius: 50%; background: <?php echo $idx === 0 ? '#dc2626' : '#d1d5db'; ?>; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); <?php echo $idx === 0 ? 'transform: scale(1.4); box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);' : ''; ?>"></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <button id="testimonials-desktop-next" style="width: 70px; height: 70px; border-radius: 50%; background: white; border: 3px solid #dc2626; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-size: 24px; box-shadow: 0 8px 25px rgba(220, 38, 38, 0.15); position: relative; overflow: hidden;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- VERSÃO MOBILE COMPACTA -->
    <section class="testimonials-mobile" style="padding: 40px 0; background: #f8f9fa; text-align: center; display: none;">
        <div style="max-width: 100%; margin: 0 auto; padding: 0 15px;">
            <!-- Header mobile compacto -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 10px;">
                    O que nossos <span style="color: #dc2626;">clientes</span> dizem
                </h2>
                <p style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0; line-height: 1.4;">
                    Depoimentos reais de investidores
                </p>
            </div>
            
            <!-- Carrossel mobile compacto -->
            <div id="testimonials-mobile-container" style="position: relative; overflow: hidden; margin: 0 auto;">
                <div id="testimonials-mobile-track" style="display: flex; transition: transform 0.4s ease;">
                    <?php if (!empty($testimonials_data)): ?>
                        <?php foreach ($testimonials_data as $idx => $testimonial): ?>
                            <div class="testimonial-mobile-item" style="min-width: 100%; padding: 0 10px; box-sizing: border-box;">
                                <div style="background: white; border-radius: 15px; padding: 20px 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: center; position: relative; border: 1px solid rgba(220, 38, 38, 0.1);">
                                    <!-- Aspas pequenas -->
                                    <div style="font-size: 40px; color: #dc2626; position: absolute; top: 8px; left: 12px; opacity: 0.1; font-family: serif; line-height: 1;">"</div>
                                    
                                    <!-- Estrelas pequenas -->
                                    <div style="display: flex; justify-content: center; gap: 3px; margin-bottom: 12px; color: #fbbf24;">
                                        <?php for ($star = 1; $star <= 5; $star++): ?>
                                            <i class="fas fa-star<?php echo $star <= $testimonial['avaliacao'] ? '' : '-o'; ?>" style="font-size: 12px;"></i>
                                        <?php endfor; ?>
                                    </div>
                                    
                                    <!-- Texto compacto -->
                                    <div style="font-size: 0.85rem; line-height: 1.4; color: #374151; margin-bottom: 15px; font-style: italic; position: relative; z-index: 2; font-weight: 400;">
                                        "<?php echo htmlspecialchars(substr($testimonial['depoimento'], 0, 120)) . (strlen($testimonial['depoimento']) > 120 ? '...' : ''); ?>"
                                    </div>
                                    
                                    <!-- Autor compacto -->
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                        <!-- Avatar pequeno -->
                                        <div style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #dc2626, #b91c1c); display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; border: 2px solid white; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2); overflow: hidden;">
                                            <?php if (!empty($testimonial['foto'])): ?>
                                                <img src="<?php echo htmlspecialchars($testimonial['foto']); ?>" alt="<?php echo htmlspecialchars($testimonial['nome']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <i class="fas fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Dados compactos -->
                                        <div style="text-align: left;">
                                            <h4 style="font-size: 0.8rem; font-weight: 600; color: #1f2937; margin-bottom: 2px;"><?php echo htmlspecialchars($testimonial['nome']); ?></h4>
                                            <p style="font-size: 0.7rem; color: #6b7280; margin-bottom: 0; font-weight: 400;"><?php echo htmlspecialchars($testimonial['cidade']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <?php if (count($testimonials_data) > 1): ?>
                    <!-- Controles mobile compactos -->
                    <div style="display: flex;    padding: 15px;
                    
                    justify-content: center; align-items: center; gap: 20px; margin-top: 20px;">
                        <button id="testimonials-mobile-prev" style="width: 35px; height: 35px; border-radius: 50%; background: white; border: 2px solid #dc2626; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 12px; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.15);">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div id="testimonials-mobile-dots" style="display: flex; gap: 6px;">
                            <?php foreach ($testimonials_data as $idx => $testimonial): ?>
                                <span class="testimonials-mobile-dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide="<?php echo $idx; ?>" style="width: 8px; height: 8px; border-radius: 50%; background: <?php echo $idx === 0 ? '#dc2626' : '#d1d5db'; ?>; cursor: pointer; transition: all 0.2s ease; <?php echo $idx === 0 ? 'transform: scale(1.2);' : ''; ?>"></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <button id="testimonials-mobile-next" style="width: 35px; height: 35px; border-radius: 50%; background: white; border: 2px solid #dc2626; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 12px; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.15);">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <style>
    /* Controle de visibilidade desktop/mobile */
    @media (max-width: 768px) {
        .testimonials-desktop {
            display: none !important;
        }
        
        .testimonials-mobile {
            display: block !important;
        }
    }
    
    @media (min-width: 769px) {
        .testimonials-desktop {
            display: block !important;
        }
        
        .testimonials-mobile {
            display: none !important;
        }
    }
    
    /* Efeitos hover para desktop */
    #testimonials-desktop-prev:hover,
    #testimonials-desktop-next:hover {
        background: #dc2626 !important;
        color: white !important;
        transform: scale(1.1) !important;
        box-shadow: 0 12px 35px rgba(220, 38, 38, 0.25) !important;
    }
    
    .testimonials-desktop-dot:hover {
        background: #dc2626 !important;
        transform: scale(1.2) !important;
    }
    
    /* Efeitos hover para mobile */
    #testimonials-mobile-prev:hover,
    #testimonials-mobile-next:hover {
        background: #dc2626 !important;
        color: white !important;
        transform: scale(1.05) !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2) !important;
    }
    
    .testimonials-mobile-dot:hover {
        background: #dc2626 !important;
        transform: scale(1.1) !important;
    }
    
    /* Animação suave para os cards */
    .testimonial-desktop-item,
    .testimonial-mobile-item {
        transition: all 0.3s ease !important;
    }
    
    .testimonial-desktop-item:hover {
        transform: translateY(-5px) !important;
    }
    
    .testimonial-mobile-item:hover {
        transform: translateY(-2px) !important;
    }
    </style>

    <script>
    // CARROSSEL DE DEPOIMENTOS - DESKTOP E MOBILE SEPARADOS
    document.addEventListener('DOMContentLoaded', function() {
        const testimonialsTotalItems = <?php echo count($testimonials_data); ?>;
        
        // ===== CARROSSEL DESKTOP =====
        const desktopTrack = document.getElementById('testimonials-desktop-track');
        const desktopPrevBtn = document.getElementById('testimonials-desktop-prev');
        const desktopNextBtn = document.getElementById('testimonials-desktop-next');
        const desktopDots = document.querySelectorAll('.testimonials-desktop-dot');
        
        let desktopCurrentIndex = 0;
        let desktopAutoPlayInterval;
        
        function updateDesktopCarousel() {
            if (!desktopTrack) return;
            
            const translateX = -desktopCurrentIndex * 100;
            desktopTrack.style.transform = `translateX(${translateX}%)`;
            
            // Atualizar dots desktop
            desktopDots.forEach((dot, index) => {
                if (index === desktopCurrentIndex) {
                    dot.style.background = '#dc2626';
                    dot.style.transform = 'scale(1.4)';
                    dot.style.boxShadow = '0 4px 15px rgba(220, 38, 38, 0.3)';
                } else {
                    dot.style.background = '#d1d5db';
                    dot.style.transform = 'scale(1)';
                    dot.style.boxShadow = 'none';
                }
            });
        }
        
        function desktopNextSlide() {
            desktopCurrentIndex = (desktopCurrentIndex + 1) % testimonialsTotalItems;
            updateDesktopCarousel();
        }
        
        function desktopPrevSlide() {
            desktopCurrentIndex = desktopCurrentIndex === 0 ? testimonialsTotalItems - 1 : desktopCurrentIndex - 1;
            updateDesktopCarousel();
        }
        
        function desktopGoToSlide(index) {
            desktopCurrentIndex = index;
            updateDesktopCarousel();
        }
        
        function desktopStartAutoPlay() {
            desktopAutoPlayInterval = setInterval(desktopNextSlide, 6000);
        }
        
        function desktopStopAutoPlay() {
            clearInterval(desktopAutoPlayInterval);
        }
        
        // Event listeners desktop
        if (desktopNextBtn) {
            desktopNextBtn.addEventListener('click', () => {
                desktopNextBtn.style.transform = 'scale(0.95)';
                setTimeout(() => { desktopNextBtn.style.transform = ''; }, 150);
                desktopNextSlide();
                desktopStopAutoPlay();
                desktopStartAutoPlay();
            });
        }
        
        if (desktopPrevBtn) {
            desktopPrevBtn.addEventListener('click', () => {
                desktopPrevBtn.style.transform = 'scale(0.95)';
                setTimeout(() => { desktopPrevBtn.style.transform = ''; }, 150);
                desktopPrevSlide();
                desktopStopAutoPlay();
                desktopStartAutoPlay();
            });
        }
        
        desktopDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                desktopGoToSlide(index);
                desktopStopAutoPlay();
                desktopStartAutoPlay();
            });
        });
        
        // ===== CARROSSEL MOBILE =====
        const mobileTrack = document.getElementById('testimonials-mobile-track');
        const mobilePrevBtn = document.getElementById('testimonials-mobile-prev');
        const mobileNextBtn = document.getElementById('testimonials-mobile-next');
        const mobileDots = document.querySelectorAll('.testimonials-mobile-dot');
        
        let mobileCurrentIndex = 0;
        let mobileAutoPlayInterval;
        
        function updateMobileCarousel() {
            if (!mobileTrack) return;
            
            const translateX = -mobileCurrentIndex * 100;
            mobileTrack.style.transform = `translateX(${translateX}%)`;
            
            // Atualizar dots mobile
            mobileDots.forEach((dot, index) => {
                if (index === mobileCurrentIndex) {
                    dot.style.background = '#dc2626';
                    dot.style.transform = 'scale(1.2)';
                } else {
                    dot.style.background = '#d1d5db';
                    dot.style.transform = 'scale(1)';
                }
            });
        }
        
        function mobileNextSlide() {
            mobileCurrentIndex = (mobileCurrentIndex + 1) % testimonialsTotalItems;
            updateMobileCarousel();
        }
        
        function mobilePrevSlide() {
            mobileCurrentIndex = mobileCurrentIndex === 0 ? testimonialsTotalItems - 1 : mobileCurrentIndex - 1;
            updateMobileCarousel();
        }
        
        function mobileGoToSlide(index) {
            mobileCurrentIndex = index;
            updateMobileCarousel();
        }
        
        function mobileStartAutoPlay() {
            mobileAutoPlayInterval = setInterval(mobileNextSlide, 5000); // Mais rápido no mobile
        }
        
        function mobileStopAutoPlay() {
            clearInterval(mobileAutoPlayInterval);
        }
        
        // Event listeners mobile
        if (mobileNextBtn) {
            mobileNextBtn.addEventListener('click', () => {
                mobileNextBtn.style.transform = 'scale(0.9)';
                setTimeout(() => { mobileNextBtn.style.transform = ''; }, 100);
                mobileNextSlide();
                mobileStopAutoPlay();
                mobileStartAutoPlay();
            });
        }
        
        if (mobilePrevBtn) {
            mobilePrevBtn.addEventListener('click', () => {
                mobilePrevBtn.style.transform = 'scale(0.9)';
                setTimeout(() => { mobilePrevBtn.style.transform = ''; }, 100);
                mobilePrevSlide();
                mobileStopAutoPlay();
                mobileStartAutoPlay();
            });
        }
        
        mobileDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                mobileGoToSlide(index);
                mobileStopAutoPlay();
                mobileStartAutoPlay();
            });
        });
        
        // ===== CONTROLE GERAL =====
        function startAutoPlay() {
            if (window.innerWidth > 768) {
                desktopStartAutoPlay();
                mobileStopAutoPlay();
            } else {
                mobileStartAutoPlay();
                desktopStopAutoPlay();
            }
        }
        
        function stopAutoPlay() {
            desktopStopAutoPlay();
            mobileStopAutoPlay();
        }
        
        // Pausar no hover
        const desktopContainer = document.getElementById('testimonials-desktop-container');
        const mobileContainer = document.getElementById('testimonials-mobile-container');
        
        if (desktopContainer) {
            desktopContainer.addEventListener('mouseenter', desktopStopAutoPlay);
            desktopContainer.addEventListener('mouseleave', () => {
                if (window.innerWidth > 768) desktopStartAutoPlay();
            });
        }
        
        if (mobileContainer) {
            mobileContainer.addEventListener('mouseenter', mobileStopAutoPlay);
            mobileContainer.addEventListener('mouseleave', () => {
                if (window.innerWidth <= 768) mobileStartAutoPlay();
            });
        }
        
        // Pausar quando a página não está visível
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoPlay();
            } else {
                startAutoPlay();
            }
        });
        
        // Resize handler
        window.addEventListener('resize', () => {
            startAutoPlay();
        });
        
        // Iniciar auto-play
        if (testimonialsTotalItems > 1) {
            startAutoPlay();
            console.log('Carrosséis de depoimentos iniciados com', testimonialsTotalItems, 'itens');
        }
    });
    </script>
    

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="section-container">
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
        <div class="section-container">
            <div class="section-header">
                <h2>Nossos Parceiros</h2>
                <p>Empresas que confiam na nossa expertise</p>
            </div>
            
            <div class="partners-carousel-wrapper">
                <div class="partners-track">
                    <!-- Primeira linha de logos -->
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7549.PNG" alt="Parceiro 1" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7550.PNG" alt="Parceiro 2" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7551.PNG" alt="Parceiro 3" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7552.PNG" alt="Parceiro 4" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7553.PNG" alt="Parceiro 5" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7554.PNG" alt="Parceiro 6" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7555.PNG" alt="Parceiro 7" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7556.PNG" alt="Parceiro 8" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7557.PNG" alt="Parceiro 9" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7558.PNG" alt="Parceiro 10" />
                        </div>
                    </div>
                    
                    <!-- Segunda linha de logos (duplicada para loop infinito) -->
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7549.PNG" alt="Parceiro 1" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7550.PNG" alt="Parceiro 2" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7551.PNG" alt="Parceiro 3" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7552.PNG" alt="Parceiro 4" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7553.PNG" alt="Parceiro 5" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7554.PNG" alt="Parceiro 6" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7555.PNG" alt="Parceiro 7" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7556.PNG" alt="Parceiro 8" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7557.PNG" alt="Parceiro 9" />
                        </div>
                    </div>
                    <div class="partner-slide">
                        <div class="partner-logo">
                            <img src="assets/images/parceiros/IMG_7558.PNG" alt="Parceiro 10" />
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
    require_once __DIR__ . '/config/version.php';
    $version = getAssetsVersion();
    ?>
    <script src="assets/js/main.js?v=<?php echo $version; ?>"></script>
    <script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
</body>
</html>
