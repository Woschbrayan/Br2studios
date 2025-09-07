/**
 * Carrossel Automático - Seção Regiões de Curitiba
 * Funcionalidade: Carrossel automático com indicadores e controle manual
 */

class RegioesCarousel {
    constructor() {
        this.track = document.getElementById('regioes-carousel-track');
        this.indicators = document.querySelectorAll('.carousel-indicators .indicator');
        this.currentSlide = 0;
        this.totalSlides = 4; // Total de regiões
        this.autoSlideInterval = null;
        this.autoSlideDelay = 3500; // 3.5 segundos para transição mais dinâmica
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
        
        // Pausar animação quando a página não está visível
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoSlide();
            } else {
                // Retomar auto-slide quando a página fica visível
                this.startAutoSlide();
            }
        });
    }
    
    setupEventListeners() {
        // Indicadores clicáveis
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.goToSlide(index);
                
                // Retomar auto-slide após 5 segundos
                setTimeout(() => {
                    this.startAutoSlide();
                }, 5000);
            });
            
            // Acessibilidade - teclado
            indicator.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.goToSlide(index);
                    
                    // Retomar auto-slide após 5 segundos
                    setTimeout(() => {
                        this.startAutoSlide();
                    }, 5000);
                }
            });
        });
        
        // Pausar no hover apenas temporariamente
        const carousel = document.querySelector('.regioes-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => {
                this.stopAutoSlide();
            });
            
            carousel.addEventListener('mouseleave', () => {
                // Retomar auto-slide após 2 segundos
                setTimeout(() => {
                    this.startAutoSlide();
                }, 2000);
            });
        }
        
        // Touch/swipe support
        this.setupTouchEvents();
        
        // Pausar quando a janela perde o foco
        window.addEventListener('blur', () => {
            this.stopAutoSlide();
        });
        
        window.addEventListener('focus', () => {
            // Retomar auto-slide quando a janela ganha foco
            this.startAutoSlide();
        });
    }
    
    setupTouchEvents() {
        let startX = 0;
        let startY = 0;
        let isDragging = false;
        
        this.track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isDragging = true;
            this.stopAutoSlide();
        });
        
        this.track.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            
            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const diffX = startX - currentX;
            const diffY = startY - currentY;
            
            // Verificar se é um swipe horizontal
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                e.preventDefault();
                
                if (diffX > 0) {
                    // Swipe para esquerda - próximo slide
                    this.nextSlide();
                } else {
                    // Swipe para direita - slide anterior
                    this.prevSlide();
                }
                
                isDragging = false;
                
                // Retomar auto-slide após 5 segundos
                setTimeout(() => {
                    this.startAutoSlide();
                }, 5000);
            }
        });
        
        this.track.addEventListener('touchend', () => {
            isDragging = false;
        });
    }
    
    goToSlide(slideIndex) {
        if (slideIndex < 0 || slideIndex >= this.totalSlides || this.isTransitioning) return;
        
        this.isTransitioning = true;
        this.currentSlide = slideIndex;
        this.updateTrackPosition();
        this.updateIndicators();
        
        // Reset transition flag após animação
        setTimeout(() => {
            this.isTransitioning = false;
        }, 800);
    }
    
    nextSlide() {
        if (this.isTransitioning) return;
        
        this.isTransitioning = true;
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.updateTrackPosition();
        this.updateIndicators();
        
        // Reset transition flag após animação
        setTimeout(() => {
            this.isTransitioning = false;
        }, 800);
    }
    
    prevSlide() {
        if (this.isTransitioning) return;
        
        this.isTransitioning = true;
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.updateTrackPosition();
        this.updateIndicators();
        
        // Reset transition flag após animação
        setTimeout(() => {
            this.isTransitioning = false;
        }, 800);
    }
    
    updateTrackPosition() {
        if (!this.track) return;
        
        // Como cada card ocupa 25% da largura total, movemos 25% por slide
        const translateX = -this.currentSlide * 25;
        this.track.style.transform = `translateX(${translateX}%)`;
    }
    
    updateIndicators() {
        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentSlide);
        });
    }
    
    startAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
        }
        
        // Sempre iniciar o auto-slide, independente de interação
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
        if (enabled && !this.isUserInteracting) {
            this.startAutoSlide();
        } else {
            this.stopAutoSlide();
        }
    }
    
    // Método público para ir para um slide específico
    goToSlidePublic(slideIndex) {
        this.goToSlide(slideIndex);
        this.isUserInteracting = true;
        
        setTimeout(() => {
            this.isUserInteracting = false;
            this.startAutoSlide();
        }, 10000);
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    // Verificar se estamos na página correta e se o carrossel existe
    if (document.getElementById('regioes-carousel-track')) {
        window.regioesCarousel = new RegioesCarousel();
        
        // Adicionar classe para animação automática
        setTimeout(() => {
            const track = document.getElementById('regioes-carousel-track');
            if (track) {
                track.classList.add('auto-sliding');
            }
        }, 1000);
    }
});

// Exportar para uso global se necessário
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RegioesCarousel;
}
