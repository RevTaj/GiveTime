<?php
session_start();
date_default_timezone_set('UTC');

// Redirection si non authentifié ou sans permission suffisante
if (!isset($_SESSION['authentification']) || $_SESSION['authentification'] != true || 
    !in_array($_SESSION['Permission'], [2, 4])) { // On autorise seulement bénévole et admin sur la page
    header('Location: ../Connexion/Erreur.php');
    exit;
}

// Include database connection if needed for form processing
include("../bdd.php");

// Process form submissions first to set notifications
// Handle mission registration
if (isset($_POST['INSCRIPTION'])) {
    $posts_id = $_POST['INSCRIPTION'];
    $benevole_id = $_SESSION['Identifiant'];
    $action = $_POST['submit'];

    if ($action == "s'inscrire ! ") {
        // Vérification si l'utilisateur est déjà inscrit
        $checkQuery = $db->prepare("
            SELECT COUNT(*) FROM gt_Inscription 
            WHERE Posts_id = :posts_id AND benevole_id = :benevole_id
        ");
        $checkQuery->execute([
            'posts_id' => $posts_id,
            'benevole_id' => $benevole_id
        ]);
        $count = $checkQuery->fetchColumn();

        if ($count > 0) {
            $_SESSION['notification'] = "Vous êtes déjà inscrit à cette mission.";
        } else {
            $insertQuery = $db->prepare("
                INSERT INTO gt_Inscription(Posts_id, benevole_id, date_debut, progression, date_fin) 
                VALUES (:posts_id, :benevole_id, :date_debut, :progression, :date_fin)
            ");
            $insertQuery->execute([
                'posts_id' => $posts_id,
                'benevole_id' => $benevole_id,
                'date_debut' => date("Y-m-d"),
                'progression' => 0,
                'date_fin' => date("Y-m-d")
            ]);
            $_SESSION['notification'] = "Vous êtes maintenant inscrit à la mission !";
        }

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle mission unsubscribe
if (isset($_POST['DELETE'])) {
    $posts_id = $_POST['DELETE'];
    $benevole_id = $_SESSION['Identifiant'];
    $action = $_POST['submit'];

    if ($action == "se désinscrire") {
        // Vérification si l'utilisateur est inscrit
        $checkQuery = $db->prepare("
            SELECT COUNT(*) FROM gt_Inscription 
            WHERE Posts_id = :posts_id AND benevole_id = :benevole_id
        ");
        $checkQuery->execute([
            'posts_id' => $posts_id,
            'benevole_id' => $benevole_id
        ]);
        $count = $checkQuery->fetchColumn();

        if ($count == 0) {
            $_SESSION['notification'] = "Vous n'êtes pas inscrit à cette mission.";
        } else {
            $deleteQuery = $db->prepare("
                DELETE FROM gt_Inscription 
                WHERE Posts_id = :posts_id AND benevole_id = :benevole_id
            ");
            $deleteQuery->execute([
                'posts_id' => $posts_id,
                'benevole_id' => $benevole_id
            ]);
            $_SESSION['notification'] = "Vous êtes maintenant désinscrit de la mission.";
        }

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>GiveTime - Espace Bénévole</title>
    
    <!-- Add notification styling directly in the head -->
    <style>
        .notification {
            position: fixed;
            top: 100px;
            left: 20px;
            background-color: var(--secondary);
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s, transform 0.3s;
        }
        
        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<?php
// Include these files AFTER handling redirects
include("../include/header.php");
include("Accueil.php");
?>
    
<div class="container">
    <div class="content">
        <!-- Section des missions disponibles -->
        <section class="left-section">
            <h1>S'inscrire à une mission</h1>
            
            <div id="scrollerDedans">
                <?php
                // Récupération des missions disponibles
                $missionsQuery = $db->prepare("
                    SELECT * FROM gt_Posts 
                    JOIN gt_association ON gt_Posts.association_id = gt_association.id_association
                    ORDER BY gt_Posts.id_Posts DESC
                ");
                $missionsQuery->execute();
                $missions = $missionsQuery->fetchAll();
                
                // Affichage des missions
                foreach ($missions as $mission) { 
                ?>
                    <form method="post" action="">
                        <h2 class="mission-title"><?= htmlspecialchars($mission['TitrePosts']) ?></h2>
                        <div class="organization-name"><?= htmlspecialchars($mission['nomAssociation']) ?></div>
                        <p class="mission-description"><?= htmlspecialchars($mission['Description']) ?></p>
                        <span class="tag"><?= htmlspecialchars($mission['tag']) ?></span>
                        
                        <input type="hidden" name="INSCRIPTION" value="<?= $mission["id_Posts"]; ?>">
                        <button type="submit" name="submit" value="s'inscrire ! " class="btn-signup">S'inscrire</button>
                    </form>
                <?php
                }
                ?>
            </div>
        </section>

        <!-- Section des missions suivies -->
        <section class="right-section">
            <h1>Mes missions suivies</h1>
            
            <?php 
            $id_benevole = $_SESSION['Identifiant'];
            $suiviesQuery = $db->prepare("
                SELECT * FROM gt_Inscription 
                JOIN gt_Posts ON gt_Inscription.Posts_id = gt_Posts.id_Posts 
                JOIN gt_Association ON gt_Posts.association_id = gt_association.id_association 
                WHERE benevole_id = :benevole_id
                ORDER BY gt_Inscription.date_debut DESC
            ");
            $suiviesQuery->execute(['benevole_id' => $id_benevole]);
            $missionsSuivies = $suiviesQuery->fetchAll();
            
            if (count($missionsSuivies) > 0) {
                foreach ($missionsSuivies as $mission) {
                ?>
                    <div class="MissionSuivie">
                        <ul>
                            <li>
                                <?= htmlspecialchars($mission['TitrePosts']) ?>
                                <b><?= htmlspecialchars($mission['nomAssociation']) ?></b>
                            </li>
                        </ul>
                        <div class="actions">
                            <form action="../Messagerie/MessagerieBenevole.php" method="post">
                                <button type="submit" name="idMessageForm" value="<?= $mission['id_Posts'] ?>" 
                                        class="btn-message">Mes messages</button>
                            </form>
                            <form method="post" action="">
                                <input type="hidden" name="DELETE" value="<?= $mission["id_Posts"] ?>">
                                <button type="submit" name="submit" value="se désinscrire" 
                                        class="btn-unsubscribe">Se désinscrire</button>
                            </form>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo '<p class="no-missions">Vous ne suivez aucune mission actuellement.</p>';
            }
            ?>
        </section>
    </div>
</div>

<?php include("../include/footer.php"); ?>

<!-- Système de notification -->
<div id="notification" class="notification"></div>

<script>
    function showNotification(message, duration = 4000) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.classList.add('show');
        
        setTimeout(() => {
            notification.classList.remove('show');
            // Clear notification text after it's hidden
            setTimeout(() => {
                notification.textContent = '';
            }, 300);
        }, duration);
    }
    
    // Afficher les notifications au chargement de la page si nécessaires
    <?php if(isset($_SESSION['notification'])) : ?>
        showNotification("<?= htmlspecialchars($_SESSION['notification']) ?>");
        <?php unset($_SESSION['notification']); ?>
    <?php endif; ?>
</script>
</body>
</html>