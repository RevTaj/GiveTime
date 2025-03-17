<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fonctions messages</title>
</head>
<body>

<?php


include "../bdd.php";  
// echo "Bdd connecté";


    

    $CourStatement=$db -> prepare("SELECT * FROM gt_inscription");
    $CourStatement -> execute();
    $donnees=$CourStatement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<hr>";
    echo "<hr> <h2> Inscription </h2><br>"; // annonce de quelle table l'item proviens
    foreach ($donnees as $cours) {


        foreach($cours as $column => $value){
            echo "$column: $value";
            echo "<br>";
        }
        echo '<hr>';
    }  

    
    echo "<hr> <h2> Afficher Messages </h2><br>"; // annonce de quelle table l'item proviens

    $CourStatement=$db -> prepare("SELECT *
                                    FROM messages
                                    INNER JOIN users
                                    ON messages.id_message = users.id_users
                                    WHERE users.id_users = $ID_destinataire 
                                    -- ORDER BY date_crea
                                    " );
                        // Users.id doit etre l'ID du destinataire.
                            
    $CourStatement -> execute();
    $donnees=$CourStatement->fetchAll(PDO::FETCH_ASSOC);

    


    foreach ($donnees as $data) {


        echo "<p class='$data[notification]'> $data[date_crea]<br>
        <b>$data[nom]$data[prenom]</b> vous envoye: <br>
         $data[contenu] </p>";
        
    }



?>

<style>

.non-lu{
    color: red;
}
.lu{
    color: green;
}

</style>
<!-- ****** AJOUTER COURS ****** -->
<h2> Ajout d'un nouveau Cour</h2>

<form action="ajouterMessager.php" method="post">
    <fieldset>
        <legend>Nouveau message</legend>
        Pour:       
                    <input type="number" 
                          name="IDcontact" 
                          required> <br>
        de:  
                    <input type="number" 
                        name="IDexpediteur" 
                            required>  <br>
        Contenu 
                    <input type="text" 
                            name="contentMessage" 
                            required>  <br>

                    <input type="submit" 
                            value="ajouter"/>
    </fieldset>
</form>




</body>
</html>