<?php 
session_start();
require '../bdd.php'; // Connexion à la base de données

// Vérification si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['Identifiant']) || $_SESSION['Permission'] != 4) {
    die("Accès non autorisé.");
}

$id_admin = $_SESSION['Identifiant'];

try {
    // Récupération des informations de l'administrateur connecté
    $stmt = $db->prepare("SELECT * FROM gt_admin WHERE id_admin = :id");
    $stmt->execute(['id' => $id_admin]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        die("Profil introuvable.");
    }

    // Affectation des valeurs récupérées (adaptez les noms de champs selon votre base de données)
    $prenom = htmlspecialchars($admin['prenomAdmin'] ?? 'Admin');
    $nom = htmlspecialchars($admin['nomAdmin'] ?? 'Système');
    $email = htmlspecialchars($admin['mailAdmin'] ?? 'admin@givetime.com');
    $tags = isset($admin['tag']) ? array_filter(array_map('trim', explode(',', $admin['tag']))) : ['Administration', 'Modération'];
    $description = nl2br(htmlspecialchars($admin['description'] ?? 'Administrateur système de GiveTime'));
    $localisation = htmlspecialchars($admin['localisation'] ?? 'France');
    $profileImage = !empty($admin['image']) ? '../images/profils/' . htmlspecialchars($admin['image']) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';

} catch (PDOException $e) {
    die("Erreur lors de la récupération des informations : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <title>Profil Administrateur - <?= $nom ?></title>
</head>
<body>

<?php include("../include/header.php"); ?>

<main class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?= $profileImage ?>" alt="Photo de profil" class="profile-img">
            <div class="profile-info">
                <h1><?= $nom ?></h1>
                <h2><?= $prenom ?></h2>
                <p class="email"><?= $email ?></p>
                <p class="status">Administrateur système</p>
            </div>
        </div>
        
        <div class="profile-tags">
            <?php foreach ($tags as $tag): ?>
                <span class="tag"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </div>
        
        <div class="profile-description">
            <h3>Présentation</h3>
            <p><?= $description ?></p>
        </div>
        
        <div class="profile-location">
            <h3>Localisation</h3>
            <p><?= $localisation ?></p>
            <div id="map"></div>
        </div>
        
        </div>
    </div>
</main>

<?php include("../include/footer.php"); ?>

<script>
    // Fonction pour géocoder l'adresse
    async function geocodeAddress(address) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
            const data = await response.json();
            
            if (data.length > 0) {
                return [data[0].lat, data[0].lon];
            } else {
                console.log("Adresse non trouvée, utilisation des coordonnées par défaut");
                return ["48.8566", "2.3522"];
            }
        } catch (error) {
            console.error("Erreur de géocodage:", error);
            return ["48.8566", "2.3522"];
        }
    }

    (async function() {
        const locationName = "<?= $localisation ?>";
        const coordinates = await geocodeAddress(locationName);
        
        const map = L.map('map').setView(coordinates, 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        L.marker(coordinates).addTo(map)
            .bindPopup(locationName)
            .openPopup();
    })();
</script>

</body>
</html>