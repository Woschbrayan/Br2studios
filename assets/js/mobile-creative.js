/* ===== JAVASCRIPT PARA FUNCIONALIDADES MOBILE CRIATIVAS ===== */

// Stack Cards para Propriedades Mobile
class PropertyStack {
    constructor() {
        this.container = document.querySelector('.properties-stack');
        this.cards = document.querySelectorAll('.property-stack-card');
        this.dots = document.querySelectorAll('.stack-dot');
        this.currentIndex = 0;
        this.init();
    }
    
    init() {
        if (!this.container) return;
        
        this.bindEvents();
        this.autoPlay();
    }
    
    bindEvents() {
        // Touch events para swipe
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        this.container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
            const diffX = currentX - startX;
            
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    this.previousCard();
                } else {
                    this.nextCard();
                }
                isDragging = false;
            }
        });
        
        this.container.addEventListener('touchend', () => {
            isDragging = false;
        });
        
        // Click nos dots
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToCard(index);
            });
        });
        
        // Click nos cards
        this.cards.forEach((card, index) => {
            card.addEventListener('click', () => {
                if (index !== this.currentIndex) {
                    this.goToCard(index);
                }
            });
        });
    }
    
    nextCard() {
        this.currentIndex = (this.currentIndex + 1) % this.cards.length;
        this.updateCards();
    }
    
    previousCard() {
        this.currentIndex = this.currentIndex === 0 ? this.cards.length - 1 : this.currentIndex - 1;
        this.updateCards();
    }
    
    goToCard(index) {
        this.currentIndex = index;
        this.updateCards();
    }
    
    updateCards() {
        this.cards.forEach((card, index) => {
            card.classList.remove('active');
            if (index === this.currentIndex) {
                card.classList.add('active');
                card.style.transform = 'translateY(0) scale(1)';
                card.style.zIndex = '3';
                card.style.opacity = '1';
            } else if (index === this.currentIndex + 1 || (this.currentIndex === this.cards.length - 1 && index === 0)) {
                card.style.transform = 'translateY(20px) scale(0.95)';
                card.style.zIndex = '2';
                card.style.opacity = '0.8';
            } else {
                card.style.transform = 'translateY(40px) scale(0.9)';
                card.style.zIndex = '1';
                card.style.opacity = '0.6';
            }
        });
        
        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    }
    
    autoPlay() {
        setInterval(() => {
            this.nextCard();
        }, 5000);
    }
}

// Cities Swiper Mobile
class CitiesSwiper {
    constructor() {
        this.container = document.querySelector('.cities-swiper');
        this.track = document.querySelector('.cities-track');
        this.slides = document.querySelectorAll('.city-slide');
        this.dots = document.querySelectorAll('.swiper-dot');
        this.prevBtn = document.querySelector('.swiper-btn.prev');
        this.nextBtn = document.querySelector('.swiper-btn.next');
        this.currentIndex = 0;
        this.init();
    }
    
    init() {
        if (!this.container) return;
        
        this.bindEvents();
        this.autoPlay();
    }
    
    bindEvents() {
        // Touch events
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        this.container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
            const diffX = currentX - startX;
            
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    this.previousSlide();
                } else {
                    this.nextSlide();
                }
                isDragging = false;
            }
        });
        
        this.container.addEventListener('touchend', () => {
            isDragging = false;
        });
        
        // Button events
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.previousSlide());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
        }
        
        // Dot events
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
            });
        });
    }
    
    nextSlide() {
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.updateSlides();
    }
    
    previousSlide() {
        this.currentIndex = this.currentIndex === 0 ? this.slides.length - 1 : this.currentIndex - 1;
        this.updateSlides();
    }
    
    goToSlide(index) {
        this.currentIndex = index;
        this.updateSlides();
    }
    
    updateSlides() {
        const translateX = -this.currentIndex * 100;
        this.track.style.transform = `translateX(${translateX}%)`;
        
        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    }
    
    autoPlay() {
        setInterval(() => {
            this.nextSlide();
        }, 4000);
    }
}

// Agent Flip Cards Mobile
class AgentFlipCards {
    constructor() {
        this.cards = document.querySelectorAll('.agent-flip-card');
        this.init();
    }
    
    init() {
        if (this.cards.length === 0) return;
        
        this.bindEvents();
    }
    
    bindEvents() {
        this.cards.forEach(card => {
            card.addEventListener('click', () => {
                card.classList.toggle('flipped');
            });
            
            // Auto flip back after 5 seconds
            card.addEventListener('click', () => {
                if (card.classList.contains('flipped')) {
                    setTimeout(() => {
                        card.classList.remove('flipped');
                    }, 5000);
                }
            });
        });
    }
}

// Stories/Testimonials Mobile
class StoriesSlider {
    constructor() {
        this.container = document.querySelector('.stories-container');
        this.slides = document.querySelectorAll('.story-slide');
        this.progressBars = document.querySelectorAll('.progress-bar');
        this.currentIndex = 0;
        this.isPlaying = true;
        this.duration = 5000;
        this.init();
    }
    
    init() {
        if (!this.container) return;
        
        this.bindEvents();
        this.startStory();
    }
    
    bindEvents() {
        // Touch events para navegação
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            this.pauseStory();
        });
        
        this.container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });
        
        this.container.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            
            const diffX = currentX - startX;
            
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    this.previousStory();
                } else {
                    this.nextStory();
                }
            } else {
                this.resumeStory();
            }
            
            isDragging = false;
        });
        
        // Click para pausar/retomar
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.story-slide')) {
                if (this.isPlaying) {
                    this.pauseStory();
                } else {
                    this.resumeStory();
                }
            }
        });
    }
    
    nextStory() {
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.updateStories();
        this.startStory();
    }
    
    previousStory() {
        this.currentIndex = this.currentIndex === 0 ? this.slides.length - 1 : this.currentIndex - 1;
        this.updateStories();
        this.startStory();
    }
    
    updateStories() {
        this.slides.forEach((slide, index) => {
            slide.classList.remove('active', 'prev');
            
            if (index === this.currentIndex) {
                slide.classList.add('active');
            } else if (index < this.currentIndex) {
                slide.classList.add('prev');
            }
        });
        
        this.progressBars.forEach((bar, index) => {
            bar.classList.remove('active');
            const fill = bar.querySelector('.progress-fill');
            
            if (index < this.currentIndex) {
                fill.style.width = '100%';
            } else if (index === this.currentIndex) {
                bar.classList.add('active');
                fill.style.width = '0%';
            } else {
                fill.style.width = '0%';
            }
        });
    }
    
    startStory() {
        this.isPlaying = true;
        this.storyTimeout = setTimeout(() => {
            this.nextStory();
        }, this.duration);
    }
    
    pauseStory() {
        this.isPlaying = false;
        clearTimeout(this.storyTimeout);
    }
    
    resumeStory() {
        this.startStory();
    }
}

// Filtros Mobile para Página de Imóveis
class MobileFilters {
    constructor() {
        this.toggleBtn = document.getElementById('filters-toggle');
        this.panel = document.getElementById('filters-panel');
        this.countBadge = document.querySelector('.filters-count');
        this.isOpen = false;
        this.init();
    }
    
    init() {
        if (!this.toggleBtn) return;
        
        this.bindEvents();
        this.updateFiltersCount();
    }
    
    bindEvents() {
        this.toggleBtn.addEventListener('click', () => {
            this.togglePanel();
        });
        
        // Fechar ao clicar fora
        document.addEventListener('click', (e) => {
            if (!this.toggleBtn.contains(e.target) && !this.panel.contains(e.target)) {
                this.closePanel();
            }
        });
        
        // Atualizar contador ao mudar filtros
        const selects = this.panel.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', () => {
                this.updateFiltersCount();
            });
        });
    }
    
    togglePanel() {
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            this.panel.style.display = 'block';
            this.toggleBtn.style.borderRadius = '12px 12px 0 0';
        } else {
            this.panel.style.display = 'none';
            this.toggleBtn.style.borderRadius = '12px';
        }
    }
    
    closePanel() {
        if (this.isOpen) {
            this.isOpen = false;
            this.panel.style.display = 'none';
            this.toggleBtn.style.borderRadius = '12px';
        }
    }
    
    updateFiltersCount() {
        const selects = this.panel.querySelectorAll('select');
        let count = 0;
        
        selects.forEach(select => {
            if (select.value) count++;
        });
        
        if (count > 0) {
            this.countBadge.textContent = count;
            this.countBadge.style.display = 'inline-block';
        } else {
            this.countBadge.style.display = 'none';
        }
    }
}

// Testimonials Slider (Desktop e Mobile)
class TestimonialsSlider {
    constructor(isMobile = false) {
        this.isMobile = isMobile;
        this.selector = isMobile ? 'mobile' : 'desktop';
        this.slides = document.querySelectorAll(`.testimonial-slide-${this.selector}`);
        this.dots = document.querySelectorAll(`.testimonial-dot-${this.selector}`);
        this.prevBtn = document.querySelector(`.testimonial-prev-${this.selector}`);
        this.nextBtn = document.querySelector(`.testimonial-next-${this.selector}`);
        this.currentIndex = 0;
        this.autoPlayInterval = null;
        this.init();
    }
    
    init() {
        if (this.slides.length === 0) return;
        
        this.bindEvents();
        this.startAutoPlay();
    }
    
    bindEvents() {
        // Botões de navegação
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.previousSlide();
                this.resetAutoPlay();
            });
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.nextSlide();
                this.resetAutoPlay();
            });
        }
        
        // Dots de navegação
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
                this.resetAutoPlay();
            });
        });
        
        // Touch events para mobile
        if (this.isMobile) {
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            
            const container = document.querySelector('.testimonials-track-mobile');
            if (container) {
                container.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isDragging = true;
                    this.pauseAutoPlay();
                });
                
                container.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    currentX = e.touches[0].clientX;
                });
                
                container.addEventListener('touchend', () => {
                    if (!isDragging) return;
                    
                    const diffX = currentX - startX;
                    
                    if (Math.abs(diffX) > 50) {
                        if (diffX > 0) {
                            this.previousSlide();
                        } else {
                            this.nextSlide();
                        }
                    }
                    
                    isDragging = false;
                    this.resetAutoPlay();
                });
            }
        }
    }
    
    nextSlide() {
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.updateSlides();
    }
    
    previousSlide() {
        this.currentIndex = this.currentIndex === 0 ? this.slides.length - 1 : this.currentIndex - 1;
        this.updateSlides();
    }
    
    goToSlide(index) {
        this.currentIndex = index;
        this.updateSlides();
    }
    
    updateSlides() {
        this.slides.forEach((slide, index) => {
            slide.classList.remove('active');
            if (index === this.currentIndex) {
                slide.classList.add('active');
            }
        });
        
        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentIndex);
        });
    }
    
    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, 5000); // 5 segundos
    }
    
    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }
    
    resetAutoPlay() {
        this.pauseAutoPlay();
        this.startAutoPlay();
    }
}

// Inicialização quando DOM carregado
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar sliders de depoimentos
    new TestimonialsSlider(false); // Desktop
    new TestimonialsSlider(true);  // Mobile
    
    // Verificar se está em mobile para outras funcionalidades
    if (window.innerWidth <= 768) {
        new PropertyStack();
        new CitiesSwiper();
        new AgentFlipCards();
        new StoriesSlider();
        new MobileFilters();
        
        console.log('Mobile creative features initialized');
    }
});

// Reinicializar ao redimensionar tela
window.addEventListener('resize', () => {
    if (window.innerWidth <= 768) {
        // Reinicializar funcionalidades mobile se necessário
        const existingStack = document.querySelector('.properties-stack');
        if (existingStack && !existingStack.hasAttribute('data-initialized')) {
            new PropertyStack();
            existingStack.setAttribute('data-initialized', 'true');
        }
    }
});

// Utility function para smooth scroll
function smoothScrollTo(element) {
    element.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Haptic feedback para dispositivos que suportam
function triggerHapticFeedback() {
    if ('vibrate' in navigator) {
        navigator.vibrate(50);
    }
}

// Toast notifications para mobile
function showMobileToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="toast-icon fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
            <span class="toast-text">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
