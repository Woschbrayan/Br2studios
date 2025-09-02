<?php
/**
 * Componente WhatsApp Flutuante
 * Sistema Br2Studios
 * 
 * Uso: <?php include 'includes/whatsapp.php'; ?>
 */

// Configurações do WhatsApp
$whatsapp_number = '554141410093';
$default_message = 'Olá! Gostaria de saber mais sobre os investimentos da Br2Studios em Curitiba';

// Mensagem personalizada por página
$page_messages = [
    'home' => 'Olá! Gostaria de saber mais sobre os investimentos da Br2Studios em Curitiba',
    'imoveis' => 'Olá! Vi os imóveis em Curitiba no site e gostaria de mais informações',
    'contato' => 'Olá! Vim pelo site e gostaria de falar com um especialista em Curitiba',
    'corretores' => 'Olá! Gostaria de falar com um corretor especialista em Curitiba',
    'regioes' => 'Olá! Gostaria de saber sobre investimentos em Curitiba',
    'sobre' => 'Olá! Gostaria de conhecer melhor a Br2Studios em Curitiba',
    'produto' => isset($imovel_data) ? 'Olá! Gostaria de saber mais sobre: ' . $imovel_data['titulo'] : 'Olá! Gostaria de saber mais sobre este imóvel'
];

$current_message = isset($current_page) && isset($page_messages[$current_page]) 
    ? $page_messages[$current_page] 
    : $default_message;

$whatsapp_url = "https://wa.me/{$whatsapp_number}?text=" . urlencode($current_message);
?>

<!-- WhatsApp Button Flutuante -->
<div class="whatsapp-button" id="whatsapp-button">
    <a href="<?php echo $whatsapp_url; ?>" target="_blank" rel="noopener noreferrer" title="Falar no WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="whatsapp-text">WhatsApp</span>
    </a>
</div>

<style>
/* WhatsApp Button Flutuante - Componente Global */
.whatsapp-button {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    transition: all 0.3s ease;
}

.whatsapp-button a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 65px;
    height: 65px;
    background: #25D366;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    font-size: 28px;
    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
    transition: all 0.3s ease;
    animation: pulse-whatsapp 2s infinite;
    position: relative;
    overflow: hidden;
}

.whatsapp-button a:hover {
    background: #128C7E;
    transform: scale(1.15) rotate(8deg);
    box-shadow: 0 12px 35px rgba(37, 211, 102, 0.5);
    animation: none;
}

.whatsapp-text {
    position: absolute;
    right: 75px;
    background: #25D366;
    color: white;
    padding: 10px 15px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
}

.whatsapp-button:hover .whatsapp-text {
    opacity: 1;
    transform: translateX(0);
}

.whatsapp-button:hover .whatsapp-text::after {
    content: '';
    position: absolute;
    top: 50%;
    right: -8px;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 8px solid #25D366;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
}

@keyframes pulse-whatsapp {
    0% {
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
    }
    50% {
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5), 0 0 0 10px rgba(37, 211, 102, 0.1);
    }
    100% {
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
    }
}

/* Responsividade Mobile */
@media (max-width: 768px) {
    .whatsapp-button {
        bottom: 20px;
        right: 20px;
    }
    
    .whatsapp-button a {
        width: 60px;
        height: 60px;
        font-size: 26px;
    }
    
    .whatsapp-text {
        right: 70px;
        font-size: 13px;
        padding: 8px 12px;
    }
}

@media (max-width: 480px) {
    .whatsapp-button {
        bottom: 15px;
        right: 15px;
    }
    
    .whatsapp-button a {
        width: 55px;
        height: 55px;
        font-size: 24px;
    }
    
    .whatsapp-text {
        display: none; /* Ocultar texto em telas muito pequenas */
    }
}

/* Tema Dark */
[data-theme="dark"] .whatsapp-text {
    background: #25D366;
    color: white;
}

[data-theme="dark"] .whatsapp-text::after {
    border-left-color: #25D366;
}
</style>

<script>
// Funcionalidade do WhatsApp Button
document.addEventListener('DOMContentLoaded', function() {
    const whatsappButton = document.getElementById('whatsapp-button');
    
    if (whatsappButton) {
        // Animação de entrada após carregamento da página
        setTimeout(() => {
            whatsappButton.style.opacity = '1';
            whatsappButton.style.transform = 'translateY(0)';
        }, 1000);
        
        // Tracking de cliques (opcional)
        whatsappButton.addEventListener('click', function() {
            console.log('WhatsApp button clicked');
            
            // Aqui você pode adicionar tracking analytics
            // gtag('event', 'whatsapp_click', { event_category: 'contact' });
        });
    }
});
</script>
