<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage-Aire</title>
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>
	<header class="header-container">
		<section class="site-header">
			<div class="logo">
				<img src="assets/logo.webp" alt="Stage-Aire">
			</div>

			<div class="burger-menu" onclick="toggleMenu()">
				&#9776; 
			</div>

			<nav class="nav-menu">
				<a name="catégorie" href="index.php?action=home">Page d'accueil</a><a class="tirets">&nbsp;|&nbsp;</a>
				<a name="catégorie" href="index.php?action=liste_entreprise">Liste des entreprises</a><a class="tirets">&nbsp;|&nbsp;</a>
				<a name="catégorie" href="index.php?action=liste_offre">Consulter les offres</a>
			</nav>

			<div class="connexion">
				<?php if (isset($_SESSION['user_id'])): ?>
					<div class="logo_connexion_connecte">
						<img src="assets/connexion.webp" alt="Stage-Aire">
					</div>
					<a href="index.php?action=profil" class="left">Mon Profil</a><a class="tirets">&nbsp;/&nbsp;</a>
					<a href="index.php?action=logout" class="right">Déconnexion</a>
				<?php else: ?>
					<div class="logo_connexion">
						<img src="assets/connexion.webp" alt="Stage-Aire">
					</div>
					<a href="index.php?action=connexion">Connexion</a>
				<?php endif; ?>
			</div>
		</section>
	</header>

<script>
    function toggleMenu() {
        document.querySelector(".nav-menu").classList.toggle("show");
    }
</script>
