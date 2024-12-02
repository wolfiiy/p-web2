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

        include_once('../helpers/utils.php');
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

        var_dump(isUserConnected());

        $bookModel = new BookModel();
        
        $latestBooks = $bookModel->getLatestBooks(5);

        $latestBooks = $this->BookPreview($latestBooks);
        
        $view = file_get_contents('../views/indexView.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    protected function BookPreview($books){
        include_once('../models/ReviewModel.php');
        $reviewModel = new ReviewModel();
        include_once('../models/AuthorModel.php');
        $authorModel = new AuthorModel();
        include_once('../models/CategoryModel.php');
        $categoryModel = new CategoryModel();
        include_once('../models/UserModel.php');
        $userModel = new UserModel();

        foreach ($books as &$book){

            // Get average rating
            $bookRating = $reviewModel->getAverageRating($book["book_id"]);
            if (!is_null($bookRating)){
                $book["average_rating"] = $bookRating;
            }
            else {
                $book["average_rating"] = "no rating";
            }

            // Get author full name
            $author = $authorModel->getAuthorById($book["author_fk"]);
            $book["author_name"] = $author['first_name'] . " " . $author['last_name'];

            // Get category name
            $category = $categoryModel->getCateoryById($book["category_fk"]);
            $book["category_name"] = $category["name"];

            // Get user who added the book
            $user = $userModel->getUserById($book["user_fk"]);
            $book["username_name"] = $user["username"];

            
        }

        return $books;

    }
}