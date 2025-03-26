<?php
// Start session to maintain state
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleNotif.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>GiveTime - Messagerie Bénévole</title>
</head>
<body>

    <?php include ('../include/header.php'); ?>
    
    <div class="message-page-content">

        <h1>Bienvenue sur votre messagerie </h1>

        <div id="Messagerie">
            <?php
            include "../bdd.php";

            if(isset($_POST['idMessageForm'])) {
                $idMessageForm = $_POST['idMessageForm'];
                

                $missionQuery = $db->prepare("SELECT TitrePosts FROM gt_posts WHERE id_Posts = :id");
                $missionQuery->execute(['id' => $idMessageForm]);
                $mission = $missionQuery->fetch();
                
                    echo '<div class="mission-header">';
                    echo '<h2 class="mission-title">Mission : ' . htmlspecialchars($mission['TitrePosts']) . '</h2>';
                    echo '</div>';
                

                $messagesQuery = $db->prepare("SELECT * FROM gt_message
                                            INNER JOIN gt_posts
                                            ON gt_message.id_posts = gt_posts.id_Posts
                                            WHERE gt_posts.id_posts = :idMessageForm
                                            ORDER BY gt_message.date");
                $messagesQuery->execute(['idMessageForm' => $idMessageForm]);
                $messages = $messagesQuery->fetchAll();
                
                if (count($messages) > 0) {
                    echo '<div class="message-list">';
                    foreach($messages as $message) {
                        echo '<div class="message-container">';
                        echo '<p class="' . htmlspecialchars($message['notification']) . '">';
                        echo htmlspecialchars($message['contenu']);
                        
                        if(isset($message['date'])) {
                            echo '<small>Envoyé le ' . htmlspecialchars($message['date']) . '</small>';
                        }
                        
                        echo '</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="empty-message">';
                    echo '<p>Aucun message pour cette mission.</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="empty-message">';
                echo '<p>Veuillez sélectionner une mission pour afficher les messages.</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    

    <?php include ('../include/footer.php'); ?>
</body>
</html>