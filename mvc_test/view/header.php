<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage-Aire</title>
    <link rel="stylesheet" href="assets/style.css"> <!-- Ajout du CSS externe -->
</head>
<body>
	<header class="header-container">
		<section class="site-header">
			<div class="logo">
				<img src="assets/logo.webp" alt="Stage-Aire">
			</div>
		
			<!-- Icône du menu burger -->
			<div class="burger-menu" onclick="toggleMenu()">
				&#9776; <!-- Symbole burger -->
			</div>
		
			<nav class="nav-menu">
				<a name="catégorie" href="index.php?home">Page d'accueil</a><a class="tirets">&nbsp;|&nbsp;</a>
				<a name="catégorie" href="liste_des_entreprises.html">Liste des entreprises</a><a class="tirets">&nbsp;|&nbsp;</a>
				<a name="catégorie" href="#">Consulter les offres</a>
			</nav>
		
			<div class="connexion">
				<div class="logo_connexion">
					<img src="assets/connexion.webp" alt="Stage-Aire">
				</div>
				<a href="index.php?action=connexion">Connexion</a>
			</div>
		</section>
	</header>
<script>
    function toggleMenu() {
        document.querySelector(".nav-menu").classList.toggle("show");
    }
</script>
