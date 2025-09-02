<?php
// Dados da empresa
$estatisticas = [
    'imoveis_vendidos' => 1250,
    'clientes_satisfeitos' => 890,
    'anos_experiencia' => 8,
    'corretores_credenciados' => 45
];

$valores = [
    [
        'icone' => 'fas fa-shield-alt',
        'titulo' => 'Transparência',
        'descricao' => 'Trabalhamos com total transparência em todos os processos.'
    ],
    [
        'icone' => 'fas fa-handshake',
        'titulo' => 'Confiança',
        'descricao' => 'Construímos relacionamentos duradouros baseados na confiança.'
    ],
    [
        'icone' => 'fas fa-lightbulb',
        'titulo' => 'Inovação',
        'descricao' => 'Sempre buscamos as melhores soluções para otimizar seus investimentos.'
    ],
    [
        'icone' => 'fas fa-heart',
        'titulo' => 'Paixão',
        'descricao' => 'Somos apaixonados por conectar pessoas aos melhores investimentos.'
    ]
];

$equipe = [
    [
        'nome' => 'Bruno Silva',
        'cargo' => 'CEO & Fundador',
        'foto' => 'assets/images/equipe/bruno-silva.jpg',
        'descricao' => 'Especialista em investimentos imobiliários com mais de 15 anos de experiência.',
        'linkedin' => '#',
        'email' => 'bruno@br2studios.com.br'
    ],
    [
        'nome' => 'Ana Costa',
        'cargo' => 'Diretora Comercial',
        'foto' => 'assets/images/equipe/ana-costa.jpg',
        'descricao' => 'Responsável por estratégias de vendas e relacionamento com clientes premium.',
        'linkedin' => '#',
        'email' => 'ana@br2studios.com.br'
    ],
    [
        'nome' => 'Carlos Mendes',
        'cargo' => 'Diretor de Investimentos',
        'foto' => 'assets/images/equipe/carlos-mendes.jpg',
        'descricao' => 'Especialista em análise de mercado e seleção de oportunidades.',
        'linkedin' => '#',
        'email' => 'carlos@br2studios.com.br'
    ]
];

$conquistas = [
    [
        'ano' => '2025',
        'titulo' => 'Expansão Nacional',
        'descricao' => 'Abertura de escritórios em 5 novos estados brasileiros'
    ],
    [
        'ano' => '2024',
        'titulo' => 'Prêmio de Excelência',
        'descricao' => 'Reconhecimento como melhor imobiliária de studios do Brasil'
    ],
    [
        'ano' => '2023',
        'titulo' => 'Milésimo Cliente',
        'descricao' => 'Atingimos a marca de 1000 clientes satisfeitos'
    ],
    [
        'ano' => '2022',
        'titulo' => 'Tecnologia Avançada',
        'descricao' => 'Implementação de sistema de gestão inteligente'
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
                    <p>Uma jornada de paixão, inovação e compromisso com o sucesso dos nossos clientes no mercado imobiliário brasileiro.</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['anos_experiencia']; ?>+</span>
                            <span class="stat-label">Anos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['imoveis_vendidos']; ?>+</span>
                            <span class="stat-label">Imóveis</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $estatisticas['clientes_satisfeitos']; ?>+</span>
                            <span class="stat-label">Clientes</span>
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
                    <p>Em 2017, um grupo de visionários apaixonados por imóveis decidiu revolucionar o mercado de investimentos imobiliários no Brasil.</p>
                    <p>Fundada com a missão de democratizar o acesso a investimentos imobiliários de qualidade, a Br2Studios começou focando em studios na cidade de São Paulo.</p>
                    <p>Hoje, nossa presença se estende por todo o Brasil, levando nossa expertise e metodologia para todas as regiões do país.</p>
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
                <p>Os princípios que guiam cada decisão e ação da nossa empresa</p>
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

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2>Nossa Equipe Executiva</h2>
                <p>Conheça os líderes que fazem a Br2Studios acontecer</p>
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
                <p>Marcos importantes na nossa jornada de sucesso</p>
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
                    <p>Transformar o sonho da casa própria em realidade, oferecendo as melhores oportunidades de investimento imobiliário com transparência, confiança e excelência.</p>
                    
                    <div class="mission-points">
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Democratizar o acesso a investimentos imobiliários</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Oferecer produtos de alta qualidade e rentabilidade</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Construir relacionamentos duradouros com nossos clientes</span>
                        </div>
                        <div class="mission-point">
                            <i class="fas fa-check-circle"></i>
                            <span>Contribuir para o desenvolvimento do mercado imobiliário brasileiro</span>
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
                    <p>Junte-se aos milhares de clientes que já transformaram seus sonhos em realidade com a Br2Studios</p>
                    <div class="cta-actions">
                        <a href="contato.php" class="btn-primary">
                            <i class="fas fa-phone"></i>
                            Falar Conosco
                        </a>
                        <a href="imoveis.php" class="btn-secondary">
                            <i class="fas fa-home"></i>
                            Ver Imóveis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
