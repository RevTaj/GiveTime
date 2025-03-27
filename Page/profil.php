<?php 
session_start();
require '../bdd.php'; // Connexion à la base de données

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['Identifiant'])) {
    die("Vous devez être connecté pour accéder à cette page.");
}
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
            
            // For volunteers, set level and points if available
            $points = isset($user['point']) ? (int)$user['point'] : 0;
            $pointsForNextLevel = 100;
            $progress = $points % $pointsForNextLevel;
            $level = floor($points / $pointsForNextLevel) + 1;
            $percentage = ($progress / $pointsForNextLevel) * 100;
            $showLevel = true;
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
            $showLevel = false;
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
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <title>Profil <?= htmlspecialchars($nom) ?></title>
    <style>
        /* Custom overrides for this page */
        .profile-container {
            width: 95%;
            max-width: 1400px;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        
        .profile-hero {
            background: linear-gradient(to right, rgba(255,255,255,0.9), rgba(255,255,255,0.7)), 
                        url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            overflow: hidden;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .profile-header {
            padding: 20px 30px;
            margin-top: 0;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
        }
        
        .profile-img {
            width: 110px;
            height: 110px;
            border: 4px solid white;
            margin-right: 25px;
        }
        
        .profile-info {
            padding-top: 0;
        }
        
        .profile-info h1 {
            margin-bottom: 5px;
        }
        
        .profile-badges {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            align-items: stretch; /* Make all badges equal height */
        }
        
        /* Common badge styles */
        .profile-level-badge,
        .profile-location-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
            min-width: 120px;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .profile-level-badge:hover,
        .profile-location-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        }
        
        .profile-level-badge i,
        .profile-location-badge i {
            margin-right: 6px;
            font-size: 1rem;
        }
        
        /* Level badge specific styles */
        .profile-level-badge {
            background-color: rgba(241, 135, 0, 0.15);
            color: var(--primary-dark);
        }
        
        .profile-level-badge i {
            color: var(--primary);
        }
        
        /* Location badge specific styles */
        .profile-location-badge {
            background-color: rgba(121, 198, 192, 0.15);
            color: var(--secondary-dark);
        }
        
        .profile-location-badge i {
            color: var(--secondary);
        }
        
        .profile-card {
            margin-bottom: 0;
            padding: 0;
            height: 100%;
        }
        
        .card-content {
            padding: 15px 20px;
        }
        
        /* Right side container */
        .side-container {
            display: grid;
            grid-template-rows: auto auto;
            gap: 20px;
            height: 100%;
        }
        
        /* Progress section */
        .progress-section {
            display: flex;
            flex-direction: column;
        }
        
        .progress-bar-container {
            height: 8px;
            margin: 5px 0 15px;
        }
        
        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .side-container {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto;
            }
        }
        
        @media (max-width: 768px) {
            .side-container {
                grid-template-columns: 1fr;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-img {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .profile-badges {
                justify-content: center;
            }
        }
        
        .card-header {
            background-color: var(--light-bg);
            padding: var(--spacing-sm) var(--spacing-md);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
        }

        .card-header i {
            font-size: 1.2rem;
            color: var(--primary);
            margin-right: var(--spacing-sm);
        }

        .card-header h3 {
            color: var(--text-dark);
            margin: 0;
            font-size: 1.2rem;
        }
        
        .profile-map {
            height: 250px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
    </style>
</head>
<body>

<?php include("../include/header.php"); ?>

<main class="profile-container">
    <!-- Profile Summary Section -->
    <section class="profile-hero">
        <div class="profile-header">
            <img src="<?= htmlspecialchars($profileImage) ?>" alt="Photo de profil" class="profile-img">
            <div class="profile-info">
                <h1><?= htmlspecialchars($nom) ?></h1>
                <?php if (!empty($prenom)): ?>
                <h2><?= htmlspecialchars($prenom) ?></h2>
                <?php endif; ?>
                <p class="email"><i class="fas fa-envelope"></i> <?= htmlspecialchars($email) ?></p>
                <div class="profile-badges">
                    <?php if (isset($showLevel) && $showLevel): ?>
                    <div class="profile-level-badge">
                        <i class="fas fa-award"></i> Niveau <?= $level ?>
                    </div>
                    <?php endif; ?>
                    <div class="profile-location-badge">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($localisation) ?>
                    </div>
                </div>
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
                    <p><?= nl2br(htmlspecialchars($description)) ?></p>
                    
                    <?php if (isset($showLevel) && $showLevel): ?>
                    <!-- Progress bar below the description for volunteers only -->
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
                            Ce bénévole a gagné des points en accomplissant des missions!
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
                    <h3>Compétences & Intérêts</h3>
                </div>
                <div class="card-content">
                    <div class="profile-tags">
                        <?php foreach ($tags as $tag): ?>
                            <?php if(trim($tag) != ''): ?>
                            <span class="tag"><i class="fas fa-check-circle"></i> <?= htmlspecialchars(trim($tag)) ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
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
</main>

<?php include("../include/footer.php"); ?>

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