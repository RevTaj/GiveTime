<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style-page-Association.css">
    <title>Gestion des Missions</title>

    <?php    
        include ('../include/header.php');
    ?>
</head>
<body>
    <h1>Gestion des Missions</h1> <!-- Titre centré en haut -->
    <?php
    if ($_SESSION['authentification'] == false || $_SESSION['Permission'] < 3 || $_SESSION['Permission'] > 3 ) {
        header('Location: ../Connexion/Erreur.php');
        exit;
    }

    include("../bdd.php");
    $id_association = $_SESSION['Identifiant'];

    // Récupérer le prénom de l'utilisateur depuis la base de données
    $query = $db->prepare("SELECT nomAssociation FROM gt_association WHERE id_Association = :id_Association");
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
        </div>
        <div class="content">
            <div class="left-section">
                <h2>Ajouter une mission :</h2>
                <form action="" method="POST" id="crea">
                    <fieldset>
                        <legend>Veuillez saisir les informations de la mission :</legend>
                        <p>
                            <label for="titre">Titre de la mission :</label>
                            <input type="text" name="titre" id="titre" required>
                        </p>
                        <p>
                            <label for="tag">Tag la mission :</label>
                            <input type="text" name="tag" id="tag" required>
                        </p>
                        <p>
                            <label for="description">Description la mission :</label>
                            <input type="text" name="description" id="description" required>
                        </p>
                        <p>
                            <input type="submit" value="Créer">
                        </p>
                    </fieldset>
                </form>

                <?php
                if (isset($_POST['titre']) && !isset($_POST['id_Posts'])) {
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

                <h2>Supprimer une mission :</h2>
                <form method="post" action="" id="supr">
                    <select name="id_Posts" form="supr">
                        <?php
                    // --------------------------------------------------
                    // --------------------------------------------------
                                    //Zone d'update pour eviter de Refresh  
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
                    // --------------------------------------------------
                    // --------------------------------------------------


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
                <h2>Vos missions créer :</h2>
                <?php
                $Postsuivie = $db->prepare("SELECT * FROM gt_Posts WHERE Association_id = :id_Prof ORDER BY tag");
                $Postsuivie->execute(array(':id_Prof' => $id_association));
                $Posts = $Postsuivie->fetchAll();
                foreach ($Posts as $lesPostssuivies) {
                    echo "<ul><li>", htmlspecialchars($lesPostssuivies['TitrePosts']), "</li></ul>
                    <form action='../Messagerie/MessagerieAssociation.php' method='Post'>
                    <button type='submit' name='idMessageForm' value=\"".$lesPostssuivies['id_Posts']."\"> Regarder mes messages</button>
                    </form> ";
                }
                ?>

                <h2>Modifier une mission :</h2>
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
                if (isset($_POST['modifier'])) {
                    $Posts_id = $_POST['id_Posts'];

                    $query = $db->prepare("SELECT * FROM gt_Posts WHERE id_Posts = :id_Posts AND Association_id = :Association_id");
                    $query->execute(array(
                        ':id_Posts' => $Posts_id,
                        ':Association_id' => $id_association
                    ));
                    $Posts = $query->fetch(PDO::FETCH_ASSOC);
                ?>
                    <h2>Modifier la mission : <?= htmlspecialchars($Posts['TitrePosts']); ?></h2>
                    <form method="post" action="">
                        <input type="hidden" name="id_Posts" value="<?= $Posts['id_Posts']; ?>">
                        <p>
                            <label for="titre">Titre de la mission :</label>
                            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($Posts['TitrePosts']); ?>" required>
                        </p>
                        <p>
                            <label for="tag">Tag de la mission :</label>
                            <input type="text" name="tag" id="tag" value="<?= htmlspecialchars($Posts['tag']); ?>" required>
                        </p>
                        <p>
                            <label for="description">Description de la mission :</label>
                            <input type="text" name="description" id="description" value="<?= htmlspecialchars($Posts['Description']); ?>" required>
                        </p>
                        <p>
                            <input type="submit" name="update" value="Mettre à jour">
                            <input type="reset" value="Annuler">
                        </p>
                    </form>
                <?php
                }


                ?>
            </div>
        </div>
    </div>

    <?php

                include ('../include/footer.php');

    ?>

</body>
</html>
