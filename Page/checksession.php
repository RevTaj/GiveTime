<?php
// Démarrer la session (obligatoire pour accéder aux variables de session)
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['authentification']) || $_SESSION['authentification'] !== true) {
    // L'utilisateur n'est pas connecté, préparer le message et la redirection
    $message = "Vous devez être connecté pour accéder à cette page.";
    
    // On définit l'URL de redirection vers la page d'accueil
    $redirect = "../index.php";
    
    // Création du script JavaScript pour afficher le message et rediriger
    echo "<script>
            alert('$message');
            window.location.href = '$redirect';
          </script>";
    exit;
}

//Reste à rajouter les vérification des perms mais je pense pas que ce soit opti de faire comme ça pour check la session is true or false
?>