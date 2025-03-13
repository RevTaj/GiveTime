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
<h1>Inscription ou désinscription d'un Posts</h1>
    <div class="container">
        <div class="header">
            <a href="deconnexion.php" class="logout">Se déconnecter</a>
        </div>



        <div class="content">
            <div class="left-section">
                <h1>S'inscrire à un Posts :</h1>
                <form method="post" action="">
                    <select name="Posts" id="Posts">
                        <?php
                        include("bdd.php");
                        $filmsStatement = $db->prepare("SELECT * FROM gt_Posts");
                        $filmsStatement->execute();
                        $films = $filmsStatement->fetchAll();
                        foreach ($films as $film) { 
                        ?>
                            <option value="<?= $film["id_Posts"]; ?>"><?= $film["TitrePosts"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="s'inscrire ! ">
                </form>

                <form method="post" action="">
                    <select name="Posts" id="Posts">
                        <?php
                        foreach ($films as $film) { 
                        ?>
                            <option value="<?= $film["id_Posts"]; ?>"><?= $film["TitrePosts"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="se désinscrire">
                </form>

                <?php
                if (isset($_POST['Posts'])) {
                    $Posts_id = $_POST['Posts'];
                    $benevole_id = $_SESSION['Identifiant'];
                    $action = $_POST['submit'];

                    if ($action == "s'inscrire ! ") {
                        // Vérification si l'utilisateur est déjà inscrit ou non
                        $sqlCheckQuery = "SELECT COUNT(*) FROM gt_Inscription WHERE Posts_id = :Posts_id AND benevole_id = :benevole_id";
                        $checkInscription = $db->prepare($sqlCheckQuery);
                        $checkInscription->execute(array(
                            'Posts_id' => $Posts_id,
                            'benevole_id' => $benevole_id
                        ));

                        $count = $checkInscription->fetchColumn();

                        if ($count > 0) {
                            echo "Vous êtes déjà inscrit à ce Posts.";
                        } else {
                            $sqlInsertQuery = "INSERT INTO gt_Inscription(Posts_id, benevole_id, date_debut, progression, date_fin) VALUES (:Posts_id, :benevole_id, :date_debut, :progression, :date_fin)";
                            $insererFilm = $db->prepare($sqlInsertQuery);
                            $insererFilm->execute(array(
                                'Posts_id' => $Posts_id,
                                'benevole_id' => $benevole_id,
                                'date_debut' => date("Y-m-d"),
                                'progression' => 0,
                                'date_fin' => date("Y-m-d")
                            ));
                            echo "Vous êtes maintenant inscrit au Posts !";
                        }
                    } elseif ($action == "se désinscrire") {
                        // On vérifie si on est inscrit
                        $sqlCheckQuery = "SELECT COUNT(*) FROM gt_Inscription WHERE Posts_id = :Posts_id AND benevole_id = :benevole_id"; //COUNT renvoie le nombre de lignes qui répondent aux critères spécifiés dans WHERE
                        $checkInscription = $db->prepare($sqlCheckQuery);
                        $checkInscription->execute(array(
                            'Posts_id' => $Posts_id,
                            'benevole_id' => $benevole_id
                        ));

                        $count = $checkInscription->fetchColumn();

                        if ($count == 0) {
                            echo "Vous n'êtes pas inscrit à ce Posts.";
                        } else {
                            $sqlDeleteQuery = "DELETE FROM gt_Inscription WHERE Posts_id = :Posts_id AND benevole_id = :benevole_id";
                            $deleteInscription = $db->prepare($sqlDeleteQuery);
                            $deleteInscription->execute(array(
                                'Posts_id' => $Posts_id,
                                'benevole_id' => $benevole_id
                            ));
                            echo "Vous êtes maintenant désinscrit du Posts.";
                        }
                    }
                }
                ?>

                <h1>Voir ma progression : </h1>
                <a href="MesPosts.php" class="logout">Ma progression</a>
            
            </div>

            <div class="right-section">
                <h1>Listes des Posts :</h1>

                
                <?php 
                foreach ($films as $film) {
                    echo '<ul><li>'.$film['TitrePosts'].' - '.$film['Description'].'</br></li></ul>';
                    ?>
                        <form method="post" action="">
                    <select name="Posts" id="Posts">
                        <?php
                        include("bdd.php");
                        $filmsStatement = $db->prepare("SELECT * FROM gt_Posts");
                        $filmsStatement->execute();
                        $films = $filmsStatement->fetchAll();
                        foreach ($films as $film) { 
                        ?>
                            <option value="<?= $film["id_Posts"]; ?>"><?= $film["TitrePosts"]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="s'inscrire ! ">
                </form>

                    <?php
                    
                }
                ?>

                <h1>Les Posts suivie :</h1>
                <?php 
                $id_benevole = $_SESSION['Identifiant'];
                $Postsuivie = $db->prepare("SELECT * FROM gt_Inscription JOIN gt_Posts ON gt_Inscription.Posts_id=gt_Posts.id_Posts WHERE benevole_id=$id_benevole");
                $Postsuivie->execute();
                $Posts = $Postsuivie->fetchAll();

                foreach ($Posts as $lesPostssuivies) {
                    echo "<ul><li>".$lesPostssuivies['TitrePosts']." - Progression : ".$lesPostssuivies['progression']."%</ul></li>";
                    }

                ?>

            </div>
        </div>

    </div>
</body>
</html>
