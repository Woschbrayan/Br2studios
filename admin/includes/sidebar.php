<?php
/**
 * Menu lateral padronizado para todas as páginas administrativas
 * Sistema Br2Studios
 */

// Determinar qual página está ativa
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
               <h2>Br2Imóveis</h2>
        </div>
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <span class="user-name"><?php echo $_SESSION['admin_nome'] ?? 'Administrador'; ?></span>
                <span class="user-role">Administrador</span>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Geral</div>
            <a href="dashboard.php" class="nav-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Gerenciamento</div>
            <a href="imoveis.php" class="nav-item <?php echo $current_page === 'imoveis' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                Imóveis
            </a>
            <a href="corretores.php" class="nav-item <?php echo $current_page === 'corretores' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i>
                Corretores
            </a>
            <a href="depoimentos.php" class="nav-item <?php echo $current_page === 'depoimentos' ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i>
                Depoimentos
            </a>
            <a href="especialistas.php" class="nav-item <?php echo $current_page === 'especialistas' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                Especialistas
            </a>
            <a href="categorias.php" class="nav-item <?php echo $current_page === 'categorias' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i>
                Categorias
            </a>
        </div>
      
        
        <div class="nav-section">
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </a>
        </div>
    </nav>
</aside>
