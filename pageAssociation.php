<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/prof.css">
    <title>Gestion des Posts</title>
</head>
<body>
    <h1>Gestion des Posts</h1> <!-- Titre centré en haut -->
    <?php
    session_start();
    if ($_SESSION['authentification'] == false || $_SESSION['Permission'] < 3 || $_SESSION['Permission'] > 3 ) {
        header('Location: Erreur.php');
        exit;
    }

    include("bdd.php");
    $id_association = $_SESSION['Identifiant'];

    // Récupérer le prénom de l'utilisateur depuis la base de données
    $query = $db->prepare("SELECT nomAssociation, FROM gt_association WHERE id_Association = :id_Association");
    $query->execute(array(':id_Association' => $id_association));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $nomAssociation = $result['nomAssociation'];
    ?>

    <div class="container">
        <div class="header">
            <div class="welcome-message">
                <?php
                // Afficher le message de bienvenue
                echo "Bonjour " . htmlspecialchars($nomAssociation) ." !";
                ?>
            </div>
            <a href="deconnexion.php" class="logout">Se déconnecter</a>
        </div>
        <div class="content">
            <div class="left-section">
                <h2>Ajouter un Posts :</h2>
                <form action="" method="POST" id="crea">
                    <fieldset>
                        <legend>Veuillez saisir les informations du Posts :</legend>
                        <p>
                            <label for="titre">Titre du Posts :</label>
                            <input type="text" name="titre" id="titre" required>
                        </p>
                        <p>
                            <label for="tag">Tag du Posts :</label>
                            <input type="text" name="tag" id="tag" required>
                        </p>
                        <p>
                            <label for="description">Description du Posts :</label>
                            <input type="text" name="description" id="description" required>
                        </p>
                        <p>
                            <input type="submit" value="Créer">
                        </p>
                    </fieldset>
                </form>

                <?php 
                if (isset($_POST['titre'])) { 
                    $sqlCheckQuery = "SELECT COUNT(*) FROM gt_Posts WHERE TitrePosts = :TitrePosts AND Association_id = :Association_id";
                    $checkStmt = $db->prepare($sqlCheckQuery);
                    $checkStmt->execute(array(
                        'TitrePosts' => $_POST['titre'],
                        'Association_id' => $id_association
                    ));
                    $count = $checkStmt->fetchColumn();

                    if ($count > 0) {
                        echo "Un Posts avec ce titre existe déjà pour cet enseignant.";
                    } else {
                        $sqlInsertQuery = "INSERT INTO gt_Posts(TitrePosts, Association_id, tag, description) VALUES (:TitrePosts, :Association_id, :tag, :description)";
                        $insererPosts = $db->prepare($sqlInsertQuery);
                        $insererPosts->execute(array(
                            'TitrePosts' => $_POST['titre'],
                            'Association_id' => $id_association,
                            'tag' => $_POST['tag'],
                            'description' => $_POST['description']
                        ));
                        echo "Posts ajouté avec succès.";
                    }
                }
                ?>

                <h2>Supprimer un Posts :</h2>
                <form method="post" action="" id="supr">
                    <select name="id_Posts" form="supr">
                        <?php
                        $modif = $db->prepare("SELECT * FROM gt_Posts WHERE Association_id = :Association_id");
                        $modif->execute(array(':Association_id' => $id_association));
                        $modifs = $modif->fetchAll();
                        foreach ($modifs as $modification) { 
                        ?>
                            <option value="<?= $modification["id_Posts"]; ?>"><?= htmlspecialchars($modification["TitrePosts"]); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="supprimer" value="Supprimer">
                    <input type="reset" value="Annuler">
                </form>

                <?php
                if (isset($_POST['supprimer'])) {
                    $id_Posts = $_POST['id_Posts'];

                    $sqlDeleteInscriptionsQuery = "DELETE FROM gt_Inscription WHERE Posts_id = :id_Posts";
                    $deleteInscriptions = $db->prepare($sqlDeleteInscriptionsQuery);
                    $deleteInscriptions->execute(array(
                        ':id_Posts' => $id_Posts
                    ));

                    $sqlDeletePostsQuery = "DELETE FROM gt_Posts WHERE id_Posts = :id_Posts AND Association_id = :Association_id";
                    $deletePosts = $db->prepare($sqlDeletePostsQuery);
                    $deletePosts->execute(array(
                        ':id_Posts' => $id_Posts,
                        ':Association_id' => $id_association
                    ));

                    echo "Le Posts et toutes les inscriptions associées ont été supprimés.";
                }
                ?>
            </div>

            <div class="right-section">
                <h2>Vos Posts :</h2>
                <?php 
                $Postsuivie = $db->prepare("SELECT * FROM gt_Posts WHERE Association_id = :id_Prof ORDER BY tag");
                $Postsuivie->execute(array(':id_Prof' => $id_association));
                $Posts = $Postsuivie->fetchAll();
                foreach ($Posts as $lesPostssuivies) {
                    echo "<ul><li>", htmlspecialchars($lesPostssuivies['TitrePosts']), "</li></ul>";
                }
                ?>

                <h2>Modifier un Posts :</h2>
                <form method="post" action="" id="modif">
                    <select name="id_Posts" form="modif">
                        <?php
                        $modif = $db->prepare("SELECT * FROM gt_Posts WHERE Association_id = :Association_id");
                        $modif->execute(array(':Association_id' => $id_association));
                        $modifs = $modif->fetchAll();
                        foreach ($modifs as $modification) { 
                        ?>
                            <option value="<?= $modification["id_Posts"]; ?>"><?= htmlspecialchars($modification["TitrePosts"]); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="modifier" value="Modifier">

                </form>

                <?php
                if (isset($_POST['id_Posts'])) {
                    $Posts_id = $_POST['id_Posts'];

                    $query = $db->prepare("SELECT * FROM gt_Posts WHERE id_Posts = :id_Posts AND Association_id = :Association_id");
                    $query->execute(array(
                        ':id_Posts' => $Posts_id,
                        ':Association_id' => $id_association
                    ));
                    $Posts = $query->fetch(PDO::FETCH_ASSOC);
                ?>
                    <h2>Modifier le Posts : <?= htmlspecialchars($Posts['TitrePosts']); ?></h2>
                    <form method="post" action="">
                        <input type="hidden" name="id_Posts" value="<?= $Posts['id_Posts']; ?>">
                        <p>
                            <label for="titre">Titre du Posts :</label>
                            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($Posts['TitrePosts']); ?>" required>
                        </p>
                        <p>
                            <label for="tag">Tag du Posts :</label>
                            <input type="text" name="tag" id="tag" value="<?= htmlspecialchars($Posts['tag']); ?>" required>
                        </p>
                        <p>
                            <label for="description">Description du Posts :</label>
                            <input type="text" name="description" id="description" value="<?= htmlspecialchars($Posts['Description']); ?>" required>
                        </p>
                        <p>
                            <input type="submit" name="update" value="Mettre à jour">
                            <input type="reset" value="Annuler">
                        </p>
                    </form>
                <?php
                }

                if (isset($_POST['update']) && !empty($_POST['id_Posts']) && !empty($_POST['titre']) && !empty($_POST['tag']) && !empty($_POST['description'])) {
                    $sqlUpdateQuery = "UPDATE gt_Posts 
                                       SET TitrePosts = :TitrePosts, tag = :tag, description = :description 
                                       WHERE id_Posts = :id_Posts AND Association_id = :Association_id";

                    $stmt = $db->prepare($sqlUpdateQuery);
                    $stmt->execute(array(
                        ':TitrePosts' => $_POST['titre'],
                        ':tag' => $_POST['tag'],
                        ':description' => $_POST['description'],
                        ':id_Posts' => $_POST['id_Posts'],
                        ':Association_id' => $id_association
                    ));

                    echo "Posts mis à jour avec succès.";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
