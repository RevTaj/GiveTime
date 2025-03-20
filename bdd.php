<?php 

try {
    $db = new PDO('mysql:host=localhost;dbname=bdd_givetime', 'root', 'root');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

?>