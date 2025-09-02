<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Br2Studios - Investimentos Imobiliários em Curitiba'; ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo $page_css; ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/mobile-enhancements.css">
    <link rel="stylesheet" href="assets/css/mobile-creative.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dark-theme-fixes.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-container">
                        <i class="fas fa-building logo-icon"></i>
                        <span class="logo-text">Br2Imóveis</span>
                    </div>
                </div>
                
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link <?php echo $current_page === 'home' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="imoveis.php" class="nav-link <?php echo $current_page === 'imoveis' ? 'active' : ''; ?>">Imóveis</a></li>
                        <li><a href="regioes.php" class="nav-link <?php echo $current_page === 'regioes' ? 'active' : ''; ?>">Regiões</a></li>
                        <li><a href="corretores.php" class="nav-link <?php echo $current_page === 'corretores' ? 'active' : ''; ?>">Corretores</a></li>
                        <li><a href="contato.php" class="nav-link <?php echo $current_page === 'contato' ? 'active' : ''; ?>">Contato</a></li>
                        <li><a href="sobre.php" class="nav-link <?php echo $current_page === 'sobre' ? 'active' : ''; ?>">Sobre</a></li>
                        
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <span>(41) 4141-0093</span>
                    </div>
                    <button class="theme-toggle" id="theme-toggle" aria-label="Alternar tema">
                        <i class="fas fa-moon" id="theme-icon"></i>
                    </button>
                    <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
