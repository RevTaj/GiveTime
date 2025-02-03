<?php

try{
    $db = new PDO ('mysql:host=localhost;dbname=give-time-db','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch (Exeption $e){
    die ('erreur:' . $e->getMessage());
}


?>