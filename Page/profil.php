<?php 
include("../bdd.php");

// Récupération des paramètres
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérification des paramètres
if (empty($type) || $id <= 0) {
    die("Paramètres invalides.");
}

// Récupération des informations selon le type d'utilisateur
try {
    if ($type == 'benevole') {
        $stmt = $db->prepare("SELECT * FROM gt_benevole WHERE id_Benevole = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $name = $user['prenomBenevole'] . ' ' . $user['nomBenevole'];
            $role = "Bénévole";
            $tags = explode(',', $user['tag']);
            $description = $user['description'];
            $profileImage = !empty($user['image']) ? '../images/profils/' . $user['image'] : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
        }
    } else if ($type == 'association') {
        $stmt = $db->prepare("SELECT * FROM gt_association WHERE id_Association = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $name = $user['nomAssociation'];
            $role = "Association";
            $tags = explode(',', $user['tag']);
            $description = $user['description'];
            $profileImage = !empty($user['image']) ? '../images/profils/' . $user['image'] : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
        }
    } else {
        die("Type d'utilisateur non reconnu.");
    }
    
    // Vérification que l'utilisateur existe
    if (!$user) {
        die("Utilisateur non trouvé.");
    }
    
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profil.css">
    <title>Profil <?= htmlspecialchars($name) ?></title>
</head>
<body>

<header>
    <nav>
        <a href="Accueil.php">Accueil</a>
        <a href="profil">Mon Profil</a>
        <a href="../Connexion/deconnexion.php">Déconnexion</a>
    </nav>
</header>

<main class="profile-container">

    <aside class="sidebar">
        <img src="<?= htmlspecialchars($profileImage) ?>" alt="Photo de <?= htmlspecialchars($name) ?>" class="profile-img">
        
        <h2><?= htmlspecialchars($name) ?></h2>
        <p class="role"><?= htmlspecialchars($role) ?> <?= $type == 'benevole' ? 'en action sociale' : '' ?></p>
        <div class="tags">
            <?php foreach ($tags as $tag): ?>
                <?php if(trim($tag) != ''): ?>
                    <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </aside>

    <section class="main-content">
        <h3>Présentation</h3>
        <p class="bio">
            <?= htmlspecialchars($description) ?>
        </p>

        <?php if ($type == 'benevole'): ?>
        <h3>Personnalité</h3>
        <ul class="list">
            <li>Très à l'écoute</li>
            <li>Bienveillant(e)</li>
            <li>Disponible et engagé(e)</li>
        </ul>

        <h3>Objectifs</h3>
        <ul class="list">
            <li>Participer à des actions sociales locales</li>
            <li>Développer ses compétences en aide humanitaire</li>
            <li>S'investir dans des projets à fort impact social</li>
        </ul>
        <?php else: ?>
        <h3>Missions</h3>
        <ul class="list">
            <li>Organisation d'événements caritatifs</li>
            <li>Collecte de fonds pour des causes sociales</li>
            <li>Soutien aux personnes en difficulté</li>
        </ul>

        <h3>Besoins en bénévoles</h3>
        <ul class="list">
            <li>Animation d'ateliers</li>
            <li>Aide logistique événementielle</li>
            <li>Support administratif</li>
        </ul>
        <?php endif; ?>
    </section>

    <aside class="right-section">
        <?php if ($type == 'benevole'): ?>
        <h3>Motivations</h3>
        <p>Aider les autres</p>
        <div class="progress-bar"><div class="progress" style="width: 90%;"></div></div>
        <p>Développer des compétences</p>
        <div class="progress-bar"><div class="progress" style="width: 75%;"></div></div>
        <p>Élargir son réseau</p>
        <div class="progress-bar"><div class="progress" style="width: 85%;"></div></div>

        <h3>Disponibilités</h3>
        <ul class="list">
            <li>Soirs en semaine</li>
            <li>Week-ends</li>
            <li>Vacances scolaires</li>
        </ul>
        <?php else: ?>
        <h3>Valeurs</h3>
        <p>Solidarité</p>
        <div class="progress-bar"><div class="progress" style="width: 95%;"></div></div>
        <p>Engagement</p>
        <div class="progress-bar"><div class="progress" style="width: 90%;"></div></div>
        <p>Innovation sociale</p>
        <div class="progress-bar"><div class="progress" style="width: 80%;"></div></div>

        <h3>Informations pratiques</h3>
        <ul class="list">
            <li>Fondée en <?= rand(1980, 2010) ?></li>
            <li>Localisation: <?= htmlspecialchars($user['localisation']) ?></li>
            <li><?= rand(10, 100) ?> bénévoles actifs</li>
        </ul>
        <?php endif; ?>

        <h3><?= $type == 'benevole' ? 'Associations favorites' : 'Partenaires' ?></h3>
        <div class="fav-brands">
            <img src="https://img.freepik.com/vecteurs-libre/mains-reliant-logo_23-2147507857.jpg" alt="Logo Association 1">
            <img src="https://www.associationmodeemploi.fr/mediatheque/9/1/2/000015219_600x400_c.jpg" alt="Logo Association 2">
            <img src="https://lh6.googleusercontent.com/proxy/IidGd0lv00o2-MAdk8M9nwNA6_TV2DueRUVygRYQ6sAB80rqjCcxKOY5AQwVTxwaFlKCEuNEVA6FWNpnofVpupz9oNqVmBysqiKe05Zgq-MnrJTZAdbnqlWvr34OUWEJMZiZfT1G" alt="Logo Association 3">
        </div>
    </aside>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <h3>GiveTime</h3>
            <p>GiveTime est une plateforme dédiée à la mise en relation des bénévoles et des associations.</p>
        </div>
        <div class="footer-center">
            <h4>Contact</h4>
            <p>Email: contact@givetime.org</p>
            <p>Téléphone: +33 1 23 45 67 89</p>
            <p>Adresse: 123 Rue de la Solidarité, 75000 Paris, France</p>
        </div>
        <div class="footer-right">
            <h4>Suivez-nous</h4>
            <div class="social-links">
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/768px-2023_Facebook_icon.svg.png" alt="Facebook"></a>
                <a href="#"><img src="https://img.freepik.com/vecteurs-libre/nouvelle-conception-icone-x-du-logo-twitter-2023_1017-45418.jpg" alt="Twitter"></a>
                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/768px-Instagram_icon.png" alt="Instagram"></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 GiveTime. Tous droits réservés.</p>
    </div>
</footer>

</body>
</html>