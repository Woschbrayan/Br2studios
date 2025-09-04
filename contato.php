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
$page_title = 'Contato - Br2Studios Curitiba';
$page_css = 'assets/css/contato.css';
include 'includes/header.php'; 
?>

<!-- Hero Section Desktop -->
<section class="contact-hero-simple desktop-only">
    <div class="hero-bg">
        <img src="assets/images/hero-2.jpg" alt="Contato BR2Studios">
        <div class="hero-overlay"></div>
    </div>
    
    <div class="hero-content">
        <div class="container">
            <div class="hero-text">
                <h1>Fale Conosco</h1>
                <p>Nossa equipe de especialistas em estúdios está pronta para ajudar você a encontrar o investimento perfeito em Curitiba.</p>
                
                <div class="hero-buttons">
                    <a href="https://wa.me/554141410093" class="btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                    <a href="#contato-form" class="btn-form">
                        <i class="fas fa-envelope"></i>
                        Formulário
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hero Mobile -->
<section class="contact-hero-mobile-simple mobile-only">
    <div class="hero-mobile-bg">
        <img src="assets/images/hero-2.jpg" alt="Contato BR2Studios">
        <div class="hero-mobile-overlay"></div>
    </div>
    
    <div class="hero-mobile-content">
        <div class="container">
            <h1>Fale Conosco</h1>
            <p>Especialistas em estúdios prontos para ajudar seu investimento em Curitiba.</p>
            
            <div class="hero-mobile-buttons">
                <a href="https://wa.me/554141410093" class="btn-mobile-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    WhatsApp
                </a>
                <a href="#contato-form" class="btn-mobile-form">
                    <i class="fas fa-envelope"></i>
                    Formulário
                </a>
            </div>
        </div>
    </div>
</section>

<!-- WhatsApp Highlight Mobile -->
<section class="whatsapp-highlight-mobile mobile-only">
    <div class="section-header">
        <h2>Fale Conosco Agora</h2>
        <p>Resposta imediata via WhatsApp</p>
    </div>
    <div class="whatsapp-card-mobile">
        <div class="whatsapp-icon-mobile">
            <i class="fab fa-whatsapp"></i>
        </div>
        <div class="whatsapp-info-mobile">
            <h3>WhatsApp Business</h3>
            <p>Atendimento especializado em investimentos imobiliários</p>
            <div class="whatsapp-features-mobile">
                <span class="feature-tag">24/7 Disponível</span>
                <span class="feature-tag">Resposta Imediata</span>
                <span class="feature-tag">Consultoria Gratuita</span>
            </div>
        </div>
        <a href="https://wa.me/554141410093" class="whatsapp-btn-mobile" target="_blank">
            <i class="fab fa-whatsapp"></i>
            Conversar Agora
        </a>
    </div>
</section>

<!-- Contact Options Desktop -->
<section class="contact-options desktop-only">
    <div class="container">
        <div class="section-header">
            <h2>Como Podemos Ajudar</h2>
            <p>Escolha a forma mais conveniente para falar conosco</p>
        </div>
        
        <div class="contact-options-grid">
            <div class="contact-option-card whatsapp-card">
                <div class="option-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="option-content">
                    <h3>WhatsApp Business</h3>
                    <p>Resposta instantânea para suas dúvidas sobre investimentos imobiliários em Curitiba</p>
                    <div class="option-features">
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Disponível 24/7</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-reply"></i>
                            <span>Resposta Imediata</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Consultoria Gratuita</span>
                        </div>
                    </div>
                    <a href="https://wa.me/554141410093" class="btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        Conversar Agora
                    </a>
                </div>
            </div>
            
            <div class="contact-option-card form-card">
                <div class="option-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="option-content">
                    <h3>Formulário de Contato</h3>
                    <p>Envie sua mensagem detalhada sobre oportunidades de investimento</p>
                    <div class="option-features">
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Resposta em até 2h</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-file-alt"></i>
                            <span>Informações Detalhadas</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-user-tie"></i>
                            <span>Consultoria Personalizada</span>
                        </div>
                    </div>
                    <a href="#contato-form" class="btn-form">
                        <i class="fas fa-envelope"></i>
                        Enviar Mensagem
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="contact-form-section" id="contato-form">
    <div class="container">
        <div class="section-header">
            <h2>Envie sua Mensagem</h2>
            <p>Preencha o formulário abaixo e nossa equipe entrará em contato em até 2 horas</p>
        </div>
        
        <div class="form-content">
            
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
                    <button type="submit" class="btn-primary btn-large">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Mensagem
                    </button>
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-undo"></i>
                        Limpar Formulário
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
<section class="agents">
    <div class="container">
        <div class="section-header">
            <h2>Nossa Equipe de Especialistas</h2>
            <p>Profissionais experientes e certificados para cuidar dos seus investimentos</p>
        </div>
        
        <div class="agents-grid">
            <div class="agent-card">
                <div class="agent-image">
                    <div class="agent-placeholder">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <div class="agent-info">
                    <h3>Ana Silva</h3>
                    <p class="agent-role">Especialista em Studios</p>
                    <p class="agent-description">8 anos de experiência no mercado imobiliário, especializada em investimentos de alto retorno.</p>
                    <div class="agent-contact">
                        <a href="https://wa.me/554141410093" class="btn-whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="agent-card">
                <div class="agent-image">
                    <div class="agent-placeholder">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="agent-info">
                    <h3>Carlos Santos</h3>
                    <p class="agent-role">Consultor de Investimentos</p>
                    <p class="agent-description">12 anos de experiência, especializado em análise de mercado e estratégias de investimento.</p>
                    <div class="agent-contact">
                        <a href="https://wa.me/554141410093" class="btn-whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="agent-card">
                <div class="agent-image">
                    <div class="agent-placeholder">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
                <div class="agent-info">
                    <h3>Mariana Costa</h3>
                    <p class="agent-role">Gerente de Relacionamento</p>
                    <p class="agent-description">6 anos de experiência, focada em atendimento personalizado e satisfação do cliente.</p>
                    <div class="agent-contact">
                        <a href="https://wa.me/554141410093" class="btn-whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <div class="cta-text">
                <h2>Pronto para Investir?</h2>
                <p>Nossa equipe está pronta para encontrar o investimento perfeito para você</p>
                <div class="cta-actions">
                    <a href="imoveis.php" class="btn-primary btn-large">
                        <i class="fas fa-home"></i>
                        Ver Imóveis Disponíveis
                    </a>
                    <a href="corretores.php" class="btn-secondary btn-large">
                        <i class="fas fa-users"></i>
                        Conhecer Nossa Equipe
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

<script>
// FAQ Toggle Function
function toggleFAQ(element) {
    const faqItem = element.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const icon = element.querySelector('i');
    
    faqItem.classList.toggle('active');
    
    if (faqItem.classList.contains('active')) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.style.maxHeight = '0';
        icon.style.transform = 'rotate(0deg)';
    }
}

// Smooth scroll para links internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<?php include 'includes/whatsapp.php'; ?>
<?php include 'includes/footer.php'; ?>
