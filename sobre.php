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
        'titulo' => 'Transparência',
        'descricao' => 'Trabalhamos com total transparência em todos os processos, desde a seleção dos imóveis até a finalização da negociação.'
    ],
    [
        'icone' => 'fas fa-handshake',
        'titulo' => 'Confiança',
        'descricao' => 'Construímos relacionamentos duradouros baseados na confiança mútua e resultados consistentes.'
    ],
    [
        'icone' => 'fas fa-lightbulb',
        'titulo' => 'Inovação',
        'descricao' => 'Sempre buscamos as melhores soluções e tecnologias para otimizar seus investimentos imobiliários.'
    ],
    [
        'icone' => 'fas fa-heart',
        'titulo' => 'Paixão',
        'descricao' => 'Somos apaixonados por conectar pessoas aos melhores investimentos e realizar sonhos.'
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
        'icone' => 'fas fa-chart-line',
        'titulo' => 'Análise de Mercado',
        'descricao' => 'Equipe especializada em análise de mercado e tendências imobiliárias para maximizar seus retornos.'
    ],
    [
        'icone' => 'fas fa-gavel',
        'titulo' => 'Assessoria Jurídica',
        'descricao' => 'Suporte jurídico completo em todas as etapas da negociação, garantindo segurança e conformidade.'
    ],
    [
        'icone' => 'fas fa-calculator',
        'titulo' => 'Planejamento Financeiro',
        'descricao' => 'Assessoria financeira personalizada para otimizar seus investimentos e planejamento patrimonial.'
    ],
    [
        'icone' => 'fas fa-headset',
        'titulo' => 'Suporte 24/7',
        'descricao' => 'Atendimento personalizado e suporte contínuo para todas as suas necessidades imobiliárias.'
    ]
];
?>

<?php 
$current_page = 'sobre';
$page_title = 'Sobre Nós - Br2Studios';
$page_css = 'assets/css/sobre.css';
include 'includes/header.php'; 
?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Nossa História</h1>
                    <p>Uma jornada de paixão, inovação e compromisso com o sucesso dos nossos clientes no mercado imobiliário brasileiro. Desde 2017, transformando sonhos em investimentos lucrativos.</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['anos_experiencia']; ?>+</span>
                            <span class="stat-label">Anos de Experiência</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['imoveis_vendidos']; ?>+</span>
                            <span class="stat-label">Imóveis Vendidos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['clientes_satisfeitos']; ?>+</span>
                            <span class="stat-label">Clientes Satisfeitos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['corretores_credenciados']; ?>+</span>
                            <span class="stat-label">Corretores Credenciados</span>
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-image">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Como Tudo Começou</h2>
                    <p>Em 2017, um grupo de visionários apaixonados por imóveis decidiu revolucionar o mercado de investimentos imobiliários no Brasil. Identificamos uma oportunidade única no mercado de studios e apartamentos compactos.</p>
                    <p>Fundada com a missão de democratizar o acesso a investimentos imobiliários de qualidade, a Br2Studios começou focando em studios na cidade de São Paulo, onde identificamos o maior potencial de valorização.</p>
                    <p>Hoje, nossa presença se estende por todo o Brasil, levando nossa expertise e metodologia comprovada para todas as regiões do país, sempre focando na qualidade e rentabilidade dos investimentos.</p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Especialização em studios e apartamentos compactos</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Análise criteriosa de mercado e localização</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Suporte completo em todo o processo</span>
                        </div>
                    </div>
                </div>
                <div class="about-visual">
                    <div class="about-image">
                        <i class="fas fa-chart-line"></i>
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

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossa Equipe Executiva</h2>
                <p>Conheça os líderes experientes e dedicados que fazem a Br2Studios acontecer e garantem o sucesso dos nossos clientes</p>
            </div>
            
            <div class="team-grid">
                <?php foreach ($equipe as $membro): ?>
                    <div class="team-card">
                        <div class="member-photo">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="member-info">
                            <h3><?php echo $membro['nome']; ?></h3>
                            <span class="member-role"><?php echo $membro['cargo']; ?></span>
                            <p><?php echo $membro['descricao']; ?></p>
                            <div class="member-social">
                                <a href="<?php echo $membro['linkedin']; ?>" class="social-link" title="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="mailto:<?php echo $membro['email']; ?>" class="social-link" title="E-mail">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="achievements-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossas Conquistas</h2>
                <p>Marcos importantes na nossa jornada de sucesso e crescimento no mercado imobiliário brasileiro</p>
            </div>
            
            <div class="achievements-grid">
                <?php foreach ($conquistas as $conquista): ?>
                    <div class="achievement-card">
                        <div class="achievement-year"><?php echo $conquista['ano']; ?></div>
                        <div class="achievement-content">
                            <h3><?php echo $conquista['titulo']; ?></h3>
                            <p><?php echo $conquista['descricao']; ?></p>
                        </div>
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
                        <i class="fas fa-bullseye"></i>
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
