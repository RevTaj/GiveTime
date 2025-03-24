<?php
// Inclusion de la connexion à la base de données
include("../bdd.php");

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Variable pour stocker les messages d'erreur ou de succès
$message = '';

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Déterminer quel formulaire a été soumis
    if (isset($_POST['submitBenevole'])) {
        // Traitement du formulaire bénévole
        $nom = htmlspecialchars($_POST['nomBenevole']);
        $prenom = htmlspecialchars($_POST['prenomBenevole']);
        $mail = htmlspecialchars($_POST['mailBenevole']);
        $login = htmlspecialchars($_POST['loginBenevole']);
        $mdp = sha1($_POST['mdpBenevole']); // Hachage SHA1 du mot de passe
        $localisation = htmlspecialchars($_POST['localisationBenevole']);
        $description = htmlspecialchars($_POST['descriptionBenevole']);
        
        // Traitement des tags - Prendre seulement le premier tag sélectionné
        // car la colonne est un ENUM qui n'accepte qu'une seule valeur
        $tag = '';
        if (isset($_POST['tagBenevole']) && is_array($_POST['tagBenevole']) && !empty($_POST['tagBenevole'])) {
            $tag = $_POST['tagBenevole'][0]; // Premier tag uniquement
        }
        
        // Traitement de l'image
        $image = 'default-profile.png'; // Image par défaut
        
        if (isset($_FILES['photoBenevole']) && $_FILES['photoBenevole']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photoBenevole']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                // Créer le répertoire s'il n'existe pas
                if (!file_exists('../images/profils/')) {
                    mkdir('../images/profils/', 0777, true);
                }
                
                $newname = uniqid() . '.' . $ext;
                $destination = '../images/profils/' . $newname;
                
                if (move_uploaded_file($_FILES['photoBenevole']['tmp_name'], $destination)) {
                    $image = $newname;
                }
            }
        }
        
        // Vérification que le login n'existe pas déjà
        $stmt = $db->prepare("SELECT COUNT(*) FROM gt_benevole WHERE loginBenevole = ?");
        $stmt->execute([$login]);
        
        if ($stmt->fetchColumn() > 0) {
            $message = "Ce login est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            try {
                // Insertion dans la base de données
                $stmt = $db->prepare("INSERT INTO gt_benevole (nomBenevole, prenomBenevole, mailBenevole, loginBenevole, mdpBenevole, tag, localisation, description, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $result = $stmt->execute([$nom, $prenom, $mail, $login, $mdp, $tag, $localisation, $description, $image]);
                
                if ($result) {
                    $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    if (count($_POST['tagBenevole']) > 1) {
                        $message .= " Note: seul le premier tag a été enregistré.";
                    }
                    // Redirection possible
                    // header('Location: ../connexion.php');
                    // exit;
                } else {
                    $message = "Erreur lors de l'insertion: " . implode(", ", $stmt->errorInfo());
                }
            } catch (PDOException $e) {
                $message = "Erreur de base de données: " . $e->getMessage();
            }
        }
    } 
    elseif (isset($_POST['submitAssociation'])) {
        // Traitement similaire pour l'association
        $nom = htmlspecialchars($_POST['nomAssociation']);
        $mail = htmlspecialchars($_POST['mailAssociation']);
        $login = htmlspecialchars($_POST['loginAssociation']);
        $mdp = sha1($_POST['mdpAssociation']); // Hachage SHA1 du mot de passe
        $localisation = htmlspecialchars($_POST['localisationAssociation']);
        $description = htmlspecialchars($_POST['descriptionAssociation']);
        
        // Traitement des tags - Prendre seulement le premier tag sélectionné
        // car la colonne est un ENUM qui n'accepte qu'une seule valeur
        $tag = '';
        if (isset($_POST['tagAssociation']) && is_array($_POST['tagAssociation']) && !empty($_POST['tagAssociation'])) {
            $tag = $_POST['tagAssociation'][0]; // Premier tag uniquement
        }
        
        // Traitement de l'image
        $image = 'default-profile.png'; // Image par défaut
        
        if (isset($_FILES['photoAssociation']) && $_FILES['photoAssociation']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photoAssociation']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                // Créer le répertoire s'il n'existe pas
                if (!file_exists('../images/profils/')) {
                    mkdir('../images/profils/', 0777, true);
                }
                
                $newname = uniqid() . '.' . $ext;
                $destination = '../images/profils/' . $newname;
                
                if (move_uploaded_file($_FILES['photoAssociation']['tmp_name'], $destination)) {
                    $image = $newname;
                }
            }
        }
        
        // Vérification que le login n'existe pas déjà
        $stmt = $db->prepare("SELECT COUNT(*) FROM gt_association WHERE loginAssociation = ?");
        $stmt->execute([$login]);
        
        if ($stmt->fetchColumn() > 0) {
            $message = "Ce login est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            try {
                // Insertion dans la base de données
                $stmt = $db->prepare("INSERT INTO gt_association (nomAssociation, mailAssociation, loginAssociation, mdpAssociation, tag, localisation, description, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                
                $result = $stmt->execute([$nom, $mail, $login, $mdp, $tag, $localisation, $description, $image]);
                
                if ($result) {
                    $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    if (count($_POST['tagAssociation']) > 1) {
                        $message .= " Note: seul le premier tag a été enregistré.";
                    }
                    // header('Location: ../connexion.php');
                    // exit;
                } else {
                    $message = "Erreur lors de l'insertion: " . implode(", ", $stmt->errorInfo());
                }
            } catch (PDOException $e) {
                $message = "Erreur de base de données: " . $e->getMessage();
            }
        }
    }
}

// Inclusion du formulaire HTML
include("Inscription.html");
?>