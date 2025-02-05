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
echo "Bdd connecté<br>";

//l'identifiant de la table doit etre un ARRAY (d'ou les [] )

DisplayItem($db, ["users"]); 
// DisplayItem($db, ["messages"]);

?>
<!-- 
<form >
    <label for="identifiant">Votre Identifiant</label>
        <select name="identifiant" id="identifiant-select">

            <?php
                    $donnees = $db->query("SELECT * FROM users");
                    $data = $donnees->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($data as $row) {
                        foreach ($row as $column ) {
                            echo "$column: $value<br>";
                        }
                    }
            ?>


</form> -->


<?php
AfficherMessage($db, 1);
AfficherMessage($db, 2);

//Montre tous les Items d'une table :
function DisplayItem($db, $tables) {
    
    
    foreach ($tables as $table) {

            $donnees = $db->query("SELECT * FROM $table");
            $data = $donnees->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<hr><h2>Table: $table</h2><br>";
            foreach ($data as $row) {
                foreach ($row as $column => $value) {
                    echo "$column: $value<br>";
                }
                echo "<br>";
            }
        } 
    }

function AfficherDiscution($db){


}





function AfficherMessage($db,$Identifiant){
    
    $donnees = $db->query("SELECT nom, prenom, contenu
                            FROM users
                            INNER JOIN messages 
                            ON users.id = messages.id_expediteur WHERE users.id= $Identifiant");
    $data = $donnees->fetchAll(PDO::FETCH_ASSOC);
   
    echo "<hr><h2>Les Messages</h2><br>";
    foreach ($data as $row) {
        foreach ($row as $column => $value) {
            echo "$column: $value<br>";
        }
        echo "<br>";
    }
}


?>

</body>
</html>
