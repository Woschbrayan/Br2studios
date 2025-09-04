// Slider automático para o hero
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.slide');
        this.indicators = document.querySelectorAll('.indicator');
        this.heroSlides = document.querySelectorAll('.hero-slide');
        this.currentSlide = 0;
        this.slideInterval = null;
        this.autoPlayDelay = 5000; // 5 segundos
        
        this.init();
    }
    
    init() {
        if (this.slides.length > 0) {
            this.startAutoPlay();
            this.bindEvents();
        }
    }
    
    startAutoPlay() {
        this.slideInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    }
    
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateSlides();
    }
    
    goToSlide(index) {
        this.currentSlide = index;
        this.updateSlides();
        this.resetAutoPlay();
    }
    
    updateSlides() {
        // Atualizar slides de imagem
        this.slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === this.currentSlide);
        });
        
        // Atualizar slides de conteúdo
        this.heroSlides.forEach((slide, index) => {
            slide.classList.toggle('active', index === this.currentSlide);
        });
        
        // Atualizar indicadores
        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentSlide);
        });
    }
    
    resetAutoPlay() {
        clearInterval(this.slideInterval);
        this.startAutoPlay();
    }
    
    bindEvents() {
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.goToSlide(index);
            });
        });
        
        // Pausar autoplay no hover
        const hero = document.querySelector('.hero');
        if (hero) {
            hero.addEventListener('mouseenter', () => {
                clearInterval(this.slideInterval);
            });
            
            hero.addEventListener('mouseleave', () => {
                this.startAutoPlay();
            });
        }
    }
}

// Header scroll effect
class HeaderScroll {
    constructor() {
        this.header = document.querySelector('.header');
        this.lastScrollY = window.scrollY;
        this.init();
    }
    
    init() {
        window.addEventListener('scroll', () => {
            this.handleScroll();
        });
    }
    
    handleScroll() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            this.header.classList.add('scrolled');
        } else {
            this.header.classList.remove('scrolled');
        }
        
        this.lastScrollY = currentScrollY;
    }
}

// Animações de entrada - DESABILITADAS para evitar conflitos
class ScrollAnimations {
    constructor() {
        this.animatedElements = document.querySelectorAll('.feature-card, .property-card, .city-card, .type-card, .agent-card, .testimonial-card');
        this.init();
    }
    
    init() {
        // Desabilitar animações que podem causar espaçamento
        this.animatedElements.forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'none';
            el.style.transition = 'none';
        });
    }
    
    observeElements() {
        // Método vazio para evitar animações
    }
}

// Smooth scroll para links internos
class SmoothScroll {
    constructor() {
        this.init();
    }
    
    init() {
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
    }
}

// Contador animado para estatísticas
class AnimatedCounter {
    constructor() {
        this.counters = document.querySelectorAll('.stat-number');
        this.init();
    }
    
    init() {
        this.observeCounters();
    }
    
    observeCounters() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });
        
        this.counters.forEach(counter => {
            observer.observe(counter);
        });
    }
    
    animateCounter(counter) {
        const originalText = counter.textContent;
        const numbers = originalText.match(/\d+/g);
        
        // Se não há números ou é texto, não animar
        if (!numbers || numbers.length === 0) {
            return;
        }
        
        const target = parseInt(numbers.join(''));
        
        // Se o número é muito pequeno ou inválido, não animar
        if (isNaN(target) || target <= 0 || target > 10000) {
            return;
        }
        
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Manter o texto original se não for um número puro
            if (originalText.match(/^\d+$/)) {
                counter.textContent = Math.floor(current);
            } else {
                // Para textos como "CRECI", "(41)", manter original
                counter.textContent = originalText;
            }
        }, 16);
    }
}

// Sistema de temas dark/light
class ThemeManager {
    constructor() {
        this.themeToggle = document.querySelector('.theme-toggle');
        this.currentTheme = localStorage.getItem('theme') || 'light';
        this.init();
    }
    
    init() {
        this.applyTheme(this.currentTheme);
        this.bindEvents();
    }
    
    bindEvents() {
        if (this.themeToggle) {
            this.themeToggle.addEventListener('click', () => {
                this.toggleTheme();
            });
        }
    }
    
    toggleTheme() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(this.currentTheme);
        localStorage.setItem('theme', this.currentTheme);
    }
    
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        
        // Atualizar o ícone do botão
        if (this.themeToggle) {
            const icon = this.themeToggle.querySelector('i');
            if (icon) {
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                    this.themeToggle.setAttribute('title', 'Mudar para tema claro');
                } else {
                    icon.className = 'fas fa-moon';
                    this.themeToggle.setAttribute('title', 'Mudar para tema escuro');
                }
            }
        }
    }
}

// Menu mobile responsivo
class MobileMenu {
    constructor() {
        this.menuToggle = document.querySelector('.mobile-menu-toggle');
        this.nav = document.querySelector('.nav');
        this.init();
    }
    
    init() {
        if (this.menuToggle) {
            this.menuToggle.addEventListener('click', () => {
                this.toggleMenu();
            });
            
            // Fechar menu ao clicar em um link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.closeMenu();
                });
            });
            
            // Fechar menu ao clicar fora
            document.addEventListener('click', (e) => {
                if (!this.nav.contains(e.target) && !this.menuToggle.contains(e.target)) {
                    this.closeMenu();
                }
            });
        }
    }
    
    toggleMenu() {
        this.nav.classList.toggle('mobile-active');
        this.menuToggle.classList.toggle('active');
        
        // Prevenir scroll quando menu estiver aberto
        if (this.nav.classList.contains('mobile-active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
    
    closeMenu() {
        this.nav.classList.remove('mobile-active');
        this.menuToggle.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Lazy loading para imagens
class LazyLoading {
    constructor() {
        this.images = document.querySelectorAll('img[data-src]');
        this.init();
    }
    
    init() {
        if ('IntersectionObserver' in window) {
            this.observeImages();
        } else {
            this.loadAllImages();
        }
    }
    
    observeImages() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    imageObserver.unobserve(entry.target);
                }
            });
        });
        
        this.images.forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    loadImage(img) {
        img.src = img.dataset.src;
        img.classList.remove('lazy');
    }
    
    loadAllImages() {
        this.images.forEach(img => {
            this.loadImage(img);
        });
    }
}

// Função para animar elementos quando entram na viewport
function animateOnScroll() {
    const elements = document.querySelectorAll('.value-card, .diferencial-card, .team-card, .achievement-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    elements.forEach(element => {
        observer.observe(element);
    });
}

// Carrossel de Depoimentos Desktop
class TestimonialsCarousel {
    constructor() {
        this.carousel = document.querySelector('.testimonials-carousel-desktop');
        this.track = document.querySelector('.testimonials-carousel-desktop .testimonials-track');
        this.slides = document.querySelectorAll('.testimonials-carousel-desktop .testimonial-slide');
        this.dots = document.querySelectorAll('.testimonials-carousel-desktop .dot');
        this.currentSlide = 0;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 5000; // 5 segundos
        
        this.init();
    }
    
    init() {
        if (this.carousel && this.slides.length > 0) {
            console.log('TestimonialsCarousel: Inicializando com', this.slides.length, 'slides');
            this.startAutoPlay();
            this.bindEvents();
            this.updateCarousel();
        } else {
            console.log('TestimonialsCarousel: Elementos não encontrados');
        }
    }
    
    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    }
    
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }
    
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateCarousel();
    }
    
    prevSlide() {
        this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
        this.updateCarousel();
    }
    
    goToSlide(index) {
        this.currentSlide = index;
        this.updateCarousel();
        this.resetAutoPlay();
    }
    
    updateCarousel() {
        // Atualizar posição do track
        const translateX = -this.currentSlide * 100;
        this.track.style.transform = `translateX(${translateX}%)`;
        
        // Atualizar slides ativos
        this.slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === this.currentSlide);
        });
        
        // Atualizar dots
        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });
    }
    
    resetAutoPlay() {
        this.stopAutoPlay();
        this.startAutoPlay();
    }
    
    bindEvents() {
        // Dots de navegação
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
            });
        });
        
        // Pausar autoplay no hover
        if (this.carousel) {
            this.carousel.addEventListener('mouseenter', () => {
                this.stopAutoPlay();
            });
            
            this.carousel.addEventListener('mouseleave', () => {
                this.startAutoPlay();
            });
        }
        
        // Touch/swipe support
        this.addTouchSupport();
    }
    
    addTouchSupport() {
        let startX = 0;
        let endX = 0;
        let isDragging = false;
        
        this.track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            this.stopAutoPlay();
        });
        
        this.track.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            endX = e.touches[0].clientX;
        });
        
        this.track.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            
            const diffX = startX - endX;
            const threshold = 50;
            
            if (Math.abs(diffX) > threshold) {
                if (diffX > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
            
            this.resetAutoPlay();
        });
    }
}

// Carrossel de Parceiros
class PartnersCarousel {
    constructor() {
        this.track = document.querySelector('.partners-track');
        this.slides = document.querySelectorAll('.partner-slide');
        this.currentPosition = 0;
        this.slideWidth = 0;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 50; // 50ms para movimento suave
        
        this.init();
    }
    
    init() {
        if (this.track && this.slides.length > 0) {
            console.log('PartnersCarousel: Inicializando com', this.slides.length, 'slides');
            this.calculateSlideWidth();
            this.startAutoPlay();
        } else {
            console.log('PartnersCarousel: Elementos não encontrados');
        }
    }
    
    calculateSlideWidth() {
        if (this.slides.length > 0) {
            const firstSlide = this.slides[0];
            this.slideWidth = firstSlide.offsetWidth + 40; // 40px é o gap
        }
    }
    
    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => {
            this.moveCarousel();
        }, this.autoPlayDelay);
    }
    
    moveCarousel() {
        this.currentPosition -= 1; // Move 1px por vez para movimento suave
        
        // Reset quando chegar ao final
        if (Math.abs(this.currentPosition) >= this.slideWidth * (this.slides.length / 2)) {
            this.currentPosition = 0;
        }
        
        this.track.style.transform = `translateX(${this.currentPosition}px)`;
    }
    
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }
}

// Carrossel de Cidades Mobile
class CitiesCarousel {
    constructor() {
        this.track = document.querySelector('.cities-track');
        this.slides = document.querySelectorAll('.city-slide');
        this.currentPosition = 0;
        this.slideWidth = 0;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 40; // 40ms para movimento suave
        
        this.init();
    }
    
    init() {
        if (this.track && this.slides.length > 0) {
            console.log('CitiesCarousel: Inicializando com', this.slides.length, 'slides');
            this.calculateSlideWidth();
            this.startAutoPlay();
            this.bindResizeEvent();
        } else {
            console.log('CitiesCarousel: Elementos não encontrados');
        }
    }
    
    bindResizeEvent() {
        window.addEventListener('resize', () => {
            this.calculateSlideWidth();
        });
    }
    
    calculateSlideWidth() {
        if (this.slides.length > 0) {
            const firstSlide = this.slides[0];
            let gap = 20; // Gap padrão
            
            if (window.innerWidth <= 480) {
                gap = 8;
            } else if (window.innerWidth <= 768) {
                gap = 10;
            }
            
            this.slideWidth = firstSlide.offsetWidth + gap;
        }
    }
    
    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => {
            this.moveCarousel();
        }, this.autoPlayDelay);
    }
    
    moveCarousel() {
        this.currentPosition -= 1; // Move 1px por vez para movimento suave
        
        // Reset quando chegar ao final
        if (Math.abs(this.currentPosition) >= this.slideWidth * (this.slides.length / 2)) {
            this.currentPosition = 0;
        }
        
        this.track.style.transform = `translateX(${this.currentPosition}px)`;
    }
    
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
    new HeroSlider();
    new HeaderScroll();
    new ScrollAnimations();
    new SmoothScroll();
    new AnimatedCounter();
    new MobileMenu();
    new LazyLoading();
    new TestimonialsCarousel();
    new PartnersCarousel();
    new CitiesCarousel();
    
    // Adicionar classe para animações CSS
    document.body.classList.add('loaded');
});

// Preloader
window.addEventListener('load', () => {
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        preloader.style.opacity = '0';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 300);
    }
});

// Parallax effect para o hero - DESABILITADO para evitar espaçamento
// window.addEventListener('scroll', () => {
//     const scrolled = window.pageYOffset;
//     const hero = document.querySelector('.hero');
//     if (hero) {
//         // Aplicar parallax apenas nas imagens de fundo, não na seção inteira
//         const heroSlides = hero.querySelectorAll('.slide img');
//         heroSlides.forEach(slide => {
//             const rate = scrolled * -0.3; // Reduzido para efeito mais sutil
//             slide.style.transform = `translateY(${rate}px) scale(1.05)`;
//         });
//     }
// });

// Efeito de hover nos cards - DESABILITADO para evitar conflitos
// document.addEventListener('DOMContentLoaded', () => {
//     const cards = document.querySelectorAll('.feature-card, .property-card, .city-card, .type-card, .agent-card, .testimonial-card');
//     
//     cards.forEach(card => {
//         card.addEventListener('mouseenter', function() {
//             this.style.transform = 'translateY(-10px) scale(1.02)';
//         });
//         
//         card.addEventListener('mouseleave', function() {
//             this.style.transform = 'translateY(0) scale(1)';
//         });
//     });
// });

// WhatsApp button com animação
const whatsappButton = document.querySelector('.whatsapp-button');
if (whatsappButton) {
    whatsappButton.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1) rotate(5deg)';
    });
    
    whatsappButton.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1) rotate(0deg)';
    });
}
