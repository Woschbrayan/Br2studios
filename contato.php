<?php
// Incluir configurações de email
require_once __DIR__ . '/config/email_config.php';

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
        try {
            // Preparar dados para envio
            $dados_contato = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'assunto' => $assunto,
                'mensagem' => $mensagem_texto,
                'tipo_contato' => $tipo_contato
            ];
            
            // Criar templates de email
            $template_admin = criarTemplateEmailContato($dados_contato);
            $template_cliente = criarTemplateConfirmacaoCliente($dados_contato);
            
            // Assuntos dos emails
            $assunto_admin = "Novo Contato - BR2Studios: " . ($assunto ? $assunto : $tipo_contato);
            $assunto_cliente = "Contato Recebido - BR2Studios";
            
            // Enviar email para admin (contato@br2imoveis.com.br)
            $email_admin_enviado = enviarEmail(TO_EMAIL, $assunto_admin, $template_admin, $nome, $email);
            
            // Enviar email de confirmação para o cliente
            $email_cliente_enviado = enviarEmail($email, $assunto_cliente, $template_cliente);
            
            if ($email_admin_enviado) {
                $mensagem = "Mensagem enviada com sucesso! Entraremos em contato em até 2 horas.";
                $tipo_mensagem = 'success';
                
                // Salvar no banco de dados (opcional)
                try {
                    require_once __DIR__ . '/config/database.php';
                    require_once __DIR__ . '/classes/Database.php';
                    
                    $db = new Database();
                    $sql = "INSERT INTO contatos (nome, email, telefone, mensagem, imovel_id, data_cadastro) VALUES (?, ?, ?, ?, NULL, NOW())";
                    $db->insert($sql, [$nome, $email, $telefone, $mensagem_texto]);
                } catch (Exception $e) {
                    // Log do erro, mas não interrompe o processo
                    error_log("Erro ao salvar contato no banco: " . $e->getMessage());
                }
            } else {
                $mensagem = "Erro ao enviar mensagem. Tente novamente ou entre em contato pelo WhatsApp.";
                $tipo_mensagem = 'error';
            }
            
        } catch (Exception $e) {
            $mensagem = "Erro interno. Tente novamente ou entre em contato pelo WhatsApp.";
            $tipo_mensagem = 'error';
            error_log("Erro no envio de email: " . $e->getMessage());
        }
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

<style>
/* Centralizar títulos e melhorar espaçamentos */
.section-header {
    text-align: center !important;
    margin-bottom: 60px !important;
    margin-top: 0 !important;
}

.section-header:first-child {
    margin-top: 0 !important;
}

.section-header h2 {
    margin-bottom: 15px !important;
    font-size: 1.8rem !important;
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

/* Espaçamento entre seções */
.contact-options {
    padding: 100px 0 80px 0 !important;
}

.contact-form-section {
    padding: 80px 0 !important;
}

.faq-section {
    padding: 80px 0 100px 0 !important;
}

.cta-section {
    padding: 100px 0 !important;
}

/* Espaçamento adicional entre seções */
.contact-options + .contact-form-section {
    margin-top: 0 !important;
}

.contact-form-section + .faq-section {
    margin-top: 0 !important;
}

.faq-section + .cta-section {
    margin-top: 0 !important;
}

/* Espaçamento específico para cada seção */
.contact-options .section-header {
    margin-bottom: 50px !important;
}

.contact-form-section .section-header {
    margin-bottom: 50px !important;
}

.faq-section .section-header {
    margin-bottom: 50px !important;
}

.cta-section .section-header {
    margin-bottom: 40px !important;
}

.faq-grid {
    max-width: 800px !important;
    margin: 0 auto !important;
}

.faq-item {
    margin-bottom: 20px !important;
}

.faq-question {
    padding: 20px !important;
    margin-bottom: 0 !important;
}

.faq-answer {
    padding: 0 20px 20px 20px !important;
    max-height: 0 !important;
    overflow: hidden !important;
    transition: max-height 0.3s ease !important;
}

.faq-item.active .faq-answer {
    max-height: 200px !important;
}

/* CTA Section igual à home */
.cta-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
    color: white !important;
    text-align: center !important;
    padding: 100px 0 !important;
}

.cta-content {
    max-width: 600px !important;
    margin: 0 auto !important;
}

.cta-content h2 {
    color: white !important;
    font-size: 3rem !important;
    font-weight: 800 !important;
    margin-bottom: 20px !important;
}

.cta-content p {
    color: #e0e0e0 !important;
    font-size: 1.2rem !important;
    margin-bottom: 0 !important;
}

.cta-actions {
    display: flex !important;
    flex-direction: column !important;
    gap: 20px !important;
    align-items: center !important;
    max-width: 300px !important;
    margin: 0 auto !important;
}

.cta-content {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    text-align: center !important;
    gap: 20px !important;
}

.btn-primary {
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    color: white !important;
    padding: 18px 35px !important;
    border-radius: 12px !important;
    text-decoration: none !important;
    font-weight: 700 !important;
    font-size: 18px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
    transition: all 0.3s ease !important;
    border: none !important;
    width: 100% !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5) !important;
    color: white !important;
    text-decoration: none !important;
}

.btn-secondary {
    background: transparent !important;
    color: white !important;
    border: 2px solid #ffffff !important;
    padding: 16px 33px !important;
    border-radius: 12px !important;
    text-decoration: none !important;
    font-weight: 700 !important;
    font-size: 18px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    transition: all 0.3s ease !important;
    width: 100% !important;
}

.btn-secondary:hover {
    background: white !important;
    color: #1a1a1a !important;
    transform: translateY(-3px) !important;
    text-decoration: none !important;
}

/* Responsividade */
@media (max-width: 768px) {
    /* Espaçamento mobile melhorado */
    .contact-options {
        padding: 60px 0 50px 0 !important;
    }
    
    .contact-form-section {
        padding: 50px 0 !important;
    }
    
    .faq-section {
        padding: 50px 0 60px 0 !important;
    }
    
    .cta-section {
        padding: 60px 0 !important;
    }
    
    /* Títulos mobile */
    .section-header {
        margin-bottom: 40px !important;
    }
    
    .section-header h2 {
        font-size: 1.6rem !important;
        margin-bottom: 12px !important;
    }
    
    .section-header p {
        font-size: 0.95rem !important;
        margin-top: 8px !important;
    }
    
    /* CTA mobile */
    .cta-content h2 {
        font-size: 2rem !important;
        margin-bottom: 15px !important;
    }
    
    .cta-content p {
        font-size: 1rem !important;
    }
    
    .cta-actions {
        max-width: 250px !important;
        gap: 15px !important;
    }
    
    /* Espaçamento específico mobile */
    .contact-options .section-header {
        margin-bottom: 35px !important;
    }
    
    .contact-form-section .section-header {
        margin-bottom: 35px !important;
    }
    
    .faq-section .section-header {
        margin-bottom: 35px !important;
    }
    
    .cta-section .section-header {
        margin-bottom: 30px !important;
    }
}

@media (max-width: 480px) {
    /* Mobile pequeno */
    .contact-options {
        padding: 50px 0 40px 0 !important;
    }
    
    .contact-form-section {
        padding: 40px 0 !important;
    }
    
    .faq-section {
        padding: 40px 0 50px 0 !important;
    }
    
    .cta-section {
        padding: 50px 0 !important;
    }
    
    .section-header h2 {
        font-size: 1.4rem !important;
    }
    
    .section-header p {
        font-size: 0.9rem !important;
    }
    
    .cta-content h2 {
        font-size: 1.8rem !important;
    }
}

/* Ajustes específicos para elementos mobile */
@media (max-width: 768px) {
    /* WhatsApp Highlight Mobile - afastar da hero */
    .whatsapp-highlight-mobile {
        padding: 60px 0 50px 0 !important;
        margin-top: 0 !important;
    }
    
    .whatsapp-highlight-mobile .section-header {
        margin-bottom: 40px !important;
        text-align: center !important;
    }
    
    .whatsapp-highlight-mobile .section-header h2 {
        font-size: 1.6rem !important;
        margin-bottom: 12px !important;
    }
    
    .whatsapp-highlight-mobile .section-header p {
        font-size: 0.95rem !important;
        margin-top: 8px !important;
    }
    
    /* WhatsApp Card Mobile - centralizar e espaçar */
    .whatsapp-card-mobile {
        max-width: 320px !important;
        margin: 0 auto !important;
        padding: 25px 20px !important;
        text-align: center !important;
        background: #ffffff !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .whatsapp-icon-mobile {
        margin: 0 auto 20px auto !important;
        width: 60px !important;
        height: 60px !important;
        background: #25d366 !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .whatsapp-icon-mobile i {
        font-size: 1.8rem !important;
        color: #ffffff !important;
    }
    
    .whatsapp-info-mobile {
        margin-bottom: 25px !important;
    }
    
    .whatsapp-info-mobile h3 {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin-bottom: 8px !important;
    }
    
    .whatsapp-info-mobile p {
        font-size: 0.9rem !important;
        color: #666 !important;
        margin: 0 !important;
        line-height: 1.5 !important;
    }
    
    .whatsapp-btn-mobile {
        background: #25d366 !important;
        color: #ffffff !important;
        padding: 15px 25px !important;
        border-radius: 10px !important;
        text-decoration: none !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        transition: all 0.3s ease !important;
        width: 100% !important;
    }
    
    .whatsapp-btn-mobile:hover {
        background: #128c7e !important;
        transform: translateY(-2px) !important;
        text-decoration: none !important;
    }
    
    /* Contact Form Section - melhor espaçamento */
    .contact-form-section {
        padding: 50px 0 !important;
    }
    
    .contact-form-section .section-header {
        margin-bottom: 40px !important;
        text-align: center !important;
    }
    
    .contact-form-section .section-header h2 {
        font-size: 1.6rem !important;
        margin-bottom: 12px !important;
    }
    
    .contact-form-section .section-header p {
        font-size: 0.95rem !important;
        margin-top: 8px !important;
    }
}
.faq-answer p {
    padding: 30px 30px 25px;
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
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
.cta-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
    color: white !important;
    text-align: center !important;
    padding: 0px 0 !important;
}
.cta-actions {
    display: flex !important
;
    flex-direction: column !important;
    gap: 20px !important;
    align-items: center !important;
    max-width: 354px !important;
    margin: 0 auto !important;
}
.cta-section .cta-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 0px 0px;
    position: relative;
    z-index: 2;
}
</style>

<!-- Hero Section Desktop -->
<section class="contact-hero-simple desktop-only">
    <div class="hero-bg">
        <img src="assets/images/contato-hero.jpeg" alt="Contato BR2Studios">
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
        <img src="assets/images/contato-hero.jpeg" alt="Contato BR2Studios">
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


<!-- CTA Section -->
<section class="cta-section">
    <div class="section-container">
        <div class="cta-content">
            <h2>Pronto para começar<br>seu investimento?</h2>
            <p>Entre em contato conosco e descubra as melhores oportunidades do mercado imobiliário</p>
            <div class="cta-actions">
            <a href="contato.php" class="btn-primary btn-large">
                            <i class="fas fa-phone"></i>
                            Falar com Especialista
                        </a>
                <a href="imoveis.php" class="btn-secondary">Ver Imóveis</a>
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
