<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Detect if we're in the root directory or a subdirectory
$basePath = '';
if (strpos($_SERVER['REQUEST_URI'], '/GiveTime/GiveTime/') !== false && 
    strpos($_SERVER['REQUEST_URI'], '/GiveTime/GiveTime/index') === false) {
    // We're in a subdirectory
    $basePath = '../';
}

// Determine the logo link based on user permission
if(isset($_SESSION['authentification']) && $_SESSION['authentification'] === true) {
    switch($_SESSION['Permission']) {
        case 2: // Bénévole
            $logoLink = $basePath . 'Page/page1.php';
            break;
        case 3: // Association
            $logoLink = $basePath . 'Page/page1-Asso.php';
            break;
        case 4: // Admin
            $logoLink = $basePath . 'Page/pageAdmin.php';
            break;
    }
}
?>
<!DOCTYPE html>
<header>
    <div class="header-container">
    <a href="../index.html" class="logo">
                <img src="../images/logo-fav/Logo.png" alt="GiveTime Logo">
    </a>
        <nav>
            <?php if(!isset($_SESSION['authentification']) || $_SESSION['authentification'] !== true): ?>
                <!-- User is not logged in -->
                <a href="<?= $basePath ?>../index.html" class="<?= basename($_SERVER['PHP_SELF']) == 'index.html' ? 'active' : '' ?>">
                    Accueil
                </a>
                <a href="<?= $basePath ?>../index/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'connexion.php' ? 'active' : '' ?>">
                    Se connecter
                </a>
                <a href="<?= $basePath ?>../inscription/inscription.html" class="nav-button">
                    S'inscrire
                </a>
                
            <?php elseif($_SESSION['Permission'] == 2): ?>
                <!-- Logged in as Bénévole -->
                <a href="<?= $basePath ?>Page/page1.php" class="<?= basename($_SERVER['PHP_SELF']) == 'page1.php' ? 'active' : '' ?>">
                    Accueil
                </a>
                <a href="<?= $basePath ?>Page/profil-user.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil-user.php' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Mon Profil
                </a>
                <a href="<?= $basePath ?>Connexion/deconnexion.php" class="nav-button">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
                
            <?php elseif($_SESSION['Permission'] == 3): ?>
                <!-- Logged in as Association -->
                <a href="<?= $basePath ?>Page/page1-Asso.php" class="<?= basename($_SERVER['PHP_SELF']) == 'page1-Asso.php' ? 'active' : '' ?>">
                    Accueil
                </a>
                <a href="<?= $basePath ?>Page/profil-user.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil-user.php' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Mon Profil
                </a>
                <a href="<?= $basePath ?>Page/pageAssociation.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pageAssociation.php' ? 'active' : '' ?>">
                    <i class="fas fa-tasks"></i> Gérer les missions
                </a>
                <a href="<?= $basePath ?>Connexion/deconnexion.php" class="nav-button">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
                
            <?php elseif($_SESSION['Permission'] == 4): ?>
                <!-- Logged in as Admin -->
                <a href="<?= $basePath ?>Page/pageAdmin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pageAdmin.php' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= $basePath ?>Page/page1-Asso.php" class="<?= basename($_SERVER['PHP_SELF']) == 'page1-Asso.php' ? 'active' : '' ?>">
                    Vue Association
                </a>
                <a href="<?= $basePath ?>Page/page1.php" class="<?= basename($_SERVER['PHP_SELF']) == 'page1.php' ? 'active' : '' ?>">
                    Vue Bénévole
                </a>
                <a href="<?= $basePath ?>Page/profil-admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil-admin.php' ? 'active' : '' ?>">
                    <i class="fas fa-user-shield"></i> Profil Admin
                </a>
                <a href="<?= $basePath ?>Connexion/deconnexion.php" class="nav-button">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>