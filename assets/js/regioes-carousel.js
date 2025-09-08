/**
 * Carrossel Automático Contínuo - Seção Regiões de Curitiba
 * Funcionalidade: Carrossel com rotação contínua automática a cada 2 segundos
 */

class RegioesCarousel {
    constructor() {
        this.track = document.getElementById('regioes-carousel-track');
        this.indicators = document.querySelectorAll('.carousel-indicators .indicator');
        this.currentSlide = 0;
        this.totalSlides = 4; // Total de regiões
        this.autoSlideInterval = null;
        this.autoSlideDelay = 2000; // 2 segundos para rotação contínua
        this.isAutoSliding = true;
        this.isTransitioning = false;
        
        this.init();
    }
    
    init() {
        if (!this.track || !this.indicators.length) {
            console.warn('Elementos do carrossel não encontrados');
            return;
        }
        
        console.log('Carrossel de regiões inicializado:', {
            track: this.track,
            indicators: this.indicators.length,
            totalSlides: this.totalSlides
        });
        
        this.setupEventListeners();
        this.startAutoSlide();
        this.updateIndicators();
        this.addVisualEffects();
        
        // Pausar animação quando a página não está visível
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoSlide();
            } else {
                this.startAutoSlide();
            }
        });
    }
    
    setupEventListeners() {
        // Apenas pausar no hover (sem controles manuais)
        const carousel = document.querySelector('.regioes-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => {
                this.stopAutoSlide();
            });
            
            carousel.addEventListener('mouseleave', () => {
                this.startAutoSlide();
            });
        }
        
        // Pausar quando a janela perde o foco
        window.addEventListener('blur', () => {
            this.stopAutoSlide();
        });
        
        window.addEventListener('focus', () => {
            this.startAutoSlide();
        });
    }
    
    // Removido setupTouchEvents e handleUserInteraction - carrossel totalmente automático
    
    nextSlide() {
        if (this.isTransitioning) return;
        
        this.isTransitioning = true;
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.updateTrackPosition();
        this.updateIndicators();
        
        // Reset transition flag após animação
        setTimeout(() => {
            this.isTransitioning = false;
        }, 500);
    }
    
    updateTrackPosition() {
        if (!this.track) return;
        
        // Cada card ocupa 25% da largura total
        const translateX = -this.currentSlide * 25;
        this.track.style.transform = `translateX(${translateX}%)`;
        
        // Transição mais rápida para rotação contínua
        this.track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    }
    
    updateIndicators() {
        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentSlide);
        });
    }
    
    addVisualEffects() {
        // Adicionar efeitos visuais aos cards
        const cards = this.track.querySelectorAll('.regiao-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in-up');
        });
    }
    
    startAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
        }
        
        this.autoSlideInterval = setInterval(() => {
            if (!this.isTransitioning) {
                this.nextSlide();
            }
        }, this.autoSlideDelay);
    }
    
    stopAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
            this.autoSlideInterval = null;
        }
    }
    
    // Método público para controlar o auto-slide
    setAutoSlide(enabled) {
        this.isAutoSliding = enabled;
        if (enabled) {
            this.startAutoSlide();
        } else {
            this.stopAutoSlide();
        }
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    // Verificar se estamos na página correta e se o carrossel existe
    if (document.getElementById('regioes-carousel-track')) {
        // Aguardar um pouco para garantir que todos os elementos estejam carregados
        setTimeout(() => {
            window.regioesCarousel = new RegioesCarousel();
            
            // Adicionar classe para animação automática
            const track = document.getElementById('regioes-carousel-track');
            if (track) {
                track.classList.add('auto-sliding');
            }
        }, 500);
    }
});

// Exportar para uso global se necessário
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RegioesCarousel;
}
