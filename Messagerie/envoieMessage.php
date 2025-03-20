<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

include '../bdd.php'; // Inclure la connexion à la base de données

// Préparer la requête INSERT avec les valeurs
$sqlInsertQuery = $db->prepare("INSERT INTO gt_message (contenu, id_posts, notification) 
                                VALUES (:contenu, :id_posts, :notification)");

// Exécuter la requête avec les données
$sqlInsertQuery->execute(array(
    'contenu' => $_POST['texte'], // Récupérer la valeur envoyée en POST
    'id_posts' => 8,             // Par exemple, id_posts = 8
    'notification' => "non-lu"   // Notification initialisée à "non-lu"
));

echo "Message inséré avec succès !";
header ('Location: MessagerieAssociation.php');


?>

</body>
</html>
