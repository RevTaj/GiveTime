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
            $prenom = $user['prenomBenevole'];
            $nom = $user['nomBenevole'];
            $email = $user['mailBenevole'];
            $tags = explode(',', $user['tag']);
            $description = $user['description'];
            $localisation = $user['localisation'];
            $profileImage = !empty($user['image']) ? '../images/profils/' . $user['image'] : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
        }
    } else if ($type == 'association') {
        $stmt = $db->prepare("SELECT * FROM gt_association WHERE id_Association = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $nom = $user['nomAssociation'];
            $prenom = "";
            $email = $user['mailAssociation'];
            $tags = explode(',', $user['tag']);
            $description = $user['description'];
            $localisation = $user['localisation'];
            $profileImage = !empty($user['image']) ? '../images/profils/' . $user['image'] : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
        }
    } else {
        die("Type d'utilisateur non reconnu.");
    }
    
    // Vérification que l'utilisateur existe
    if (!$user) {
        die("Utilisateur non trouvé.");
    }
    
    // Convertir l'adresse en coordonnées pour la carte (exemple fictif)
    // En production, il faudrait utiliser une API de géocodage
    // Format attendu: "latitude,longitude"
    $coordinates = "48.8566,2.3522"; // Coordonnées par défaut (Paris)
    
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <title>Profil <?= htmlspecialchars($nom) ?></title>
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
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Photo de profil" class="profile-img">
            <div class="profile-info">
                <h1><?= htmlspecialchars($nom) ?></h1>
                <?php if (!empty($prenom)): ?>
                <h2><?= htmlspecialchars($prenom) ?></h2>
                <?php endif; ?>
                <p class="email"><?= htmlspecialchars($email) ?></p>
            </div>
        </div>
        
        <div class="profile-tags">
            <?php foreach ($tags as $tag): ?>
                <?php if(trim($tag) != ''): ?>
                    <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <div class="profile-description">
            <h3>Présentation</h3>
            <p><?= htmlspecialchars($description) ?></p>
        </div>
        
        <div class="profile-location">
            <h3>Localisation</h3>
            <p><?= htmlspecialchars($localisation) ?></p>
            <div id="map"></div>
        </div>
    </div>
</main>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <h3>GiveTime</h3>
            <p>Connecter les associations et les bénévoles.</p>
        </div>
        <div class="footer-right">
            <p>&copy; 2025 GiveTime. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script>
    // Fonction pour géocoder l'adresse
    async function geocodeAddress(address) {
        try {
            // Utilisation de l'API Nominatim d'OpenStreetMap pour le géocodage
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
            const data = await response.json();
            
            if (data && data.length > 0) {
                // Retourner les coordonnées trouvées
                return [data[0].lat, data[0].lon];
            } else {
                // Si rien n'est trouvé, retourner les coordonnées par défaut (Paris)
                console.log("Adresse non trouvée, utilisation des coordonnées par défaut");
                return ["48.8566", "2.3522"];
            }
        } catch (error) {
            console.error("Erreur de géocodage:", error);
            // En cas d'erreur, retourner les coordonnées par défaut
            return ["48.8566", "2.3522"];
        }
    }

    // Initialisation de la carte avec géocodage
    (async function() {
        const locationName = "<?= htmlspecialchars($localisation) ?>";
        // Obtenir les coordonnées à partir de l'adresse
        const coordinates = await geocodeAddress(locationName);
        
        // Créer la carte avec les coordonnées obtenues
        const map = L.map('map').setView(coordinates, 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Ajouter un marqueur
        L.marker(coordinates).addTo(map)
            .bindPopup(locationName)
            .openPopup();
    })();
</script>

</body>
</html>