<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Corretor.php';

// Instanciar classe de corretores
$corretor = new Corretor();

// Filtros de busca - APENAS CRECI
$filters = [];

// Filtro por CRECI
if (isset($_GET['creci']) && !empty($_GET['creci'])) {
    $filters['creci'] = $_GET['creci'];
}

// Buscar corretores do banco de dados
try {
    $corretores_result = $corretor->listarTodos($filters);
    
    // A classe retorna um array direto
    if (is_array($corretores_result)) {
        $corretores_db = $corretores_result;
    } else {
        $corretores_db = [];
    }
    
    // Organizar corretores por estado - ACEITAR TODOS OS REGISTROS VÁLIDOS
    $corretores = [];
    
    if (is_array($corretores_db)) {
        foreach ($corretores_db as $corretor_item) {
            // Verificar se o corretor tem dados mínimos válidos
            if (empty($corretor_item['nome']) || empty($corretor_item['email'])) {
                continue; // Pular corretores sem nome ou email
            }
            
            // Usar estado padrão se não tiver
            $estado = !empty($corretor_item['estado']) ? $corretor_item['estado'] : 'PR';
            $cidade = !empty($corretor_item['cidade']) ? $corretor_item['cidade'] : 'Curitiba';
            
            if (!isset($corretores[$estado])) {
                $corretores[$estado] = [
                    'estado' => getEstadoNome($estado),
                    'corretores' => []
                ];
            }
            
            // Adicionar dados extras para compatibilidade com o frontend
            $corretores[$estado]['corretores'][] = [
                'nome' => $corretor_item['nome'],
                'cidade' => $cidade,
                'creci' => $corretor_item['creci'] ?? '',
                'whatsapp' => $corretor_item['telefone'] ?? '',
                'email' => $corretor_item['email'],
                'especialidade' => 'Especialista em Imóveis',
                'experiencia' => '5+ anos',
                'imagem' => $corretor_item['foto'] ?: 'assets/images/corretores/default-avatar.jpg',
                'destaque' => true,
                'avaliacao' => 4.8,
                'vendas' => rand(50, 200)
            ];
        }
    }
    
    // Se não há corretores válidos no banco, manter array vazio
    // O sistema irá exibir uma mensagem apropriada na interface
    
} catch (Exception $e) {
    // Em caso de erro, usar array vazio
    error_log("Erro ao buscar corretores: " . $e->getMessage());
    $corretores = [];
}

// Função para obter nome do estado
function getEstadoNome($sigla) {
    $estados = [
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
    ];
    
    return $estados[$sigla] ?? $sigla;
}

$estado_filtro = $_GET['estado'] ?? '';
$corretor_filtro = $_GET['corretor'] ?? '';
?>

<?php 
$current_page = 'corretores';
$page_title = 'Corretores em Curitiba - Br2Studios';
$page_css = 'assets/css/corretores.css';
include 'includes/header.php'; 
?>
<style>

.section-header h2 {
    margin-top: 10%;
    font-size: 1.5rem !important;
    font-weight: 700 !important;
    margin-bottom: var(--title-margin-bottom) !important;
    line-height: 1.2 !important;
    color: #333333 !important;
}
</style>
    <!-- Page Banner Desktop -->
    <section class="page-banner desktop-only">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1>Corretores Credenciados</h1>
                    <p>Nossa equipe de especialistas está pronta para encontrar o imóvel perfeito para você em Curitiba e região</p>
                    <div class="banner-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($corretores); ?></span>
                            <span class="stat-label">Estados</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php 
                                $total_corretores = 0;
                                foreach ($corretores as $estado) {
                                    $total_corretores += count($estado['corretores']);
                                }
                                echo $total_corretores;
                            ?></span>
                            <span class="stat-label">Corretores</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">98%</span>
                            <span class="stat-label">Satisfação</span>
                        </div>
                    </div>
                </div>
                <div class="banner-visual">
                    <div class="banner-team">
                        <div class="team-avatars">
                            <div class="avatar avatar-1">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="avatar avatar-2">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="avatar avatar-3">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                    <div class="banner-badge">
                        <i class="fas fa-certificate"></i>
                        <span>CRECI Credenciado</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Header Mobile Simples -->
    <section class="corretores-header-mobile mobile-only">
        <div class="container">
            <div class="header-mobile-content">
                <h1>Nossa Equipe</h1>
                <p><?php 
                    $total_corretores = 0;
                    foreach ($corretores as $estado) {
                        $total_corretores += count($estado['corretores']);
                    }
                    echo $total_corretores;
                ?> especialistas certificados</p>
                <div class="quick-stats-mobile">
                    <span class="quick-stat">
                        <i class="fas fa-users"></i>
                        <?php echo $total_corretores; ?> Corretores
                    </span>
                    <span class="quick-stat">
                        <i class="fas fa-award"></i>
                        Certificados
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filters Desktop -->
    <section class="search-section">
        <div class="container">
            <div class="search-content">
                <div class="search-header">
                    <h2>Encontre seu Corretor</h2>
                    <p>Digite o código CRECI para encontrar o especialista ideal</p>
                </div>
                
                <div class="search-filters">
                    <div class="filter-group">
                        <label for="creci-search">Buscar por CRECI:</label>
                        <div class="search-input">
                            <input type="text" id="creci-search" placeholder="Digite o código CRECI..." onkeyup="filtrarCorretores()" value="<?php echo htmlspecialchars($_GET['creci'] ?? ''); ?>">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <button class="btn-clear-filters" onclick="limparFiltros()">
                            <i class="fas fa-times"></i>
                            Limpar Filtro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Corretores Mobile - Cards Simples -->
    <section class="corretores-mobile mobile-only">
        <div class="section-header">
            <h2>Nossos Especialistas</h2>
            <p>Entre em contato direto</p>
        </div>
        <div class="corretores-grid-mobile">
            <?php foreach ($corretores as $sigla => $estado): ?>
                <?php foreach ($estado['corretores'] as $corretor): ?>
                    <div class="corretor-card-mobile">
                        <div class="corretor-avatar-mobile">
                            <?php if (isset($corretor['imagem']) && !empty($corretor['imagem'])): ?>
                                <img src="<?php echo htmlspecialchars($corretor['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($corretor['nome']); ?>"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-fallback-mobile" style="display: none;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            <?php else: ?>
                                <div class="avatar-fallback-mobile">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="corretor-info-mobile">
                            <h3><?php echo htmlspecialchars($corretor['nome']); ?></h3>
                            <div class="corretor-location-mobile">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($corretor['cidade'] . ', ' . $sigla); ?>
                            </div>
                            <div class="corretor-creci-mobile">
                                <i class="fas fa-certificate"></i>
                                CRECI: <?php echo htmlspecialchars($corretor['creci'] ?: 'Em processo'); ?>
                            </div>
                            <div class="corretor-stats-mobile">
                                <span class="corretor-stat-mobile">
                                    <i class="fas fa-star"></i>
                                    <?php echo $corretor['avaliacao']; ?>
                                </span>
                                <span class="corretor-stat-mobile">
                                    <i class="fas fa-handshake"></i>
                                    <?php echo $corretor['vendas']; ?>+ vendas
                                </span>
                            </div>
                        </div>
                        <div class="corretor-contact-mobile">
                            <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $corretor['whatsapp']); ?>?text=Olá <?php echo urlencode($corretor['nome']); ?>! Vi seu perfil no site da Br2Studios e gostaria de conversar sobre imóveis." 
                               class="btn-whatsapp-corretor" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Brokers Grid Desktop -->
    <section class="brokers-section desktop-only">
        <div class="container">
            <!-- Contador de Resultados -->
            <div class="results-counter">
                <i class="fas fa-users"></i>
                <span id="results-count"><?php 
                    $total_corretores = 0;
                    foreach ($corretores as $estado) {
                        $total_corretores += count($estado['corretores']);
                    }
                    echo $total_corretores;
                ?> corretores encontrados</span>
                <?php if (!empty($filters)): ?>
                    <span class="filter-info">(com filtros aplicados)</span>
                <?php endif; ?>
            </div>
            
            <div class="brokers-grid" id="brokers-grid">
                <?php foreach ($corretores as $sigla => $estado): ?>
                    <?php foreach ($estado['corretores'] as $corretor): ?>
                        <div class="broker-card" data-estado="<?php echo $sigla; ?>" data-nome="<?php echo strtolower($corretor['nome']); ?>" data-creci="<?php echo strtolower($corretor['creci']); ?>">
                            <div class="broker-header">
                                <div class="broker-avatar <?php echo (isset($corretor['imagem']) && !empty($corretor['imagem']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $corretor['imagem'])) ? 'has-photo' : ''; ?>">
                                    <?php if (isset($corretor['imagem']) && !empty($corretor['imagem']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $corretor['imagem'])): ?>
                                        <img src="<?php echo htmlspecialchars($corretor['imagem']); ?>" 
                                             alt="<?php echo htmlspecialchars($corretor['nome']); ?>"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="avatar-fallback" style="display: none;">
                                            <span class="avatar-initials"><?php echo strtoupper(substr($corretor['nome'], 0, 1)); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="avatar-fallback">
                                            <span class="avatar-initials"><?php echo strtoupper(substr($corretor['nome'], 0, 1)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="tooltip">
                                        <?php echo htmlspecialchars($corretor['nome']); ?> - <?php echo htmlspecialchars($corretor['especialidade']); ?>
                                    </div>
                                </div>
                                <div class="broker-badges">
                                    <?php if ($corretor['destaque']): ?>
                                        <span class="badge-destaque">
                                            <i class="fas fa-star"></i>
                                            Destaque
                                        </span>
                                    <?php endif; ?>
                                    <span class="badge-creci">
                                        <i class="fas fa-certificate"></i>
                                        CRECI
                                    </span>
                                </div>
                            </div>
                            
                            <div class="broker-content">
                                <h3 class="broker-name"><?php echo $corretor['nome']; ?></h3>
                                <p class="broker-location">
                            <i class="fas fa-map-marker-alt"></i>
                                    <?php echo $corretor['cidade']; ?>, <?php echo $sigla; ?>
                                </p>
                                
                                <div class="broker-details">
                                    <div class="detail-item">
                                        <i class="fas fa-id-card"></i>
                                        <span class="label">CRECI:</span>
                                        <span class="value"><?php echo $corretor['creci']; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-briefcase"></i>
                                        <span class="label">Especialidade:</span>
                                        <span class="value"><?php echo $corretor['especialidade']; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span class="label">Experiência:</span>
                                        <span class="value"><?php echo $corretor['experiencia']; ?></span>
                                    </div>
                                    <?php if (isset($corretor['avaliacao'])): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-star"></i>
                                        <span class="label">Avaliação:</span>
                                        <span class="value"><?php echo $corretor['avaliacao']; ?>/5.0</span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (isset($corretor['vendas'])): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-home"></i>
                                        <span class="label">Vendas:</span>
                                        <span class="value"><?php echo $corretor['vendas']; ?> imóveis</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                        <div class="broker-contact">
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $corretor['whatsapp']); ?>" 
                                       class="btn-whatsapp" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                                WhatsApp
                            </a>
                                    <a href="mailto:<?php echo $corretor['email']; ?>" class="btn-email">
                                        <i class="fas fa-envelope"></i>
                                        E-mail
                                    </a>
                        </div>
                    </div>
                </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="no-results" id="no-results" style="display: none;">
                <div class="no-results-content">
                    <i class="fas fa-search"></i>
                    <h3>Nenhum corretor encontrado</h3>
                    <p>Tente ajustar os filtros ou entre em contato conosco</p>
                    <a href="contato.php" class="btn-primary">Falar Conosco</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Highlights -->
    <section class="team-highlights">
        <div class="container">
            <div class="section-header">
                <h2>Nossa Equipe de Especialistas</h2>
                <p>Profissionais experientes e certificados para cuidar dos seus investimentos</p>
            </div>
            
            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Formação Completa</h3>
                    <p>Todos os nossos corretores possuem formação superior e certificações CRECI</p>
                </div>
                
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-chart-line"></i>
                        </div>
                    <h3>Experiência Comprovada</h3>
                    <p>Média de 8+ anos de experiência no mercado imobiliário brasileiro</p>
                        </div>
                
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Atendimento Personalizado</h3>
                    <p>Cada cliente recebe atenção exclusiva e acompanhamento completo</p>
                    </div>
                    
                <div class="highlight-card">
                    <div class="highlight-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Segurança e Transparência</h3>
                    <p>Processos transparentes e seguros para todos os negócios</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Precisa de Ajuda Especializada?</h2>
                    <p>Nossa equipe está pronta para encontrar o investimento perfeito para você</p>
                    <div class="cta-actions">
                        <a href="contato.php" class="btn-primary btn-large">
                            <i class="fas fa-phone"></i>
                            Falar com Especialista
                        </a>
                        <a href="imoveis.php" class="btn-secondary btn-large">
                            <i class="fas fa-home"></i>
                            Ver Imóveis
                        </a>
                    </div>
                </div>
                <div class="cta-visual">
                    <div class="cta-team">
                        <div class="team-member">
                            <i class="fas fa-user-tie"></i>
            </div>
                        <div class="team-member">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="team-member">
                            <i class="fas fa-user-tie"></i>
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
<script src="assets/js/mobile-creative.js?v=<?php echo $version; ?>"></script>
<script>
// Funcionalidade dos filtros de corretores - APENAS CRECI
function filtrarCorretores() {
    const creci = document.getElementById('creci-search').value;
    
    // Construir URL com parâmetros
    const params = new URLSearchParams();
    if (creci) params.append('creci', creci);
    
    // Redirecionar com filtros
    const url = params.toString() ? 'corretores.php?' + params.toString() : 'corretores.php';
    window.location.href = url;
}

function limparFiltros() {
    window.location.href = 'corretores.php';
}

// Filtros em tempo real para desktop - APENAS CRECI
document.addEventListener('DOMContentLoaded', function() {
    const creciSearch = document.getElementById('creci-search');
    
    // Auto-submit quando filtro mudar (com delay para busca)
    let searchTimeout;
    if (creciSearch) {
        creciSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filtrarCorretores();
            }, 500); // 500ms de delay para evitar muitas requisições
        });
    }
    
    // Mostrar/ocultar resultados baseado no filtro CRECI
    const brokerCards = document.querySelectorAll('.broker-card');
    const noResults = document.getElementById('no-results');
    const resultsCount = document.getElementById('results-count');
    
    function updateResults() {
        const creci = creciSearch ? creciSearch.value.toLowerCase() : '';
        
        let visibleCount = 0;
        
        brokerCards.forEach(card => {
            const cardCreci = card.getAttribute('data-creci') || '';
            
            let show = true;
            
            // Filtro por CRECI
            if (creci && !cardCreci.includes(creci)) {
                show = false;
            }
            
            if (show) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Atualizar contador de resultados
        if (resultsCount) {
            resultsCount.textContent = `${visibleCount} corretores encontrados`;
        }
        
        // Mostrar/ocultar mensagem de "nenhum resultado"
        if (noResults) {
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }
    }
    
    // Executar filtros na carga inicial
    updateResults();
});
</script>
