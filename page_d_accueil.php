<?php
session_start(); // Démarrer la session pour récupérer les infos de l'utilisateur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <link rel="stylesheet" href="assets/page_d_accueil.css">
</head>
<body>
    <header class="header-container">
        <section class="site-header">
            <div class="logo">
                <img src="images/logo.webp" alt="Stage-Aire">
            </div>
            <nav>
                <a name="catégorie" href="#">Page d'accueil</a><a class="tirets">&nbsp;|&nbsp;</a>
                <a name="catégorie" href="liste_des_entreprises.html">Liste des entreprises</a><a class="tirets">&nbsp;|&nbsp;</a>
                <a name="catégorie" href="#">Consulter les offres</a>
            </nav>
            <div class="connexion">
                <div class="logo_connexion">
                    <img src="images/connexion.webp" alt="Stage-Aire">
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profil.php">Mon Profil</a>
                    <a href="deconnexion.php" style="margin-left: 10px; color: red;">Déconnexion</a>
                <?php else: ?>
                    <a href="page_de_connexion.html">Connexion</a>
                <?php endif; ?>
            </div>
        </section>
    </header>
    <main>
        <div class="image-container">
            <img src="images/travail_1.webp" alt="Travail_1">
            <img src="images/travail_2.webp" alt="Travail_2">
        </div>
        <section class="center-texte-img-container">
            <section class="texte-img-container">
                <div class="texte-bouton">
                    <h1>Bienvenue dans Stage-Aire</h1>
                    <p>L’entreprise qui vous permettra de trouver votre stage en quelques clics.</p>	
                    <a class="bouton" href="page_de_connexion.html">Connexion à votre compte</a>
                </div>
                <div class="image-ovale">
                    <img src="images/oval_main.webp" alt="Image ronde">
                </div>
            </section>
        </section>
        <section class="list-button-container">
            <h2>Trouver le stage idéal</h2>
            <div class="list-button-container">
                <div class="container">
                    <button class="btn">Administratif et Gestion</button>
                    <button class="btn">Informatique et Digital</button>
                    <button class="btn">Industriel et Technique</button>
                    <button class="btn">Commercial et Marketing</button>
                    <button class="btn">Santé et Social</button>
                    <button class="btn">Transport et Logistique</button>
                    <button class="btn">Éducation et Formation</button>
                    <button class="btn">Culture et Créativité</button>
                    <button class="btn hidden">Juridique et Droit</button>
                    <button class="btn hidden">Agroalimentaire</button>
                    <button class="btn hidden">Sécurité et Défense</button>
                    <button class="btn hidden">Tourisme et Hôtellerie</button>
                </div>
                <br>
                <button id="toggleBtn" class="btn show-more">Voir plus</button>
            </div>
            <script>
                function checkScreenWidth() {
                    let hiddenButtons = document.querySelectorAll(".hidden");
                    let toggleBtn = document.getElementById("toggleBtn");
                    if (window.innerWidth <= 820) {
                        hiddenButtons.forEach(btn => btn.style.display = "block");
                        toggleBtn.style.display = "none";
                    } else {
                        hiddenButtons.forEach(btn => btn.style.display = "none");
                        toggleBtn.style.display = "block";
                    }
                }
                document.getElementById("toggleBtn").addEventListener("click", function() {
                    let hiddenButtons = document.querySelectorAll(".hidden");
                    let isHidden = hiddenButtons[0].style.display === "none" || hiddenButtons[0].style.display === "";
                    hiddenButtons.forEach(btn => {
                        btn.style.display = isHidden ? "block" : "none";
                    });
                    this.textContent = isHidden ? "Voir moins" : "Voir plus";
                });
                window.addEventListener("load", checkScreenWidth);
                window.addEventListener("resize", checkScreenWidth);
            </script>
        </section>
    </main>
    <footer class="site-footer">
        <img src="images/logo.webp" alt="Logo Footer" class="footer-logo" />
        <nav class="footer-text">
            <a>© 2025</a>
            <a name="ud_txt" href="A_propos.html">A propos</a> <a>&nbsp;|&nbsp;</a>
            <a name="ud_txt" href="Accesibilite.html">Accesibilité</a> <a>&nbsp;|&nbsp;</a>
            <a name="ud_txt" href="Conditions_generales_d'utilisation.html">Condition générales d'utilisation</a> <a>&nbsp;|&nbsp;</a>
            <a name="ud_txt" href="Politique_de_confidentialite.html">Politique de confidentialité</a> <a>&nbsp;|&nbsp;</a>
            <a name="ud_txt" href="Politique_relative_aux_cookies.html">Politique relative aux cookies</a>
        </nav>
    </footer>
</body>
</html>
