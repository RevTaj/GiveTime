<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-admin.css">
    <title>Admin - Gestion des GT_Association</title>

    <?php    include ('../include/header.php');
        ?>
</head>
<body>
    <h1>Gestion des Associations</h1> <!-- Titre centré en haut -->
    <a href="../Connexion/deconnexion.php" class="logout">Se déconnecter</a>
    <?php
    session_start();
    // Afficher les erreurs pour le débogage
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($_SESSION['authentification'] == false || $_SESSION['Permission'] < 4) {
        header('Location: ../Connexion/Erreur.php');
        exit;
    }

    include("../bdd.php");
    $id_Admin = $_SESSION['Identifiant'];

    // Récupérer le prénom de l'utilisateur depuis la base de données
    $query = $db->prepare("SELECT prenomAdmin,nomAdmin FROM GT_Admin WHERE id_admin = :id_Admin");
    $query->execute(array(':id_Admin' => $id_Admin));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $prenomAssociation = $result['prenomAdmin'];
    $nomAssociation = $result['nomAdmin'];
    
    // Ajouter un Association
    if (isset($_POST['add_asso'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $mdp = sha1($_POST['mdp']);

        // Vérification des données
        if (empty($nom) || empty($prenom) || empty($email) || empty($login) || empty($mdp)) {
            echo "Tous les champs sont obligatoires.";
        } else {
            $sqlInsertasso = "INSERT INTO GT_Association (nomAssociation, prenomAssociation, mailAssociation, loginAssociation, mdpAssociation)
                              VALUES (:nom, :prenom, :email, :login, :mdp)";
            $insertasso = $db->prepare($sqlInsertasso);
            $result = $insertasso->execute(array(
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':login' => $login,
                ':mdp' => $mdp
            ));
            
            if ($result) {
                echo "Association ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout de l'Association.";
            }
        }
    }

    // Modifier un Association
    if (isset($_POST['update_asso'])) {
        $id_asso = $_POST['id_asso'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        if (!empty($_POST['mdp'])) {
            $mdp = sha1($_POST['mdp']);
        } else {
            $mdp = null;
        }

        // Vérification des données
        if (empty($nom) || empty($prenom) || empty($email) || empty($login)) {
            echo "Tous les champs sont obligatoires, sauf le mot de passe.";
        } else {
            $sqlUpdateasso = "UPDATE GT_Association SET nomAssociation = :nom, prenomAssociation = :prenom, mailAssociation = :email, loginAssociation = :login";
            if ($mdp) {
                $sqlUpdateasso .= ", mdpAssociation = :mdp";
            }
            $sqlUpdateasso .= " WHERE id_Association = :id_asso";
            $updateasso = $db->prepare($sqlUpdateasso);
            $params = array(
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':login' => $login,
                ':id_asso' => $id_asso
            );
            if ($mdp) {
                $params[':mdp'] = $mdp;
            }
            $result = $updateasso->execute($params);
            
            if ($result) {
                echo "Association modifié avec succès.";
            } else {
                echo "Erreur lors de la modification de l'Association.";
            }
        }
    }

    // Supprimer un Association et ses Posts et inscriptions associées
    if (isset($_POST['delete_asso'])) {
        $id_asso = $_POST['id_asso'];

        // Supprimer les inscriptions associées aux Posts de l'Association
        $sqlDeleteInscriptions = "DELETE FROM GT_Inscription WHERE Posts_id IN (SELECT id_Posts FROM GT_Posts WHERE id_Association = :id_asso)";
        $deleteInscriptions = $db->prepare($sqlDeleteInscriptions);
        $deleteInscriptions->execute(array(':id_asso' => $id_asso));

        // Supprimer les Posts de l'Association
        $sqlDeletePosts = "DELETE FROM GT_Posts WHERE Association_id = :id_asso";
        $deletePosts = $db->prepare($sqlDeletePosts);
        $deletePosts->execute(array(':id_asso' => $id_asso));

        // Supprimer l'Association
        $sqlDeleteasso = "DELETE FROM GT_Association WHERE id_Association = :id_asso";
        $deleteasso = $db->prepare($sqlDeleteasso);
        $deleteasso->execute(array(':id_asso' => $id_asso));

        echo "Association et ses Posts et inscriptions associées supprimés avec succès.";
    }

    // Récupérer la liste des Associations
    $query = $db->prepare("SELECT * FROM GT_Association");
    $query->execute();
    $Associations = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
    <div class="welcome-message">
                <?php
                // Afficher le message de bienvenue
                echo "Bonjour " . htmlspecialchars($prenomAssociation) ." ".htmlspecialchars($nomAssociation). " !" ;
                ?>
    </div>
        <div class="content">
            
            <div class="left-section">
                <h2>Liste des Associations :</h2>
                <ul>
                    <?php foreach ($Associations as $Association): ?>
                        <li>
                            <?= htmlspecialchars($Association['prenomAssociation'] . ' ' . $Association['nomAssociation']); ?>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="id_asso" value="<?= $Association['id_Association']; ?>">
                                <input type="submit" name="delete_asso" value="Supprimer">
                            </form>
                            <button onclick="document.getElementById('editForm-<?= $Association['id_Association']; ?>').style.display='block'">Modifier</button>
                            <div id="editForm-<?= $Association['id_Association']; ?>" style="display:none;">
                                <form method="post" action="">
                                    <input type="hidden" name="id_asso" value="<?= $Association['id_Association']; ?>">
                                    <p>
                                        <label for="nom">Nom :</label>
                                        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($Association['nomAssociation']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="prenom">Prénom :</label>
                                        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($Association['prenomAssociation']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="email">Email :</label>
                                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($Association['mailAssociation']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="login">Login :</label>
                                        <input type="text" name="login" id="login" value="<?= htmlspecialchars($Association['loginAssociation']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="mdp">Mot de passe (laisser vide pour ne pas changer) :</label>
                                        <input type="password" name="mdp" id="mdp">
                                    </p>
                                    <p>
                                        <input type="submit" name="update_asso" value="Modifier">
                                    </p>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="right-section">
                <h2>Ajouter un Association :</h2>
                <form action="" method="POST" id="add_asso_form">
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
                        <input type="submit" name="add_asso" value="Ajouter">
                    </p>
                </form>
            </div>
        </div>
        <div class="admin-link">
        <a href="PageAdminBenevole.php" class="btn">Gestion Bénévoles</a>
        </div>
    </div>
    
    
    <?php
        include ('../include/footer.php');
    ?>
</body>
</html>
