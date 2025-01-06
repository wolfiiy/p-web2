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
     * Default value of the category filter (i.e. shows everything).
     */
    const DEFAULT_CATEGORY_FILTER = 0;

    /**
     * First page number in the "list all books" page.
     */
    const DEFAULT_PAGE_NUMBER = 1;

    /**
     * Default value of the search filter (i.e. searches nothing).
     */
    const DEFAULT_SEARCH_FILTER = "";

    /**
     * Path to the book details view.
     */
    const PATH_TO_BOOK_DETAILS = '../views/detailBook.php';

    /**
     * Number of result per page for pagination
     */
    const RESULT_PER_PAGE = 10;

    /**
     * Dispatch current action
     *
     * @return mixed
     */
    public function display()
    {
        include_once('../helpers/utils.php');
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
        include_once('../helpers/HtmlWriter.php');
        include_once("../models/CategoryModel.php");
        include_once('../models/BookModel.php');

        $bookModel = new BookModel();
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllCategory();

        // By default page 1
        if (!isset($_GET["page"]))
            $page = 1;
        else
            $page = $_GET["page"];

        // Defaults to all all books of all categories (i.e. empty filters)
        if (!isset($_GET["bookGenre"]))
            $_GET["bookGenre"] = self::DEFAULT_CATEGORY_FILTER;

        if (!isset($_GET["searchName"]))
            $_GET["searchName"] = self::DEFAULT_SEARCH_FILTER;

        // Create the category filter dropdown
        $categoryDropdown = '';
        foreach ($categories as $c) {
            $categoryDropdown .= '<option value="' . $c['category_id'] . '"';

            if ($c["category_id"] == $_GET["bookGenre"])
                $categoryDropdown .= "selected";

            $categoryDropdown .= ">" . ucfirst($c['name']) . '</option>';
        }

        // Get latest book with category filter and name filter
        // Get total number of result for pagination
        if ($_GET["bookGenre"] == 0) {
            $books = $bookModel->getLatestBooks(
                self::RESULT_PER_PAGE,
                $page,
                keyword: $_GET["searchName"]
            );

            $nbResult = $bookModel->resultCount(
                keyword: $_GET["searchName"]
            );
        } else {
            $books = $bookModel->getLatestBooks(
                self::RESULT_PER_PAGE,
                $page,
                $_GET["bookGenre"],
                keyword: $_GET["searchName"]
            );

            $nbResult = $bookModel->resultCount(
                $_GET["bookGenre"],
                keyword: $_GET["searchName"]
            );
        }

        // Get the total number of result and pages
        $maxPage = ceil($nbResult / self::RESULT_PER_PAGE);
        error_log($maxPage);

        // Between 0 and RESULT_PER_PAGE, one page
        if ($maxPage == 0)
            $maxPage = self::DEFAULT_PAGE_NUMBER;

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
    private function detailAction()
    {
        // Check if a user is authentified
        if (!isUserConnected()){
            header("Location: index.php");
        }

        // Check if ID has been set.
        // TODO error page if ID is not set.
        if (isset($_GET['id'])) $id = $_GET['id'];
        else $id = 0;

        $bookModel = new BookModel();
        $userModel = new UserModel();

        $book = $bookModel->getBookById($id);
        $book = DataHelper::getOneBookDetails($book);

        // Check that cover exists locally
        if (!file_exists($book['cover_image']))
            $book['cover_image'] = Constants::DEFAULT_COVER;

        // Handle ratings
        if (isset($_POST['rate'])) {
            // Add rating to database
            if (isset($_SESSION['id'])) {
                $userModel->setBookRating(
                    $id,
                    (int)$_SESSION['user_id'],
                    $_POST['rating']
                );
            }

            // Clear post to allow refreshes
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }

        $user = $userModel->getUserById($book['user_fk']);

        $userRating = isset($_SESSION['user_id'])
            ? $userModel->getBookRating($id, $_SESSION['user_id'])
            : '-';

        $dropdown = DataHelper::createRatingDropdown($userRating);

        $view = file_get_contents(self::PATH_TO_BOOK_DETAILS);

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Display a form to add a new book
     */
    private function addAction()
    {
        $h1 = "Ajout d'un livre";
        $submitButton = "Ajouter";

        // If no user is connected, redirect to index
        if (!isUserConnected()) {
            header("Location: index.php");
        }

        include_once("../models/CategoryModel.php");
        $categoryModel = new CategoryModel();
        $genres = $categoryModel->getAllCategory();

        // Form
        $actionURL = "index.php?controller=book&action=insert";
        $authorFirstName    = $_POST["authorFirstName"] ?? '';
        $authorLastName     = $_POST["authorLastName"] ?? '';
        $bookTitle          = $_POST["bookTitle"] ?? '';
        $bookEditor         = $_POST["bookEditor"] ?? '';
        $bookPageNb         = $_POST["bookPageNb"] ?? '';
        $snippetLink        = $_POST["snippetLink"] ?? '';
        $bookSummary        = $_POST["bookSummary"] ?? '';
        $bookEditionYear    = $_POST["bookEditionYear"] ?? '';
        $bookGenre           = $_POST["bookGenre"] ?? '';

        $view = file_get_contents('../views/addBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Display a form to modify a book
     */
    private function modifyAction()
    {

        $h1 = "Modification d'un livre";
        $submitButton = "Modifier";

        include_once("../models/BookModel.php");
        $bookModel = new BookModel();
        $book = $bookModel->getBookById($_GET["id"]);

        include_once("../models/AuthorModel.php");
        $authorModel = new AuthorModel();
        $author = $authorModel->getAuthorById($book["author_fk"]);

        include_once("../models/PublisherModel.php");
        $publisherModel = new PublisherModel();
        $publisher = $publisherModel->getPublisherById($book["publisher_fk"]);

        include_once("../models/CategoryModel.php");
        $categoryModel = new CategoryModel();
        $genres = $categoryModel->getAllCategory();

        // If no user is connected, redirect to index
        if (isset($_SESSION["user_id"])) {
            if (!isAdminConnectedUser() && $_SESSION["user_id"] != $book["user_fk"]) {
                header("Location: index.php");
            }
        } else {
            header("Location: index.php");
        }

        $view = file_get_contents('../views/addBook.php');
        $actionURL = "index.php?controller=book&action=update&id=" . $book["book_id"];
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Insert a new book in the database
     */
    private function insertAction()
    {
        include_once("../models/BookModel.php");
        include_once("../models/PublisherModel.php");
        include_once("../models/AuthorModel.php");
        include_once("../models/ConstantsModel.php");

        $addBook = new BookModel();
        $idbook = new BookModel();
        $addPublisher = new PublisherModel();
        $addAuthor = new AuthorModel();

        $submitButton = "Ajouter";
        $errors    = [];
        $authorFirstName = "";
        $authorLastName = "";
        $bookTitle = "";
        $bookEditor = "";
        $bookPageNb = "";
        $snippetLink = "";
        $bookSummary = "";
        $bookEditionYear="";
        $bookGenre="";

        $_POST["validated"] = 0;
        error_log($_POST["bookEditionYear"]);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            error_log($_POST["bookEditionYear"]);
            /*
            * NETTOYAGE DES DONNES
            *  récupérer plusieurs valeurs d'une super-globale et filter ses données.
            * INPUT_POST pour la super globale $_POST
            */
            $_POST = filter_input_array(
                INPUT_POST,
                [
                    "authorFirstName"   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    "authorLastName"    => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    "bookTitle"         => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    "bookEditor"        => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    "bookPageNb"        => FILTER_SANITIZE_NUMBER_INT,
                    "snippetLink"       => FILTER_SANITIZE_URL,
                    "bookSummary"       => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    "bookEditionYear"   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                    "bookGenre"         => FILTER_SANITIZE_FULL_SPECIAL_CHARS


                ]
            );
            error_log($_POST["bookEditionYear"]);
            /*
                * VALIDATIONS DES DONNEES
                * les ?? qui permettent d'initialiser les variables avec la valeur '' dans le cas ou $_POST['key'] est null
             */

            $authorFirstName    = $_POST["authorFirstName"] ?? '';
            $authorLastName     = $_POST["authorLastName"] ?? '';
            $bookTitle          = $_POST["bookTitle"] ?? '';
            $bookEditor         = $_POST["bookEditor"] ?? '';
            $bookPageNb         = $_POST["bookPageNb"] ?? '';
            $snippetLink        = $_POST["snippetLink"] ?? '';
            $bookSummary        = $_POST["bookSummary"] ?? '';
            $bookEditionYear    = $_POST["bookEditionYear"] ?? '';
            $bookGenre           = $_POST["bookGenre"] ?? '';

            error_log($bookEditionYear);
            //snippetLink
            if (!$snippetLink) {
                $errors["snippetLink"] = Constants::ERROR_REQUIRED;
            } else if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $snippetLink)) {
                $errors["snippetLink"] = Constants::ERROR_TEXT;
            }
            // authorFirstName
            if (!$authorFirstName) {
                $errors["authorFirstName"] = Constants::ERROR_REQUIRED;
            } else if (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/", $authorFirstName)) {
                $errors["authorFirstName"] = Constants::ERROR_NAME;
            } elseif (mb_strlen($authorFirstName) < 2 || mb_strlen($authorFirstName) > 150) {
                $errors["authorFirstName"] = Constants::ERROR_LENGTH;
            }

            // authorLastName
            if (! $authorLastName) {
                $errors["authorLastName"] = Constants::ERROR_REQUIRED;
            } else if (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/",  $authorLastName)) {
                $errors["authorLastName"] = Constants::ERROR_NAME;
            } elseif (mb_strlen($authorLastName) < 2 || mb_strlen($authorLastName) > 150) {
                $errors["authorLastName"] = Constants::ERROR_LENGTH;
            }

            //bookTitle 
            if (!$bookTitle) {
                $errors["bookTitle"] = Constants::ERROR_REQUIRED;
            } else if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $bookTitle)) {
                $errors["bookTitle"] = Constants::ERROR_TEXT;
            } elseif (mb_strlen($bookTitle) < 2 || mb_strlen($bookTitle) > 150) {
                $errors["bookTitle"] = Constants::ERROR_LENGTH;
            }

            //bookEditor
            if (! $bookEditor) {
                $errors["bookEditor"] = Constants::ERROR_REQUIRED;
            } else if (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/",  $bookEditor)) {
                $errors["bookEditor"] = Constants::ERROR_NAME;
            } elseif (mb_strlen($bookEditor) < 2 || mb_strlen($bookEditor) > 150) {
                $errors["bookEditor"] = Constants::ERROR_LENGTH;
            }

            //bookSummary
            if (!$bookSummary) {
                $errors["bookSummary"] = Constants::ERROR_REQUIRED;
            } else if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $bookSummary)) {
                $errors["bookSummary"] = Constants::ERROR_TEXT;
            } elseif (mb_strlen($bookEditor) < 50 || mb_strlen($bookEditor) > 2000) {
                $errors["bookSummary"] = Constants::ERROR_RESUME;
            }

            // Controler si l'éditeur existe déja 
            $idPublisher = $addPublisher->getPublisherByName($bookEditor);

            //Créer un éditeur s'il n'existe pas
            if ($idPublisher === 0) {
                $publisher = $addPublisher->insertPublisher($bookEditor);
                $idPublisher = (int)$addPublisher->getPublisherByName($bookEditor);
            }
            
            //Controler si l'auteur exite déjà
            $idAuthor = $addAuthor->getAuthorByNameAndFirstname($authorFirstName, $authorLastName);

            //Créer l'autheur s'il n'existe pas
            if ($idAuthor === 0) {
                $author = $addAuthor->insertAuthor($authorFirstName, $authorLastName);
                $idAuthor = (int)$addAuthor->getAuthorByNameAndFirstname($authorFirstName, $authorLastName);
            }

            //téléchargement et traitement des images
            if (!isset($_FILES["coverImage"]) || $_FILES["coverImage"]["error"] !== UPLOAD_ERR_OK) {
                $errors["coverImage"] = "Erreur lors du téléchargement de l'image. ";
            }

            // Vérifier la taille du fichier (limite : 2 Mo)
            if ($_FILES["coverImage"]["size"] > 2 * 1024 * 1792) {
                $errors["coverImage"] .= "Le fichier est trop volumineux. ";
            }

            // Vérifier le type MIME du fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES["coverImage"]["type"], $allowedTypes)) {
                $errors["coverImage"] .= " Type de fichier non autorisé. ";
            }

            // Récupérer l'extension du fichier original
            $fileExtension = pathinfo($_FILES["coverImage"]["name"], PATHINFO_EXTENSION);

            // Générer un nom de fichier unique et court
            $filename = uniqid('img_', true) . '.' . $fileExtension; // "img_" pour une identification facile

            // Définir le chemin de destination
            $destination = "assets/img/cover/" . $filename;

            // Définir la source du fichier temporaire
            $source = $_FILES["coverImage"]["tmp_name"];

            // Déplacer le fichier
            $result = move_uploaded_file($source, $destination);
            if (!$result) {
                $errors["coverImage"] .= "Impossible de déplacer le fichier téléchargé.";
            }

            //utilisateur
            $user_fk = $_SESSION["user_id"];

            //controle si des erreurs existent
            if (count($errors) < 1) {
                $_POST['validated'] = 1;
                //ajout d'un livre
                $addBook->insertBook(
                    $bookTitle,
                    $snippetLink,
                    $bookSummary,
                    $_POST["bookEditionYear"],
                    $destination,
                    $_POST["bookPageNb"],
                    $user_fk,
                    $_POST["bookGenre"],
                    $idPublisher,
                    $idAuthor
                );
                            //recupérer l'id
            $id = $idbook->getIdBook($user_fk);
            $destination = 'Location: index.php?controller=book&action=detail&id=' . $id;
            header($destination);
            } else {
                // Form is invalid
                $_POST['validated'] = 0;
                error_log($bookEditionYear);
                // Form
                include_once("../models/CategoryModel.php");
                $categoryModel = new CategoryModel();
                $genres = $categoryModel->getAllCategory();
                $actionURL = "index.php?controller=book&action=insert";

                // View
                $view = file_get_contents('../views/addBook.php');
                ob_start();
                eval('?>' . $view);
                $content = ob_get_clean();

                return $content;
            }

        } else {
            echo " Merci de valider le formulaire.";
        }
    }

    /**
     * Update a book in the database
     */
    private function updateAction()
    {
        include_once("../models/BookModel.php");
        include_once("../models/PublisherModel.php");
        include_once("../models/AuthorModel.php");

        $addBook = new BookModel();
        $addPublisher = new PublisherModel();
        $addAuthor = new AuthorModel();

        $book = $addBook->getBookById($_GET["id"]);
        $currentCover = $book["cover_image"];

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

            $destination = "";
            if (isset($_FILES["coverImage"]) && $_FILES["coverImage"]["error"] == UPLOAD_ERR_OK) {
                // Vérifier la taille du fichier (limite : 40 Ko)
                if ($_FILES["coverImage"]["size"] > 40 * 1024) {
                    die("Erreur : Le fichier est trop volumineux.");
                }

                // Vérifier le type MIME du fichier
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($_FILES["coverImage"]["type"], $allowedTypes)) {
                    die("Erreur : Type de fichier non autorisé.");
                }

                // Vérifier l'extension du fichier
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $fileExtension = strtolower(pathinfo($_FILES["coverImage"]["name"], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedExtensions)) {
                    die("Erreur : Extension de fichier non autorisée.");
                }

                // Générer un nom de fichier unique et court
                $filename = uniqid('img_', true) . '.' . $fileExtension; // "img_" pour une identification facile

                // Définir le chemin de destination
                $destination = "assets/img/cover/" . $filename;

                // Définir la source du fichier temporaire
                $source = $_FILES["coverImage"]["tmp_name"];

                // Vérifier si le dossier de destination est accessible en écriture
                if (!is_writable("assets/img/cover/")) {
                    die("Erreur : Le dossier de destination n'est pas accessible en écriture.");
                }

                // Déplacer le fichier
                $result = move_uploaded_file($source, $destination);
                if (!$result) {
                    die("Erreur : Impossible de déplacer le fichier téléchargé.");
                }

                // Débogage pour confirmer le chemin final
                error_log("Fichier téléchargé avec succès : " . $destination);

                // Supprimer la couverture précédente (si applicable)
                if (isset($currentCover) && file_exists($currentCover)) {
                    unlink($currentCover);
                }
            } else {
                die("Erreur lors du téléchargement de l'image : " . $_FILES["coverImage"]["error"]);
            }


            //ajout d'un livre
            $addBook->updateBook($_GET["id"], $_POST["bookTitle"], $_POST["snippetLink"], $_POST["bookSummary"], $_POST["bookEditionYear"], $destination, $_POST["bookPageNb"], $_POST["bookGenre"], $idPublisher, $idAuthor);

            header('Location: index.php?controller=book&action=detail&id=' . $_GET["id"]);
        }
    }

    /**
     * Delete a book with a specific ID
     */
    public function deleteAction()
    {

        include_once("../models/BookModel.php");
        $bookModel = new BookModel();
        $book = $bookModel->getBookById($_GET["id"]);

        // Check for privilege before deletion
        if (isAdminConnectedUser() || $_SESSION["user_id"] == $book["user_fk"]) {
            $bookModel->deleteBook($_GET["id"]);
        }

        // Return to index
        header("Location: index.php");
    }

    /**
     * Rate a book with current authentified user 
     */
    public function rateAction()
    {

        // Rate the book
        include_once("../models/UserModel.php");
        $userModel = new UserModel();
        $userModel->setBookRating($_GET["book_id"], $_SESSION["user_id"], $_POST["rating"]);

        // Return to book details
        header("Location: index.php?controller=book&action=detail&id=" . $_GET["book_id"]);
    }
}
