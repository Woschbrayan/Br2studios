<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-grid">
                <!-- Coluna 1: Logo e Descrição -->
                <div class="footer-col">
                    <div class="footer-logo-container">
                        <i class="fas fa-building footer-logo-icon"></i>
                        <span class="footer-logo-text">Br2Studios</span>
                    </div>
                    <p class="footer-description">
                        Transformando sonhos em investimentos lucrativos. Especialistas em studios de alta rentabilidade em todo o Brasil.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="WhatsApp">
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
                        <li><a href="sobre.php">Sobre Nós</a></li>
                        
                    </ul>
                </div>

                <!-- Coluna 3: Serviços -->
                <div class="footer-col">
                    <h4>Nossos Serviços</h4>
                    <ul class="footer-links">
                        <li><a href="#">Investimentos Imobiliários</a></li>
                        <li><a href="#">Studios de Alta Rentabilidade</a></li>
                        <li><a href="#">Assessoria Completa</a></li>
                        <li><a href="#">Análise de Mercado</a></li>
                        <li><a href="#">Financiamento</a></li>
                        <li><a href="#">Consultoria Especializada</a></li>
                    </ul>
                </div>

                <!-- Coluna 4: Contato e Newsletter -->
                <div class="footer-col">
                    <h4>Contato</h4>
                    <div class="contact-info-footer">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>(800) 987 6543</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>contato@br2studios.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>São Paulo, SP - Brasil</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Seg-Sex: 9h às 18h</span>
                        </div>
                    </div>

                    <div class="newsletter">
                        <h5>Newsletter</h5>
                        <p>Receba as melhores oportunidades de investimento</p>
                        <form class="newsletter-form" id="newsletter-form">
                            <input type="email" placeholder="Seu e-mail" required>
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
                <p>&copy; 2024 Br2Studios. Todos os direitos reservados.</p>
                <div class="footer-bottom-links">
                    <a href="#">Política de Privacidade</a>
                    <a href="#">Termos de Uso</a>
                    <a href="#">Cookies</a>
                    <a href="#">Mapa do Site</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="assets/js/main.js"></script>

<!-- Script para Newsletter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            
            // Simulação de envio (substitua por sua lógica real)
            alert('Obrigado! Você foi inscrito em nossa newsletter.');
            this.reset();
        });
    }
});
</script>

</body>
</html>
