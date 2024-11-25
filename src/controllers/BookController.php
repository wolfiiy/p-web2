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
    public function display() {

        $action = $_GET['action'] . "Action";

        return call_user_func(array($this, $action));
    }

    /**
     * Display Index Action
     *
     * @return string
     */
    private function listAction() {

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

    private function addAction(){

        $view = file_get_contents('../views/addBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }
}