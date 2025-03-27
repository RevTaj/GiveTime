<?php
date_default_timezone_set('UTC');
session_start();

// Vérification que l'utilisateur est une association ou un admin
if (!isset($_SESSION['authentification']) || $_SESSION['authentification'] !== true || $_SESSION['Permission'] < 3) {
    header('Location: ../Connexion/Erreur.php');
    exit;
}

include("../bdd.php");

// Handle ending a mission
if (isset($_POST['END_MISSION'])) {
    $post_id = (int)$_POST['END_MISSION'];
    $association_id = $_SESSION['Identifiant'];
    
    // Verify that this mission belongs to this association
    $checkOwnership = $db->prepare("SELECT id_Posts FROM gt_posts WHERE id_Posts = ? AND association_id = ?");
    $checkOwnership->execute([$post_id, $association_id]);
    
    if ($checkOwnership->rowCount() > 0) {
        // Start a transaction
        $db->beginTransaction();
        
        try {
            // Get all users who were registered for this mission
            $getUsers = $db->prepare("SELECT benevole_id FROM gt_inscription WHERE Posts_id = ?");
            $getUsers->execute([$post_id]);
            $registeredUsers = $getUsers->fetchAll(PDO::FETCH_COLUMN);
            
            // Award 5 points to each registered user
            if (!empty($registeredUsers)) {
                $updatePoints = $db->prepare("UPDATE gt_benevole SET point = point + 5 WHERE id_benevole = ?");
                
                foreach ($registeredUsers as $userId) {
                    $updatePoints->execute([$userId]);
                }
            }
            
            // Delete all registrations for this mission
            $deleteRegistrations = $db->prepare("DELETE FROM gt_inscription WHERE Posts_id = ?");
            $deleteRegistrations->execute([$post_id]);
            
            // Delete the mission itself
            $deleteMission = $db->prepare("DELETE FROM gt_posts WHERE id_Posts = ?");
            $deleteMission->execute([$post_id]);
            
            // Commit the transaction
            $db->commit();
            
            $_SESSION['notification'] = "Mission clôturée avec succès. Les participants ont reçu 5 points.";
        } catch (Exception $e) {
            // Something went wrong, rollback
            $db->rollBack();
            $_SESSION['notification'] = "Erreur lors de la clôture de la mission : " . $e->getMessage();
        }
    } else {
        $_SESSION['notification'] = "Vous n'êtes pas autorisé à clôturer cette mission.";
    }
    
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>GiveTime - Page Association</title>
    
    <style>
        .notification {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #79c6c0;
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
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .endMissionBTN {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        .endMissionBTN:hover {
            background-color: #c0392b;
        }
        
        .messageBTN {
            flex: 1;
        }
    </style>
</head>
<body>

<?php 
include("../include/header.php");
include("Accueil.php");
?>

<div class="container">

        <div class="content">
            <div class="left-section">
                <h1>Liste des missions publiée :</h1>

                <div id="scrollerDedans">
                    <?php
                    include("../bdd.php");
                    $filmsStatement = $db->prepare("SELECT * FROM gt_posts 
                                                    JOIN gt_association
                                                    ON gt_posts.association_id = gt_association.id_association ");
                    $filmsStatement->execute();
                    $films = $filmsStatement->fetchAll();
                    foreach ($films as $film) { 
                    ?>
                        <div class="mission-item">
                            <h2><?= htmlspecialchars($film['TitrePosts']) ?></h2>
                            <b><?= htmlspecialchars($film['nomAssociation']) ?></b>
                            <img src="" alt="">
                            <p><?= htmlspecialchars($film['Description']) ?></p>
                            <li class="tag"><?= htmlspecialchars($film['tag']) ?></li>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="right-section">
                <h1>Statistiques des missions :</h1>
                
                <?php 
                $id_association = $_SESSION['Identifiant'];
                $Postsuivie = $db->prepare("SELECT gt_posts.*, 
                                           COUNT(gt_inscription.Posts_id) as nombre_inscrits 
                                           FROM gt_posts 
                                           LEFT JOIN gt_inscription ON gt_posts.id_Posts = gt_inscription.Posts_id 
                                           WHERE association_id = :id_association 
                                           GROUP BY gt_posts.id_Posts");
                $Postsuivie->execute(['id_association' => $id_association]);
                $Posts = $Postsuivie->fetchAll();
                
                if (count($Posts) > 0) {
                    foreach ($Posts as $post) {
                        echo "<div class='MissionSuivie'>";
                        echo "<ul><li>". htmlspecialchars($post['TitrePosts']) ."</li></ul>";
                        echo "<p>Nombre d'inscrits: <strong>". $post['nombre_inscrits'] ."</strong></p>";
                        
                        echo "<div class='action-buttons'>";
                        
                        // Message button
                        echo "<form action='../Messagerie/MessagerieAssociation.php' method='Post'>";
                        echo "<button type='submit' name='idMessageForm' value=". $post['id_Posts']. " class='messageBTN'>
                            <i class='fas fa-envelope'></i> Voir les messages</button>";
                        echo "</form>";
                        
                        // End mission button
                        echo "<button onclick='confirmEndMission(" . $post['id_Posts'] . ")' class='endMissionBTN'>
                            <i class='fas fa-check-circle'></i> Mettre fin à la mission</button>";
                        
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Vous n'avez pas encore créé de missions.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    
    <?php 
    include("../include/footer.php");
    ?>
    
    <div id="notification" class="notification"></div>
    
    <script>
        function showNotification(message, duration = 4000) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, duration);
        }
        
        // Confirmation dialog for ending a mission
        function confirmEndMission(postId) {
            if (confirm("Êtes-vous sûr de vouloir clore cette mission ? Tous les participants inscrits recevront 5 points.")) {
                // Create a form and submit it programmatically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = ''; // Submit to the same page
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'END_MISSION';
                input.value = postId;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Afficher les notifications au chargement de la page si nécessaires
        <?php if(isset($_SESSION['notification'])) : ?>
            showNotification("<?= $_SESSION['notification'] ?>");
            <?php unset($_SESSION['notification']); ?>
        <?php endif; ?>
    </script>
</body>
</html>