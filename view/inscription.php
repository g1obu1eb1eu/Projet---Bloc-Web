<?php include 'header.php'; ?>

<main class="login-content">
      <h1 class="page-title">Inscription</h1>
      <p class="register-link">
        Déjà un compte ? <a href="index.php?action=connexion" class="signup-link">Se connecter</a>
      </p>

      <p class="welcome-message">
        Ravi de vous retrouver sur Stage-Aire !<br />
        Inscrivez-vous et accédez à des centaines d'offres adaptées aux étudiants. <br />
        Bénéficiez d'offres personnalisées, gérez vos candidatures facilement, et communiquez directement avec les recruteurs.<br />
        Boostez votre avenir professionnel dès aujourd'hui !
      </p>


      <div class="divider">
        <hr class="divider-line" />
        <span class="divider-text">🌐</span>
        <hr class="divider-line" />
      </div>


      <form class="login-form" action="index.php?action=inscription" method="POST">
      <div class="form-group">
        <label for="nom" class="form-label">Nom : </label>
        <input type="text" id="nom" name="nom" class="form-input" required>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email :</label>
        <input type="email" id="email" name="email" class="form-input" required>
      </div>

      <div class="form-group">
        <label for="mot_de_passe" class="form-label">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-input" required>
      </div>

      <div class="form-group">
        <label for="prenom" class="form-label">Prenom :</label>
        <input type="text" id="prenom" name="prenom" class="form-input" required>
      </div>

      <div class="form-group">
        <label for="telephone" class="form-label">Telephone :</label>
        <input type="tel" id="telephone" name="telephone" class="form-input" required>
      </div>

      <div class="form-group">
        <label for="date_de_naissance" class="form-label">Date de naissance :</label>
        <input type="date" id="date_de_naissance" name="date_de_naissance" class="form-input" required>
      </div>

      <div class="form-group">
        <label for="genre" class="form-label">Genre :</label>
        <select id="genre" name="genre" class="form-input">
          <option value="homme">Homme</option>
          <option value="femme">Femme</option>
          <option value="autre">Autre</option>
          </select>
          <!-- <input type="text" id="genre" name="genre" class="form-input" required>   -->
      </div>

        <button type="submit" class="login-button">S'inscrire</button>
    </form>
  </main>

<?php include 'footer.php'; ?>
