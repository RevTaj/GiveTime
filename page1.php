<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-page-association.css">
    <title>Document</title>

    <?php
    date_default_timezone_set('UTC');
    session_start();
    if ($_SESSION['authentification'] == false && $_SESSION['Permission'] <  2 || $_SESSION['Permission'] >  2  ) {
        header('Location: ../Connexion/Erreur.php');
        
    }
    ?>
</head>
<body>
<header>

    <h1>Inscription ou désinscription d'une mission</h1>
    
</header>

<div class="container">
        <div class="header">
            <a href="../Connexion/deconnexion.php" class="logout">Se déconnecter</a>
        </div>



        <div class="content">
            <div class="left-section">
                <h1>S'inscrire à une mission :</h1>

                <div id="scrollerDedans">
                    
                                  
                    <form method="post" action="">
                        <?php
                        include("../bdd.php");
                        $filmsStatement = $db->prepare("SELECT * FROM gt_Posts 
                                                        JOIN gt_association
                                                        ON gt_Posts.association_id = gt_association.id_association ");
                        $filmsStatement->execute();
                        $films = $filmsStatement->fetchAll();
                        foreach ($films as $film) { 
                        
                        ?>
                            
                            <form method="post" action="">
                                <h2> <?= htmlspecialchars($film['TitrePosts']) ?></h2> <b> <?= htmlspecialchars($film['nomAssociation']) ?> </b>
                                <img src="" alt="">
                                <p> <?= htmlspecialchars($film['Description']) ?> </p>
                                <li class="tag"> <?= htmlspecialchars($film['tag']) ?> </li>
                            
                                <input type="hidden" name="INSCRIPTION" value="<?= $film["id_Posts"]; ?>"> 
                                <input type="submit" name="submit" value="s'inscrire ! ">
                            </form>


                        <?php
                        }
                        ?>
                </form>

</div>



                <?php
                if (isset($_POST['INSCRIPTION'])) {

                    // $Posts_id = $_POST['INSCRIPTION'];
                    $benevole_id = $_SESSION['Identifiant'];
                    $action = $_POST['submit'];

                    // echo $_POST['INSCRIPTION'];
                    

                    if ($action == "s'inscrire ! ") {
                        // Vérification si l'utilisateur est déjà inscrit ou non
                        $sqlCheckQuery = "SELECT COUNT(*) FROM gt_Inscription WHERE Posts_id = :Posts_id AND benevole_id = :benevole_id";
                        $checkInscription = $db->prepare($sqlCheckQuery);
                        $checkInscription->execute(array(
                            'Posts_id' => $_POST['INSCRIPTION'],
                            'benevole_id' => $benevole_id
                        ));

                        $count = $checkInscription->fetchColumn();

                    }

                    if ($count > 0) {
                        echo "Vous êtes déjà inscrit à ce Posts.";
                    } else {
                        $sqlInsertQuery = "INSERT INTO gt_Inscription(Posts_id, benevole_id, date_debut, progression, date_fin) VALUES (:Posts_id, :benevole_id, :date_debut, :progression, :date_fin)";
                        $insererFilm = $db->prepare($sqlInsertQuery);
                        $insererFilm->execute(array(
                            'Posts_id' => $_POST['INSCRIPTION'],
                            'benevole_id' => $benevole_id,
                            'date_debut' => date("Y-m-d"),
                            'progression' => 0,
                            'date_fin' => date("Y-m-d")
                        ));
                        echo "Vous êtes maintenant inscrit au Posts !";
                    }
                }



                if (isset($_POST['DELETE'])) {

                    $benevole_id = $_SESSION['Identifiant'];
                    $action = $_POST['submit'];
                    $Post_Id =  $_POST['DELETE'];

                    if ($action == "se désinscrire") {

                            // On vérifie si on est inscrit ---> COUNT
                            $sqlCheckQuery = "SELECT COUNT(*) FROM gt_Inscription WHERE Posts_id = :Posts_id AND benevole_id = :benevole_id"; //COUNT renvoie le nombre de lignes qui répondent aux critères spécifiés dans WHERE
                            echo "Requête SQL : " . $sqlCheckQuery;
                            $checkInscription = $db->prepare($sqlCheckQuery);
                            $checkInscription->execute(array(
                                
                                'Posts_id' => $Post_Id ,
                                'benevole_id' => $benevole_id
                            ));


                            $count = $checkInscription->fetchColumn();
                            
                            
                                    
                                if ($count == 0) {
                                echo "Vous n'êtes pas inscrit à ce Posts.";
                                }
                                else {
                                    $sqlDeleteQuery = "DELETE FROM gt_Inscription WHERE Posts_id = :Posts_id AND benevole_id = :benevole_id";
                                    echo "Requête SQL : " . $sqlDeleteQuery;
                                    $deleteInscription = $db->prepare($sqlDeleteQuery);
                                    $deleteInscription->execute(array(

                                        'Posts_id' =>$Post_Id ,
                                        'benevole_id' => $benevole_id
                                    ));
                                
                        echo "Vous êtes maintenant désinscrit du Posts.";
    
                        }
                    }
                }


                    
                    
                ?>

            </div>

            <div class="right-section">

                <h1>Les Missions suivies :</h1>
                
                <?php 
                        $id_benevole = $_SESSION['Identifiant'];
                        $Postsuivie = $db->prepare("SELECT * FROM gt_Inscription 
                                                    JOIN gt_Posts 
                                                    ON gt_Inscription.Posts_id=gt_Posts.id_Posts 
                                                    JOIN gt_Association
                                                    ON gt_Posts.association_id = gt_association.id_association 
                                                    WHERE benevole_id=$id_benevole");
                        $Postsuivie->execute();
                        $Posts = $Postsuivie->fetchAll();
                        
                        foreach ($Posts as $lesPostssuivies) {
                            echo "<div class='MissionSuivie'> <ul><li>".$lesPostssuivies['TitrePosts']." - <b>".$lesPostssuivies['nomAssociation']."</b></li></ul>
                            <form action='../Messagerie/MessagerieBenevole.php' method='Post'>
                            <button type='submit' name='idMessageForm' value=".$lesPostssuivies['id_Posts']. " class='messageBTN'> Regarder mes messages</button>
                            </form>
                            <form method='post' action=''>
                            <input type='hidden' name='DELETE' value='".$lesPostssuivies["id_Posts"]."'>
                            <input type='submit' name='submit' value='se désinscrire' class='delete'>
                            </form></div>";
                        }
                        ?>
            </div>



            </div>
        </div>

    </div>
</body>
</html>
