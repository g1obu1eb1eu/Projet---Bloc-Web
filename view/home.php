<?php include 'header.php'; ?>

<main>
    <div class="image-container">
        <img src="assets/travail_1.webp" alt="Travail_1">
        <img src="assets/travail_2.webp" alt="Travail_2">
    </div>

    <section class="center-texte-img-container">
        <section class="texte-img-container">
            <div class="texte-bouton">
                <h1>Bienvenue dans Stage-Aire</h1>
                <p>L’entreprise qui vous permettra de trouver votre stage en quelques clics.</p>    
                <a class="bouton" href="index.php?action=connexion">Connexion à votre compte</a>
            </div>
            <div class="image-ovale">
                <img src="assets/oval_main.webp" alt="Image ronde">
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

<?php include 'footer.php'; ?>
