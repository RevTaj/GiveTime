<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajoute</title>
    
    <?php 
        
        include("bdd.php");

    $contact= $_POST["IDcontact"];
    $content = $_POST["contentMessage"];
    $expediteur = $_POST["IDexpediteur"];
    
echo $contact,"<br>";
echo $content,"<br>";


$sqlInsertQuery =   "INSERT INTO messages (contenu, date_envoi, id, id_destinataire, id_expediteur, lu)
                    VALUES (:content, :dateEnvoi, :id, :id_destinataire ,:id_expediteur, :lu)";

$insererCours = $db->prepare($sqlInsertQuery);

$insererCours->execute(array(
    'content'=> $content,
    'dateEnvoi'=> date("m/d/y h:i:s a" ,time()),
    'id'=> 11,
    'id_destinataire' => $contact ,
    'id_expediteur'=> $expediteur,
    'lu'=> 1)    );

 //retour a la page en fonction de l'utilisateur 

        header('Location: message.php');
?> 

</head>
<body>
    

</body>
</html>