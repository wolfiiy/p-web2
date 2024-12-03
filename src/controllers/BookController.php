<?php

/**
 * ETML
 * Auteur : Valentin Pignat, Sébastien Tille
 * Date: 18.11.2024
 * Description: Controller used for pages dedicated to books.
 */

include_once('../helpers/DataHelper.php');
include_once('../helpers/FormatHelper.php');
include_once('../models/AuthorModel.php');
include_once('../models/BookModel.php');
include_once('../models/PublisherModel.php');
include_once('../models/UserModel.php');

class BookController extends Controller {

    /**
     * Path to the book details view.
     */
    const PATH_TO_BOOK_DETAILS = '../views/detailBook.php';

    /**
     * Dispatch current action
     *
     * @return mixed
     */
    public function display()
    {

        $action = $_GET['action'] . "Action";

        return call_user_func(array($this, $action));
    }

    /**
     * Display page with a list of most recent books with pagination
     *
     * @return string
     */
    private function listAction()
    {
        session_start();

        define("RESULT_PER_PAGE", 10);

        // Get categories for filter select
        include_once("../models/CategoryModel.php");
        $categoryModel = new CategoryModel();
        $genres = $categoryModel->getAllCategory();

        include_once('../models/BookModel.php');
        $bookModel = new BookModel();
        
        // By default page 1
        if(!isset($_GET["page"])){
            $page = 1;
        }
        else{
            $page = $_GET["page"];
        }
        
        // If book category filter changed, change session variable
        if(isset($_GET["bookGenre"])){
            if ($_GET["bookGenre"] == 0){
                unset($_SESSION["genreFilter"]);
            }
            else{
                $_SESSION["genreFilter"] = $_GET["bookGenre"];
            }
        }

        // Get latest book with category filter
        if (!isset($_SESSION["genreFilter"])){
            $books = $bookModel->getLatestBooks(RESULT_PER_PAGE, $page);
            $nbResult = $bookModel->resultCount();
        }
        else{
            $books = $bookModel->getLatestBooks(RESULT_PER_PAGE, $page, $_SESSION["genreFilter"]);
            $nbResult = $bookModel->resultCount($_SESSION["genreFilter"]);
        }

        // Get the total number of result and pages
        $maxPage = round($nbResult/RESULT_PER_PAGE);

        // Entre 0 et RESULT_PER_PAGE, une seule page
        if ($maxPage == 0) $maxPage = 1;

        $books = DataHelper::BookPreview($books);
        
        $view = file_get_contents('../views/listView.php');


        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Opens a page containing details about a specific book.
     * @param int $id Unique ID of the book.
     * @return string The page to be printed.
     */
    private function detailAction() {
        // Check if ID has been set.
        // TODO error page if ID is not set.
        if (isset($_GET['id'])) $id = $_GET['id'];
        else $id = 0;

        $bookModel = new BookModel();
        $userModel = new UserModel();

        $book = $bookModel->getBookById($id);
        $book = DataHelper::getOneBookDetails($book);

        // Handle ratings
        if (isset($_POST['rate'])) {
            // Add rating to database
            if (isset($_SESSION['id'])) {
                $userModel->setBookRating(
                    $id, 
                    (int)$_SESSION['id'], 
                    $_POST['rating']
                );
            }

            // Clear post to allow refreshes
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }

        $user = $userModel->getUserById($book['user_fk']);

        $userRating = isset($_SESSION['username']) 
            ? $userModel->getBookRating($id, $_SESSION['id'])
            : '-';

        $dropdown = DataHelper::createRatingDropdown($userRating);

        $view = file_get_contents(self::PATH_TO_BOOK_DETAILS);

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    private function addAction()
    {
        include_once("../models/CategoryModel.php");
        $categoryModel = new CategoryModel();
        $genres = $categoryModel->getAllCategory();

        $view = file_get_contents('../views/addBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    private function textController($info)
    {
        //vérifier le champs 
        if (isset($info)) {
            $info = trim($info); //suppression des espaces en début et fin

            if (empty($info)) {
                return false; // si le champs est vide
            } elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-]+$/", $info)) {
                return false;
            }
            $info = htmlspecialchars($info);
            return $info;
        } else {
            return false;
        }
    }
    private function yearController($year){
        if (isset($year)){
            $year = trim($year);

            if(empty($year)){
                return false;
            }
            elseif (!preg_match("/^\d{4}$/", $year)){
                return false;
            }
            return $year;
        }
        else{
            return false;
        }
    }
    private function pageController($number){
        
        if (isset($number)){
            $number = trim($number);

            if(empty($number)){
                return false;
            }
            elseif(!is_numeric($number)){
                return false;
            }
            return $number;
        }
        else{
            return false;
        }
    }
    private function urlController($url){
        if (isset($url)){
            $url = trim($url);

            if(empty($url)){
                return false;
            }
            elseif (!preg_match("/(?:http[s]?:\/\/.)?(?:www\.)?[-a-zA-Z0-9@%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/", $url)){
                return false;
            }
            return $url;
        }
        else{
            return false;
        }
    }
    private function resumeController($resume){
        if (isset($resume)){
            $url = trim($resume);

            if(empty($resume)){
                return false;
            }
            elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $resume)){
                return false;
            }
            $resume = htmlspecialchars($resume);
            return $resume;
        }
        else{
            return false;
        }
    }
}
