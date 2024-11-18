<?php

// Set to true to report all errors
$debug = true;

if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
date_default_timezone_set('Europe/Zurich');

include_once '../controllers/Controller.php';
include_once '../controllers/HomeController.php';
include_once '../controllers/BookController.php';



class MainController {

    /**
     * Permet de sélectionner le bon contrôler et l'action
     */
    public function dispatch() {
        
        if (!isset($_GET['controller'])) {
            $_GET['controller'] = 'home';
            $_GET['action'] = 'index';
        }


        $currentLink = $this->menuSelected($_GET['controller']);
        $this->viewBuild($currentLink);
    }

    /**
     * Selectionner la page et instancier le contrôleur
     *
     * @param string $page : page sélectionner
     * @return $link : instanciation d'un contrôleur
     */
    protected function menuSelected ($controller) {

        switch($controller){
            case "book":
                $link = new BookController();
                break;
            default:
                $link = new HomeController();
                break;
        }

        return $link;
    }

    /**
     * Construction de la page
     *
     * @param $currentPage : page qui doit s'afficher
     */
    protected function viewBuild($currentPage) {

            $content = $currentPage->display();
            include(dirname(__FILE__, 2) . '../views/partials/head.php');
            include(dirname(__FILE__, 2) . '../views/partials/nav.php');
            echo $content;
            include(dirname(__FILE__, 2) . '../views/partials/footer.php');
    }
}

/**
 * Affichage du site internet - appel du contrôleur par défaut
 */
$controller = new MainController();
$controller->dispatch();

?>