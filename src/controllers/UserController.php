<?php
/**
 * ETML
 * Auteur : Valentin Pignat
 * Date: 18.11.2024
 * Controler pour les pages liées aux utilisateurs
 */
include_once('../helpers/utils.php');

class UserController extends Controller {

    /**
     * Error message to display if a field is left empty during the sign up
     * process.
     */
    private const ERROR_EMPTY_FIELD =
        "Veuillez remplir tout les champs.";

    /**
     * Error message to display if the user did not provide the correct 
     * password when attempting to sign in.
     */
    private const ERROR_PASSWORD_IS_INCORRECT =
        "Le mot de passe est érroné.";

    /**
     * Error message to display when the passwords given by the user during the
     * account creation process do not match.
     */
    private const ERROR_PASSWORD_MISMATCH =
        "Les mots de passes ne sont pas identiques.";

    /**
     * Error message to display when a user could not be found.
     */
    private const ERROR_INVALID_CREDENTIALS = "
        L'utilisateur spécifié n'a pas été trouvé ou le mot de passe est 
        incorrect.
    ";

    /**
     * Error message to display if the user attempts to create an account
     * with a username that is not available.
     */
    private const ERROR_USERNAME_UNAVAILABLE = 
        "Le nom d'utilisateur spécifié n'est pas disponible.";

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
     * Display user info page
     * @param int $id id of the user to display
     * @return string
     */
    private function detailAction() {
        include_once('../helpers/HtmlWriter.php');
        include_once('../helpers/DataHelper.php');
        include_once('../models/UserModel.php');
        include_once('../models/BookModel.php');
       
        $usermodel = new UserModel();
        $bookmodel = new BookModel();

        if (isset($_GET['id'])) $id = $_GET['id'];
        else $id = $_SESSION["user_id"];

        // Values
        $user = $usermodel->getUserById($id);

        // Values of Books
        $userPublishedBook = $bookmodel->countUserPublishedBookById($id)[0]["count(*)"];
        $userReviewedBook = $bookmodel->countUserReviewBookById($id)[0]["count(*)"];

        $books = $bookmodel->booksReviewedByUser($id);

        $books = DataHelper::BookPreview($books);
        $view = file_get_contents('../views/detailUser.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Logs in the user.
     * @return string
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
                    $_SESSION['is_admin'] = $userData['id_admin'];

                    // Redirect to user page
                    header('Location: index.php?controller=user&action=detail&id=' . $_SESSION["user_id"]);
                    return true;
                }
            } else {
                $errors[] = self::ERROR_INVALID_CREDENTIALS;
            }
        } else {
            $errors[] = self::ERROR_INVALID_CREDENTIALS;
        }

        // To display the login page if the form has not been completed correctly
        return $content;
    }

    /*
    * Logout Action
    */
    private function logoutAction() {
        session_destroy();
        // Use of the header method because the commonly used display causes a bug with the information of a session that does not exist but shows as if it did.
        header('Location: index.php?controller=user&action=login');  // To show login page
    }

    /**
     * Displays the signup form.
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