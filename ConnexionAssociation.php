<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1);
echo '<hr> <h2> Connexion à bdd réussi </h2>';
echo("Bonjour ");

include("bdd.php");

$login = strip_tags($_POST['login']);
$mdp = strip_tags($_POST['mdp']);


/*
if (!empty($login) && !empty($mdp)) {
    $User = $db->prepare("SELECT * FROM SAE203_Enseignant WHERE loginEnseignant=? AND mdpEnseignant=?");
    $User->execute([$login, sha1($mdp)]);
    $test = $User->fetch();

    if ($test) {
        session_start();
        $_SESSION['login'] = $login;
        $_SESSION['authentification'] = true;
        $_SESSION['ID'] = 3;
        $_SESSION['date'] = date("Y-m-d");
        header('location: pageProf.php');
    }else {
        header('location: index2.php?msg="Erreur de login / Erreur de mot de passe"');
    }
}
*/


if (isset($login) && isset($mdp)) {
    $User = $db->prepare("SELECT * FROM gt_association WHERE loginAssociation=? AND mdpAssociation=?");
    $User->execute([$login, sha1($mdp)]);
    $test = $User->fetch();

    if ($test) {
        session_start();
        $_SESSION['login'] = $login;
        $_SESSION['authentification'] = true;
        $_SESSION['Identifiant'] = $test['id_Association'];
        $_SESSION['Permission'] = 3;
        $_SESSION['date'] = date("Y-m-d");
        header('location: pageAssociation.php');
    }else {
        header('location: Form_Association.php?msg="Erreur de login / Erreur de mot de passe"');
    }
}

?>
</body>
</html>
