<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="style.css">

</head>
<body>

<?php


include "bdd.php";  
echo "Bdd connecté";


allItems($db , "users");
allItems($db , "messages");
allItems($db , "groupes");
allItems($db , "posts");


// afficherMessage($db, 1);

afficherGLOBAL($db, 3); // on entre en 2e paramettre le filtre (ID-users benevole pour le moment)
afficherGLOBAL($db, 4); // on entre en 2e paramettre le filtre (ID-users benevole pour le moment)

//------------------------------------------------------------------------------------------------------- 
// ---------------------------------------------- FONCTIONS----------------------------------------------
//-------------------------------------------------------------------------------------------------------






function afficherGLOBAL($db, $filtre){
    
    echo "<div class='cube'>";
    
    afficherGroupe($db, $filtre); 
    AfficherMessage($db, $filtre);

    echo "</div>";
}





// affiche toutes les entrées de la table specifié
function allItems($db, $table){
    
    
    $CourStatement=$db -> prepare("SELECT * FROM $table");
    $CourStatement -> execute();
    $donnees=$CourStatement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2> table=  $table </h2><br>"; // annonce de quelle table l'item proviens
    
    foreach ($donnees as $cours) {
        echo "<div class='cube'>";

        foreach($cours as $column => $value){
            echo "$column: $value";
            echo "<br>";

        }
        echo "</div>";
    }  


}






// afficher un groupe:
function afficherGroupe($db, $filtre){

    echo "<h2>afficher groupe</h2>"; 

    $CourStatement=$db -> prepare("SELECT * FROM groupes 
                                    INNER JOIN users 
                                    ON groupes.id_usersCrea = users.id_users 
                                    INNER JOIN posts 
                                    ON groupes.id_post = posts.id_posts
                                    WHERE id_usersCrea = $filtre");
                                    
    $CourStatement -> execute();
    $donnees=$CourStatement->fetchAll(PDO::FETCH_ASSOC);
    
    

    foreach ($donnees as $data) {


        echo "<ul>
                <h3>$data[titre]</h3>
                <p class='$data[type]'>$data[nom] $data[date_crea]</p>
                <a href=''>$data[email]</a>

                <p>$data[content]</p>

                <p><b> Skills Requis: </b></p>
                <li>$data[skills]</li>

            </ul>";
        
        echo "<button action='submit'> Regarder </button> ";
    }  
}









function AfficherMessage($db, $filtre){
    
    echo "<h2> Afficher Messages </h2><br>"; // annonce de quelle table l'item proviens

    $CourStatement=$db -> prepare("SELECT *
                                    FROM messages
                                    INNER JOIN groupes
                                    ON messages.id_message = groupes.id_groupe 
                                    INNER JOIN users
                                    ON messages.id_users = users.id_users 
                                    WHERE users.id_users = $filtre
                                    ORDER BY messages.date_crea" );
                        // Users.id doit etre l'ID du destinataire.
                            
    $CourStatement -> execute();
    $donnees=$CourStatement->fetchAll(PDO::FETCH_ASSOC);

    
    echo "<div class='ZoneMessage'>" ;

    foreach ($donnees as $data) {


        echo "<p class='$data[notification]'> $data[date_crea]<br>
        <b>$data[nom]$data[prenom]</b> vous envoye: <br>
         $data[contenu] </p>";
        
    }
    echo "</div>";
}


?>



<!-- ****** AJOUTER COURS ****** -->
<!-- <h2> Ajout d'un nouveau Cour</h2>

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
</form> -->




</body>
</html>