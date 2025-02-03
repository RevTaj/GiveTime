<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php


include "bdd.php";  
echo "Bdd connecté";


allItems($db , "users");
allItems($db , "messages");


// affiche toutes les entrées de la table specifié
function allItems($db, $table){
    

    $CourStatement=$db -> prepare("SELECT * FROM $table");
    $CourStatement -> execute();
    $donnees=$CourStatement->fetchAll();
    
    
    foreach ($donnees as $cours) {
        
        $i = 0;
        
        echo "<hr> $table <br>";
        while ($i < count($cours)/2): // ATTENTION : si bug d'affichage sur votre bdd, enlevez le /2 (bidouillage)
            
            
            echo "$cours[$i] <br>";
            
            $i++;
            
        endwhile;
    }
    
}




?>

</body>
</html>