<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur d'accès</title>
    <link rel="stylesheet" href="style/erreur.css">
</head>
<body>
<div class="container">
    <div class="error-container">
        <div class="icon">⚠️</div>
        <div class="error-message">Vous ne pouvez accéder à cette page</div>
        <a href="../index.html"class="btn">Revenir à l'acceuil</a>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.error-container').style.display = 'block';
            }, 1000);
        });
    </script>
    
</body>
</html>
