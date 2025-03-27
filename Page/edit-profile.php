<?php 
session_start();
require '../bdd.php'; // Connexion à la base de données

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['Identifiant'])) {
    header('Location: login.php');
    exit;
}

$id_benevole = $_SESSION['Identifiant'];
$successMessage = '';
$errorMessage = '';

try {
    // Récupération des informations du bénévole connecté
    $stmt = $db->prepare("SELECT * FROM gt_benevole WHERE id_benevole = :id");
    $stmt->execute(['id' => $id_benevole]);
    $benevole = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$benevole) {
        die("Profil introuvable.");
    }

    // Traitement du formulaire d'édition
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $prenom = trim($_POST['prenom']);
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $description = trim($_POST['description']);
        $localisation = trim($_POST['localisation']);
        $tags = trim($_POST['tags']);
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation basique
        if (empty($prenom) || empty($nom) || empty($email)) {
            $errorMessage = "Les champs prénom, nom et email sont obligatoires.";
        } else {
            // Commencer la transaction
            $db->beginTransaction();
            
            try {
                // Mise à jour des informations de base
                $updateStmt = $db->prepare("
                    UPDATE gt_benevole 
                    SET prenomBenevole = :prenom, 
                        nomBenevole = :nom, 
                        mailBenevole = :email, 
                        description = :description,
                        localisation = :localisation,
                        tag = :tags
                    WHERE id_benevole = :id
                ");
                
                $updateStmt->execute([
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'email' => $email,
                    'description' => $description,
                    'localisation' => $localisation,
                    'tags' => $tags,
                    'id' => $id_benevole
                ]);
                
                // Traitement de l'upload de l'image
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../images/profils/';
                    
                    // Vérifier que le répertoire existe
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    // Générer un nom de fichier unique
                    $fileExtension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                    $fileName = 'profile_' . $id_benevole . '_' . time() . '.' . $fileExtension;
                    $targetPath = $uploadDir . $fileName;
                    
                    // Vérifier le type de fichier
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!in_array($_FILES['profile_image']['type'], $allowedTypes)) {
                        throw new Exception("Le fichier doit être une image (JPG, PNG ou GIF).");
                    }
                    
                    // Déplacer le fichier téléchargé
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
                        // Mettre à jour la base de données avec le nouveau nom de fichier
                        $updateImageStmt = $db->prepare("UPDATE gt_benevole SET image = :image WHERE id_benevole = :id");
                        $updateImageStmt->execute([
                            'image' => $fileName,
                            'id' => $id_benevole
                        ]);
                    } else {
                        throw new Exception("Erreur lors de l'upload de l'image.");
                    }
                }
                
                // Traitement du mot de passe si nécessaire
                if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
                    // Vérifier que le mot de passe actuel est correct
                    if (password_verify($currentPassword, $benevole['motDePasse'])) {
                        // Vérifier que les nouveaux mots de passe correspondent
                        if ($newPassword === $confirmPassword) {
                            // Hasher le nouveau mot de passe et le mettre à jour
                            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            $updatePasswordStmt = $db->prepare("UPDATE gt_benevole SET motDePasse = :password WHERE id_benevole = :id");
                            $updatePasswordStmt->execute([
                                'password' => $hashedPassword,
                                'id' => $id_benevole
                            ]);
                        } else {
                            throw new Exception("Les nouveaux mots de passe ne correspondent pas.");
                        }
                    } else {
                        throw new Exception("Le mot de passe actuel est incorrect.");
                    }
                }
                
                // Si tout s'est bien passé, valider la transaction
                $db->commit();
                $successMessage = "Profil mis à jour avec succès!";
                
                // Rediriger vers la page profil après une mise à jour réussie
                header('Location: profil-user.php?updated=1');
                exit;
                
            } catch (Exception $e) {
                // En cas d'erreur, annuler la transaction
                $db->rollBack();
                $errorMessage = "Erreur lors de la mise à jour: " . $e->getMessage();
            }
        }
    }
    
    // Récupérer les données actuelles pour les afficher dans le formulaire
    $prenom = htmlspecialchars($benevole['prenomBenevole']);
    $nom = htmlspecialchars($benevole['nomBenevole']);
    $email = htmlspecialchars($benevole['mailBenevole']);
    $description = htmlspecialchars($benevole['description']);
    $localisation = htmlspecialchars($benevole['localisation']);
    $tags = htmlspecialchars($benevole['tag']);
    $profileImage = !empty($benevole['image']) ? '../images/profils/' . htmlspecialchars($benevole['image']) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';

} catch (PDOException $e) {
    die("Erreur lors de la récupération des informations: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Modifier le profil</title>
    <style>
        /* Custom styles for edit profile page */
        .edit-profile-container {
            width: 95%;
            max-width: 1000px;
            margin: 20px auto;
        }
        
        .edit-profile-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .edit-header {
            background-color: var(--primary);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .edit-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .edit-header .back-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: 600;
        }
        
        .edit-header .back-link i {
            margin-right: 5px;
        }
        
        .edit-form {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--text-medium);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-medium);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(241, 135, 0, 0.2);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .profile-image-upload {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .current-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        
        .upload-btn {
            background-color: var(--light-bg);
            color: var(--text-medium);
            padding: 8px 15px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-medium);
            font-size: 0.9rem;
            cursor: pointer;
        }
        
        .upload-btn:hover {
            background-color: var(--gray-bg);
        }
        
        .upload-btn i {
            margin-right: 5px;
        }
        
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-name {
            margin-left: 10px;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .password-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }
        
        .password-section h3 {
            margin-bottom: 15px;
            color: var(--text-dark);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 150px;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: var(--light-bg);
            color: var(--text-medium);
            border: 1px solid var(--border-medium);
        }
        
        .btn-secondary:hover {
            background-color: var(--gray-bg);
        }
        
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }
        
        .alert-success {
            background-color: rgba(60, 179, 113, 0.1);
            border: 1px solid rgba(60, 179, 113, 0.3);
            color: #2e8b57;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }
        
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include("../include/header.php"); ?>
    
    <main class="edit-profile-container">
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $successMessage ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= $errorMessage ?>
            </div>
        <?php endif; ?>
        
        <div class="edit-profile-card">
            <div class="edit-header">
                <h1><i class="fas fa-user-edit"></i> Modifier mon profil</h1>
                <a href="profil-user.php" class="back-link"><i class="fas fa-arrow-left"></i> Retour au profil</a>
            </div>
            
            <form class="edit-form" method="post" enctype="multipart/form-data">
                <!-- Profile Image Upload -->
                <div class="profile-image-upload">
                    <img src="<?= $profileImage ?>" alt="Photo de profil" class="current-image">
                    <div>
                        <div class="upload-btn-wrapper">
                            <button type="button" class="upload-btn">
                                <i class="fas fa-camera"></i> Changer la photo
                            </button>
                            <input type="file" name="profile_image" id="profile_image">
                        </div>
                        <span class="file-name" id="file-name-display">Aucun fichier sélectionné</span>
                        <p class="form-text">JPG, PNG ou GIF. 2 MB max.</p>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $prenom ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= $nom ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $email ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="localisation">Localisation</label>
                    <input type="text" name="localisation" id="localisation" class="form-control" value="<?= $localisation ?>" placeholder="Ville, Pays">
                </div>
                
                <div class="form-group">
                    <label for="tags">Compétences & Intérêts (séparés par des virgules)</label>
                    <input type="text" name="tags" id="tags" class="form-control" value="<?= $tags ?>" placeholder="Exemple: Informatique, Cuisine, Sport">
                </div>
                
                <div class="form-group">
                    <label for="description">Présentation</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Parlez-nous de vous..."><?= str_replace('<br />', '', $description) ?></textarea>
                </div>
                
                <!-- Password Change Section -->
                <div class="password-section">
                    <h3><i class="fas fa-lock"></i> Changer le mot de passe</h3>
                    <p>Laissez les champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>
                    
                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" name="current_password" id="current_password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" name="new_password" id="new_password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="profil-user.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <?php include("../include/footer.php"); ?>
    
    <script>
        // Afficher le nom du fichier sélectionné
        document.getElementById('profile_image').addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Aucun fichier sélectionné';
            document.getElementById('file-name-display').textContent = fileName;
        });
    </script>
</body>
</html>