<?php
class PageController {
    public function renderPage($page) {
        switch ($page) {
            case 'home':
                include 'view/home.php';
                break;
            case 'connexion':
                include 'view/connexion.php';
                break;
            case 'politique_confidentialite':
                include 'view/politique_confidentialite.php';
                break;
            case 'condition_generales_d_utilisation':
                include 'view/condition_generales_d_utilisation.php';
                break;
            case 'accessibilite':
                include 'view/accessibilite.php';
                break;           
            case 'cookie':
                include 'view/cookie.php';
                break;
            case 'a_propos':
                include 'view/a_propos.php';
                break;
            default:
                include 'view/home.php';
                break;
        }
    }
}
