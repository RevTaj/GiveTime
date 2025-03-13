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
    if ($_SESSION['authentification'] == false || $_SESSION['Permission'] < 2 || $_SESSION['Permission'] >  2) {
        header('Location: index.html');
        exit;
    }

    include("test.php");
    $id_Etudiant = $_SESSION['Identifiant'];

    // Récupérer le prénom de l'utilisateur depuis la base de données
    $query = $db->prepare("SELECT prenomEtudiant, nomEtudiant FROM SAE203_Etudiant WHERE id_Etudiant = :id_Etudiant");
    $query->execute(array(':id_Etudiant' => $id_Etudiant));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $prenomEtudiant = $result['prenomEtudiant'];
    $nomEtudiant = $result['nomEtudiant'];
    ?>

    <div class="container">
        <div class="header">
            <div class="welcome-message">
                <?php
                // Afficher le message de bienvenue
                echo "Bonjour " . htmlspecialchars($prenomEtudiant) . " " . htmlspecialchars($nomEtudiant) . " !";
                ?>
            </div>
            <a href="deconnexion.php" class="logout">Se déconnecter</a>
        </div>
        <div class="content">
            <div class="right-section">
                <h2>Vos cours :</h2>
                <?php 
                $coursuivie = $db->prepare("SELECT c.TitreCours FROM SAE203_Cours c
                                            JOIN SAE203_Inscription i ON c.id_Cours = i.Cours_id 
                                            WHERE i.etudiant_id = :id_Etudiant 
                                            ORDER BY c.tag");
                $coursuivie->execute(array(':id_Etudiant' => $id_Etudiant));
                $Cours = $coursuivie->fetchAll();
                foreach ($Cours as $lescourssuivies) {
                    echo "<ul><li>", htmlspecialchars($lescourssuivies['TitreCours']), "</li></ul>";
                }
                ?>

                <h2>Modifier la progression d'un cours :</h2>
                <form method="post" action="" id="modif">
                    <select name="id_cours" form="modif">
                        <?php
                        $modif = $db->prepare("SELECT c.id_Cours, c.TitreCours 
                                               FROM SAE203_Cours c
                                               JOIN SAE203_Inscription i ON c.id_Cours = i.Cours_id 
                                               WHERE i.etudiant_id = :id_Etudiant");
                        $modif->execute(array(':id_Etudiant' => $id_Etudiant));
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

                    $query = $db->prepare("SELECT c.TitreCours, i.progression 
                                           FROM SAE203_Cours c 
                                           JOIN SAE203_Inscription i ON c.id_Cours = i.Cours_id 
                                           WHERE c.id_Cours = :id_Cours AND i.etudiant_id = :id_Etudiant");
                    $query->execute(array(
                        ':id_Cours' => $cours_id,
                        ':id_Etudiant' => $id_Etudiant
                    ));
                    $cours = $query->fetch(PDO::FETCH_ASSOC);
                ?>
                    <h2>Modifier la progression du cours : <?= htmlspecialchars($cours['TitreCours']); ?></h2>
                    <form method="post" action="">
                        <input type="hidden" name="id_Cours" value="<?= $cours_id; ?>">
                        <p>
                            <label for="progression">Progression du cours :</label>
                            <input type="range" name="progression" id="progression" value="<?= htmlspecialchars($cours['progression']); ?>" required min="0" max="100">
                        </p>
                        <p>
                            <input type="submit" name="update" value="Mettre à jour">
                            <input type="reset" value="Annuler">
                        </p>
                    </form>
                <?php
                }

                if (isset($_POST['update']) && !empty($_POST['id_Cours']) && !empty($_POST['progression'])) {
                    $sqlUpdateQuery = "UPDATE SAE203_Inscription 
                                       SET progression = :progression 
                                       WHERE Cours_id = :id_Cours AND etudiant_id = :id_Etudiant";

                    $stmt = $db->prepare($sqlUpdateQuery);
                    $stmt->execute(array(
                        ':progression' => $_POST['progression'],
                        ':id_Cours' => $_POST['id_Cours'],
                        ':id_Etudiant' => $id_Etudiant
                    ));

                    echo "Progression du cours mise à jour avec succès.";
                }
                ?>
            </div>
            </div>
    </div>
    <a href="page1.php" class="btn">Retour</a> <!-- Bouton de retour -->
</body>
</html>
