<?php

/**
 * Authors: Cindy Hardegger, Valentin Pignat, Sebastien Tille
 * Date: November 25th, 2024
 */

include_once '../controllers/Controller.php';
include_once '../controllers/HomeController.php';
include_once '../controllers/BookController.php';
include_once '../controllers/UserController.php';

// Enables debugging
$debug = true;

if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

date_default_timezone_set('Europe/Zurich');

/**
 * Main controller. Serves as an entry point to the application.
 */
class MainController {

    /**
     * Selects the correct controller and action.
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
     * Selects and instancies the required controller.
     * @param string $page Page to open.
     * @return $link Controller instantiation.
     */
    protected function menuSelected($controller) {
        switch($controller){
            case "book":
                $link = new BookController();
                break;
            case "user":
                $link = new UserController();
                break;
            default:
                $link = new HomeController();
                break;
        }

        return $link;
    }

    /**
     * Builds a page.
     * @param $currentPage Page to build.
     */
    protected function viewBuild($currentPage) {
            $content = $currentPage->display();
            include __DIR__ . '/../views/partials/head.php';
            include __DIR__ . '/../views/partials/nav.php';
            echo $content;
            include __DIR__ . '/../views/partials/footer.php';
    }
}

/**
 * Entry point (display the default controller).
 */
$controller = new MainController();
$controller->dispatch();

?>