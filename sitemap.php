<?php
/**
 * Mapa do Site - BR2Studios
 */

// Incluir header
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 40px 20px;">
    <h1>Mapa do Site</h1>
    <p>Navegue facilmente por todas as páginas do nosso site.</p>
    
    <div class="sitemap-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 30px;">
        
        <!-- Páginas Principais -->
        <div class="sitemap-section">
            <h2>Páginas Principais</h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <a href="index.php" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="sobre.php" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-info-circle"></i> Sobre Nós
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="contato.php" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-envelope"></i> Contato
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Imóveis -->
        <div class="sitemap-section">
            <h2>Imóveis</h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <a href="imoveis.php" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-building"></i> Todos os Imóveis
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="imoveis.php?categoria=studio" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-home"></i> Studios
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="imoveis.php?categoria=apartamento" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-building"></i> Apartamentos
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="imoveis.php?categoria=casa" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-home"></i> Casas
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Regiões -->
        <div class="sitemap-section">
            <h2>Regiões</h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <a href="regioes.php" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-map-marker-alt"></i> Todas as Regiões
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="regioes.php?regiao=curitiba" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-city"></i> Curitiba
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="regioes.php?regiao=regiao-metropolitana" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-map"></i> Região Metropolitana
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Corretores -->
        <div class="sitemap-section">
            <h2>Equipe</h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <a href="corretores.php" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-users"></i> Nossos Corretores
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="corretores.php?especialidade=investimentos" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-chart-line"></i> Especialistas em Investimentos
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Serviços -->
        <div class="sitemap-section">
            <h2>Serviços</h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <a href="contato.php?servico=assessoria" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-handshake"></i> Assessoria Completa
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="contato.php?servico=analise" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-chart-bar"></i> Análise de Mercado
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="contato.php?servico=financiamento" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-credit-card"></i> Financiamento
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="contato.php?servico=consultoria" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-user-tie"></i> Consultoria Especializada
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Informações Legais -->
        <div class="sitemap-section">
            <h2>Informações Legais</h2>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px;">
                    <a href="#politica-privacidade" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-shield-alt"></i> Política de Privacidade
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="#termos-uso" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-file-contract"></i> Termos de Uso
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="#cookies" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-cookie-bite"></i> Política de Cookies
                    </a>
                </li>
                <li style="margin-bottom: 10px;">
                    <a href="#lgpd" style="color: #dc2626; text-decoration: none;">
                        <i class="fas fa-gavel"></i> LGPD
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
        <h3>Contato</h3>
        <p><strong>BR2Studios - CRECI 10.007</strong></p>
        <p><i class="fas fa-phone"></i> (41) 8804-9999</p>
        <p><i class="fas fa-envelope"></i> contato@br2imoveis.com.br</p>
        <p><i class="fas fa-map-marker-alt"></i> Travessa Rafael Francisco Greca, nº. 41-B, Bairro Água Verde, Curitiba - PR</p>
    </div>
</div>

<?php
// Incluir footer
require_once __DIR__ . '/includes/footer.php';
?>
