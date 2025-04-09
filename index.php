<?php
require_once 'controller/pageController.php';

$page = isset($_GET['action']) ? $_GET['action'] : 'home';

$controller = new PageController();
$controller->renderPage($page);
?>
