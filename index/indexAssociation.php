<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Connexion Association - GiveTime</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../images/logo-fav/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/style-form.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<?php include("../include/header.php"); ?>

  <div class="page-container">
    <div class="login-card">
      <div class="login-header">
        <a href="../index.html" class="back-link">
          <i class="fas fa-arrow-left"></i> Retour 
        </a><br>
        <h1>Connexion Association</h1>
        <p class="login-subtitle">Accédez à votre espace pour gérer vos offres de bénévolat</p>
      </div>
      
      <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
      <?php endif; ?>
      
      <form action="../Connexion/ConnexionAssociation.php" method="post">
        <div class="form-group">
          <label for="login">
            <i class="fas fa-user"></i> Nom d'utilisateur
          </label>
          <input type="text" name="login" id="login" required placeholder="Entrez votre identifiant">
        </div>
        
        <div class="form-group">
          <label for="mdp">
            <i class="fas fa-lock"></i> Mot de passe
          </label>
          <div class="password-container">
            <input type="password" name="mdp" id="mdp" required placeholder="Entrez votre mot de passe">
            <button type="button" class="toggle-password" aria-label="Afficher/masquer le mot de passe">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        
        <div class="form-options">
          <div class="remember-me">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Se souvenir de moi</label>
          </div>
          <a href="#" class="forgot-password">Mot de passe oublié ?</a>
        </div>
        
        <button type="submit" class="btn-submit">
          <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
      </form>
      
      <div class="login-footer">
        <p>Vous n'avez pas encore de compte ?</p>
        <a href="../Inscription/Inscription.html" class="btn-register">
          Créer un compte
        </a>
      </div>
    </div>
    
    <div class="login-benefits">
      <h2>Bienvenue sur GiveTime</h2>
      <p>Connectez-vous pour accéder à votre espace association et :</p>
      <ul>
        <li><i class="fas fa-bullhorn"></i> Publier vos offres de bénévolat</li>
        <li><i class="fas fa-users"></i> Gérer vos bénévoles</li>
        <li><i class="fas fa-calendar-alt"></i> Organiser vos événements</li>
        <li><i class="fas fa-chart-line"></i> Suivre l'impact de vos actions</li>
      </ul>
      <div class="testimonial">
        <blockquote>
          "GiveTime nous a permis de trouver des bénévoles motivés et de gérer efficacement nos projets."
        </blockquote>
        <cite>— Association Solidarité</cite>
      </div>
    </div>
  </div>
  
  <script>
    // Script pour afficher/masquer le mot de passe
    document.querySelector('.toggle-password').addEventListener('click', function() {
      const passwordInput = document.querySelector('#mdp');
      const icon = this.querySelector('i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  </script>
  
  <?php include("../include/footer.php"); ?>
</body>
</html>