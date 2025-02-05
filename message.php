<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
include "Connexion.php";  
echo "Bdd connecté<br>";

try {
    displayTableData($db, ["users", "messages"]);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

function displayTableData($db, $tables) {
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT * FROM $table");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<hr>Table: $table<br>";
            foreach ($data as $row) {
                foreach ($row as $column => $value) {
                    echo "$column: $value<br>";
                }
                echo "<br>";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la récupération des données de la table $table : " . $e->getMessage();
        }
    }
}
?>

</body>
</html>
