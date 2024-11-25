<?php
/**
 * ETML
 * Auteur : Cindy Hardegger
 * Date: 22.01.2019
 * Controler pour la page d'acceuil
 */

class HomeController extends Controller {

    /**
     * Dispatch current action
     *
     * @return mixed
     */
    public function display() {

        $action = $_GET['action'] . "Action";

        return call_user_func(array($this, $action));
    }

    /**
     * Display Index Action
     *
     * @return string
     */
    private function indexAction() {

        
        include_once('../models/BookModel.php');
        $bookModel = new BookModel();
        include_once('../models/ReviewModel.php');
        $reviewModel = new ReviewModel();
        
        $latestBooks = $bookModel->getLatestBooks(5);
        $ratings;
        foreach ($latestBooks as $book){
            $bookRating = $reviewModel->getAverageRating($book["book_id"]);
            if (!is_null($bookRating)){
                $ratings[$book["book_id"]] = $bookRating;
            }
            else {
                error_log("aaa");
                $ratings[$book["book_id"]] = "no rating";
            }

        }
        
        $view = file_get_contents('../views/indexView.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }
}