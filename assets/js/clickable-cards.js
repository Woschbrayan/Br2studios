/**
 * Sistema de Cards Clicáveis - Br2Studios
 * Torna os cards de imóveis totalmente clicáveis mantendo os botões funcionais
 */

document.addEventListener('DOMContentLoaded', function() {
    // Função para tornar cards clicáveis
    function makeCardsClickable() {
        // Selecionar todos os cards de imóveis (desktop e mobile)
        const cards = document.querySelectorAll('.property-card, .property-card-mobile, .valorizacao-card, .valorizacao-card-mobile');
        
        cards.forEach(card => {
            // Encontrar o link "Ver Detalhes" dentro do card
            const link = card.querySelector('.btn-view-property, .property-action-mobile');
            
            if (link) {
                const url = link.getAttribute('href');
                
                // Adicionar atributo data-url ao card
                card.setAttribute('data-url', url);
                
                // Adicionar classe para estilização
                card.classList.add('clickable-card');
                
                // Adicionar evento de clique ao card
                card.addEventListener('click', function(e) {
                    // Verificar se o clique foi no botão ou em um link
                    const isButton = e.target.closest('.btn-view-property, .property-action-mobile');
                    
                    // Se clicou no botão, deixar o comportamento padrão
                    if (isButton) {
                        return;
                    }
                    
                    // Caso contrário, navegar para a URL do card
                    window.location.href = url;
                });
                
                // Adicionar cursor pointer ao card (exceto no botão)
                card.style.cursor = 'pointer';
                
                // Prevenir que o botão herde o cursor pointer
                if (link) {
                    link.style.cursor = 'pointer';
                }
            }
        });
    }
    
    // Executar ao carregar a página
    makeCardsClickable();
    
    // Observar mudanças no DOM para cards adicionados dinamicamente
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                makeCardsClickable();
            }
        });
    });
    
    // Observar o body para novos cards
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});


