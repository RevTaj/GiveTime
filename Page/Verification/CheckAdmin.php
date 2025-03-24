<?php
session_start();

function redirectWithError($message, $redirect = "../index.html") {
    echo "<script>
            alert('$message');
            window.location.href = '$redirect';
          </script>";
    exit;
}

if(!isset($_SESSION['authentification']) || $_SESSION['authentification'] !== true) {
    redirectWithError("Vous devez être connecté pour accéder à cette page.");
}

if(!isset($_SESSION['type']) || $_SESSION['type'] !== 'admin') {
    redirectWithError("Cette page est réservée aux administrateurs. Vous n'avez pas les permissions nécessaires.", "../index.html");
}

define("IS_ADMIN", true);

?>