<?php
session_start(); // Démarrer la session pour récupérer les infos de l'utilisateur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <link rel="stylesheet" href="page_d_accueil.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo.png" alt="Stage-Aire">
        </div>
        <nav>
            <a name="catégorie" href="#">Page d'accueil</a><a class="tirets">&nbsp;|&nbsp;</a>
            <a name="catégorie" href="liste_des_entreprises.html">Liste des entreprises</a><a class="tirets">&nbsp;|&nbsp;</a>
            <a name="catégorie" href="#">Consulter les offres</a>
        </nav>
        <div class="connexion">
            <div class="logo_connexion">
                <img src="images/connexion.png" alt="Stage-Aire">
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profil.php">Mon Profil</a>
                <a href="deconnexion.php" style="margin-left: 10px; color: red;">Déconnexion</a>
            <?php else: ?>
                <a href="page_de_connexion.html">Connexion</a>
            <?php endif; ?>

        </div>
    </header>
</body>
</html>
