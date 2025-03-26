<?php
// Démarrer la session
session_start();

    // Initialisation des variables
$message = '';

// Connexion à la base de données
include('../bdd.php');

// Traitement du formulaire Bénévole
if (isset($_POST['submitBenevole'])) {
    // Récupérer les données
    $nom = htmlspecialchars($_POST['nomBenevole']);
    $prenom = htmlspecialchars($_POST['prenomBenevole']);
    $email = htmlspecialchars($_POST['mailBenevole']);
    $login = htmlspecialchars($_POST['loginBenevole']);
    $mdp = $_POST['mdpBenevole'];
    $localisation = htmlspecialchars($_POST['localisationBenevole']);
    $description = htmlspecialchars($_POST['descriptionBenevole']);
    
    // Traitement des tags
    $tags = isset($_POST['tagBenevole']) ? $_POST['tagBenevole'] : [];
    $tagsString = implode(', ', $tags);
    
    // Vérifier si le login existe déjà
    $checkLogin = $db->prepare("SELECT * FROM gt_benevole WHERE loginBenevole = ?");
    $checkLogin->execute([$login]);
    
    if ($checkLogin->rowCount() > 0) {
        $message = "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Traitement de la photo
        $photoPath = "../images/profils/default.jpg"; // Chemin par défaut
        
        if (isset($_FILES['photoBenevole']) && $_FILES['photoBenevole']['error'] == 0) {
            // Définir les types de fichiers acceptés
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (in_array($_FILES['photoBenevole']['type'], $allowed)) {
                $uniqueName = uniqid('benevole_') . '_' . $_FILES['photoBenevole']['name'];
                $uploadDir = "../images/profils/";
                $uploadFile = $uploadDir . $uniqueName;
                
                // Vérifier si le dossier existe, sinon le créer
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                if (move_uploaded_file($_FILES['photoBenevole']['tmp_name'], $uploadFile)) {
                    $photoPath = $uploadFile;
                }
            }
        }
        
        // Hashage du mot de passe
        $mdp_hash = sha1($mdp);
        
        // Insertion dans la base de données avec les noms de colonnes corrects
        try {
            $stmt = $db->prepare("
                INSERT INTO gt_benevole (nomBenevole, prenomBenevole, mailBenevole, loginBenevole, mdpBenevole, localisation, description, image, tag)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([$nom, $prenom, $email, $login, $mdp_hash, $localisation, $description, $photoPath, $tagsString]);
            
            if ($result) {
                $message = "Inscription réussie avec succès ! Vous pouvez maintenant vous connecter.";
                // Redirection vers la page de connexion après 2 secondes
                header("Refresh: 2; URL=../index/index.php");
            } else {
                $errorInfo = $stmt->errorInfo();
                $message = "Erreur lors de l'inscription : " . $errorInfo[2];
                error_log("Database error: " . print_r($errorInfo, true));
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'inscription : " . $e->getMessage();
            // Log the error for debugging
            error_log("Erreur d'inscription bénévole: " . $e->getMessage());
        }
    }
}

// Traitement du formulaire Association
if (isset($_POST['submitAssociation'])) {
    // Récupérer les données
    $nom = htmlspecialchars($_POST['nomAssociation']);
    $email = htmlspecialchars($_POST['mailAssociation']);
    $login = htmlspecialchars($_POST['loginAssociation']);
    $mdp = $_POST['mdpAssociation'];
    $localisation = htmlspecialchars($_POST['localisationAssociation']);
    $description = htmlspecialchars($_POST['descriptionAssociation']);
    
    // Traitement des tags
    $tags = isset($_POST['tagAssociation']) ? $_POST['tagAssociation'] : [];
    $tagsString = implode(', ', $tags);
    
    // Vérifier si le login existe déjà
    $checkLogin = $db->prepare("SELECT * FROM gt_association WHERE loginAssociation = ?");
    $checkLogin->execute([$login]);
    
    if ($checkLogin->rowCount() > 0) {
        $message = "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Traitement de la photo
        $photoPath = "../images/profils/default.jpg"; // Chemin par défaut
        
        if (isset($_FILES['photoAssociation']) && $_FILES['photoAssociation']['error'] == 0) {
            // Définir les types de fichiers acceptés
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (in_array($_FILES['photoAssociation']['type'], $allowed)) {
                $uniqueName = uniqid('association_') . '_' . $_FILES['photoAssociation']['name'];
                $uploadDir = "../images/profils/";
                $uploadFile = $uploadDir . $uniqueName;
                
                // Vérifier si le dossier existe, sinon le créer
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                if (move_uploaded_file($_FILES['photoAssociation']['tmp_name'], $uploadFile)) {
                    $photoPath = $uploadFile;
                }
            }
        }
        
        // Hashage du mot de passe
        $mdp_hash = sha1($mdp);
        
        // Insertion dans la base de données avec les noms de colonnes corrects
        try {
            $stmt = $db->prepare("
                INSERT INTO gt_association (nomAssociation, mailAssociation, loginAssociation, mdpAssociation, localisation, description, image, tag)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([$nom, $email, $login, $mdp_hash, $localisation, $description, $photoPath, $tagsString]);
            
            if ($result) {
                $message = "Inscription réussie avec succès ! Vous pouvez maintenant vous connecter.";
                // Redirection vers la page de connexion après 2 secondes
                header("Refresh: 2; URL=../index/index.php");
            } else {
                $errorInfo = $stmt->errorInfo();
                $message = "Erreur lors de l'inscription : " . $errorInfo[2];
                error_log("Database error: " . print_r($errorInfo, true));
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'inscription : " . $e->getMessage();
            // Log the error for debugging
            error_log("Erreur d'inscription association: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiveTime - Inscription</title>
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/Inscription.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include('../include/header.php'); ?>

    <!-- Main Content -->
    <section class="section inscription-section">
        <div class="container">
            <h1 class="section-title">Inscription</h1>
            
            <?php if (!empty($message)): ?>
                <div class="alert <?= strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger' ?>">
                    <i class="fas <?= strpos($message, 'succès') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="inscription-type-selector">
                <button id="btnBenevole" class="btn btn-primary">Bénévole</button>
                <button id="btnAssociation" class="btn btn-secondary">Association</button>
            </div>
            
            <div class="form-wrapper">
                <div id="formBenevole" class="form-container">
                    <h2 class="form-title">Inscription Bénévole</h2>
                    <form action="Inscription.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nomBenevole">Nom:</label>
                            <input type="text" id="nomBenevole" name="nomBenevole" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenomBenevole">Prénom:</label>
                            <input type="text" id="prenomBenevole" name="prenomBenevole" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mailBenevole">Email:</label>
                            <input type="email" id="mailBenevole" name="mailBenevole" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="loginBenevole">Login:</label>
                            <input type="text" id="loginBenevole" name="loginBenevole" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mdpBenevole">Mot de passe:</label>
                            <input type="password" id="mdpBenevole" name="mdpBenevole" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tags (sélectionnez un ou plusieurs):</label>
                            <div class="tags-container">
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagBenevole[]" value="Culture"> Culture
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagBenevole[]" value="Sport"> Sport
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagBenevole[]" value="Ecologie"> Ecologie
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagBenevole[]" value="Education"> Education
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagBenevole[]" value="Solidarite"> Solidarité
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="localisationBenevole">Localisation (ville):</label>
                            <input type="text" id="localisationBenevole" name="localisationBenevole" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descriptionBenevole">Description:</label>
                            <textarea id="descriptionBenevole" name="descriptionBenevole" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="photoBenevole">Photo de profil:</label>
                            <input type="file" id="photoBenevole" name="photoBenevole" accept="image/*">
                        </div>
                        
                        <button type="submit" name="submitBenevole" class="btn btn-primary">S'inscrire</button>
                    </form>
                </div>
                
                <div id="formAssociation" class="form-container" style="display: none;">
                    <h2 class="form-title">Inscription Association</h2>
                    <form action="Inscription.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nomAssociation">Nom:</label>
                            <input type="text" id="nomAssociation" name="nomAssociation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mailAssociation">Email:</label>
                            <input type="email" id="mailAssociation" name="mailAssociation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="loginAssociation">Login:</label>
                            <input type="text" id="loginAssociation" name="loginAssociation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mdpAssociation">Mot de passe:</label>
                            <input type="password" id="mdpAssociation" name="mdpAssociation" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tags (sélectionnez un ou plusieurs):</label>
                            <div class="tags-container">
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagAssociation[]" value="Solidarite"> Solidarité
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagAssociation[]" value="Ecologie"> Ecologie
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagAssociation[]" value="Sport"> Sport
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagAssociation[]" value="Culture"> Culture
                                </label>
                                <label class="tag-checkbox">
                                    <input type="checkbox" name="tagAssociation[]" value="Education"> Education
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="localisationAssociation">Localisation (ville):</label>
                            <input type="text" id="localisationAssociation" name="localisationAssociation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descriptionAssociation">Description:</label>
                            <textarea id="descriptionAssociation" name="descriptionAssociation" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="photoAssociation">Photo de profil:</label>
                            <input type="file" id="photoAssociation" name="photoAssociation" accept="image/*">
                        </div>
                        
                        <button type="submit" name="submitAssociation" class="btn btn-association">S'inscrire</button>
                    </form>
                </div>
            
                <div class="preview-container">
                    <h2 class="form-title">Prévisualisation du Profil</h2>
                    <div class="profile-preview">
                        <img id="previewPhoto" src="../images/profils/default.jpg" alt="Photo de profil">
                        <div class="preview-info">
                            <p><strong>Nom:</strong> <span id="previewNom"></span></p>
                            <p><strong>Prénom:</strong> <span id="previewPrenom"></span></p>
                            <p><strong>Email:</strong> <span id="previewEmail"></span></p>
                            <p><strong>Login:</strong> <span id="previewLogin"></span></p>
                            <p><strong>Tag:</strong> <span id="previewTag"></span></p>
                            <p><strong>Localisation:</strong> <span id="previewLocalisation"></span></p>
                            <p><strong>Description:</strong> <span id="previewDescription"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélection des éléments
        const btnBenevole = document.getElementById('btnBenevole');
        const btnAssociation = document.getElementById('btnAssociation');
        const formBenevole = document.getElementById('formBenevole');
        const formAssociation = document.getElementById('formAssociation');
        
        // Éléments de prévisualisation
        const previewPhoto = document.getElementById('previewPhoto');
        const previewNom = document.getElementById('previewNom');
        const previewPrenom = document.getElementById('previewPrenom');
        const previewEmail = document.getElementById('previewEmail');
        const previewLogin = document.getElementById('previewLogin');
        const previewTag = document.getElementById('previewTag');
        const previewLocalisation = document.getElementById('previewLocalisation');
        const previewDescription = document.getElementById('previewDescription');

        // Afficher le formulaire bénévole par défaut
        formBenevole.style.display = 'block';
        formAssociation.style.display = 'none';
        btnBenevole.classList.add('active');

        // Gestion des clics sur les boutons
        btnBenevole.addEventListener('click', function() {
            formBenevole.style.display = 'block';
            formAssociation.style.display = 'none';
            btnBenevole.classList.add('active');
            btnAssociation.classList.remove('active');
            // Réinitialiser la prévisualisation pour bénévole
            resetPreview();
        });

        btnAssociation.addEventListener('click', function() {
            formBenevole.style.display = 'none';
            formAssociation.style.display = 'block';
            btnAssociation.classList.add('active');
            btnBenevole.classList.remove('active');
            // Réinitialiser la prévisualisation pour association
            resetPreview();
        });
        
        // Fonction pour réinitialiser la prévisualisation
        function resetPreview() {
            previewPhoto.src = "../images/profils/default.jpg";
            previewNom.textContent = "";
            previewPrenom.textContent = "";
            previewEmail.textContent = "";
            previewLogin.textContent = "";
            previewTag.textContent = "";
            previewLocalisation.textContent = "";
            previewDescription.textContent = "";
        }

        // Fonction pour mettre à jour les tags sélectionnés
        function updateTags(formId) {
            const checkboxes = document.querySelectorAll(`#${formId} input[type="checkbox"]:checked`);
            const selectedTags = Array.from(checkboxes).map(cb => cb.value);
            previewTag.textContent = selectedTags.join(', ');
        }

        // Écouteurs d'événements pour le formulaire bénévole
        formBenevole.addEventListener('input', function(event) {
            const target = event.target;
            
            if (target.name === 'tagBenevole[]') {
                updateTags('formBenevole');
                return;
            }
            
            switch (target.id) {
                case 'nomBenevole':
                    previewNom.textContent = target.value;
                    break;
                case 'prenomBenevole':
                    previewPrenom.textContent = target.value;
                    break;
                case 'mailBenevole':
                    previewEmail.textContent = target.value;
                    break;
                case 'loginBenevole':
                    previewLogin.textContent = target.value;
                    break;
                case 'localisationBenevole':
                    previewLocalisation.textContent = target.value;
                    break;
                case 'descriptionBenevole':
                    previewDescription.textContent = target.value;
                    break;
                case 'photoBenevole':
                    const file = target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewPhoto.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                    break;
            }
        });

        // Écouteurs d'événements pour le formulaire association
        formAssociation.addEventListener('input', function(event) {
            const target = event.target;
            
            if (target.name === 'tagAssociation[]') {
                updateTags('formAssociation');
                return;
            }
            
            switch (target.id) {
                case 'nomAssociation':
                    previewNom.textContent = target.value;
                    break;
                case 'mailAssociation':
                    previewEmail.textContent = target.value;
                    break;
                case 'loginAssociation':
                    previewLogin.textContent = target.value;
                    break;
                case 'localisationAssociation':
                    previewLocalisation.textContent = target.value;
                    break;
                case 'descriptionAssociation':
                    previewDescription.textContent = target.value;
                    break;
                case 'photoAssociation':
                    const file = target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewPhoto.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                    break;
            }
        });
    });
    </script>

    <?php include('../include/footer.php'); ?>
</body>
</html>