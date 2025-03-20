<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<?php
include "../bdd.php";

$idMessageForm = $_POST['idMissionForm'];

// echo "$idMessageForm";

$filmsStatement = $db->prepare("SELECT * FROM gt_message
                                INNER JOIN gt_posts
                                ON gt_message.id_posts = gt_posts.id_Posts
                                WHERE gt_posts.id_posts = $idMessageForm
                                ORDER BY gt_message.date
                                ");
$filmsStatement->execute();
$films = $filmsStatement->fetchAll();

foreach ($films as $messages) {
    echo "<p class=".$messages['notification'].">".$messages['contenu']."</p>";
    }
?>

 


<form action="envoieMessage.php" method="POST">
        <label for="texte">Entrez un texte :</label>
        <input type="text" id="texte" name="texte" required>
        <button type="submit">Envoyer</button>
    </form>

</body>
</html>