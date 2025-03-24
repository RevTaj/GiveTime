<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-admin.css">
    <title>Admin - Gestion des Bénévoles</title>  
    
    <?php    
        include ('../include/header.php');
    ?>
</head>
<body>
    <h1>Gestion des Bénévoles</h1> <!-- Titre centré en haut -->
    <?php
    // Afficher les erreurs pour le débogage
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($_SESSION['authentification'] == false || $_SESSION['Permission'] < 4) {
        header('Location: ../Connexion/Erreur.php');
        exit;
    }

    include("../bdd.php");

    // ---------------------------------------------------------- Ajouter un Bénévole
    if (isset($_POST['add_Benevole'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $mdp = sha1($_POST['mdp']);

        // Vérification des données
        if (empty($nom) || empty($prenom) || empty($email) || empty($login) || empty($mdp)) {
            echo "Tous les champs sont obligatoires.";
        } else {
            $sqlInsertBenevole = "INSERT INTO GT_Benevole (nomBenevole, prenomBenevole, mailBenevole, loginBenevole, mdpBenevole)
                                  VALUES (:nom, :prenom, :email, :login, :mdp)";
            $insertBenevole = $db->prepare($sqlInsertBenevole);
            $result = $insertBenevole->execute(array(
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':login' => $login,
                ':mdp' => $mdp
            ));
            
            if ($result) {
                echo "Benevole ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout du bénévole.";
            }
        }
    }





    //----------------------------------------------------------  Modifier un Bénévole
    if (isset($_POST['update_Benevole'])) {
        $id_Benevole = $_POST['id_Benevole'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        if (!empty($_POST['mdp'])) {
            $mdp = sha1($_POST['mdp']);
        } else {
            $mdp = null;
        }

        // Construire la requête SQL
        $sqlUpdateBenevole = "UPDATE GT_Benevole SET nomBenevole = :nom, prenomBenevole = :prenom, mailBenevole = :email, loginBenevole = :login";
        $params = array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':login' => $login,
            ':id_Benevole' => $id_Benevole
        );
        if (!empty($mdp)) {
            $sqlUpdateBenevole .= ", mdpBenevole = :mdp";
            $params[':mdp'] = $mdp;
        }
        $sqlUpdateBenevole .= " WHERE id_Benevole = :id_Benevole";

        // Préparer et exécuter la requête
        $sqlUpdateBenevole = $db->prepare($sqlUpdateBenevole);
        $result = $sqlUpdateBenevole->execute($params);

        if ($result) {
            echo "Benevole modifié avec succès.";
        } else {
            echo "Erreur lors de la modification de l'Bénévole.";
        }
    }



    

    //----------------------------------------------------------   Supprimer un Bénévole et ses inscriptions associées
    if (isset($_POST['delete_Benevole'])) {
        $id_Benevole = $_POST['id_Benevole'];

        // Supprimer les inscriptions associées aux Posts de l'Bénévole
        $sqlDeleteInscriptions = "DELETE GT_Inscription FROM GT_Inscription JOIN GT_Posts ON GT_Inscription.Posts_id = GT_Posts.id_Posts WHERE GT_Inscription.Benevole_id = $id_Benevole";
        $deleteInscriptions = $db->prepare($sqlDeleteInscriptions);
        $deleteInscriptions->execute();
        

        // Supprimer l'Bénévole
        $sqlDeleteBenevole = "DELETE FROM GT_Benevole WHERE id_Benevole = :id_Benevole";
        $deleteBenevole = $db->prepare($sqlDeleteBenevole);
        $deleteBenevole->execute(array(':id_Benevole' => $id_Benevole));

        echo "Benevole et ses inscriptions associées supprimés avec succès.";
    }




    //----------------------------------------------------------  Récupérer la liste des Bénévoles
    $query = $db->prepare("SELECT * FROM GT_Benevole");
    $query->execute();
    $benevoles = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
        <div class="content">
            <div class="left-section">
                <h2>Liste des Bénévoles :</h2>
                <div class="scrollable-list">
                    <ul>
                        <?php foreach ($benevoles as $benevole): ?>
                            <li>
                                <?= htmlspecialchars($benevole['prenomBenevole'] . ' ' . $benevole['nomBenevole']); ?>
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="id_Benevole" value="<?= $benevole['id_benevole']; ?>">
                                    <input type="submit" name="delete_Benevole" value="Supprimer">
                                </form>
                                <button onclick="document.getElementById('editForm-<?= $benevole['id_benevole']; ?>').style.display='block'">Modifier</button>
                                <div id="editForm-<?= $benevole['id_benevole']; ?>" style="display:none;">
                                    <form method="post" action="">
                                        <input type="hidden" name="id_Benevole" value="<?= $benevole['id_benevole']; ?>">
                                        <p>
                                            <label for="nom">Nom :</label>
                                            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($benevole['nomBenevole']); ?>" required>
                                        </p>
                                        <p>
                                            <label for="prenom">Prénom :</label>
                                            <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($benevole['prenomBenevole']); ?>" required>
                                        </p>
                                        <p>
                                            <label for="email">Email :</label>
                                            <input type="email" name="email" id="email" value="<?= htmlspecialchars($benevole['mailBenevole']); ?>" required>
                                        </p>
                                        <p>
                                            <label for="login">Login :</label>
                                            <input type="text" name="login" id="login" value="<?= htmlspecialchars($benevole['loginBenevole']); ?>" required>
                                        </p>
                                        <p>
                                            <label for="mdp">Mot de passe (laisser vide pour ne pas changer) :</label>
                                            <input type="password" name="mdp" id="mdp">
                                        </p>
                                        <p>
                                            <input type="submit" name="update_Benevole" value="Modifier">
                                        </p>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="right-section">
                <h2>Ajouter un Bénévole :</h2>
                <form action="" method="POST" id="add_Benevole_form">
                    <p>
                        <label for="nom">Nom :</label>
                        <input type="text" name="nom" id="nom" required>
                    </p>
                    <p>
                        <label for="prenom">Prénom :</label>
                        <input type="text" name="prenom" id="prenom" required>
                    </p>
                    <p>
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" required>
                    </p>
                    <p>
                        <label for="login">Login :</label>
                        <input type="text" name="login" id="login" required>
                    </p>
                    <p>
                        <label for="mdp">Mot de passe :</label>
                        <input type="password" name="mdp" id="mdp" required>
                    </p>
                    <p>
                        <input type="submit" name="add_Benevole" value="Ajouter">
                    </p>
                </form>
            </div>
        </div>
        <div class="admin-link">
        <a href="pageAdmin.php" class="btn">Retour</a> <!-- Bouton de retour -->
        </div></div>
    
    
    
    <?php
        include ('../include/footer.php');
    ?>
</body>
</html>
