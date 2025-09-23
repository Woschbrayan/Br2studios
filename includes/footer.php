<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-grid">
                <!-- Coluna 1: Logo e Descrição -->
                <div class="footer-col">
                    <div class="footer-logo-container">
                        <i class="fas fa-building footer-logo-icon"></i>
                        <span class="footer-logo-text">Br2Imóveis</span>
                    </div>
                    <p class="footer-description">
                        Especialistas em imóveis de qualidade em Curitiba e região metropolitana. CRECI 10.007 - Sua confiança, nosso compromisso.
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/br2imoveis" target="_blank" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/br2imoveis" target="_blank" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/br2imoveis" target="_blank" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://wa.me/554141410093" target="_blank" class="social-link" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Coluna 2: Links Rápidos -->
                <div class="footer-col">
                    <h4>Links Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="imoveis.php">Imóveis</a></li>
                        <li><a href="regioes.php">Regiões</a></li>
                        <li><a href="corretores.php">Corretores</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <li><a href="sobre.php">Sobre Nós</a></li>
                    </ul>
                </div>
                
                <!-- Coluna 3: Contato e Newsletter -->
                <div class="footer-col">
                    <h4>Contato</h4>
                    <div class="contact-info-footer">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>(41) 4141-0093</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>contato@br2imoveis.com.br</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Travessa Rafael Francisco Greca, nº. 41-B, Bairro Água Verde, Curitiba - PR</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-certificate"></i>
                            <span>CRECI 10.007</span>
                        </div>
                    </div>

                    <div class="newsletter">
                        <h5>Newsletter</h5>
                        <p>Receba as melhores oportunidades de investimento</p>
                        <form class="newsletter-form" id="newsletter-form">
                            <input type="email" name="email" placeholder="Seu e-mail" required>
                            <button type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <p>&copy; 2025 - Br2Imóveis - CRECI 10.007. Todos os direitos reservados.</p>
                <div class="footer-bottom-links">
                    <a href="sitemap.php">Mapa do Site</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<?php 
// Versionamento centralizado para forçar atualização do cache
require_once __DIR__ . '/../config/version.php';
$version = getAssetsVersion();
?>
<script src="assets/js/main.js?v=<?php echo $version; ?>"></script>

<!-- Script para Newsletter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Mostrar loading
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            // Enviar para o servidor
            fetch('newsletter_simples.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                
                return response.text(); // Primeiro como texto para debug
            })
            .then(text => {
                console.log('Response text:', text);
                
                try {
                    const data = JSON.parse(text);
                    
                    if (data.success) {
                        alert('✅ ' + data.message);
                        this.reset();
                    } else {
                        alert('❌ ' + data.message);
                    }
                } catch (parseError) {
                    console.error('Erro ao fazer parse do JSON:', parseError);
                    console.error('Texto recebido:', text);
                    alert('❌ Resposta inválida do servidor. Verifique o console.');
                }
            })
            .catch(error => {
                console.error('Erro completo:', error);
                alert('❌ Erro ao processar inscrição: ' + error.message);
            })
            .finally(() => {
                // Restaurar botão
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    }
});
</script>
