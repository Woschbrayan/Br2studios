<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17672648705"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-17672648705');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Br2Studios - Investimentos Imobiliários em Curitiba'; ?></title>
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php 
    // Versionamento centralizado para forçar atualização do cache
    require_once __DIR__ . '/../config/version.php';
    $version = getAssetsVersion();
    ?>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo $version; ?>">
    <?php if (isset($page_css)): ?>
        <link rel="stylesheet" href="<?php echo $page_css; ?>?v=<?php echo $version; ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/mobile-enhancements.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/mobile-creative.css?v=<?php echo $version; ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dark-theme-fixes.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/scroll-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/hero-mobile-fixes.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/spacing-fixes.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/section-overrides.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/corretores-alignment-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/mobile-responsiveness-fixes.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/features-mobile-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/hero-spacing-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/section-spacing-mobile.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/property-cards-improvements.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/imoveis-cards-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/imoveis-layout-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/dynamic-filters.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/regioes-carousel.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/sobre-fixes.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/logo-fix.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="assets/css/clickable-cards.css?v=<?php echo $version; ?>">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php" class="logo-link">
                        <div class="logo-container">
                            <img src="assets/images/logo/logoBLACK (1).png" alt="Br2Studios" class="logo-image">
                        </div>
                    </a>
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
                        <span>(41) 8804-9999</span>
                    </div>
             
                    <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
