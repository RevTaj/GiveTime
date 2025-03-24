<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<header>
    <nav>
        <?php if(!isset($_SESSION['Permission'])): ?>
            <a href="/GiveTime/GiveTime/index.html">Accueil</a>
            <a href="/GiveTime/GiveTime/Connexion/connexion.php">Se connecter</a>
            <a href="/GiveTime/GiveTime/Connexion/inscription.php">S'inscrire</a>
        <?php else: ?>
            <?php if($_SESSION['Permission'] == 2): ?>
                <a href="/GiveTime/GiveTime/Page/page1.php">Accueil</a>
                <a href="/GiveTime/GiveTime/Page/profil-user.php">Mon Profil</a>
            <?php elseif($_SESSION['Permission'] == 3): ?>
                <a href="/GiveTime/GiveTime/Page/page1-Asso.php">Accueil</a>
                <a href="/GiveTime/GiveTime/Page/profil-user.php">Mon Profil</a>
                <a href="/GiveTime/GiveTime/Page/pageAssociation.php">Gérer les missions</a>
            <?php elseif($_SESSION['Permission'] == 4): ?>
                <a href="/GiveTime/GiveTime/Page/pageAdmin.php">Accueil</a>
                <a href="/GiveTime/GiveTime/Page/page1-Asso.php">Accueil-Association</a>
                <a href="/GiveTime/GiveTime/Page/page1.php">Accueil-Benevole</a>
                <a href="/GiveTime/GiveTime/Page/profil-admin.php">Mon Profil</a>
            <?php endif; ?>
            <a href="/GiveTime/GiveTime/Connexion/deconnexion.php">Déconnexion</a>
        <?php endif; ?>
    </nav>
</header>