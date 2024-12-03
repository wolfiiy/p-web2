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

class BookController extends Controller
{

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
    private function detailAction()
    {
        // Check if ID has been set.
        // TODO error page if ID is not set.
        if (isset($_GET['id'])) $id = $_GET['id'];
        else $id = 0;

        $bookModel = new BookModel();
        $publisherModel = new PublisherModel();
        $userModel = new UserModel();
        $authorModel = new AuthorModel();

        $book = $bookModel->getBookById($id);
        //$book = DataHelper::BookPreview($book);

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
    private function insertAction()
    {
        include_once("../models/BookModel.php");
        include_once("../models/PublisherModel.php");
        include_once("../models/AuthorModel.php");

        $addBook = new BookModel();
        $addPublisher = new PublisherModel();
        $addAuthor = new AuthorModel();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Controler si l'éditeur existe déja 
            $idPublisher = $addPublisher->getPublisherByName($_POST["bookEditor"]);

            //Créer un éditeur s'il n'existe pas
            if ($idPublisher === 0) {
                $publisher = $addPublisher->insertPublisher($_POST["bookEditor"]);
                $idPublisher = (int)$addPublisher->getPublisherByName($_POST["bookEditor"]);
            }

            //Controler si l'auteur exite déjà
            $idAuthor = $addAuthor->getAuthorByNameAndFirstname($_POST["authorFirstName"], $_POST["authorLastName"]);

            //Créer l'autheur s'il n'existe pas
            if ($idAuthor === 0) {
                $author = $addAuthor->insertAuthor($_POST["authorFirstName"], $_POST["authorLastName"]);
                $idAuthor = (int)$addAuthor->getAuthorByNameAndFirstname($_POST["authorFirstName"], $_POST["authorLastName"]);
            }

            //téléchargement et traitement des images
            if (!isset($_FILES["coverImage"]) || $_FILES["coverImage"]["error"] !== UPLOAD_ERR_OK) {
                die("Erreur lors du téléchargement de l'image : " . $_FILES["coverImage"]["error"]);
            }

            // Vérifier la taille du fichier (limite : 2 Mo)
            if ($_FILES["coverImage"]["size"] > 2 * 1024 * 1792) {
                die("Erreur : Le fichier est trop volumineux.");
            }

            // Vérifier le type MIME du fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES["coverImage"]["type"], $allowedTypes)) {
                die("Erreur : Type de fichier non autorisé.");
            }

            // Récupérer l'extension du fichier original
            $fileExtension = pathinfo($_FILES["coverImage"]["name"], PATHINFO_EXTENSION);

            // Générer un nom de fichier unique et court
            $filename = uniqid('img_', true) . '.' . $fileExtension; // "img_" pour une identification facile

            // Définir le chemin de destination
            $destination = "../public/assets/img/cover/" . $filename;

            // Définir la source du fichier temporaire
            $source = $_FILES["coverImage"]["tmp_name"];

            // Déplacer le fichier
            $result = move_uploaded_file($source, $destination);
            if (!$result) {
                die("Erreur : Impossible de déplacer le fichier téléchargé.");
            }

            // Débogage pour confirmer le chemin final
            error_log("Fichier téléchargé avec succès : " . $destination);

            //TODO: utilisateur défini pour les test
            $user_fk = 1;

            //ajout d'un livre
            $addBook->insertBook($_POST["bookTitle"], $_POST["snippetLink"], $_POST["bookSummary"], $_POST["bookEditionYear"], $destination, $_POST["bookPageNb"], $user_fk, $_POST["bookGenre"], $idPublisher, $idAuthor);

            header('Location: index.php?controller=book&action=add');
        }
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
    private function yearController($year)
    {
        if (isset($year)) {
            $year = trim($year);

            if (empty($year)) {
                return false;
            } elseif (!preg_match("/^\d{4}$/", $year)) {
                return false;
            }
            return $year;
        } else {
            return false;
        }
    }
    private function pageController($number)
    {

        if (isset($number)) {
            $number = trim($number);

            if (empty($number)) {
                return false;
            } elseif (!is_numeric($number)) {
                return false;
            }
            return $number;
        } else {
            return false;
        }
    }
    private function urlController($url)
    {
        if (isset($url)) {
            $url = trim($url);

            if (empty($url)) {
                return false;
            } elseif (!preg_match("/(?:http[s]?:\/\/.)?(?:www\.)?[-a-zA-Z0-9@%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/", $url)) {
                return false;
            }
            return $url;
        } else {
            return false;
        }
    }
    private function resumeController($resume)
    {
        if (isset($resume)) {
            $url = trim($resume);

            if (empty($resume)) {
                return false;
            } elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $resume)) {
                return false;
            }
            $resume = htmlspecialchars($resume);
            return $resume;
        } else {
            return false;
        }
    }
}
