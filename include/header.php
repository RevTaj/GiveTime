<!DOCTYPE html>
<header>
    <nav>
        <?php if(!isset($_SESSION['Permission'])): ?>
            <a href="/GiveTime/GiveTime/index.html">Accueil</a>
            <a href="/GiveTime/GiveTime/Connexion/connexion.php">Se connecter</a>
            <a href="/GiveTime/GiveTime/Connexion/inscription.php">S'inscrire</a>
        <?php else: ?>
            <a href="/GiveTime/GiveTime/Page/page1.php">Accueil</a>
            <?php if($_SESSION['Permission'] == 2): ?>
                <a href="/GiveTime/GiveTime/Page/profil-user.php">Mon Profil</a>
            <?php elseif($_SESSION['Permission'] == 3): ?>
                <a href="/GiveTime/GiveTime/Page/profil-user.php">Mon Profil</a>
            <?php endif; ?>
            <a href="/GiveTime/GiveTime/Connexion/deconnexion.php">Déconnexion</a>
        <?php endif; ?>
    </nav>
</header>