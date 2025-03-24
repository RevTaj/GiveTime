<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/Inscription.css">
</head>
<body>

    <?php
        include ('../include/header.php');
    ?>

    <h1>Inscription</h1>
    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="container">
        <button id="btnBenevole" class="btn">Bénévole</button>
        <button id="btnAssociation" class="btn">Association</button>
    </div>
    
    <div class="form-wrapper">
        <div id="formBenevole" class="form-container">
            <h2>Inscription Bénévole</h2>
            <form action="Inscription.php" method="POST" enctype="multipart/form-data">
                <label for="nomBenevole">Nom:</label>
                <input type="text" id="nomBenevole" name="nomBenevole" required>
                
                <label for="prenomBenevole">Prénom:</label>
                <input type="text" id="prenomBenevole" name="prenomBenevole" required>
                
                <label for="mailBenevole">Email:</label>
                <input type="email" id="mailBenevole" name="mailBenevole" required>
                
                <label for="loginBenevole">Login:</label>
                <input type="text" id="loginBenevole" name="loginBenevole" required>
                
                <label for="mdpBenevole">Mot de passe:</label>
                <input type="password" id="mdpBenevole" name="mdpBenevole" required>
                
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
                
                <label for="localisationBenevole">Localisation (ville):</label>
                <input type="text" id="localisationBenevole" name="localisationBenevole" required>
                
                <label for="descriptionBenevole">Description:</label>
                <textarea id="descriptionBenevole" name="descriptionBenevole" required></textarea>
                
                <label for="photoBenevole">Photo de profil:</label>
                <input type="file" id="photoBenevole" name="photoBenevole" accept="image/*">
                
                <button type="submit" name="submitBenevole" class="btn">S'inscrire</button>
            </form>
        </div>
        
        <div id="formAssociation" class="form-container" style="display: none;">
            <h2>Inscription Association</h2>
            <form action="Inscription.php" method="POST" enctype="multipart/form-data">
                <label for="nomAssociation">Nom:</label>
                <input type="text" id="nomAssociation" name="nomAssociation" required>
                
                <label for="mailAssociation">Email:</label>
                <input type="email" id="mailAssociation" name="mailAssociation" required>
                
                <label for="loginAssociation">Login:</label>
                <input type="text" id="loginAssociation" name="loginAssociation" required>
                
                <label for="mdpAssociation">Mot de passe:</label>
                <input type="password" id="mdpAssociation" name="mdpAssociation" required>
                
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
                
                <label for="localisationAssociation">Localisation (ville):</label>
                <input type="text" id="localisationAssociation" name="localisationAssociation" required>
                
                <label for="descriptionAssociation">Description:</label>
                <textarea id="descriptionAssociation" name="descriptionAssociation" required></textarea>
                
                <label for="photoAssociation">Photo de profil:</label>
                <input type="file" id="photoAssociation" name="photoAssociation" accept="image/*">
                
                <button type="submit" name="submitAssociation" class="btn">S'inscrire</button>
            </form>
        </div>
    
        <div class="preview-container">
            <h2>Prévisualisation du Profil</h2>
            <img id="previewPhoto" src="../images/default-profile.png" alt="Photo de profil">
            <p><strong>Nom:</strong> <span id="previewNom"></span></p>
            <p><strong>Prénom:</strong> <span id="previewPrenom"></span></p>
            <p><strong>Email:</strong> <span id="previewEmail"></span></p>
            <p><strong>Login:</strong> <span id="previewLogin"></span></p>
            <p><strong>Tag:</strong> <span id="previewTag"></span></p>
            <p><strong>Localisation:</strong> <span id="previewLocalisation"></span></p>
            <p><strong>Description:</strong> <span id="previewDescription"></span></p>
        </div>
    </div>
    
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

    // Gestion des clics sur les boutons
    btnBenevole.addEventListener('click', function() {
        formBenevole.style.display = 'block';
        formAssociation.style.display = 'none';
        // Réinitialiser la prévisualisation pour bénévole
        resetPreview();
    });

    btnAssociation.addEventListener('click', function() {
        formBenevole.style.display = 'none';
        formAssociation.style.display = 'block';
        // Réinitialiser la prévisualisation pour association
        resetPreview();
    });
    
    // Fonction pour réinitialiser la prévisualisation
    function resetPreview() {
        previewPhoto.src = "../images/default-profile.png";
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


<?php
include ('../include/footer.php');
?>

</body>
</html>