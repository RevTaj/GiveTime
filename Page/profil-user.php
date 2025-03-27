<?php 
session_start();
require '../bdd.php'; // Connexion à la base de données

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['Identifiant'])) {
    die("Vous devez être connecté pour accéder à cette page.");
}

// Détermine si l'utilisateur est un bénévole ou une association
$userType = isset($_SESSION['userType']) ? $_SESSION['userType'] : 
           (isset($_SESSION['Permission']) && $_SESSION['Permission'] == 3 ? 'association' : 'benevole');

$userId = $_SESSION['Identifiant'];

try {
    if ($userType == 'association') {
        // Récupération des informations de l'association
        $stmt = $db->prepare("SELECT * FROM gt_association WHERE id_Association = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            die("Profil d'association introuvable.");
        }
        
        // Affectation des valeurs pour une association
        $nom = htmlspecialchars($user['nomAssociation']);
        $prenom = ""; // Les associations n'ont pas de prénom
        $email = htmlspecialchars($user['mailAssociation']);
        $tags = array_filter(array_map('trim', explode(',', $user['tag'])));
        $description = nl2br(htmlspecialchars($user['description']));
        $localisation = htmlspecialchars($user['localisation']);
        $profileImage = !empty($user['image']) ? '../images/profils/' . htmlspecialchars($user['image']) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
        
        // Les associations n'ont pas de points/niveaux
        $showLevel = false;
    } else {
        // Récupération des informations du bénévole
        $stmt = $db->prepare("SELECT * FROM gt_benevole WHERE id_benevole = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            die("Profil de bénévole introuvable.");
        }
        
        // Affectation des valeurs pour un bénévole
        $prenom = htmlspecialchars($user['prenomBenevole']);
        $nom = htmlspecialchars($user['nomBenevole']);
        $email = htmlspecialchars($user['mailBenevole']);
        $tags = array_filter(array_map('trim', explode(',', $user['tag'])));
        $description = nl2br(htmlspecialchars($user['description']));
        $localisation = htmlspecialchars($user['localisation']);
        $profileImage = !empty($user['image']) ? '../images/profils/' . htmlspecialchars($user['image']) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';
        
        // Points et niveaux pour les bénévoles
        $points = (int)$user['point'];
        $pointsForNextLevel = 100;
        $progress = $points % $pointsForNextLevel;
        $level = floor($points / $pointsForNextLevel) + 1;
        $percentage = ($progress / $pointsForNextLevel) * 100;
        $showLevel = true;
    }
    
    // Pour la compatibilité, on garde une référence à $benevole si c'est un bénévole
    $benevole = $userType == 'benevole' ? $user : null;
    
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
    <title>Profil <?= $userType == 'association' ? 'de ' . $nom : 'de ' . $nom . ' ' . $prenom ?></title>
</head>
<body>

<?php include("../include/header.php"); ?>

<main class="profile-container">
    <!-- Profile Summary Section - More Compact -->
    <section class="profile-hero">
        <div class="profile-header">
            <img src="<?= $profileImage ?>" alt="Photo de profil" class="profile-img">
            <div class="profile-info">
                <h1><?= $nom ?> <?= $prenom ?></h1>
                <p class="email"><i class="fas fa-envelope"></i> <?= $email ?></p>
                <div class="profile-badges">
                    <?php if ($showLevel): ?>
                    <div class="profile-level-badge">
                        <i class="fas fa-award"></i> Niveau <?= $level ?>
                    </div>
                    <?php endif; ?>
                    <div class="profile-location-badge">
                        <i class="fas fa-map-marker-alt"></i> <?= $localisation ?>
                    </div>
                </div>
            </div>
            <!-- Quick Action Buttons -->
            <div class="profile-quick-actions">
                <a href="edit-profile.php" class="btn-quick-edit"><i class="fas fa-edit"></i> Modifier</a>
            </div>
        </div>
    </section>
    
    <!-- Better organized grid layout -->
    <div class="profile-grid">
        <!-- Main Content Column -->
        <div class="main-content">
            <!-- About Me Card -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Présentation</h3>
                </div>
                <div class="card-content">
                    <p><?= $description ?></p>
                    
                    <?php if ($showLevel): ?>
                    <!-- Progress bar below the description -->
                    <div class="progress-section">
                        <h4 style="margin: 20px 0 5px; font-size: 16px;">
                            <i class="fas fa-chart-line" style="color: var(--primary); margin-right: 5px;"></i> 
                            Progression
                        </h4>
                        
                        <div class="progress-stats">
                            <div class="stat-item">
                                <i class="fas fa-star"></i>
                                <div class="stat-info">
                                    <span class="stat-value"><?= $points ?></span>
                                    <span class="stat-label">Points totaux</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-trophy"></i>
                                <div class="stat-info">
                                    <span class="stat-value"><?= $level ?></span>
                                    <span class="stat-label">Niveau actuel</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress-next">
                            <span><?= $progress ?>/<?= $pointsForNextLevel ?> pour le niveau suivant</span>
                        </div>
                        
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?= $percentage ?>%">
                                <div class="progress-glow"></div>
                            </div>
                        </div>
                        
                        <p class="progress-text">
                            <i class="fas fa-lightbulb"></i>
                            Terminez des missions pour gagner des points et débloquer le prochain niveau!
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Side Content Column -->
        <div class="side-container">
            <!-- Skills/Tags Card -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-tags"></i>
                    <h3><?= $userType == 'association' ? 'Domaines d\'action' : 'Compétences & Intérêts' ?></h3>
                </div>
                <div class="card-content">
                    <div class="profile-tags">
                        <?php if (!empty($tags)): ?>
                            <?php foreach ($tags as $tag): ?>
                                <span class="tag"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun élément spécifié</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Location Card -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Localisation</h3>
                </div>
                <div class="card-content">
                    <div id="map" class="profile-map"></div>
                </div>
            </div>
        </div>
    </div>
    
<!-- Action Buttons -->
<div class="profile-actions">
    <?php if ($userType == 'association'): ?>
        <a href="page1-Asso.php" class="btn btn-primary"><i class="fas fa-tasks"></i> Mes missions</a>
    <?php else: ?>
        <a href="page1.php" class="btn btn-primary"><i class="fas fa-tasks"></i> Mes missions</a>
    <?php endif; ?>
    <a href="edit-profile.php" class="btn btn-secondary"><i class="fas fa-cog"></i> Paramètres du profil</a>
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
