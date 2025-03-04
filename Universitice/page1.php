<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/page1.css">
    <title>Document</title>

    <?php
    date_default_timezone_set('UTC');
    session_start();
    if ($_SESSION['authentification'] == false && $_SESSION['Permission'] <  2 || $_SESSION['Permission'] >  2  ) {
        header('Location: Erreur.php');
        
    }
    ?>
</head>
<body>
<h1>Inscription ou désinscription d'un cours</h1>
    <div class="container">
        <div class="header">
            <a href="deconnexion.php" class="logout">Se déconnecter</a>
        </div>
        <div class="content">
            <div class="left-section">
                <h1>S'inscrire à un cours :</h1>
                <form method="post" action="">
                    <select name="cours" id="cours">
                        <?php
                        include("test.php");
                        $filmsStatement = $db->prepare("SELECT * FROM SAE203_Cours");
                        $filmsStatement->execute();
                        $films = $filmsStatement->fetchAll();
                        foreach ($films as $film) { 
                        ?>
                            <option value="<?= $film["id_Cours"]; ?>"><?= $film["TitreCours"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="s'inscrire ! ">
                </form>

                <form method="post" action="">
                    <select name="cours" id="cours">
                        <?php
                        foreach ($films as $film) { 
                        ?>
                            <option value="<?= $film["id_Cours"]; ?>"><?= $film["TitreCours"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="se désinscrire">
                </form>

                <?php
                if (isset($_POST['cours'])) {
                    $cours_id = $_POST['cours'];
                    $etudiant_id = $_SESSION['Identifiant'];
                    $action = $_POST['submit'];

                    if ($action == "s'inscrire ! ") {
                        // Vérification si l'utilisateur est déjà inscrit ou non
                        $sqlCheckQuery = "SELECT COUNT(*) FROM SAE203_Inscription WHERE cours_id = :cours_id AND etudiant_id = :etudiant_id";
                        $checkInscription = $db->prepare($sqlCheckQuery);
                        $checkInscription->execute(array(
                            'cours_id' => $cours_id,
                            'etudiant_id' => $etudiant_id
                        ));

                        $count = $checkInscription->fetchColumn();

                        if ($count > 0) {
                            echo "Vous êtes déjà inscrit à ce cours.";
                        } else {
                            $sqlInsertQuery = "INSERT INTO SAE203_Inscription(cours_id, etudiant_id, date_debut, progression, date_fin) VALUES (:cours_id, :etudiant_id, :date_debut, :progression, :date_fin)";
                            $insererFilm = $db->prepare($sqlInsertQuery);
                            $insererFilm->execute(array(
                                'cours_id' => $cours_id,
                                'etudiant_id' => $etudiant_id,
                                'date_debut' => date("Y-m-d"),
                                'progression' => 0,
                                'date_fin' => date("Y-m-d")
                            ));
                            echo "Vous êtes maintenant inscrit au cours !";
                        }
                    } elseif ($action == "se désinscrire") {
                        // On vérifie si on est inscrit
                        $sqlCheckQuery = "SELECT COUNT(*) FROM SAE203_Inscription WHERE cours_id = :cours_id AND etudiant_id = :etudiant_id"; //COUNT renvoie le nombre de lignes qui répondent aux critères spécifiés dans WHERE
                        $checkInscription = $db->prepare($sqlCheckQuery);
                        $checkInscription->execute(array(
                            'cours_id' => $cours_id,
                            'etudiant_id' => $etudiant_id
                        ));

                        $count = $checkInscription->fetchColumn();

                        if ($count == 0) {
                            echo "Vous n'êtes pas inscrit à ce cours.";
                        } else {
                            $sqlDeleteQuery = "DELETE FROM SAE203_Inscription WHERE cours_id = :cours_id AND etudiant_id = :etudiant_id";
                            $deleteInscription = $db->prepare($sqlDeleteQuery);
                            $deleteInscription->execute(array(
                                'cours_id' => $cours_id,
                                'etudiant_id' => $etudiant_id
                            ));
                            echo "Vous êtes maintenant désinscrit du cours.";
                        }
                    }
                }
                ?>

                <h1>Voir ma progression : </h1>
                <a href="Mescours.php" class="logout">Ma progression</a>
            
            </div>

            <div class="right-section">
                <h1>Listes des cours :</h1>
                <?php 
                foreach ($films as $film) {
                    echo '<ul><li>'.$film['TitreCours'].' - '.$film['Description'].'</br></li></ul>';
                }
                ?>

                <h1>Les cours suivie :</h1>
                <?php 
                $id_etudiant = $_SESSION['Identifiant'];
                $coursuivie = $db->prepare("SELECT * FROM SAE203_Inscription JOIN SAE203_Cours ON SAE203_Inscription.cours_id=SAE203_Cours.id_cours WHERE etudiant_id=$id_etudiant");
                $coursuivie->execute();
                $Cours = $coursuivie->fetchAll();
                foreach ($Cours as $lescourssuivies) {
                    echo "<ul><li>".$lescourssuivies['TitreCours']." - Progression : ".$lescourssuivies['progression']."%</ul></li>";
                }
                ?>
            </div>
        </div>

    </div>
</body>
</html>
