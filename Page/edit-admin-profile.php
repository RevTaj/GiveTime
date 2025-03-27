<?php 
session_start();
require '../bdd.php'; // Connexion à la base de données

// Vérification si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['Identifiant']) || $_SESSION['Permission'] != 4) {
    die("Accès non autorisé.");
}

$id_admin = $_SESSION['Identifiant'];
$notification = '';
$notificationType = 'success';

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupération des données du formulaire
        $nom = strip_tags(trim($_POST['nom']));
        $prenom = strip_tags(trim($_POST['prenom']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        // Variables pour construire la requête SQL
        $setFields = [
            "nomAdmin = :nom",
            "prenomAdmin = :prenom",
            "mailAdmin = :email"
        ];
        
        $params = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'id' => $id_admin
        ];
        
        // Gestion du changement de mot de passe
        if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $setFields[] = "mdpAdmin = :password";
                $params['password'] = sha1($_POST['new_password']);
            } else {
                $notification = "Les mots de passe ne correspondent pas.";
                $notificationType = 'error';
            }
        }
        
        // Gestion de l'image de profil
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                $newname = 'admin_' . $id_admin . '_' . time() . '.' . $ext;
                $destination = '../images/profils/' . $newname;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                    $setFields[] = "image = :image";
                    $params['image'] = $newname;
                } else {
                    $notification = "Erreur lors de l'upload de l'image.";
                    $notificationType = 'error';
                }
            } else {
                $notification = "Format d'image non accepté. Formats autorisés: " . implode(', ', $allowed);
                $notificationType = 'error';
            }
        }
        
        // Construction dynamique de la requête SQL
        $sql = "UPDATE gt_admin SET " . implode(", ", $setFields) . " WHERE id_admin = :id";
        
        // Exécution de la requête si pas d'erreur
        if ($notificationType !== 'error') {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $notification = "Profil mis à jour avec succès !";
        }
        
    } catch (PDOException $e) {
        $notification = "Erreur lors de la mise à jour du profil: " . $e->getMessage();
        $notificationType = 'error';
    }
}

// Récupération des informations actuelles de l'administrateur
try {
    $stmt = $db->prepare("SELECT * FROM gt_admin WHERE id_admin = :id");
    $stmt->execute(['id' => $id_admin]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        die("Profil introuvable.");
    }

    // Préparation des données pour affichage
    $prenom = htmlspecialchars($admin['prenomAdmin'] ?? 'Admin');
    $nom = htmlspecialchars($admin['nomAdmin'] ?? 'Système');
    $email = htmlspecialchars($admin['mailAdmin'] ?? 'admin@givetime.com');
    $profileImage = !empty($admin['image']) ? '' . htmlspecialchars($admin['image']) : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg';

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
    <link rel="stylesheet" href="../css/edit-profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Modifier le profil administrateur - <?= $nom ?></title>
    <style>
        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .form-header .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .notification {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .notification.success {
            background-color: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        
        .notification.error {
            background-color: rgba(244, 67, 54, 0.1);
            color: #d32f2f;
            border-left: 4px solid #d32f2f;
        }
        
        .profile-admin-form .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e5e5;
        }
        
        .profile-admin-form .section-title {
            font-size: 18px;
            color: #550080;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .profile-admin-form .section-title i {
            margin-right: 8px;
            color: #550080;
        }
    </style>
</head>
<body>

<?php include("../include/header.php"); ?>

<main class="profile-container">
    <div class="edit-profile-container">
        <div class="edit-profile-header">
            <h1><i class="fas fa-user-shield"></i> Modifier le profil administrateur</h1>
            <a href="profil-admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour au profil</a>
        </div>
        
        <?php if (!empty($notification)): ?>
        <div class="notification <?= $notificationType ?>">
            <?= $notification ?>
        </div>
        <?php endif; ?>
        
        <form class="profile-admin-form" method="post" enctype="multipart/form-data">
            <!-- En-tête du formulaire avec photo de profil -->
            <div class="form-header">
                <img src="<?= $profileImage ?>" alt="Photo de profil" class="profile-img">
                <div>
                    <h2><?= $nom ?> <?= $prenom ?></h2>
                    <p>Administrateur GiveTime</p>
                </div>
            </div>
            
            <!-- Section des informations personnelles -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-id-card"></i> Informations personnelles</h3>
                
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?= $nom ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?= $prenom ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $email ?>" required>
                </div>
            </div>
            
            <!-- Section de la photo de profil -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-camera"></i> Photo de profil</h3>
                
                <div class="form-group">
                    <label for="profile_image">Changer la photo de profil:</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*">
                    <small>Formats acceptés: JPG, PNG, GIF. Taille max: 2Mo.</small>
                </div>
            </div>
            
            <!-- Section du changement de mot de passe -->
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-lock"></i> Sécurité</h3>
                
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe:</label>
                    <input type="password" id="new_password" name="new_password">
                    <small>Laissez vide si vous ne souhaitez pas changer de mot de passe.</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe:</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Enregistrer les modifications</button>
                <a href="profil-admin.php" class="btn-cancel"><i class="fas fa-times"></i> Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php include("../include/footer.php"); ?>

<script>
    // Prévisualisation de l'image
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.querySelector('.profile-img').src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Validation du formulaire
    document.querySelector('.profile-admin-form').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== '' && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
        }
    });
</script>

</body>
</html>