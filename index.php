<?php
require_once 'controller/pageController.php';

// Récupérer l'action demandée
$page = isset($_GET['action']) ? $_GET['action'] : 'home';

// Instancier le contrôleur
$controller = new PageController();
$controller->renderPage($page);
?>
