<?php include 'header_connex.php'; ?>

<main class="login-content">
        <h1 class="page-title">Connexion</h1>

        <p class="register-link">
          Pas encore inscrit ? <a href="inscription.html" class="signup-link">S'inscrire</a>
        </p>

        <p class="welcome-message">
          Ravi de vous retrouver sur Stage-Aire !<br />
          Retrouver toutes vos offres et candidatures en vous connectant
        </p>

        

        <div class="divider">
          <hr class="divider-line" />
          <span class="divider-text">Ou</span>
          <hr class="divider-line" />
        </div>

        <!-- Formulaire de connexion modifié -->
        <form class="login-form" method="POST" action="connexion.php">
          <div class="form-group">
            <label for="email" class="form-label"
              >E-mail<span class="required">*</span></label
            >
            <input type="email" id="email" name="email" class="form-input" required />
          </div>

          <div class="form-group">
            <label for="password" class="form-label"
              >Mot de passe<span class="required">*</span></label
            >
            <div class="password-container">
              <input
                type="password"
                id="password"
                name="mot_de_passe"
                class="form-input"
                maxlength="30"
                required
              />
              <button type="button" class="password-toggle">👀</button>
            </div>
          </div>

          <a href="#" class="forgot-password">Mot de passe oublié ?</a>

          <button type="submit" class="login-button">Me connecter</button>
        </form>
      </main>
      <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const toggleButton = document.querySelector(".password-toggle");

            toggleButton.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    toggleButton.textContent = "🙈"; // Changer l'icône
                } else {
                    passwordInput.type = "password";
                    toggleButton.textContent = "👀";
                }
            });
        });
</script>


<?php include 'footer.php'; ?>
