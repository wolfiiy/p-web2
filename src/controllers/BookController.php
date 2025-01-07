<?php

/**
 * ETML
 * Auteur : Valentin Pignat, Sébastien Tille, Abigaël Périsset
 * Date: 07.01, 2024
 * Description: Controller used for pages dedicated to books.
 */

include_once('../helpers/DataHelper.php');
include_once('../helpers/FormatHelper.php');
include_once('../models/AuthorModel.php');
include_once('../models/BookModel.php');
include_once('../models/PublisherModel.php');
include_once('../models/UserModel.php');

/**
 * Controller used to handle books-related pages.
 */
class BookController extends Controller
{

    /**
     * Default value of the category filter (i.e. shows everything).
     */
    private const DEFAULT_CATEGORY_FILTER = 0;

    /**
     * First page number in the "list all books" page.
     */
    private const DEFAULT_PAGE_NUMBER = 1;

    /**
     * Default value of the search filter (i.e. searches nothing).
     */
    private const DEFAULT_SEARCH_FILTER = "";

    /**
     * Path to the book details view.
     */
    private const PATH_TO_BOOK_DETAILS = '../views/detailBook.php';

    /**
     * Path to the cover directory.
     */
    private const PATH_TO_COVERS = "assets/img/cover/";

    /**
     * Number of result per page for pagination
     */
    private const RESULT_PER_PAGE = 10;

    /**
     * Default ID when nothing was found.
     */
    private const DEFAULT_ID = 0;

    /**
     * Minimum number of pages allowed in a book.
     */
    private const MIN_NB_PAGE = 1;

    /**
     * Minimum length allowed for titles.
     */
    private const MIN_TITLE_LENGTH = 2;

    /**
     * Maximum length allowed for titles.
     */
    private const MAX_TITLE_LENGTH = 100;

    /**
     * Minimum length allowed for summaries.
     */
    private const MIN_SUMMARY_LENGTH = 50;

    /**
     * Maximum length allowed for summaries.
     */
    private const MAX_SUMMARY_LENGTH = 2000;

    /**
     * Minimum length allowed for publisher names.
     */
    private const MIN_PUBLISHER_LENGTH = 2;

    /**
     * Maximum length allowed for publisher names.
     */
    private const MAX_PUBLISHER_LENGTH = 50;

    /**
     * Minimum length allowed for names.
     */
    private const MIN_NAME_LENGTH = 2;

    /**
     * Maximum length allowed for names.
     */
    private const MAX_NAME_LENGTH = 128;

    /**
     * Covers can weight up to 2 mb.
     */
    private const MAX_COVER_WEIGHT = 2 * 1024 * 1792;

    /**
     * Dispatches the current action
     * @return mixed A callback to a function.
     */
    public function display() {
        include_once('../helpers/utils.php');
        $action = $_GET['action'] . "Action";
        return call_user_func(array($this, $action));
    }

    /**
     * Displays all books from most recent to oldest. The list is split into 
     * pages of 10 elements.
     * @return string The book list view.
     */
    private function listAction() {
        include_once('../helpers/HtmlWriter.php');
        include_once("../models/CategoryModel.php");
        include_once('../models/BookModel.php');

        $bookModel = new BookModel();
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllCategory();

        // By default page 1
        if (!isset($_GET["page"]))
            $page = self::DEFAULT_PAGE_NUMBER;
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

        $books = DataHelper::bookPreview($books);

        $view = file_get_contents('../views/listView.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Opens a page containing details about a specific book.
     * @param int $id The unique ID of the book.
     * @return string The page to be printed.
     */
    private function detailAction() {
        // Check if a user is authentified
        if (!isUserConnected()) {
            header("Location: index.php?controller=user&action=login");
        }
        // Check if ID has been set.
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
     * Displays the form used to add a new entry to the database.
     * @return string The addition view.
     */
    private function addAction() {
        $h1 = "Ajout d'un livre";
        $submitButton = "Ajouter";
        $actionURL = "index.php?controller=book&action=insert";

        // If no user is connected, redirect to index
        if (!isUserConnected()) {
            header("Location: index.php");
        }

        include_once("../models/CategoryModel.php");
        $categoryModel = new CategoryModel();
        $genres = $categoryModel->getAllCategory();

        // Form
        $author["first_name"] = $_SESSION['form_data']['authorFirstName'] ?? '';
        $author["last_name"] = $_SESSION['form_data']['authorLastName'] ?? '';
        $book["title"] = $_SESSION['form_data']['bookTitle'] ?? '';
        $publisher["name"] = $_SESSION['form_data']['bookEditor'] ?? '';
        $book["number_of_pages"] = $_SESSION['form_data']['bookPageNb'] ?? '';
        $book["excerpt"] = $_SESSION['form_data']['snippetLink'] ?? '';
        $book["summary"] = $_SESSION['form_data']['bookSummary'] ?? '';
        $book["release_date"] = $_SESSION['form_data']['bookEditionYear'] ?? '';
        $book["category_fk"] = $_SESSION['form_data']['bookGenre'] ?? '';
        $errors = $_SESSION['form_errors'] ?? [];

        // Clear form data and errors from session
        unset($_SESSION['form_data']);
        unset($_SESSION['form_errors']);

        $view = file_get_contents('../views/addBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Displays the form used to modify an existing entry.
     * @return string The modification view.
     */
    private function modifyAction() {
        $errors = array();
        $h1 = "Modification d'un livre";
        $submitButton = "Modifier";

        // If no user is connected, redirect to index
        if (!isUserConnected()) {
            header("Location: index.php");
        }

        include_once("../models/BookModel.php");
        $bookModel = new BookModel();
        $book = $bookModel->getBookById($_GET["id"]);

        // If not admin and and not the owner of the book, redirect to index
        if (!isAdminConnectedUser() && $_SESSION["user_id"] != $book["user_fk"]) {
            header("Location: index.php");
        }

        // If errors are to be diplayed, the form has been submited and the values are updated
        if(!empty( $_SESSION['form_errors'])){
            // Form
            error_log($_SESSION['form_data']['authorFirstName']);
            error_log($_SESSION['form_data']['bookTitle']);

            $author["first_name"] = $_SESSION['form_data']['authorFirstName'] ?? '';
            $author["last_name"] = $_SESSION['form_data']['authorLastName'] ?? '';
            $book["title"] = $_SESSION['form_data']['bookTitle'] ?? '';
            $publisher["name"] = $_SESSION['form_data']['bookEditor'] ?? '';
            $book["number_of_pages"] = $_SESSION['form_data']['bookPageNb'] ?? '';
            $book["excerpt"] = $_SESSION['form_data']['snippetLink'] ?? '';
            $book["summary"] = $_SESSION['form_data']['bookSummary'] ?? '';
            $book["release_date"] = $_SESSION['form_data']['bookEditionYear'] ?? '';
            $book["category_fk"] = $_SESSION['form_data']['bookGenre'] ?? '';
            $errors = $_SESSION['form_errors'] ?? [];

             // Clear form data and errors from session
            unset($_SESSION['form_data']);
            unset($_SESSION['form_errors']);
        }
        
 
       
        
        $actionURL = "index.php?controller=book&action=update&id=" . $book["book_id"];

        include_once("../models/AuthorModel.php");
        $authorModel = new AuthorModel();
        $author = $authorModel->getAuthorById($book["author_fk"]);

        include_once("../models/PublisherModel.php");
        $publisherModel = new PublisherModel();
        $publisher = $publisherModel->getPublisherById($book["publisher_fk"]);

        include_once("../models/CategoryModel.php");
        $categoryModel = new CategoryModel();
        $genres = $categoryModel->getAllCategory();

        $view = file_get_contents('../views/addBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Inserts a new book in the database.
     */
    private function insertAction() {
        $h1 = "Modification d'un livre";
        $submitButton = "Modifier";
        $submitButton = "Ajouter";

        mb_internal_encoding("UTF-8");

        include_once("../models/BookModel.php");
        include_once("../models/PublisherModel.php");
        include_once("../models/AuthorModel.php");
        include_once("../models/ConstantsModel.php");

        $addBook = new BookModel();
        $idbook = new BookModel();
        $addPublisher = new PublisherModel();
        $addAuthor = new AuthorModel();

        $errors    = [];
        $authorFirstName = "";
        $authorLastName = "";
        $bookTitle = "";
        $bookEditor = "";
        $bookPageNb = "";
        $snippetLink = "";
        $bookSummary = "";
        $bookEditionYear = "";
        $bookGenre = "";

        $imageIsValid = true;
        $publisherIsValid =true;
        $nameIsValid = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize user input
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

            // Validate input. Fallback to an empty string in case of missing 
            // data.
            $authorFirstName    = $_POST["authorFirstName"] ?? '';
            $authorLastName     = $_POST["authorLastName"] ?? '';
            $bookTitle          = $_POST["bookTitle"] ?? '';
            $bookEditor         = $_POST["bookEditor"] ?? '';
            $bookPageNb         = $_POST["bookPageNb"] ?? '';
            $snippetLink        = $_POST["snippetLink"] ?? '';
            $bookSummary        = $_POST["bookSummary"] ?? '';
            $bookEditionYear    = $_POST["bookEditionYear"] ?? '';
            $bookGenre          = $_POST["bookGenre"] ?? '';

            //bookEditionYear
            if (!$bookEditionYear) {
                $errors["bookEditionYear"] = Constants::ERROR_REQUIRED;
            }

            //bookPageNb
            if (!$bookPageNb) {
                $errors["bookPageNb"] = Constants::ERROR_REQUIRED;
            } elseif ($bookPageNb < self::MIN_NB_PAGE) {
                $errors["bookPageNb"] = Constants::ERROR_PAGE;
            }

            //snippetLink
            if (!$snippetLink) {
                $errors["snippetLink"] = Constants::ERROR_REQUIRED;
            } else if (!filter_var($snippetLink, FILTER_VALIDATE_URL)) {
                $errors["snippetLink"] = "Seul les URLs sont acceptés.";
            }

            //bookTitle 
            if (!$bookTitle) {
                $errors["bookTitle"] = Constants::ERROR_REQUIRED;
            } elseif (mb_strlen($bookTitle) < self::MIN_TITLE_LENGTH 
                  || mb_strlen($bookTitle) > self::MAX_TITLE_LENGTH) {
                $errors["bookTitle"] = Constants::ERROR_TITLE;
            }

            //bookSummary
            if (!$bookSummary) {
                $errors["bookSummary"] = Constants::ERROR_REQUIRED;
            } elseif (mb_strlen($bookSummary) < self::MIN_SUMMARY_LENGTH 
                      || mb_strlen($bookSummary) > self::MAX_SUMMARY_LENGTH) {
                $errors["bookSummary"] = Constants::ERROR_RESUME;
            }

            //bookEditor
            if (!$bookEditor) {
                $errors["bookEditor"] = Constants::ERROR_REQUIRED;
                $publisherIsValid = false;
            } elseif (mb_strlen($bookEditor) < self::MIN_PUBLISHER_LENGTH 
                      || mb_strlen($bookEditor) > self::MAX_PUBLISHER_LENGTH) {
                $errors["bookEditor"] = Constants::ERROR_PUBLISHER;
                $publisherIsValid = false;
            }

            if ($publisherIsValid) {
                // Check if publisher exists
                $idPublisher = $addPublisher->getPublisherByName($bookEditor);

                // Add a new publisher if none was found
                if ($idPublisher === self::DEFAULT_ID) {
                    $publisher = $addPublisher->insertPublisher($bookEditor);
                    $idPublisher = (int)$addPublisher->getPublisherByName($bookEditor);
                }
            }

            // authorFirstName
            if (!$authorFirstName) {
                $errors["authorFirstName"] = Constants::ERROR_REQUIRED;
                $nameIsValid = false;
            } elseif (mb_strlen($authorFirstName) < self::MIN_NAME_LENGTH 
                    || mb_strlen($authorFirstName) > self::MAX_NAME_LENGTH) {
                $errors["authorFirstName"] = Constants::ERROR_NAME;
                $nameIsValid = false;
            }

            // authorLastName
            if (! $authorLastName) {
                $errors["authorLastName"] = Constants::ERROR_REQUIRED;
                $nameIsValid = false;
            } elseif (mb_strlen($authorLastName) < self::MIN_NAME_LENGTH 
                    || mb_strlen($authorLastName) > self::MAX_NAME_LENGTH) {
                $errors["authorLastName"] = Constants::ERROR_NAME;
                $nameIsValid = false;
            }

            if ($nameIsValid) {
                // Check if author exists
                $idAuthor = 
                    $addAuthor->getAuthorByNameAndFirstname($authorFirstName, 
                                                            $authorLastName);

                // Create author if needed
                if ($idAuthor === self::DEFAULT_ID) {
                    $author = $addAuthor->insertAuthor($authorFirstName, 
                                                       $authorLastName);

                    $idAuthor 
                        = (int)$addAuthor->getAuthorByNameAndFirstname($authorFirstName, 
                                                                       $authorLastName);
                }
            }

            // Handle image download
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!isset($_FILES["coverImage"]) || $_FILES["coverImage"]["error"] !== UPLOAD_ERR_OK) {
                $errors["coverImage"] = Constants::ERROR_IMAGE;
                $imageIsValid = false;
            }
            // Check image size (up to 2 mb allowed)
            elseif ($_FILES["coverImage"]["size"] > self::MAX_COVER_WEIGHT) {
                $errors["coverImage"] = Constants::ERROR_SIZE;
                $imageIsValid = false;
            }
            // Check file MIME
            elseif (!in_array($_FILES["coverImage"]["type"], $allowedTypes)) {
                $errors["coverImage"] = Constants::ERROR_FILE_MIME;
                $imageIsValid = false;
            }
            if ($imageIsValid) {
                // Get original file extension
                $fileExtension = pathinfo($_FILES["coverImage"]["name"], PATHINFO_EXTENSION);

                // Generate random and unique filename
                // The img_ prefix makes it easier to identify
                $filename = uniqid('img_', true) . '.' . $fileExtension;

                // Save path
                $destination = self::PATH_TO_COVERS . $filename;

                // Temporary file
                $source = $_FILES["coverImage"]["tmp_name"];

                // Move file to cover directory
                $result = move_uploaded_file($source, $destination);
                if (!$result) {
                    $errors["coverImage"] 
                        = Constants::ERROR_FILE_MOVE;
                }
            }

            // User
            $user_fk = $_SESSION["user_id"];

            // Check if errors occured
            if (count($errors) == 0) {
                $_POST['validated'] = true;
                
                // Add the book to the database
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

                // Get book ID
                $id = $idbook->getIdBook($user_fk);
                $destination = 'Location: index.php?controller=book&action=detail&id=' . $id;

                // Redirect to the book
                header($destination);
            } else {
                // If image was stored but book isnt validated, delete the image
                if ($result) {
                    if (file_exists($destination)) {
                        unlink($destination);
                    }
                }

                // Store form data and errors in session
                $_SESSION['form_data'] = $_POST;
                $_SESSION['form_errors'] = $errors;

                // Redirect to addAction
                header('Location: index.php?controller=book&action=add');
                exit;
            }
        } else {
            echo " Merci de valider le formulaire.";
        }
    }

    /**
     * Updates an existing book.
     */
    private function updateAction() {
        include_once("../models/BookModel.php");
        include_once("../models/PublisherModel.php");
        include_once("../models/AuthorModel.php");

        $bookModel = new BookModel();
        $publisherModel = new PublisherModel();
        $authorModel = new AuthorModel();

        $book = $bookModel->getBookById($_GET["id"]);
        $currentCover = $book["cover_image"];

        $errors    = [];
        $authorFirstName = "";
        $authorLastName = "";
        $bookTitle = "";
        $bookEditor = "";
        $bookPageNb = "";
        $snippetLink = "";
        $bookSummary = "";
        $bookEditionYear = "";
        $bookGenre = "";

        $imageIsValid = true;
        $publisherIsValid =true;
        $nameIsValid = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize user input
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

            error_log($_POST["authorFirstName"] );
             // Validate input. Fallback to an empty string in case of missing 
            // data.
            $authorFirstName    = $_POST["authorFirstName"] ?? '';
            $authorLastName     = $_POST["authorLastName"] ?? '';
            $bookTitle          = $_POST["bookTitle"] ?? '';
            $bookEditor         = $_POST["bookEditor"] ?? '';
            $bookPageNb         = $_POST["bookPageNb"] ?? '';
            $snippetLink        = $_POST["snippetLink"] ?? '';
            $bookSummary        = $_POST["bookSummary"] ?? '';
            $bookEditionYear    = $_POST["bookEditionYear"] ?? '';
            $bookGenre          = $_POST["bookGenre"] ?? '';

            //bookEditionYear
            if (!$bookEditionYear) {
                $errors["bookEditionYear"] = Constants::ERROR_REQUIRED;
            }

            //bookPageNb
            if (!$bookPageNb) {
                $errors["bookPageNb"] = Constants::ERROR_REQUIRED;
            } elseif ($bookPageNb < self::MIN_NB_PAGE) {
                $errors["bookPageNb"] = "Seul les nombres au dessus de 0 sont autorisés.";
            }

            //snippetLink
            if (!$snippetLink) {
                $errors["snippetLink"] = Constants::ERROR_REQUIRED;
            } else if (!filter_var($snippetLink, FILTER_VALIDATE_URL)) {
                $errors["snippetLink"] = "Seul les URLs sont acceptés.";
            }

            //bookTitle 
            if (!$bookTitle) {
                $errors["bookTitle"] = Constants::ERROR_REQUIRED;
            } elseif (mb_strlen($bookTitle) < self::MIN_TITLE_LENGTH 
                  || mb_strlen($bookTitle) > self::MAX_TITLE_LENGTH) {
                $errors["bookTitle"] = Constants::ERROR_TITLE;
            }

            //bookSummary
            if (!$bookSummary) {
                $errors["bookSummary"] = Constants::ERROR_REQUIRED;
            } elseif (mb_strlen($bookSummary) < self::MIN_SUMMARY_LENGTH 
                      || mb_strlen($bookSummary) > self::MAX_SUMMARY_LENGTH) {
                $errors["bookSummary"] = Constants::ERROR_RESUME;
            }

            //bookEditor
            if (!$bookEditor) {
                $errors["bookEditor"] = Constants::ERROR_REQUIRED;
                $publisherIsValid = false;
            } elseif (mb_strlen($bookEditor) < self::MIN_PUBLISHER_LENGTH 
                      || mb_strlen($bookEditor) > self::MAX_PUBLISHER_LENGTH) {
                $errors["bookEditor"] = Constants::ERROR_PUBLISHER;
                $publisherIsValid = false;
            }

            if ($publisherIsValid) {
                // Check if publisher exists
                $idPublisher = $publisherModel->getPublisherByName($bookEditor);

                // Add a new publisher if none was found
                if ($idPublisher === self::DEFAULT_ID) {
                    $publisher = $publisherModel->insertPublisher($bookEditor);
                    $idPublisher = (int)$publisherModel->getPublisherByName($bookEditor);
                }
            }

            // authorFirstName
            if (!$authorFirstName) {
                $errors["authorFirstName"] = Constants::ERROR_REQUIRED;
                $nameIsValid = false;
            } elseif (mb_strlen($authorFirstName) < self::MIN_NAME_LENGTH 
                    || mb_strlen($authorFirstName) > self::MAX_NAME_LENGTH) {
                $errors["authorFirstName"] = Constants::ERROR_NAME;
                $nameIsValid = false;
            }

            // authorLastName
            if (! $authorLastName) {
                $errors["authorLastName"] = Constants::ERROR_REQUIRED;
                $nameIsValid = false;
            } elseif (mb_strlen($authorLastName) < self::MIN_NAME_LENGTH 
                    || mb_strlen($authorLastName) > self::MAX_NAME_LENGTH) {
                $errors["authorLastName"] = Constants::ERROR_NAME;
                $nameIsValid = false;
            }

            if ($nameIsValid) {
                // Check if author exists
                $idAuthor = 
                    $authorModel->getAuthorByNameAndFirstname($authorFirstName, 
                                                            $authorLastName);

                // Create author if needed
                if ($idAuthor === self::DEFAULT_ID) {
                    $author = $authorModel->insertAuthor($authorFirstName, 
                                                       $authorLastName);

                    $idAuthor 
                        = (int)$authorModel->getAuthorByNameAndFirstname($authorFirstName, 
                                                                       $authorLastName);
                }
            }

            // User
            $user_fk = $_SESSION["user_id"];


            // Checks if publisher exists, create one if not 
            if ($publisherIsValid){
                $idPublisher = $publisherModel->getPublisherByName($_POST["bookEditor"]);
                if ($idPublisher === 0) {
                    $publisher = $publisherModel->insertPublisher($_POST["bookEditor"]);
                    $idPublisher = (int)$publisherModel->getPublisherByName($_POST["bookEditor"]);
                }
            }

            // Checks if author exists, create one if not 
            if ($nameIsValid){
                $idAuthor = $authorModel->getAuthorByNameAndFirstname($_POST["authorFirstName"], $_POST["authorLastName"]);
                if ($idAuthor === 0) {
                    $author = $authorModel->insertAuthor($_POST["authorFirstName"], $_POST["authorLastName"]);
                    $idAuthor = (int)$authorModel->getAuthorByNameAndFirstname($_POST["authorFirstName"], $_POST["authorLastName"]);
                }
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
            }

            // Check if errors occured
            if (count($errors) == 0) {
                $_POST['validated'] = true;
                
                // Update the book to the database
                $bookModel->updateBook($_GET["id"], $_POST["bookTitle"], $_POST["snippetLink"], $_POST["bookSummary"], $_POST["bookEditionYear"], $destination, $_POST["bookPageNb"], $_POST["bookGenre"], $idPublisher, $idAuthor);

                // Get book ID
                $id = $idbook->getIdBook($user_fk);
                $destination = 'Location: index.php?controller=book&action=detail&id=' . $id;

                // Redirect to the book
                header($destination);
            } else {
                // If image was stored but book isnt validated, delete the image
                if ($result) {
                    if (file_exists($destination)) {
                        unlink($destination);
                    }
                }

                // Store form data and errors in session
                $_SESSION['form_data'] = $_POST;
                $_SESSION['form_errors'] = $errors;

                // Redirect to modifyAction
                $destination = 'Location: index.php?controller=book&action=modify&id=' . $_GET["id"];
                header($destination);
                exit;
            }
        }
    }

    /**
     * Given an ID, deletes a specific book.
     * Note: The ID is not specified in the method because the ID of the page
     * from which the deletion is requested is used.
     */
    public function deleteAction() {
        include_once("../models/BookModel.php");
        $bookModel = new BookModel();
        $book = $bookModel->getBookById($_GET["id"]);

        if (isset($book['cover_image']) && file_exists($book['cover_image'])) {
            unlink($book['cover_image']);
        }

        // Check for privileges before deletion
        if (isAdminConnectedUser() || $_SESSION["user_id"] == $book["user_fk"]) {
            $bookModel->deleteBook($_GET["id"]);
        }

        // Return to index
        header("Location: index.php");
    }

    /**
     * Add a new review from the logged user.
     */
    public function rateAction() {
        // Rate the book
        include_once("../models/UserModel.php");
        $userModel = new UserModel();
        $userModel->setBookRating($_GET["book_id"], $_SESSION["user_id"], $_POST["rating"]);

        // Return to book details
        header("Location: index.php?controller=book&action=detail&id=" . $_GET["book_id"]);
    }
}
