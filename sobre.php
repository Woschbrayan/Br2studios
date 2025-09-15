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
        'imoveis_vendidos' => $imoveis_vendidos > 0 ? $imoveis_vendidos : 500, // Valor padrão se não há vendidos
        'clientes_satisfeitos' => $imoveis_vendidos > 0 ? intval($imoveis_vendidos * 0.8) : 95, // 80% dos vendidos
        'anos_experiencia' => 8, // Valor fixo da empresa
        'corretores_credenciados' => $corretores_credenciados > 0 ? $corretores_credenciados : 5
    ];
    
} catch (Exception $e) {
    error_log("Erro ao buscar estatísticas: " . $e->getMessage());
    
    // Valores padrão em caso de erro
    $estatisticas = [
        'imoveis_vendidos' => 500,
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
        'titulo' => 'Foco exclusivo em Studios',
        'descricao' => 'Não somos uma imobiliária genérica. Nosso trabalho é totalmente dedicado ao mercado de Studios, permitindo análises profundas e decisões mais assertivas.'
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

.hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('assets/images/sobre/hero-sobre.jpeg') !important;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-attachment: fixed !important;
}

.hero-section .hero-content {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 60px !important;
    align-items: center !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 40px !important;
    width: 100% !important;
    top: 80px !important;
}

.hero-visual {
    width: 100% !important;
    max-width: 500px !important;
    margin: 0 auto !important;
}

.hero-text {
    width: 100% !important;
    text-align: left !important;
}

.hero-section .hero-text h1 {
    color: #ffffff !important;
}

.hero-section .hero-text p {
    color: #ffffff !important;
}

.hero-section .hero-subtitle {
    color: #ffffff !important;
}

.hero-section .hero-description {
    color: #ffffff !important;
}

.btn-primary {
    background: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #ffffff !important;
}

.btn-primary:hover {
    background: transparent !important;
    color: #ffffff !important;
    border-color: #ffffff !important;
}

.btn-secondary {
    background: transparent !important;
    color: #ffffff !important;
    border-color: #ffffff !important;
}

.btn-secondary:hover {
    background: #ffffff !important;
    color: #000000 !important;
}

/* Mobile Styles */
.sobre-header-mobile {
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('assets/images/sobre/hero-sobre.jpeg') !important;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-attachment: scroll !important;
    padding: 40px 0 !important;
    margin-bottom: 0 !important;
}

.sobre-header-mobile h1 {
    color: #ffffff !important;
}

.sobre-header-mobile .mobile-subtitle {
    color: #ffffff !important;
}

.sobre-header-mobile .mobile-description {
    color: #ffffff !important;
}

.sobre-header-mobile .quick-stat {
    background: rgba(255, 255, 255, 0.9) !important;
    color: #000000 !important;
    border-radius: 10px !important;
    padding: 10px 15px !important;
    margin: 5px !important;
    display: inline-block !important;
}

@media (max-width: 768px) {
    .hero-section {
        background-attachment: scroll !important;
        padding: 80px 0 80px !important;
        min-height: auto !important;
    }
    
    .hero-text h1 {
        font-size: 2.5rem !important;
        text-align: center !important;
    }
    
    .hero-text p {
        font-size: 1rem !important;
        text-align: center !important;
    }
    
    .hero-subtitle {
        font-size: 1.2rem !important;
        text-align: center !important;
    }
    
    .hero-description {
        font-size: 0.95rem !important;
        text-align: center !important;
    }
    
    .hero-actions {
        justify-content: center !important;
        flex-direction: column !important;
        align-items: center !important;
        gap: 1rem !important;
    }
    
    .hero-actions a {
        width: 100% !important;
        max-width: 250px !important;
        text-align: center !important;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: 60px 0 60px !important;
    }
    
    .hero-text h1 {
        font-size: 2rem !important;
    }
    
    .hero-image {
        max-width: 100% !important;
        height: 200px !important;
    }
}
.about-section {
    background: #ffffff !important;
    padding: 80px 0 !important;
    margin: 0 !important;
}

.about-content {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 60px !important;
    align-items: center !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 20px !important;
}

.about-text {
    width: 100% !important;
    text-align: left !important;
}

.about-visual {
    width: 100% !important;
    max-width: 500px !important;
    margin: 0 auto !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}

.section-header {
    text-align: center !important;
    margin-bottom: 50px !important;
}

.section-header h2 {
    margin-bottom: 15px !important;
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    color: #1a1a1a !important;
}

.section-header p {
    margin-top: 10px !important;
    margin-bottom: 0 !important;
    font-size: 1.1rem !important;
    color: #666 !important;
    max-width: 600px !important;
    margin-left: auto !important;
    margin-right: auto !important;
}

/* CTA Section - Botão vermelho */
.cta-section .btn-primary {
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    color: white !important;
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
    border: none !important;
}

.cta-section .btn-primary:hover {
    background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5) !important;
    color: white !important;
    text-decoration: none !important;
}

.sobre-header-mobile .header-mobile-content {
    text-align: center;
    max-width: 600px;
    margin: 40px auto;
    padding: 0 20px;
}

/* Esconder todas as imagens no mobile */
@media (max-width: 768px) {
    .hero-visual,
    .about-visual,
    .mission-visual,
    .about-image,
    .mission-image,
    img {
        display: none !important;
    }
    
    /* Ajustar layouts que dependem de imagens */
    .hero-content {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
    }
    
    .about-content {
        grid-template-columns: 1fr !important;
        gap: 30px !important;
        text-align: center !important;
    }
    
    .about-text {
        text-align: center !important;
    }
    
    .mission-content {
        flex-direction: column !important;
        text-align: center !important;
    }
}
</style>

    <!-- Hero Section Desktop -->
    <section class="hero-section desktop-only">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                   
                    <h1>Sobre a Br2Studios</h1>
                    <p class="hero-subtitle">Especialistas em Studios, especialistas em investimentos.</p>
                    <p class="hero-description">Localizada no coração de Curitiba, a BR2 Studios nasceu com um propósito claro: transformar o investimento em imóveis em uma experiência segura, rentável e estratégica. Nossa atuação é 100% focada em Studios, um dos segmentos mais promissores do mercado imobiliário.</p>
                    
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
                
            </div>
        </div>
    </section>

    <!-- Header Mobile Simples -->
    <section class="sobre-header-mobile mobile-only">
        <div class="container">
            <div class="header-mobile-content">
                <h1>Sobre a Br2Studios</h1>
                <p class="mobile-subtitle">Especialistas em Studios, especialistas em investimentos.</p>
                <p class="mobile-description">Localizada no coração de Curitiba, transformamos investimentos em imóveis em uma experiência segura, rentável e estratégica.</p>
                <div class="quick-stats-mobile">
                    <span class="quick-stat">
                        <i class="fas fa-home"></i>
                        <?php echo $estatisticas['imoveis_vendidos']; ?>+ Imoveis
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
            <p>Foco exclusivo em Studios</p>
        </div>
        <div class="about-content-mobile">
            <div class="about-card-mobile">
                <div class="about-icon-mobile">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Foco em Studios</h3>
                <p>100% dedicados ao mercado de Studios, com análises profundas e decisões assertivas</p>
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
                    <p>Aqui, cada detalhe é pensado para investidores que buscam oportunidades reais de valorização e retorno. Nossa equipe é formada por profissionais altamente treinados para analisar cenários, identificar tendências e indicar as melhores opções de Studios na região central de Curitiba.</p>
                    <p>Com suporte completo em todas as etapas do processo, oferecemos confiança, transparência e resultados sólidos — para que você invista com segurança e colha o melhor que o mercado imobiliário pode oferecer.</p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Foco exclusivo em Studios</span>
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
                        <img src="assets/images/sobre/listagem-1.jpeg" alt="BR2 Studios - Nossa Especialização">
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
