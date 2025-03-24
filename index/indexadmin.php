<!DOCTYPE html>
<html>
<head>
  <title>Authentification</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../css/style-form.css">
</head>
<body>
  <a href="../index.php" class="back-button">Retour</a>
  <h1>Page de Connexion - Administrateur</h1>
  <div class="container">
    <fieldset>
      <legend>Authentification</legend>
      <form action="../Connexion/ConnexionAdmin.php" method="post">
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
</body>
</html>
