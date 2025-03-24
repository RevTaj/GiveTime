<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>GiveTime - Page Association</title>
    
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
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
    </style>

    <?php
    date_default_timezone_set('UTC');
    session_start();
    // Vérification que l'utilisateur est une association ou un admin
    if (!isset($_SESSION['authentification']) || $_SESSION['authentification'] !== true || $_SESSION['Permission'] < 3) {
        header('Location: ../Connexion/Erreur.php');
        exit;
    }
    ?>
</head>
<body>
<?php 
include("Accueil.php");
?>

<div class="container">

        <div class="content">
            <div class="left-section">
                <h1>Liste des missions disponibles :</h1>

                <div id="scrollerDedans">
                    <?php
                    include("../bdd.php");
                    $filmsStatement = $db->prepare("SELECT * FROM gt_Posts 
                                                    JOIN gt_association
                                                    ON gt_Posts.association_id = gt_association.id_association ");
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
                $Postsuivie = $db->prepare("SELECT gt_Posts.*, 
                                           COUNT(gt_Inscription.Posts_id) as nombre_inscrits 
                                           FROM gt_Posts 
                                           LEFT JOIN gt_Inscription ON gt_Posts.id_Posts = gt_Inscription.Posts_id 
                                           WHERE association_id = :id_association 
                                           GROUP BY gt_Posts.id_Posts");
                $Postsuivie->execute(['id_association' => $id_association]);
                $Posts = $Postsuivie->fetchAll();
                
                if (count($Posts) > 0) {
                    foreach ($Posts as $post) {
                        echo "<div class='MissionSuivie'>";
                        echo "<ul><li>". htmlspecialchars($post['TitrePosts']) ."</li></ul>";
                        echo "<p>Nombre d'inscrits: <strong>". $post['nombre_inscrits'] ."</strong></p>";
                        echo "<form action='../Messagerie/MessagerieAssociation.php' method='Post'>";
                        echo "<button type='submit' name='idMessageForm' value=". $post['id_Posts']. " class='messageBTN'>Voir les messages</button>";
                        echo "</form>";
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
        
        // Afficher les notifications au chargement de la page si nécessaires
        <?php if(isset($_SESSION['notification'])) : ?>
            showNotification("<?= $_SESSION['notification'] ?>");
            <?php unset($_SESSION['notification']); ?>
        <?php endif; ?>
    </script>
</body>
</html>