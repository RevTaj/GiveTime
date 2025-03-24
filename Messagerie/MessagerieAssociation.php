<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-messagerie.css">
    <title>Document</title>
</head>
<body>

<a href="../page/pageAssociation.php">Retour a ma page d'accueil</a>

<?php
session_start(); // Démarre la session pour stocker temporairement les données

include "../bdd.php";

// Si idMissionForm est transmis via POST, on le stocke dans une session
if (isset($_POST['idMissionForm'])) {
    $_SESSION['idMissionForm'] = $_POST['idMissionForm'];
}

$idMessageForm = $_SESSION['idMissionForm'] ?? null; // Récupère la valeur depuis la session

if ($idMessageForm) {
    // Requête pour récupérer les messages associés
    $filmsStatement = $db->prepare("SELECT * FROM gt_message
                                    INNER JOIN gt_posts
                                    ON gt_message.id_posts = gt_posts.id_Posts
                                    WHERE gt_posts.id_posts = :idMessageForm
                                    ORDER BY gt_message.date");
    $filmsStatement->execute(['idMessageForm' => $idMessageForm]);
    $films = $filmsStatement->fetchAll();

    // Afficher les messages récupérés
    foreach ($films as $messages) {
        echo "<p class='".$messages['notification']."'>".$messages['contenu']."</p>";
    }
}
?>

<!-- Formulaire d'envoi de message -->
<form action="" method="POST">
    <label for="texte">Entrez un texte :</label>
    <input type="text" id="texte" name="texte" required>
    <input type="hidden" name="idMissionForm" value="<?php echo htmlspecialchars($idMessageForm); ?>">
    <button type="submit">Envoyer</button>
</form>

<?php
if (isset($_POST['texte']) && !empty($_POST['texte'])) {
    $ContenuMessage = $_POST['texte'];

    // Insérer le message dans la base de données
    $sqlInsertQuery = $db->prepare("INSERT INTO gt_message (contenu, id_posts, notification) 
                                    VALUES (:contenu, :id_posts, :notification)");
    $sqlInsertQuery->execute([
        'contenu' => $ContenuMessage,
        'id_posts' => $idMessageForm,
        'notification' => "non-lu"
    ]);

    // **Supprimer les anciens messages si plus de 50**
    $countQuery = $db->prepare("SELECT COUNT(*) AS total FROM gt_message WHERE id_posts = :id_posts");
    $countQuery->execute(['id_posts' => $idMessageForm]);
    $result = $countQuery->fetch();


    // debugging
    $nombreMessageMax = 50;

    if ($result['total'] > $nombreMessageMax) {
        // Supprimer les messages les plus anciens jusqu'à ce qu'il n'en reste que 50
        $deleteQuery = $db->prepare("DELETE FROM gt_message 
                                     WHERE id_message IN (
                                         SELECT id_message FROM (
                                             SELECT id_message FROM gt_message 
                                             WHERE id_posts = :id_posts
                                             LIMIT :excess
                                         ) AS subquery
                                     )");
        $excess = $result['total'] - $nombreMessageMax;
        $deleteQuery->bindParam(':id_posts', $idMessageForm, PDO::PARAM_INT);
        $deleteQuery->bindParam(':excess', $excess, PDO::PARAM_INT);
        $deleteQuery->execute();
    }

    // Rediriger pour éviter une double soumission
    header("Location: " . $_SERVER['PHP_SELF']); // Recharge la page
    exit; // Arrête l'exécution du script
}
?>


</body>
</html> 