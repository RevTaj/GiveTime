<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
// Remove debugging output in production
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// echo '<hr> <h2> Connexion à bdd réussi </h2>';
// echo("Bonjour ");

include("../bdd.php");

// Sanitize inputs
$login = isset($_POST['login']) ? strip_tags($_POST['login']) : '';
$mdp = isset($_POST['mdp']) ? strip_tags($_POST['mdp']) : '';

// Check if inputs are not empty
if (empty($login) || empty($mdp)) {
    header('location: ../index/indexAssociation.php?msg="Veuillez remplir tous les champs"');
    exit;
}

// Query the association table specifically
$User = $db->prepare("SELECT * FROM gt_association WHERE loginAssociation = ?");
$User->execute([$login]);
$association = $User->fetch();

// Check if association exists and password matches
if ($association && $association['mdpAssociation'] === sha1($mdp)) {
    // Start session and set association-specific variables
    session_start();
    
    // Clear any existing session data to prevent confusion
    session_unset();
    
    // Set association-specific session variables
    $_SESSION['login'] = $login;
    $_SESSION['authentification'] = true;
    $_SESSION['Identifiant'] = $association['id_Association'];
    $_SESSION['Permission'] = 3; // Association permission level
    $_SESSION['userType'] = 'association'; // Explicitly set user type
    $_SESSION['date'] = date("Y-m-d");
    
    // Redirect to association dashboard
    header('location: ../Page/page1-Asso.php');
    exit;
} else {
    // Invalid credentials
    header('location: ../index/indexAssociation.php?msg="Identifiants incorrects. Veuillez vérifier votre nom d\'utilisateur et mot de passe."');
    exit;
}
?>
</body>
</html>
