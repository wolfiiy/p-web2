<?php

/**
 * ETML
 * Author: Valentin Pignat
 * Date: November 18th, 2024
 */

include_once('../helpers/utils.php');

/**
 * Controller used to handle user-related pages.
 */
class UserController extends Controller {

    /**
     * Error message to display if a field is left empty during the sign up
     * process.
     */
    private const ERROR_EMPTY_FIELD =
        "Veuillez remplire tous les champs.";

    /**
     * Error message to display when the passwords given by the user during the
     * account creation process do not match.
     */
    private const ERROR_PASSWORD_MISMATCH =
        "Les mots de passes ne sont pas identiques.";

    /**
     * Error message to display when a user could not be found. Purposefully
     * vague for privacy reasons.
     */
    private const ERROR_INVALID_CREDENTIALS = "
        L'utilisateur spécifié n'a pas été trouvé ou le mot de passe est 
        incorrect.
    ";

    /**
     * Title displayed over the list of added entries.
     */
    private const TITLE_ADDITIONS = "Ajouts";

    /**
     * Title displayed over the list of reviews.
     */
    private const TITLE_REVIEWS = "Reviews";

    /**
     * Represents one element (addition of review).
     */
    private const ONE_ELEMENT = 1;

    /**
     * Error message to display if the user attempts to create an account
     * with a username that is not available.
     */
    private const ERROR_USERNAME_UNAVAILABLE = 
        "Le nom d'utilisateur spécifié n'est pas disponible.";

    /**
     * Dispatch current action
     * @return mixed The function to be called.
     */
    public function display() {
        include_once('../helpers/utils.php');
        $action = $_GET['action'] . "Action";
        return call_user_func(array($this, $action));
    }

    /**
     * Displays a user profile.
     * @param int $id The unique ID of a user.
     * @return string The profile view of that specific user.
     */
    private function detailAction() {
        include_once('../helpers/HtmlWriter.php');
        include_once('../helpers/DataHelper.php');
        include_once('../models/UserModel.php');
        include_once('../models/BookModel.php');
       
        $usermodel = new UserModel();
        $bookmodel = new BookModel();

        // Used in the view
        $titleAdditions = self::TITLE_ADDITIONS;
        $titleReviews = self::TITLE_REVIEWS;
        $labelAdditions = "";
        $labelReviews = "";

        if (isset($_GET['id'])) $id = $_GET['id'];
        else $id = $_SESSION["user_id"];

        // Values
        $user = $usermodel->getUserById($id);
        $signupDateLabel 
            = "Membre depuis le " 
            . FormatHelper::getFullDate($user['sign_up_date']);

        // Values of Books
        $nbAdditions = $bookmodel->countUserPublishedBookById($id)[0]["count(*)"];
        $nbReviews = $bookmodel->countUserReviewBookById($id)[0]["count(*)"];

        if ($nbAdditions < self::ONE_ELEMENT) {
            $titleAdditions = "";
            $labelAdditions = "Aucun livre n'a été ajouté par cet utilisateur.";
        } else if ($nbAdditions == self::ONE_ELEMENT) {
            $labelAdditions = $nbAdditions . " livre ajouté";
        } else {
            $labelAdditions = $nbAdditions . " livres ajoutés";
        }

        if ($nbReviews < self::ONE_ELEMENT) {
            $titleReviews = "";
            $labelReviews = "Aucun livre n'a été noté par cet utilisateur.";
        } else if ($nbReviews == self::ONE_ELEMENT) {
            $labelReviews = $nbReviews . " livre noté";
        } else {
            $labelReviews = $nbReviews . " livres notés";
        }

        $books = $bookmodel->booksReviewedByUser($id);
        $books = DataHelper::bookPreview($books);

        $publishedBooks = $bookmodel->userPublishedBookByID($id);
        $publishedBooks = DataHelper::bookPreview($publishedBooks);

        $view = file_get_contents('../views/detailUser.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Logs the user in.
     * @return true|string True if the user could be logged in, the sign in view
     * otherwise.
     */
    private function loginAction() {
        include_once('../models/UserModel.php');
        $userModel = new UserModel();
        $view = file_get_contents('../views/login.php'); 
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        if($_SERVER['REQUEST_METHOD'] === 'POST' && !isUserConnected()) {
            $errors = array();

            // Sanitize input
            $usernameInput = trim($_POST['usernameInput'] ?? '');
            $usernameInput = strtolower($usernameInput);
            $passwordInput = trim($_POST['passwordInput'] ?? '');
            $userExists = $userModel->userExists($usernameInput);

            if ($userExists) {
                // Get credentials
                $userData = $userModel->getUserByUsername($usernameInput);
                $usernameFound = strtolower($userData['username']);
                $passwordFound = $userData['password'];

                // Attempt user log-in
                if ($usernameInput === $usernameFound 
                    && password_verify($passwordInput, $passwordFound)) {
                    // Store user information in session
                    $_SESSION['user_id'] = $userData['user_id'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['is_admin'] = $userData['is_admin'];

                    // Redirect to user page
                    $redirect = 'index.php?controller=user&action=detail&id='
                              . $_SESSION["user_id"];

                    header("Location: $redirect");
                    return true;
                }
            } else {
                $errors[] = self::ERROR_INVALID_CREDENTIALS;
            }
        } else {
            $errors[] = self::ERROR_INVALID_CREDENTIALS;
        }

        // Displays the sign in form if incomplete
        return $content;
    }

    /*
    * Logs the user out.
    * Note: Use of the header method because the commonly used display causes a 
    * bug with the information of a session that does not exist but shows as if 
    * it did.
    */
    private function logoutAction() {
        session_destroy();
        header('Location: index.php?controller=user&action=login');  // To show login page
    }

    /**
     * Displays the signup form and check if the input is valid.
     * @return string The sign up view.
     */
    private function signupAction() {
        include_once('../models/UserModel.php');
        $userModel = new UserModel();
        $view = file_get_contents('../views/signupView.php');
    
        // Handle user account creation
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isUserConnected()) {
            $errors = array();

            // Sanitize input
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $passwordConfirm = trim($_POST['password-confirm'] ?? '');
    
            // Validate input fields
            if (empty($username) || empty($password) || empty($passwordConfirm)) {
                $errors[] = self::ERROR_EMPTY_FIELD;
            }
    
            // Check if username is available
            if ($userModel->userExists($username)) {
                $errors[] = self::ERROR_USERNAME_UNAVAILABLE;
            }
    
            // Verify passwords
            if ($password !== $passwordConfirm) {
                $errors[] = self::ERROR_PASSWORD_MISMATCH;
            }
    
            // Create the account if no errors occured, otherwise display error
            // messages
            if (empty($errors)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $userModel->createAccount($username, $hash);
                header("Location: index.php");
                exit;
            }
        }
    
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();
    
        return $content;
    }
    
}