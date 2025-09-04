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
                        <li><a href="contato.php">Contato</a></li>
                        <li><a href="sobre.php">Sobre Nós</a></li>
                    </ul>
                </div>
                
                <!-- Coluna 3: Área Administrativa -->
                <div class="footer-col">
                    <h4>Área Administrativa</h4>
                    <ul class="footer-links">
                        <li><a href="admin/" class="admin-link">
                            <i class="fas fa-user-shield"></i>
                            Painel Administrativo
                        </a></li>
                        <li><a href="admin/dashboard.php" class="admin-link">
                            <i class="fas fa-chart-dashboard"></i>
                            Dashboard
                        </a></li>
                        <li><a href="admin/imoveis.php" class="admin-link">
                            <i class="fas fa-home"></i>
                            Gerenciar Imóveis
                        </a></li>
                        <li><a href="admin/corretores.php" class="admin-link">
                            <i class="fas fa-users"></i>
                            Gerenciar Corretores
                        </a></li>
                    </ul>
                </div>

                <!-- Coluna 4: Serviços -->
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
                <p>&copy; 2024 - Br2Imóveis - CRECI 10.007. Todos os direitos reservados.</p>
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
<?php 
// Versionamento para forçar atualização do cache
$version = '1.0.0';
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
            
            // Simulação de envio (substitua por sua lógica real)
            alert('Obrigado! Você foi inscrito em nossa newsletter.');
            this.reset();
        });
    }
});
</script>

</body>
</html>
