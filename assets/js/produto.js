// ===== PRODUTO.JS FIXED - Br2Studios =====
// Versão simplificada sem JavaScript problemático

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== GALERIA DE IMAGENS SIMPLES =====
    const mainImage = document.querySelector('.main-image img');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (thumbnails.length > 0) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Remover classe active de todos os thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                
                // Adicionar classe active ao thumbnail clicado
                this.classList.add('active');
                
                // Trocar imagem principal
                if (mainImage) {
                    const newSrc = this.querySelector('img').src;
                    mainImage.src = newSrc;
                }
            });
        });
    }
    
    // ===== FORMULÁRIO DE CONTATO SIMPLES =====
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validação simples
            const name = document.getElementById('contact-name');
            const email = document.getElementById('contact-email');
            const phone = document.getElementById('contact-phone');
            const message = document.getElementById('contact-message');
            
            let isValid = true;
            
            // Limpar erros anteriores
            clearFieldErrors();
            
            // Validar nome
            if (!name.value.trim()) {
                showFieldError(name, 'Nome é obrigatório');
                isValid = false;
            }
            
            // Validar email
            if (!email.value.trim()) {
                showFieldError(email, 'E-mail é obrigatório');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showFieldError(email, 'E-mail inválido');
                isValid = false;
            }
            
            // Validar telefone
            if (!phone.value.trim()) {
                showFieldError(phone, 'Telefone é obrigatório');
                isValid = false;
            } else if (!isValidPhone(phone.value)) {
                showFieldError(phone, 'Telefone inválido');
                isValid = false;
            }
            
            // Validar mensagem
            if (!message.value.trim()) {
                showFieldError(message, 'Mensagem é obrigatória');
                isValid = false;
            }
            
            if (isValid) {
                // Simular envio (substituir por envio real)
                showFormMessage('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
                contactForm.reset();
            }
        });
    }
    
    function showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.color = '#dc2626';
        errorDiv.style.fontSize = '14px';
        errorDiv.style.marginTop = '5px';
        
        field.parentNode.appendChild(errorDiv);
        field.style.borderColor = '#dc2626';
    }
    
    function clearFieldErrors() {
        const errors = document.querySelectorAll('.field-error');
        errors.forEach(error => error.remove());
        
        const fields = document.querySelectorAll('#contact-form input, #contact-form textarea');
        fields.forEach(field => field.style.borderColor = '');
    }
    
    function showFormMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `form-message ${type}`;
        messageDiv.textContent = message;
        messageDiv.style.padding = '15px';
        messageDiv.style.borderRadius = '8px';
        messageDiv.style.marginTop = '20px';
        messageDiv.style.fontWeight = 'bold';
        
        if (type === 'success') {
            messageDiv.style.backgroundColor = '#d1fae5';
            messageDiv.style.color = '#065f46';
            messageDiv.style.border = '1px solid #10b981';
        } else {
            messageDiv.style.backgroundColor = '#fee2e2';
            messageDiv.style.color = '#991b1b';
            messageDiv.style.border = '1px solid #ef4444';
        }
        
        const form = document.getElementById('contact-form');
        if (form) {
            form.appendChild(messageDiv);
            
            // Remover mensagem após 5 segundos
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidPhone(phone) {
        const phoneRegex = /^[\d\s\(\)\-\+]+$/;
        return phoneRegex.test(phone) && phone.replace(/\D/g, '').length >= 10;
    }
    
    // ===== SCROLL SUAVE SIMPLES =====
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ===== ANIMAÇÃO SIMPLES DE ENTRADA =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observar elementos para animação
    const animatedElements = document.querySelectorAll('.section-header, .product-info, .property-details, .investment-benefits');
    animatedElements.forEach(el => observer.observe(el));
    
    // ===== SISTEMA DE LER MAIS PARA DESCRIÇÃO =====
    const productSubtitle = document.querySelector('.product-subtitle');
    if (productSubtitle) {
        const text = productSubtitle.textContent.trim();
        const words = text.split(' ');
        
        // Se a descrição tiver mais de 30 palavras, aplicar truncamento
        if (words.length > 30) {
            const truncatedText = words.slice(0, 30).join(' ') + '...';
            const fullText = text;
            
            // Criar elementos
            const truncatedElement = document.createElement('span');
            truncatedElement.textContent = truncatedText;
            truncatedElement.classList.add('product-subtitle', 'truncated');
            
            const readMoreBtn = document.createElement('button');
            readMoreBtn.textContent = 'Ler mais';
            readMoreBtn.classList.add('read-more-btn');
            
            const fullElement = document.createElement('span');
            fullElement.textContent = fullText;
            fullElement.classList.add('product-subtitle', 'full-text');
            fullElement.style.display = 'none';
            
            // Substituir o elemento original
            const parent = productSubtitle.parentNode;
            parent.insertBefore(truncatedElement, productSubtitle);
            parent.insertBefore(readMoreBtn, productSubtitle);
            parent.insertBefore(fullElement, productSubtitle);
            productSubtitle.remove();
            
            // Event listener para o botão
            readMoreBtn.addEventListener('click', function() {
                if (truncatedElement.style.display !== 'none') {
                    // Mostrar texto completo
                    truncatedElement.style.display = 'none';
                    fullElement.style.display = 'block';
                    readMoreBtn.textContent = 'Ler menos';
                } else {
                    // Mostrar texto truncado
                    truncatedElement.style.display = 'block';
                    fullElement.style.display = 'none';
                    readMoreBtn.textContent = 'Ler mais';
                }
            });
        }
    }
    
    // ===== SISTEMA DE LER MAIS PARA DESCRIÇÃO MOBILE =====
    const mobileDescription = document.querySelector('.mobile-description p');
    if (mobileDescription) {
        const text = mobileDescription.textContent.trim();
        const words = text.split(' ');
        
        // Se a descrição tiver mais de 25 palavras, aplicar truncamento
        if (words.length > 25) {
            const truncatedText = words.slice(0, 25).join(' ') + '...';
            const fullText = text;
            
            // Criar elementos
            const truncatedElement = document.createElement('span');
            truncatedElement.textContent = truncatedText;
            truncatedElement.classList.add('mobile-description-text', 'truncated');
            
            const readMoreBtn = document.createElement('button');
            readMoreBtn.textContent = 'Ler mais';
            readMoreBtn.classList.add('read-more-btn', 'mobile-read-more');
            
            const fullElement = document.createElement('span');
            fullElement.textContent = fullText;
            fullElement.classList.add('mobile-description-text', 'full-text');
            fullElement.style.display = 'none';
            
            // Substituir o elemento original
            const parent = mobileDescription.parentNode;
            parent.insertBefore(truncatedElement, mobileDescription);
            parent.insertBefore(readMoreBtn, mobileDescription);
            parent.insertBefore(fullElement, mobileDescription);
            mobileDescription.remove();
            
            // Event listener para o botão
            readMoreBtn.addEventListener('click', function() {
                if (truncatedElement.style.display !== 'none') {
                    // Mostrar texto completo
                    truncatedElement.style.display = 'none';
                    fullElement.style.display = 'block';
                    readMoreBtn.textContent = 'Ler menos';
                } else {
                    // Mostrar texto truncado
                    truncatedElement.style.display = 'block';
                    fullElement.style.display = 'none';
                    readMoreBtn.textContent = 'Ler mais';
                }
            });
        }
    }
    
    console.log('✅ Produto.js carregado com sucesso - versão simplificada');
});
