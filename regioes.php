<?php
require_once __DIR__ . '/classes/Imovel.php';

$imovel = new Imovel();
$regiao = $_GET['regiao'] ?? '';

// Função para mapear endereço para região
function mapearEnderecoParaRegiao($endereco) {
    $endereco_lower = strtolower($endereco);
    
    // Sítio Cercado
    if (strpos($endereco_lower, 'sítio cercado') !== false || 
        strpos($endereco_lower, 'sitio cercado') !== false) {
        return 'sitio-cercado';
    }
    
    // Água Verde
    if (strpos($endereco_lower, 'água verde') !== false || 
        strpos($endereco_lower, 'agua verde') !== false) {
        return 'agua-verde';
    }
    
    // Batel
    if (strpos($endereco_lower, 'batel') !== false) {
        return 'batel';
    }
    
    // Cabral
    if (strpos($endereco_lower, 'cabral') !== false) {
        return 'cabral';
    }
    
    // Centro Cívico
    if (strpos($endereco_lower, 'centro cívico') !== false || 
        strpos($endereco_lower, 'centro civico') !== false) {
        return 'centro-civico';
    }
    
    // Juvevê
    if (strpos($endereco_lower, 'juvevê') !== false || 
        strpos($endereco_lower, 'juveve') !== false) {
        return 'juveve';
    }
    
    // Centro
    if (strpos($endereco_lower, 'centro') !== false || 
        strpos($endereco_lower, 'visconde de guarapuava') !== false ||
        strpos($endereco_lower, 'josé loureiro') !== false) {
        return 'centro';
    }
    
    // Jardim Botânico
    if (strpos($endereco_lower, 'jardim botânico') !== false || 
        strpos($endereco_lower, 'jardim botanico') !== false) {
        return 'jardim-botanico';
    }
    
    // Default: Centro se não conseguir mapear
    return 'centro';
}

// Definir regiões baseadas nos bairros de atuação definidos
$regioes = [
    'sitio-cercado' => [
        'nome' => 'Sítio Cercado',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro com excelente infraestrutura e crescimento imobiliário',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Crescimento e infraestrutura',
        'bairros' => ['Sítio Cercado']
    ],
    'agua-verde' => [
        'nome' => 'Água Verde',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro residencial com ótima localização e crescimento imobiliário',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Residencial e bem localizado',
        'bairros' => ['Água Verde']
    ],
    'batel' => [
        'nome' => 'Batel',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro nobre com excelente qualidade de vida e valorização',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Região nobre e valorizada',
        'bairros' => ['Batel']
    ],
    'cabral' => [
        'nome' => 'Cabral',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro tradicional com boa infraestrutura e potencial de valorização',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Média-Alta',
        'destaque' => 'Tradicional e em crescimento',
        'bairros' => ['Cabral']
    ],
    'centro-civico' => [
        'nome' => 'Centro Cívico',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Região administrativa com infraestrutura completa e fácil acesso',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Centro administrativo',
        'bairros' => ['Centro Cívico']
    ],
    'juveve' => [
        'nome' => 'Juvevê',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro nobre com excelente qualidade de vida e valorização',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Qualidade de vida premium',
        'bairros' => ['Juvevê']
    ],
    'centro' => [
        'nome' => 'Centro',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Região central com infraestrutura completa e fácil acesso',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Centro histórico e comercial',
        'bairros' => ['Centro']
    ],
    'jardim-botanico' => [
        'nome' => 'Jardim Botânico',
        'estado' => 'PR',
        'imagem' => 'assets/images/Mapas/Curitiba-PR.png',
        'descricao' => 'Bairro verde com natureza preservada e excelente qualidade de vida',
        'imoveis' => 0, // Será atualizado com dados reais
        'valorizacao' => 'Alta',
        'destaque' => 'Natureza e qualidade de vida',
        'bairros' => ['Jardim Botânico']
    ]
];

// Buscar imóveis reais do banco de dados para cada região
$imoveis_regiao = [];
$total_imoveis = 0;

try {
    // Buscar todos os imóveis disponíveis
    $filtros_gerais = ['status' => 'disponivel'];
    $resultado_geral = $imovel->listarTodos($filtros_gerais);
    
    if (is_array($resultado_geral) && isset($resultado_geral['imoveis'])) {
        $todos_imoveis = $resultado_geral['imoveis'];
        $total_imoveis = count($todos_imoveis);
        
        // Buscar imóveis da região selecionada baseado no endereço
        if ($regiao && isset($regioes[$regiao])) {
            $imoveis_regiao = [];
            
            // Filtrar imóveis que pertencem à região selecionada
            foreach ($todos_imoveis as $imovel_item) {
                $endereco = $imovel_item['endereco'] ?? '';
                $regiao_mapeada = mapearEnderecoParaRegiao($endereco);
                
                if ($regiao_mapeada === $regiao) {
                    $imoveis_regiao[] = $imovel_item;
                }
            }
        }
        
        // Atualizar contadores de imóveis por região baseado nos endereços reais
        foreach ($regioes as $key => &$regiao_data) {
            $contador = 0;
            
            // Contar imóveis que correspondem à região baseado no endereço
            foreach ($todos_imoveis as $imovel_item) {
                $endereco = $imovel_item['endereco'] ?? '';
                $regiao_mapeada = mapearEnderecoParaRegiao($endereco);
                
                // Verificar se o imóvel pertence à região atual
                if ($regiao_mapeada === $key) {
                    $contador++;
                }
            }
            
            $regiao_data['imoveis'] = $contador;
        }
    }
    
} catch (Exception $e) {
    error_log("Erro ao buscar imóveis da região: " . $e->getMessage());
    $total_imoveis = 0;
}
?>

<?php 
$current_page = 'regioes';
$page_title = 'Regiões de Curitiba - Br2Studios';
$page_css = 'assets/css/regioes.css';
include 'includes/header.php'; 
?>
<style>

.section-header h2 {
    font-size: 1.8rem !important;
    font-weight: 700 !important;
    margin-bottom: var(--title-margin-bottom) !important;
    line-height: 1.2 !important;
    color: #333333 !important;
    margin-top: 15%;
}.cta-section .btn-primary{

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
</style>
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
                            <span class="stat-number"><?php echo $total_imoveis; ?></span>
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
                        <?php echo $total_imoveis; ?> Imóveis
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Regions Overview -->
    <section class="regions-overview">
        <div class="container">
            <div class="section-header">
                <h2>Nossos Bairros de Atuação</h2>
                <p>Especialistas em investimentos imobiliários em Curitiba - Conheça os bairros onde atuamos</p>
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
            <h2>Nossos Bairros de Atuação</h2>
            <p>Escolha seu bairro ideal</p>
        </div>
        <div class="regioes-grid-mobile">
            <?php foreach ($regioes as $key => $regiao_item): ?>
                <div class="regiao-card-mobile" onclick="exploreRegion('<?php echo $key; ?>', '<?php echo $regiao_item['nome']; ?>')">
                    <div class="regiao-icon-mobile">
                        <i class="fas fa-<?php 
                            echo $key === 'sitio-cercado' ? 'tree' : 
                                ($key === 'agua-verde' ? 'leaf' : 
                                ($key === 'batel' ? 'building' : 
                                ($key === 'cabral' ? 'home' : 
                                ($key === 'centro-civico' ? 'landmark' : 
                                ($key === 'juveve' ? 'heart' : 
                                ($key === 'centro' ? 'city' : 
                                ($key === 'jardim-botanico' ? 'seedling' : 'map-marker-alt'))))))); 
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

  
<div class="divespac" style="    padding: 30px;
    border: none;
    box-shadow: none; background: var(--bg-secondary);"></div>
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
<script>
// Funcionalidade para explorar região
function exploreRegion(regiaoKey, regiaoNome) {
    // Mapear região para bairros correspondentes
    const regiaoBairros = {
        'sitio-cercado': ['Sítio Cercado'],
        'agua-verde': ['Água Verde'],
        'batel': ['Batel'],
        'cabral': ['Cabral'],
        'centro-civico': ['Centro Cívico'],
        'juveve': ['Juvevê'],
        'centro': ['Centro'],
        'jardim-botanico': ['Jardim Botânico']
    };
    
    // Usar o primeiro bairro da região para o filtro
    const bairros = regiaoBairros[regiaoKey] || [regiaoNome];
    const filtroBairro = bairros[0];
    
    // Redirecionar para página de imóveis com filtro da região
    const url = `imoveis.php?cidade=${encodeURIComponent(filtroBairro)}`;
    window.location.href = url;
}

// Adicionar efeitos visuais aos cards
document.addEventListener('DOMContentLoaded', function() {
    const regionCards = document.querySelectorAll('.region-card, .regiao-card-mobile');
    
    regionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
