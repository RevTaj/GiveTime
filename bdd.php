<?php 

try {
    $db = new PDO('mysql:host=localhost;dbname=u358086552_bdd_givetime', 'u358086552_root', 'ReZero240/');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

?>