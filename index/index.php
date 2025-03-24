<!DOCTYPE html>
<html>
<head>
  <title>Authentification</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../css/style-form.css">
</head>
<body>
  <a href="../index.php" class="back-button">Retour</a>
  <h1>Page de Connexion - Benevole</h1>
  <div class="container">
    <fieldset>
      <legend>Authentification</legend>
      <form action="../Connexion/Connexion.php" method="post">
        <p>
          <label for="login">Veuillez saisir votre nom d'utilisateur : </label>
          <input type="text" name="login" id="login" required>
        </p>
        <p>
          <label for="mdp">Veuillez saisir votre mot de passe : </label>
          <input type="password" name="mdp" id="mdp" required>
        </p>
        <p>
          <input type="submit" value="valider">
        </p>
      </form>

      <?php
      if (isset($_GET['msg'])) {
          echo '<p class="error-message">' . htmlspecialchars($_GET['msg']) . '</p>';
      }
      ?>
    </fieldset>
  </div>
  
  <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <h3>GiveTime</h3>
                <p>GiveTime est une plateforme dédiée à la mise en relation des bénévoles et des associations.</p>
            </div>
            <div class="footer-center">
                <h4>Contact</h4>
                <p>Email: contact@givetime.org</p>
                <p>Téléphone: +33 1 23 45 67 89</p>
                <p>Adresse: 123 Rue de la Solidarité, 75000 Paris, France</p>
            </div>
            <div class="footer-right">
                <h4>Suivez-nous</h4>
                <div class="social-links">
                    <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/768px-2023_Facebook_icon.svg.png" alt="Facebook"></a>
                    <a href="#"><img src="https://img.freepik.com/vecteurs-libre/nouvelle-conception-icone-x-du-logo-twitter-2023_1017-45418.jpg" alt="Twitter"></a>
                    <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/768px-Instagram_icon.png" alt="Instagram"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 GiveTime. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>