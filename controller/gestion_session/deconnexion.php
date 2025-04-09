<?php
session_start();
session_destroy(); // Détruit toutes les variables de session
header("Location: page_d_accueil.php"); // Redirige vers l'accueil
exit();
