<?php
// Buscar dados reais do banco de dados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Imovel.php';
require_once __DIR__ . '/classes/Corretor.php';

try {
    $imovel = new Imovel();
    $corretor = new Corretor();
    
    // Contar imóveis vendidos no banco
    $imoveis_vendidos = $imovel->contarTotal(['status' => 'vendido']);
    
    // Contar corretores cadastrados
    $corretores_result = $corretor->listarTodos();
    $corretores_credenciados = is_array($corretores_result) ? count($corretores_result) : 0;
    
    // Dados da empresa
    $estatisticas = [
        'imoveis_vendidos' => $imoveis_vendidos > 0 ? $imoveis_vendidos : 120, // Valor padrão se não há vendidos
        'clientes_satisfeitos' => $imoveis_vendidos > 0 ? intval($imoveis_vendidos * 0.8) : 95, // 80% dos vendidos
        'anos_experiencia' => 8, // Valor fixo da empresa
        'corretores_credenciados' => $corretores_credenciados > 0 ? $corretores_credenciados : 5
    ];
    
} catch (Exception $e) {
    error_log("Erro ao buscar estatísticas: " . $e->getMessage());
    
    // Valores padrão em caso de erro
    $estatisticas = [
        'imoveis_vendidos' => 120,
        'clientes_satisfeitos' => 95,
        'anos_experiencia' => 8,
        'corretores_credenciados' => 5
    ];
}

$valores = [
    [
        'icone' => 'fas fa-shield-alt',
        'titulo' => 'Transparência acima de tudo',
        'descricao' => 'Cada decisão é guiada pela clareza. Trabalhamos com informações reais e objetivas para que nossos clientes invistam com confiança e previsibilidade.'
    ],
    [
        'icone' => 'fas fa-chart-line',
        'titulo' => 'Compromisso com resultados',
        'descricao' => 'Mais do que vender imóveis, nosso foco está em construir histórias de sucesso. Buscamos constantemente oportunidades que proporcionem rentabilidade e valorização para cada investidor.'
    ],
    [
        'icone' => 'fas fa-users',
        'titulo' => 'Atendimento humano e especializado',
        'descricao' => 'Cada cliente é único. Por isso, oferecemos um atendimento consultivo, próximo e personalizado, para entender seu perfil e indicar os investimentos mais adequados.'
    ]
];

$equipe = [
    [
        'nome' => 'Bruno Silva',
        'cargo' => 'CEO & Fundador',
        'foto' => 'assets/images/equipe/bruno-silva.jpg',
        'descricao' => 'Especialista em investimentos imobiliários com mais de 15 anos de experiência no mercado brasileiro.',
        'linkedin' => '#',
        'email' => 'bruno@br2studios.com.br'
    ],
    [
        'nome' => 'Ana Costa',
        'cargo' => 'Diretora Comercial',
        'foto' => 'assets/images/equipe/ana-costa.jpg',
        'descricao' => 'Responsável por estratégias de vendas e relacionamento com clientes premium e investidores institucionais.',
        'linkedin' => '#',
        'email' => 'ana@br2studios.com.br'
    ],
    [
        'nome' => 'Carlos Mendes',
        'cargo' => 'Diretor de Investimentos',
        'foto' => 'assets/images/equipe/carlos-mendes.jpg',
        'descricao' => 'Especialista em análise de mercado, seleção de oportunidades e gestão de portfólio imobiliário.',
        'linkedin' => '#',
        'email' => 'carlos@br2studios.com.br'
    ]
];

$conquistas = [
    [
        'ano' => '2025',
        'titulo' => 'Expansão Nacional',
        'descricao' => 'Abertura de escritórios em 5 novos estados brasileiros, consolidando nossa presença nacional'
    ],
    [
        'ano' => '2024',
        'titulo' => 'Prêmio de Excelência',
        'descricao' => 'Reconhecimento como melhor imobiliária de studios do Brasil pelo Instituto de Qualidade Imobiliária'
    ],
    [
        'ano' => '2023',
        'titulo' => 'Milésimo Cliente',
        'descricao' => 'Atingimos a marca de 1000 clientes satisfeitos e investimentos realizados com sucesso'
    ],
    [
        'ano' => '2022',
        'titulo' => 'Tecnologia Avançada',
        'descricao' => 'Implementação de sistema de gestão inteligente e plataforma digital para investidores'
    ]
];

$diferenciais = [
    [
        'icone' => 'fas fa-home',
        'titulo' => 'Foco exclusivo em estúdios',
        'descricao' => 'Não somos uma imobiliária genérica. Nosso trabalho é totalmente dedicado ao mercado de estúdios, permitindo análises profundas e decisões mais assertivas.'
    ],
    [
        'icone' => 'fas fa-brain',
        'titulo' => 'Inteligência de investimento',
        'descricao' => 'Nossa equipe é treinada para ir além da venda. Entregamos estudos detalhados sobre valorização, rentabilidade e potencial de cada oportunidade.'
    ],
    [
        'icone' => 'fas fa-handshake',
        'titulo' => 'Suporte completo',
        'descricao' => 'Do primeiro contato até o pós-compra, você conta com nossa consultoria integral — cuidamos de cada etapa para que seu investimento seja seguro e descomplicado.'
    ]
];
?>

<?php 
$current_page = 'sobre';
$page_title = 'Sobre Nós - Br2Studios Curitiba';
$page_css = 'assets/css/sobre.css';
include 'includes/header.php'; 
?>
<style>

.hero-section .hero-content {
    display: grid !important
;
    grid-template-columns: 1fr 1fr !important;
    gap: 60px !important;
    align-items: center !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 40px !important;
    width: 100% !important;
    top: 80px !important;
}
</style>

    <!-- Hero Section Desktop -->
    <section class="hero-section desktop-only">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                   
                    <h1>Sobre a BR2 Estúdios</h1>
                    <p class="hero-subtitle">Especialistas em estúdios, especialistas em investimentos.</p>
                    <p class="hero-description">Localizada no coração de Curitiba, a BR2 Estúdios nasceu com um propósito claro: transformar o investimento em imóveis em uma experiência segura, rentável e estratégica. Nossa atuação é 100% focada em estúdios, um dos segmentos mais promissores do mercado imobiliário.</p>
                    
                    <div class="hero-actions">
                        <a href="contato.php" class="btn-primary">
                            <i class="fas fa-phone"></i>
                            Falar com Especialista
                        </a>
                        <a href="imoveis.php" class="btn-secondary">
                            <i class="fas fa-chart-line"></i>
                            Ver Oportunidades
                        </a>
                    </div>
                </div>
                
                <div class="hero-visual">
                    <div class="hero-image-container">
                        <div class="hero-image">
                            <img src="assets/images/sobre/b3c6cb8b-6f89-44e9-ae44-44b42c6b32ee.jpeg" alt="BR2 Studios - Especialistas em Estúdios">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Header Mobile Simples -->
    <section class="sobre-header-mobile mobile-only">
        <div class="container">
            <div class="header-mobile-content">
                <h1>Sobre a BR2 Estúdios</h1>
                <p class="mobile-subtitle">Especialistas em estúdios, especialistas em investimentos.</p>
                <p class="mobile-description">Localizada no coração de Curitiba, transformamos investimentos em imóveis em uma experiência segura, rentável e estratégica.</p>
                <div class="quick-stats-mobile">
                    <span class="quick-stat">
                        <i class="fas fa-calendar"></i>
                        <?php echo $estatisticas['anos_experiencia']; ?>+ Anos
                    </span>
                    <span class="quick-stat">
                        <i class="fas fa-home"></i>
                        <?php echo $estatisticas['imoveis_vendidos']; ?>+ Estúdios
                    </span>
                    <span class="quick-stat">
                        <i class="fas fa-chart-line"></i>
                        100% Foco
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Mobile - Cards Simples -->
    <section class="about-mobile mobile-only">
        <div class="section-header">
            <h2>Nossa Especialização</h2>
            <p>Foco exclusivo em estúdios</p>
        </div>
        <div class="about-content-mobile">
            <div class="about-card-mobile">
                <div class="about-icon-mobile">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Foco em Estúdios</h3>
                <p>100% dedicados ao mercado de estúdios, com análises profundas e decisões assertivas</p>
            </div>
            
            <div class="about-card-mobile">
                <div class="about-icon-mobile">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>Inteligência de Investimento</h3>
                <p>Estudos detalhados sobre valorização, rentabilidade e potencial de cada oportunidade</p>
            </div>
            
            <div class="about-highlights-mobile">
                <div class="highlight-mobile">
                    <i class="fas fa-shield-alt"></i>
                    <span>Transparência</span>
                </div>
                <div class="highlight-mobile">
                    <i class="fas fa-chart-line"></i>
                    <span>Resultados</span>
                </div>
                <div class="highlight-mobile">
                    <i class="fas fa-users"></i>
                    <span>Especializado</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section Desktop -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Nossa Especialização</h2>
                    <p>Aqui, cada detalhe é pensado para investidores que buscam oportunidades reais de valorização e retorno. Nossa equipe é formada por profissionais altamente treinados para analisar cenários, identificar tendências e indicar as melhores opções de estúdios na região central de Curitiba.</p>
                    <p>Com suporte completo em todas as etapas do processo, oferecemos confiança, transparência e resultados sólidos — para que você invista com segurança e colha o melhor que o mercado imobiliário pode oferecer.</p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Foco exclusivo em estúdios</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Análise profunda de mercado e localização</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Suporte completo em todas as etapas</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Consultoria especializada em investimentos</span>
                        </div>
                    </div>
                </div>
                <div class="about-visual">
                    <div class="about-image">
                        <img src="assets/images/sobre/66b9df37-63c3-462f-8666-25c431c03d99.jpeg" alt="BR2 Studios - Nossa Especialização">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossos Valores</h2>
                <p>Os princípios fundamentais que guiam cada decisão e ação da nossa empresa, garantindo excelência e confiança em todos os nossos serviços</p>
            </div>
            
            <div class="values-grid">
                <?php foreach ($valores as $valor): ?>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="<?php echo $valor['icone']; ?>"></i>
                        </div>
                        <h3><?php echo $valor['titulo']; ?></h3>
                        <p><?php echo $valor['descricao']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Values Mobile - Cards Simples -->
    <section class="values-mobile mobile-only">
        <div class="section-header">
            <h2>Nossos Valores</h2>
            <p>O que nos move</p>
        </div>
        <div class="values-grid-mobile">
            <?php foreach ($valores as $valor): ?>
                <div class="value-card-mobile">
                    <div class="value-icon-mobile">
                        <i class="<?php echo $valor['icone']; ?>"></i>
                    </div>
                    <h3><?php echo $valor['titulo']; ?></h3>
                    <p><?php echo $valor['descricao']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Diferenciais Section -->
    <section class="diferenciais-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossos Diferenciais</h2>
                <p>O que nos torna únicos no mercado imobiliário brasileiro e por que escolher a Br2Studios para seus investimentos</p>
            </div>
            
            <div class="diferenciais-grid">
                <?php foreach ($diferenciais as $diferencial): ?>
                    <div class="diferencial-card">
                        <div class="diferencial-icon">
                            <i class="<?php echo $diferencial['icone']; ?>"></i>
                        </div>
                        <h3><?php echo $diferencial['titulo']; ?></h3>
                        <p><?php echo $diferencial['descricao']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="mission-content">
                <div class="mission-text">
                    <h2>Nossa Missão</h2>
                    <p>Transformar o sonho da casa própria em realidade, oferecendo as melhores oportunidades de investimento imobiliário com transparência, confiança e excelência em todos os nossos serviços.</p>
                    
                    <div class="mission-points">
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Democratizar o acesso a investimentos imobiliários de qualidade</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Oferecer produtos de alta qualidade e rentabilidade comprovada</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Construir relacionamentos duradouros baseados na confiança</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Contribuir para o desenvolvimento do mercado imobiliário brasileiro</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Garantir a satisfação total dos nossos clientes</span>
                        </div>
                    </div>
                </div>
                
                <div class="mission-visual">
                    <div class="mission-image">
                        <img src="assets/images/sobre/92ade89f-f371-4e34-98a6-85bd35e113d6.jpeg" alt="BR2 Studios - Nossa Missão">
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
                    <h2>Faça Parte da Nossa História</h2>
                    <p>Junte-se aos milhares de clientes que já transformaram seus sonhos em realidade com a Br2Studios. Nossa equipe está pronta para ajudá-lo a encontrar o investimento perfeito.</p>
                    <div class="cta-actions">
                        <a href="contato.php" class="btn-primary">
                            <i class="fas fa-phone"></i>
                            Falar Conosco
                        </a>
                        <a href="imoveis.php" class="btn-secondary">
                            <i class="fas fa-home"></i>
                            Ver Imóveis
                        </a>
                        <a href="corretores.php" class="btn-secondary">
                            <i class="fas fa-users"></i>
                            Nossos Corretores
                        </a>
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
<script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
