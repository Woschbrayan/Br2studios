<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Corretor.php';

// Instanciar classe de corretores
$corretor = new Corretor();

// Buscar corretores do banco de dados
try {
    $corretores_result = $corretor->listarTodos();
    
    // A classe retorna um array direto
    if (is_array($corretores_result)) {
        $corretores_db = $corretores_result;
    } else {
        $corretores_db = [];
    }
    
    // Organizar corretores por estado - FILTRAR APENAS REGISTROS VÁLIDOS
    $corretores = [];
    
    if (is_array($corretores_db)) {
        foreach ($corretores_db as $corretor_item) {
            // Verificar se o corretor tem dados mínimos válidos
            if (empty($corretor_item['nome']) || empty($corretor_item['email'])) {
                continue; // Pular corretores sem nome ou email
            }
            
            // Verificar se tem cidade e estado
            if (empty($corretor_item['cidade']) || empty($corretor_item['estado'])) {
                continue; // Pular corretores sem localização
            }
            
            $estado = $corretor_item['estado'];
            
            if (!isset($corretores[$estado])) {
                $corretores[$estado] = [
                    'estado' => getEstadoNome($estado),
                    'corretores' => []
                ];
            }
            
            // Adicionar dados extras para compatibilidade com o frontend
            $corretores[$estado]['corretores'][] = [
                'nome' => $corretor_item['nome'],
                'cidade' => $corretor_item['cidade'],
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
$page_title = 'Corretores Credenciados - Br2Studios';
$page_css = 'assets/css/corretores.css';
include 'includes/header.php'; 
?>

    <!-- Page Banner -->
    <section class="page-banner">
        <div class="container">
            <div class="banner-content">
                <div class="banner-text">
                    <h1>Corretores Credenciados</h1>
                    <p>Nossa equipe de especialistas está pronta para encontrar o imóvel perfeito para você em todo o Brasil</p>
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

    <!-- Search and Filters -->
    <section class="search-section">
        <div class="container">
            <div class="search-content">
                <div class="search-header">
                    <h2>Encontre seu Corretor</h2>
                    <p>Filtre por estado ou busque por nome para encontrar o especialista ideal</p>
                </div>
                
                <div class="search-filters">
                    <div class="filter-group">
                        <label for="estado-filter">Estado:</label>
                        <select id="estado-filter" onchange="filtrarCorretores()">
                            <option value="">Todos os Estados</option>
                            <?php foreach ($corretores as $sigla => $estado): ?>
                                <option value="<?php echo $sigla; ?>" <?php echo $estado_filtro === $sigla ? 'selected' : ''; ?>>
                                    <?php echo $estado['estado']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="corretor-search">Buscar Corretor:</label>
                        <div class="search-input">
                            <input type="text" id="corretor-search" placeholder="Digite o nome do corretor..." onkeyup="filtrarCorretores()">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label for="foto-filter">Com Foto:</label>
                        <select id="foto-filter" onchange="filtrarCorretores()">
                            <option value="">Todos</option>
                            <option value="com-foto">Apenas com Foto</option>
                            <option value="sem-foto">Apenas sem Foto</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button class="btn-clear-filters" onclick="limparFiltros()">
                            <i class="fas fa-times"></i>
                            Limpar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brokers Grid -->
    <section class="brokers-section">
        <div class="container">
            <div class="brokers-grid" id="brokers-grid">
                <?php foreach ($corretores as $sigla => $estado): ?>
                    <?php foreach ($estado['corretores'] as $corretor): ?>
                        <div class="broker-card" data-estado="<?php echo $sigla; ?>" data-nome="<?php echo strtolower($corretor['nome']); ?>">
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
