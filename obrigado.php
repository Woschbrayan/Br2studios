<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrigado pelo Contato - Br2Studios Curitiba</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/contato.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
    </style>
</head>
<body>

<style>
/* Estilos específicos para página de agradecimento */
.thank-you-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 40px 0 60px 0;
}

.thank-you-container {
    max-width: 500px;
    text-align: center;
    background: white;
    padding: 50px 35px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
}

.thank-you-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #25d366, #128c7e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    animation: pulse 2s infinite;
}

.thank-you-icon i {
    font-size: 2rem;
    color: white;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

.thank-you-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 18px;
    line-height: 1.2;
}

.thank-you-subtitle {
    font-size: 1.2rem;
    color: #25d366;
    font-weight: 600;
    margin-bottom: 18px;
}

.thank-you-message {
    font-size: 1rem;
    color: #666;
    line-height: 1.6;
    margin-bottom: 35px;
}

.thank-you-details {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 35px;
    border-left: 4px solid #25d366;
}

.thank-you-details h3 {
    color: #1a1a1a;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.thank-you-details p {
    color: #666;
    margin: 0;
    line-height: 1.5;
}

.thank-you-actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
}

.btn-home {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    padding: 18px 35px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    transition: all 0.3s ease;
    border: none;
    width: 100%;
    max-width: 300px;
}

.btn-home:hover {
    background: linear-gradient(135deg, #b91c1c, #991b1b);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
    color: white;
    text-decoration: none;
}

.btn-whatsapp-thank {
    background: #25d366;
    color: white;
    padding: 15px 30px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    width: 100%;
    max-width: 300px;
}

.btn-whatsapp-thank:hover {
    background: #128c7e;
    transform: translateY(-2px);
    text-decoration: none;
    color: white;
}

/* Responsividade */
@media (max-width: 768px) {
    .thank-you-section {
        padding: 30px 20px 50px 20px;
        min-height: 100vh;
    }
    
    .thank-you-container {
        padding: 35px 20px;
        margin: 0 10px;
        max-width: 450px;
    }
    
    .thank-you-icon {
        width: 80px;
        height: 80px;
        margin-bottom: 25px;
    }
    
    .thank-you-icon i {
        font-size: 2rem;
    }
    
    .thank-you-title {
        font-size: 2rem;
        margin-bottom: 15px;
    }
    
    .thank-you-subtitle {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }
    
    .thank-you-message {
        font-size: 1rem;
        margin-bottom: 30px;
    }
    
    .thank-you-details {
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .thank-you-actions {
        gap: 15px;
    }
    
    .btn-home,
    .btn-whatsapp-thank {
        padding: 15px 25px;
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .thank-you-section {
        padding: 25px 15px 40px 15px;
    }
    
    .thank-you-container {
        padding: 30px 20px;
        max-width: 400px;
    }
    
    .thank-you-title {
        font-size: 1.8rem;
    }
    
    .thank-you-subtitle {
        font-size: 1rem;
    }
    
    .thank-you-message {
        font-size: 0.95rem;
    }
}
</style>

<section class="thank-you-section">
    <div class="container">
        <div class="thank-you-container">
            <div class="thank-you-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <h1 class="thank-you-title">Mensagem Enviada!</h1>
            <p class="thank-you-subtitle">Obrigado pelo seu contato</p>
            
            <p class="thank-you-message">
                Recebemos sua mensagem e nossa equipe de especialistas em investimentos imobiliários 
                entrará em contato com você em até 2 horas durante o horário comercial.
            </p>
            
            <div class="thank-you-details">
                <h3>O que acontece agora?</h3>
                <p>
                    Nossa equipe analisará sua solicitação e preparará as melhores oportunidades 
                    de investimento em estúdios em Curitiba, personalizadas para seu perfil e objetivos.
                </p>
            </div>
            
            <div class="thank-you-actions">
                <a href="index.php" class="btn-home">
                    <i class="fas fa-home"></i>
                    Voltar ao Início
                </a>
                
                <a href="https://wa.me/554188049999" class="btn-whatsapp-thank" target="_blank">
                    <i class="fab fa-whatsapp"></i>
                    Falar no WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

</body>
</html>
