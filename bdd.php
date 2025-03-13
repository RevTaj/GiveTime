<?php 

try {
    $db = new PDO('mysql:host=localhost;dbname=givetime_db', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

?>