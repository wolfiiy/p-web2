<?php

/**
 * ETML
 * Auteur : Valentin Pignat, Sébastien Tille
 * Date: 18.11.2024
 * Description: Controller used for pages dedicated to books.
 */

include('../models/AuthorModel.php');
include('../models/BookModel.php');
include('../models/PublisherModel.php');
include('../models/UserModel.php');

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
     * Display Index Action
     *
     * @return string
     */
    private function listAction()
    {

        $view = file_get_contents('../views/indexView.php');

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
        $publisherModel = new PublisherModel();
        $userModel = new UserModel();
        $authorModel = new AuthorModel();

        $book = $bookModel->getBookById($id);
        $publisher = $publisherModel->getPublisherById($book['publisher_fk']);
        $author = $authorModel->getAuthorById($book['author_fk']);
        $user = $userModel->getUserById($book['user_fk']);
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
