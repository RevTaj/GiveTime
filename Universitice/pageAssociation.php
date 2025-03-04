<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/prof.css">
    <title>Gestion des cours</title>
</head>
<body>
    <h1>Gestion des cours</h1> <!-- Titre centré en haut -->
    <?php
    session_start();
    if ($_SESSION['authentification'] == false || $_SESSION['Permission'] < 3 || $_SESSION['Permission'] > 3 ) {
        header('Location: Erreur.php');
        exit;
    }

    include("test.php");
    $id_Prof = $_SESSION['Identifiant'];

    // Récupérer le prénom de l'utilisateur depuis la base de données
    $query = $db->prepare("SELECT prenomEnseignant,nomEnseignant FROM SAE203_Enseignant WHERE id_Enseignant = :id_Prof");
    $query->execute(array(':id_Prof' => $id_Prof));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $prenomEnseignant = $result['prenomEnseignant'];
    $nomEnseignant = $result['nomEnseignant'];
    ?>

    <div class="container">
        <div class="header">
            <div class="welcome-message">
                <?php
                // Afficher le message de bienvenue
                echo "Bonjour " . htmlspecialchars($prenomEnseignant) ." ".htmlspecialchars($nomEnseignant). " !";
                ?>
            </div>
            <a href="deconnexion.php" class="logout">Se déconnecter</a>
        </div>
        <div class="content">
            <div class="left-section">
                <h2>Ajouter un cours :</h2>
                <form action="" method="POST" id="crea">
                    <fieldset>
                        <legend>Veuillez saisir les informations du cours :</legend>
                        <p>
                            <label for="titre">Titre du cours :</label>
                            <input type="text" name="titre" id="titre" required>
                        </p>
                        <p>
                            <label for="tag">Tag du cours :</label>
                            <input type="text" name="tag" id="tag" required>
                        </p>
                        <p>
                            <label for="description">Description du cours :</label>
                            <input type="text" name="description" id="description" required>
                        </p>
                        <p>
                            <input type="submit" value="Créer">
                        </p>
                    </fieldset>
                </form>

                <?php 
                if (isset($_POST['titre'])) { 
                    $sqlCheckQuery = "SELECT COUNT(*) FROM SAE203_Cours WHERE TitreCours = :TitreCours AND Enseignant_id = :Enseignant_id";
                    $checkStmt = $db->prepare($sqlCheckQuery);
                    $checkStmt->execute(array(
                        'TitreCours' => $_POST['titre'],
                        'Enseignant_id' => $id_Prof
                    ));
                    $count = $checkStmt->fetchColumn();

                    if ($count > 0) {
                        echo "Un cours avec ce titre existe déjà pour cet enseignant.";
                    } else {
                        $sqlInsertQuery = "INSERT INTO SAE203_Cours(TitreCours, Enseignant_id, tag, description) VALUES (:TitreCours, :Enseignant_id, :tag, :description)";
                        $insererCours = $db->prepare($sqlInsertQuery);
                        $insererCours->execute(array(
                            'TitreCours' => $_POST['titre'],
                            'Enseignant_id' => $id_Prof,
                            'tag' => $_POST['tag'],
                            'description' => $_POST['description']
                        ));
                        echo "Cours ajouté avec succès.";
                    }
                }
                ?>

                <h2>Supprimer un cours :</h2>
                <form method="post" action="" id="supr">
                    <select name="id_Cours" form="supr">
                        <?php
                        $modif = $db->prepare("SELECT * FROM SAE203_Cours WHERE Enseignant_id = :Enseignant_id");
                        $modif->execute(array(':Enseignant_id' => $id_Prof));
                        $modifs = $modif->fetchAll();
                        foreach ($modifs as $modification) { 
                        ?>
                            <option value="<?= $modification["id_Cours"]; ?>"><?= htmlspecialchars($modification["TitreCours"]); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="supprimer" value="Supprimer">
                    <input type="reset" value="Annuler">
                </form>

                <?php
                if (isset($_POST['supprimer'])) {
                    $id_Cours = $_POST['id_Cours'];

                    $sqlDeleteInscriptionsQuery = "DELETE FROM SAE203_Inscription WHERE cours_id = :id_Cours";
                    $deleteInscriptions = $db->prepare($sqlDeleteInscriptionsQuery);
                    $deleteInscriptions->execute(array(
                        ':id_Cours' => $id_Cours
                    ));

                    $sqlDeleteCoursQuery = "DELETE FROM SAE203_Cours WHERE id_Cours = :id_Cours AND Enseignant_id = :Enseignant_id";
                    $deleteCours = $db->prepare($sqlDeleteCoursQuery);
                    $deleteCours->execute(array(
                        ':id_Cours' => $id_Cours,
                        ':Enseignant_id' => $id_Prof
                    ));

                    echo "Le cours et toutes les inscriptions associées ont été supprimés.";
                }
                ?>
            </div>

            <div class="right-section">
                <h2>Vos cours :</h2>
                <?php 
                $coursuivie = $db->prepare("SELECT * FROM SAE203_Cours WHERE Enseignant_id = :id_Prof ORDER BY tag");
                $coursuivie->execute(array(':id_Prof' => $id_Prof));
                $Cours = $coursuivie->fetchAll();
                foreach ($Cours as $lescourssuivies) {
                    echo "<ul><li>", htmlspecialchars($lescourssuivies['TitreCours']), "</li></ul>";
                }
                ?>

                <h2>Modifier un cours :</h2>
                <form method="post" action="" id="modif">
                    <select name="id_cours" form="modif">
                        <?php
                        $modif = $db->prepare("SELECT * FROM SAE203_Cours WHERE Enseignant_id = :Enseignant_id");
                        $modif->execute(array(':Enseignant_id' => $id_Prof));
                        $modifs = $modif->fetchAll();
                        foreach ($modifs as $modification) { 
                        ?>
                            <option value="<?= $modification["id_Cours"]; ?>"><?= htmlspecialchars($modification["TitreCours"]); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="modifier" value="Modifier">

                </form>

                <?php
                if (isset($_POST['id_cours'])) {
                    $cours_id = $_POST['id_cours'];

                    $query = $db->prepare("SELECT * FROM SAE203_Cours WHERE id_Cours = :id_Cours AND Enseignant_id = :Enseignant_id");
                    $query->execute(array(
                        ':id_Cours' => $cours_id,
                        ':Enseignant_id' => $id_Prof
                    ));
                    $cours = $query->fetch(PDO::FETCH_ASSOC);
                ?>
                    <h2>Modifier le cours : <?= htmlspecialchars($cours['TitreCours']); ?></h2>
                    <form method="post" action="">
                        <input type="hidden" name="id_Cours" value="<?= $cours['id_Cours']; ?>">
                        <p>
                            <label for="titre">Titre du cours :</label>
                            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($cours['TitreCours']); ?>" required>
                        </p>
                        <p>
                            <label for="tag">Tag du cours :</label>
                            <input type="text" name="tag" id="tag" value="<?= htmlspecialchars($cours['tag']); ?>" required>
                        </p>
                        <p>
                            <label for="description">Description du cours :</label>
                            <input type="text" name="description" id="description" value="<?= htmlspecialchars($cours['Description']); ?>" required>
                        </p>
                        <p>
                            <input type="submit" name="update" value="Mettre à jour">
                            <input type="reset" value="Annuler">
                        </p>
                    </form>
                <?php
                }

                if (isset($_POST['update']) && !empty($_POST['id_Cours']) && !empty($_POST['titre']) && !empty($_POST['tag']) && !empty($_POST['description'])) {
                    $sqlUpdateQuery = "UPDATE SAE203_Cours 
                                       SET TitreCours = :TitreCours, tag = :tag, description = :description 
                                       WHERE id_Cours = :id_Cours AND Enseignant_id = :Enseignant_id";

                    $stmt = $db->prepare($sqlUpdateQuery);
                    $stmt->execute(array(
                        ':TitreCours' => $_POST['titre'],
                        ':tag' => $_POST['tag'],
                        ':description' => $_POST['description'],
                        ':id_Cours' => $_POST['id_Cours'],
                        ':Enseignant_id' => $id_Prof
                    ));

                    echo "Cours mis à jour avec succès.";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
