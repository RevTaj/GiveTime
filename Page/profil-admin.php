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
    <!-- Profile Summary Section -->
    <section class="profile-hero">
        <div class="profile-header">
            <img src="<?= $profileImage ?>" alt="Photo de profil" class="profile-img">
            <div class="profile-info">
                <h1><?= $nom ?> <?= $prenom ?></h1>
                <p class="email"><i class="fas fa-envelope"></i> <?= $email ?></p>
                <div class="profile-badges">
                    <div class="profile-level-badge" style="background-color: rgba(85, 0, 128, 0.15); color: #550080;">
                        <i class="fas fa-shield-alt" style="color: #550080;"></i> Administrateur
                    </div>
                    <div class="profile-location-badge">
                        <i class="fas fa-map-marker-alt"></i> <?= $localisation ?>
                    </div>
                </div>
            </div>
            <!-- Quick Action Buttons -->
            <div class="profile-quick-actions">
                <a href="edit-admin-profile.php" class="btn-quick-edit"><i class="fas fa-edit"></i> Modifier</a>
            </div>
        </div>
    </section>
    
    <!-- Grid layout -->
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
                    
                    <!-- Admin Stats Section -->
                    <div class="progress-section">
                        <h4 style="margin: 20px 0 5px; font-size: 16px;">
                            <i class="fas fa-chart-line" style="color: var(--primary); margin-right: 5px;"></i> 
                            Statistiques d'administration
                        </h4>
                        
                        <div class="progress-stats">
                            <div class="stat-item">
                                <i class="fas fa-users-cog" style="background-color: rgba(85, 0, 128, 0.1);"></i>
                                <div class="stat-info">
                                    <span class="stat-value">25000</span>
                                    <span class="stat-label">Utilisateurs gérés</span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-calendar-check" style="background-color: rgba(85, 0, 128, 0.1);"></i>
                                <div class="stat-info">
                                    <span class="stat-value"><?= isset($admin['last_login']) ? date('d/m/Y', strtotime($admin['last_login'])) : date('d/m/Y') ?></span>
                                    <span class="stat-label">Dernière connexion</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Side Content Column -->
        <div class="side-container">
            <!-- Roles/Permissions Card -->
            <div class="profile-card">
                <div class="card-header">
                    <i class="fas fa-tags"></i>
                    <h3>Rôles & Permissions</h3>
                </div>
                <div class="card-content">
                    <div class="profile-tags">
                        <?php if (!empty($tags)): ?>
                            <?php foreach ($tags as $tag): ?>
                                <span class="tag"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun rôle spécifié</p>
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
        <a href="pageAdmin.php" class="btn btn-primary"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
        <a href="pageAdmin.php" class="btn btn-secondary"><i class="fas fa-users"></i> Gestion des utilisateurs</a>
        <a href="edit-admin-profile.php" class="btn btn-secondary"><i class="fas fa-cog"></i> Paramètres du profil</a>
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