<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-admin.css">
    <title>Admin - Gestion des Bénévoles</title>
</head>
<body>
    <h1>Gestion des Bénévoles</h1> <!-- Titre centré en haut -->

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
    $Associations = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
        <div class="content">
            <div class="left-section">
                <h2>Liste des Bénévoles :</h2>
                <ul>
                    <?php foreach ($Associations as $Association): ?>
                        <li>
                            <?= htmlspecialchars($Association['prenomBenevole'] . ' ' . $Association['nomBenevole']); ?>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="id_Benevole" value="<?= $Association['id_Benevole']; ?>">
                                <input type="submit" name="delete_Benevole" value="Supprimer">
                            </form>
                            <button onclick="document.getElementById('editForm-<?= $Association['id_Benevole']; ?>').style.display='block'">Modifier</button>
                            <div id="editForm-<?= $Association['id_Benevole']; ?>" style="display:none;">
                                <form method="post" action="">
                                    <input type="hidden" name="id_Benevole" value="<?= $Association['id_Benevole']; ?>">
                                    <p>
                                        <label for="nom">Nom :</label>
                                        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($Association['nomBenevole']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="prenom">Prénom :</label>
                                        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($Association['prenomBenevole']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="email">Email :</label>
                                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($Association['mailBenevole']); ?>" required>
                                    </p>
                                    <p>
                                        <label for="login">Login :</label>
                                        <input type="text" name="login" id="login" value="<?= htmlspecialchars($Association['loginBenevole']); ?>" required>
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
        <a href="pageAdmin.php"><button>Retour</button></a> <!-- Bouton de retour -->
    </div>
    
    
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <h3>GiveTime</h3>
                <p>GiveTime est une plateforme dédiée à la mise en relation des bénévoles et des associations.</p>
            </div>
            <div class="footer-center">
                <h4>Contact</h4>
                <p>Email: contact@givetime.org</p>
                <p>Téléphone: +33 1 23 45 67 89</p>
                <p>Adresse: 123 Rue de la Solidarité, 75000 Paris, France</p>
            </div>
            <div class="footer-right">
                <h4>Suivez-nous</h4>
                <div class="social-links">
                    <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/768px-2023_Facebook_icon.svg.png" alt="Facebook"></a>
                    <a href="#"><img src="https://img.freepik.com/vecteurs-libre/nouvelle-conception-icone-x-du-logo-twitter-2023_1017-45418.jpg" alt="Twitter"></a>
                    <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/768px-Instagram_icon.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 GiveTime. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
