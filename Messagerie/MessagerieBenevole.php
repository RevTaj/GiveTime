<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleNotif.css">
    <title>Document</title>
</head>
<body>
    <a href="../page/page1.php">Retour a ma page d'accueil</a>
    <h1> Bienvenu sur messagerie benevole</h1>
   
<?php
   
include "../bdd.php";
$idMessageForm = $_POST['idMessageForm'];
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

</body>
</html>