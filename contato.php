<?php
// Processar formulário de contato
$mensagem = '';
$tipo_mensagem = '';

if ($_POST) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $mensagem_texto = $_POST['mensagem'] ?? '';
    $tipo_contato = $_POST['tipo_contato'] ?? '';
    
    if ($nome && $email && $mensagem_texto) {
        // Aqui você pode adicionar lógica para salvar no banco ou enviar e-mail
        $mensagem = "Mensagem enviada com sucesso! Entraremos em contato em breve.";
        $tipo_mensagem = 'success';
    } else {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
        $tipo_mensagem = 'error';
    }
}
?>

<?php 
$current_page = 'contato';
$page_title = 'Contato - Br2Studios';
$page_css = 'assets/css/contato.css';
include 'includes/header.php'; 
?>

<!-- Page Banner -->
<section class="page-banner">
    <div class="container">
        <div class="banner-content">
            <div class="banner-text">
                <h1>Entre em Contato</h1>
                <p>Nossa equipe está pronta para transformar seus sonhos em investimentos lucrativos</p>
                <div class="banner-stats">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Suporte</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">< 2h</span>
                        <span class="stat-label">Resposta</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfação</span>
                    </div>
                </div>
            </div>
            <div class="banner-visual">
                <div class="banner-image">
                    <img src="assets/images/hero-1.jpg" alt="Equipe Br2Studios">
                </div>
                <div class="banner-badge">
                    <i class="fas fa-headset"></i>
                    <span>Atendimento Premium</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Methods -->
<section class="contact-methods">
    <div class="container">
        <div class="section-header">
            <h2>Formas de Contato</h2>
            <p>Escolha a forma mais conveniente para você</p>
        </div>
        
        <div class="methods-grid">
            <div class="method-card">
                <div class="method-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3>WhatsApp</h3>
                <p>Resposta instantânea para suas dúvidas</p>
                <div class="method-details">
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span>24/7</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-reply"></i>
                        <span>Resposta Imediata</span>
                    </div>
                </div>
                <a href="https://wa.me/5511999999999" class="btn-method" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    Conversar Agora
                </a>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Telefone</h3>
                <p>Atendimento personalizado por especialistas</p>
                <div class="method-details">
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span>Seg-Sex: 9h às 18h</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-user-tie"></i>
                        <span>Especialistas</span>
                    </div>
                </div>
                <a href="tel:+5511999999999" class="btn-method">
                    <i class="fas fa-phone"></i>
                    Ligar Agora
                </a>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>E-mail</h3>
                <p>Envie sua mensagem detalhada</p>
                <div class="method-details">
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span>Resposta em até 2h</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Documentos Anexos</span>
                    </div>
                </div>
                <a href="mailto:contato@br2studios.com.br" class="btn-method">
                    <i class="fas fa-envelope"></i>
                    Enviar E-mail
                </a>
            </div>
            
            <div class="method-card">
                <div class="method-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Escritório</h3>
                <p>Visite nossa sede em São Paulo</p>
                <div class="method-details">
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span>Seg-Sex: 9h às 18h</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-coffee"></i>
                        <span>Agendamento</span>
                    </div>
                </div>
                <a href="#" class="btn-method" onclick="abrirMapa()">
                    <i class="fas fa-map-marker-alt"></i>
                    Ver Localização
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="contact-form-section">
    <div class="container">
        <div class="form-content">
            <div class="form-header">
                <h2>Envie sua Mensagem</h2>
                <p>Preencha o formulário abaixo e nossa equipe entrará em contato em até 2 horas</p>
            </div>
            
            <?php if ($mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?>">
                    <i class="fas fa-<?php echo $tipo_mensagem === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            
            <form class="contact-form" method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone">
                    </div>
                    <div class="form-group">
                        <label for="tipo_contato">Tipo de Contato</label>
                        <select id="tipo_contato" name="tipo_contato">
                            <option value="">Selecione uma opção</option>
                            <option value="investimento">Quero Investir</option>
                            <option value="consulta">Consulta sobre Imóvel</option>
                            <option value="parceria">Proposta de Parceria</option>
                            <option value="outro">Outro Assunto</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="assunto">Assunto</label>
                    <input type="text" id="assunto" name="assunto" placeholder="Ex: Investimento em Studio em São Paulo">
                </div>
                
                <div class="form-group">
                    <label for="mensagem">Mensagem *</label>
                    <textarea id="mensagem" name="mensagem" rows="5" required placeholder="Descreva sua necessidade ou dúvida..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Mensagem
                    </button>
                    <button type="reset" class="btn-reset">
                        <i class="fas fa-undo"></i>
                        Limpar
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <h2>Perguntas Frequentes</h2>
            <p>Respostas para as principais dúvidas dos nossos clientes</p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Como funciona o processo de investimento?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Nosso processo é simples: análise do perfil, apresentação de oportunidades, escolha do imóvel, documentação e fechamento. Tudo com acompanhamento completo da nossa equipe.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Quais são as formas de pagamento aceitas?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Aceitamos todas as formas de pagamento: à vista, financiamento bancário, consórcio e até mesmo troca por outros imóveis. Analisamos cada caso individualmente.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Vocês atendem em todo o Brasil?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Sim! Temos corretores credenciados em todos os estados brasileiros, oferecendo oportunidades de investimento em todo o território nacional.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Qual o prazo para resposta?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Nos comprometemos a responder em até 2 horas durante o horário comercial. Para contatos fora do horário, respondemos no próximo dia útil.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <div class="section-header">
            <h2>Nossa Equipe de Especialistas</h2>
            <p>Profissionais experientes e certificados para cuidar dos seus investimentos</p>
        </div>
        
        <div class="team-grid">
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Ana Silva</h3>
                <p class="team-role">Especialista em Studios</p>
                <p class="team-description">8 anos de experiência no mercado imobiliário, especializada em investimentos de alto retorno.</p>
                <div class="team-contact">
                    <a href="https://wa.me/5511999999999" class="btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
            
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Carlos Santos</h3>
                <p class="team-role">Consultor de Investimentos</p>
                <p class="team-description">12 anos de experiência, especializado em análise de mercado e estratégias de investimento.</p>
                <div class="team-contact">
                    <a href="https://wa.me/5511999999999" class="btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
            
            <div class="team-card">
                <div class="team-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Mariana Costa</h3>
                <p class="team-role">Gerente de Relacionamento</p>
                <p class="team-description">6 anos de experiência, focada em atendimento personalizado e satisfação do cliente.</p>
                <div class="team-contact">
                    <a href="https://wa.me/5511999999999" class="btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
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
                <h2>Pronto para Investir?</h2>
                <p>Nossa equipe está pronta para encontrar o investimento perfeito para você</p>
                <div class="cta-actions">
                    <a href="imoveis.php" class="btn-primary btn-large">
                        <i class="fas fa-home"></i>
                        Ver Imóveis
                    </a>
                    <a href="corretores.php" class="btn-secondary btn-large">
                        <i class="fas fa-users"></i>
                        Conhecer Corretores
                    </a>
                </div>
            </div>
            <div class="cta-visual">
                <div class="cta-illustration">
                    <div class="floating-element">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="floating-element">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="floating-element">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
