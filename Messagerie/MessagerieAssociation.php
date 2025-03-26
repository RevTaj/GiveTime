<?php
// Adding session_start() at the beginning is essential!
session_start();

// IMPORTANT: This was the issue - update session variable BEFORE processing form submission
if (isset($_POST['idMessageForm'])) {
    $_SESSION['idMissionForm'] = $_POST['idMessageForm'];
}

// Process message submission AFTER updating session variable
if (isset($_POST['texte']) && !empty($_POST['texte'])) {
    $ContenuMessage = trim($_POST['texte']);
    $missionId = $_SESSION['idMissionForm'] ?? null;
    
    if ($missionId) {
        include "../bdd.php"; // Include database connection for processing
        
        $sqlInsertQuery = $db->prepare("INSERT INTO gt_message (contenu, id_posts, notification) 
                                      VALUES (:contenu, :id_posts, :notification)");
        $sqlInsertQuery->execute([
            'contenu' => $ContenuMessage,
            'id_posts' => $missionId,
            'notification' => "non-lu"
        ]);
        
        // Message deletion logic remains unchanged
        $nombreMessageMax = 50;
        $countQuery = $db->prepare("SELECT COUNT(*) AS total FROM gt_message WHERE id_posts = :id_posts");
        $countQuery->execute(['id_posts' => $missionId]);
        $result = $countQuery->fetch();
        
        if ($result['total'] > $nombreMessageMax) {
            $deleteQuery = $db->prepare("DELETE FROM gt_message 
                                       WHERE id_message IN (
                                           SELECT id_message FROM (
                                               SELECT id_message FROM gt_message 
                                               WHERE id_posts = :id_posts
                                               ORDER BY date ASC
                                               LIMIT :excess
                                           ) AS subquery
                                       )");
            $excess = $result['total'] - $nombreMessageMax;
            $deleteQuery->bindParam(':id_posts', $missionId, PDO::PARAM_INT);
            $deleteQuery->bindParam(':excess', $excess, PDO::PARAM_INT);
            $deleteQuery->execute();
        }
        
        // Redirect with a GET request to prevent form resubmission and maintain correct mission
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
    <link rel="stylesheet" href="../css/styleNotif.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>GiveTime - Messagerie Association</title>
    <style>
        /* Styles for the textarea */
        #ChatInput textarea {
            width: 100%;
            min-height: 80px;
            padding: 10px;
            border: 1px solid var(--border-medium);
            border-radius: var(--border-radius);
            resize: vertical;
            font-family: inherit;
            font-size: 1em;
            transition: var(--transition);
        }
        
        #ChatInput textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(241, 135, 0, 0.2);
            outline: none;
        }
        
        /* Style for preserving line breaks in messages without extra space */
        .message-container p {
            white-space: pre-wrap; /* This preserves line breaks */
            margin: 0;
            padding: var(--spacing-sm);
        }
    </style>
</head>
<body>
    <?php include ('../include/header.php'); ?>
    
    <div class="message-page-content">
        <h1>Messagerie Association</h1>
        
        <?php
        include "../bdd.php";
        
        $idMessageForm = $_SESSION['idMissionForm'] ?? null;
        
        if ($idMessageForm) {
            // Message display code
            $missionQuery = $db->prepare("SELECT TitrePosts FROM gt_posts WHERE id_Posts = :id");
            $missionQuery->execute(['id' => $idMessageForm]);
            $mission = $missionQuery->fetch();
            
            if ($mission) {
                echo '<div class="mission-header">';
                echo '<h2 class="mission-title">Mission : ' . htmlspecialchars($mission['TitrePosts']) . '</h2>';
                echo '</div>';
            }

            $messagesQuery = $db->prepare("SELECT * FROM gt_message
                                          INNER JOIN gt_posts
                                          ON gt_message.id_posts = gt_posts.id_Posts
                                          WHERE gt_posts.id_posts = :idMessageForm
                                          ORDER BY gt_message.date");
            $messagesQuery->execute(['idMessageForm' => $idMessageForm]);
            $messages = $messagesQuery->fetchAll();
        ?>
            
            <div id="Messagerie">
                <?php if (count($messages) > 0) { ?>
                    <div class="message-list">
                        <?php foreach ($messages as $message) { ?>
                            <div class="message-container">
                                <p class="<?php echo htmlspecialchars($message['notification']); ?>">
                                    <?php echo nl2br(htmlspecialchars(trim($message['contenu']))); ?><?php if (isset($message['date'])) { ?><small>Envoyé le <?php echo htmlspecialchars($message['date']); ?></small><?php } ?> <!--- Ajout de nl2br() voir la doc -->
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="empty-message">
                        <p>Aucun message pour cette mission.</p>
                    </div>
                <?php } ?>
            </div>
            
            <!-- Chat input form - making sure it uses POST and not conflicting with mission selection -->
            <form action="" method="POST" id="ChatInput">
                <label for="texte">Entrez votre message :</label>
                <textarea id="texte" name="texte" required placeholder="Envoyer un message..."></textarea>
                <button type="submit">Envoyer</button>
            </form>
            
        <?php } else { ?>
            <div class="no-mission-selected">
                <p>Veuillez sélectionner une mission pour afficher les messages.</p>
            </div>
        <?php } ?>
    </div>
    
    <?php include ('../include/footer.php'); ?>
</body>
</html>