<?php
/**
 * ETML
 * Authors : Cindy Hardegger, Valentin Pignat, Sébastien Tille
 * Date: January 22nd, 2019
 */

include_once('../models/ConstantsModel.php');

/**
 * Home page controller.
 */
class HomeController extends Controller {

    /**
     * Dispatch current action.
     * @return mixed A callback to a function.
     */
    public function display() {
        $action = $_GET['action'] . "Action";
        return call_user_func(array($this, $action));
    }

    /**
     * Prints the home page view.
     * @return string A strong that contains all the content of the home page.
     */
    private function indexAction() {
        include_once('../models/BookModel.php');
        $bookModel = new BookModel();
        
        $latestBooks = $bookModel->getLatestBooks(5);
        $latestBooks = $this->BookPreview($latestBooks);
        
        $view = file_get_contents('../views/indexView.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Gets all details regarding an array of books.
     * @param array $books An array of books.
     * @return array The same array of books with additionnal details.
     */
    protected function BookPreview($books){
        include_once('../models/ReviewModel.php');
        include_once('../models/AuthorModel.php');
        include_once('../models/CategoryModel.php');
        include_once('../models/UserModel.php');

        $reviewModel = new ReviewModel();
        $authorModel = new AuthorModel();
        $categoryModel = new CategoryModel();
        $userModel = new UserModel();

        foreach ($books as &$book){
            // Get the average rating or use a placeholder text
            $bookRating = $reviewModel->getAverageRating($book["book_id"]);
            
            if (!is_null($bookRating)) 
                $book["average_rating"] = $bookRating;
            else 
                $book["average_rating"] = Constants::NO_RATING;

            // Get the author's full name
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