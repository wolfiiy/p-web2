<?php

/**
 * ETML
 * Authors: Cindy Hardegger, Valentin Pignat, Sébastien Tille
 * Date: January 22nd, 2019
 */

include_once('../models/ConstantsModel.php');
include_once('../helpers/utils.php');

/**
 * Home page controller.
 */
class HomeController extends Controller {

    /**
     * Dispatch current action.
     * @return mixed A callback to a function.
     */
    public function display() {
        include_once('../helpers/utils.php');
        $action = $_GET['action'] . "Action";
        return call_user_func(array($this, $action));
    }

    /**
     * Prints the home page view.
     * @return string The home view.
     */
    private function indexAction() {
        include_once('../helpers/DataHelper.php');
        include_once('../helpers/HtmlWriter.php');
        include_once('../models/BookModel.php');

        $bookModel = new BookModel();
        
        $latestBooks = $bookModel->getLatestBooks(5);
        $latestBooks = DataHelper::bookPreview($latestBooks);
        
        $view = file_get_contents('../views/indexView.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }
}