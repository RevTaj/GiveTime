<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
include("../bdd.php");

$login = strip_tags($_POST['login']);
$mdp = strip_tags($_POST['mdp']);

if (!empty($login) && !empty($mdp)) {
    $User = $db->prepare("SELECT loginBenevole,mdpBenevole,id_benevole FROM GT_Benevole WHERE loginBenevole=? AND mdpBenevole=?");
    $User->execute([$login, sha1($mdp)]);
    $test = $User->fetch();

    if (!empty($test)) {
        session_start();
        $_SESSION['login'] = $login;
        $_SESSION['authentification'] = true;
        $_SESSION['Permission'] = 2;
        $_SESSION['Identifiant'] = $test['id_benevole'];
        $_SESSION['date'] = date("Y-m-d");
        header('location: ../Page/page1.php');
    }else {
        header('location: ../index/index.php?msg="Erreur de login / Erreur de mot de passe"');
    }
}
/*
if (isset($login) && isset($mdp)) {
    $User = $db->prepare("SELECT loginbenevole,mdpbenevole FROM SAE203_benevole WHERE loginbenevole=? AND mdpbenevole=?");
    $User->execute([$login, sha1($mdp)]);
    $test = $User->fetch();

    if ($test) {
        session_start();
        $_SESSION['login'] = $login;
        $_SESSION['authentification'] = true;
        $_SESSION['ID'] = 3; // Par défaut l'id = 1 donc n+1 pour chaque rôle. 
        $_SESSION['date'] = date("Y-m-d");
        header('location: page1.php');
    }else {
        header('location: index.php?msg="Erreur de login / Erreur de mot de passe"');
    }
}*/

?>
</body>
</html>
