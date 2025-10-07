<?php
$current_page = 'error';
$page_title = 'Página não encontrada - Br2Studios';
$page_css = 'assets/css/style.css';
include 'includes/header.php';
?>

<div class="error-page">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-search"></i>
            </div>
            
            <h1>404</h1>
            <h2>Página não encontrada</h2>
            <p>A página que você está procurando não existe ou foi movida.</p>
            
            <div class="error-actions">
                <a href="index.php" class="btn-primary">
                    <i class="fas fa-home"></i>
                    Voltar ao Início
                </a>
                <a href="imoveis.php" class="btn-secondary">
                    <i class="fas fa-building"></i>
                    Ver Imóveis
                </a>
            </div>
            
            <div class="error-help">
                <h3>Precisa de ajuda?</h3>
                <p>Entre em contato conosco:</p>
                <div class="contact-info">
                    <a href="tel:554188049999" class="contact-item">
                        <i class="fas fa-phone"></i>
                        (41) 8804-9999
                    </a>
                    <a href="https://wa.me/554188049999" target="_blank" class="contact-item">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    padding: 40px 20px;
}

.error-content {
    text-align: center;
    max-width: 600px;
}

.error-icon {
    font-size: 4rem;
    color: var(--color-accent);
    margin-bottom: 20px;
}

.error-content h1 {
    font-size: 6rem;
    font-weight: 900;
    color: var(--color-accent);
    margin: 0;
    line-height: 1;
}

.error-content h2 {
    font-size: 2rem;
    color: var(--text-primary);
    margin: 10px 0 20px 0;
}

.error-content p {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 30px;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    padding: 15px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--color-gradient);
    color: white;
}

.btn-secondary {
    background: transparent;
    color: var(--color-accent);
    border: 2px solid var(--color-accent);
}

.btn-primary:hover, .btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.error-help {
    background: var(--bg-card);
    padding: 30px;
    border-radius: 20px;
    border: 1px solid var(--border-color);
}

.error-help h3 {
    color: var(--text-primary);
    margin-bottom: 15px;
}

.error-help p {
    margin-bottom: 20px;
}

.contact-info {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    text-decoration: none;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: var(--color-accent);
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .error-content h1 {
        font-size: 4rem;
    }
    
    .error-content h2 {
        font-size: 1.5rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .contact-info {
        flex-direction: column;
        align-items: center;
    }
}

/* Mobile 404 */
@media (max-width: 768px) {
    .error-page {
        padding: 50px 0;
        margin-top: 70px;
        min-height: 60vh;
    }
    
    .error-content {
        padding: 0 20px;
        text-align: center;
        max-width: 350px;
        margin: 0 auto;
    }
    
    .error-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }
    
    .error-content h1 {
        font-size: 3rem;
        margin-bottom: 15px;
    }
    
    .error-content h2 {
        font-size: 1.4rem;
        margin-bottom: 12px;
    }
    
    .error-content p {
        font-size: 1rem;
        margin-bottom: 30px;
        line-height: 1.5;
    }
    
    .error-actions {
        flex-direction: column;
        gap: 15px;
        margin-bottom: 40px;
    }
    
    .error-actions .btn-primary,
    .error-actions .btn-secondary {
        padding: 16px 25px;
        font-size: 1rem;
        border-radius: 12px;
        justify-content: center;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .error-help {
        background: var(--bg-card);
        padding: 25px 20px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
    }
    
    .error-help h3 {
        font-size: 1.2rem;
        margin-bottom: 8px;
    }
    
    .error-help p {
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
    
    .contact-info {
        flex-direction: column;
        gap: 12px;
    }
    
    .contact-item {
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 0.9rem;
        justify-content: center;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .contact-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
}
</style>

<?php include 'includes/footer.php'; ?>
