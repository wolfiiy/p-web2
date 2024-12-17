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
     * Login page user
     *
     * @return string
     */
    private function loginAction() {
        include_once('../models/UserModel.php');
        $usermodel = new UserModel();
        $bookmodel = new BookModel();
        $view = file_get_contents('../views/login.php'); 
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        if($_SERVER['REQUEST_METHOD'] === 'POST' && !isUserConnected()) 
        {
            // Values
            $userCredentials = $usermodel->checkUser($_POST['userAttemp']); // Real credentials

            if($userCredentials)
            {
                // Values of Books
                $userPublishedBook = $bookmodel->countUserPublishedBookById($userCredentials['user_id']);
                $userReviewedBook = $bookmodel->countUserReviewBookById($userCredentials['user_id']);

                // TODO: hashed passwords must be stored
                if($userCredentials['username'] === $_POST['userAttemp'] && $userCredentials['pass'] === $_POST['passAttemp'])
                {
                    // Stores user id in session
                    $_SESSION['user_id'] = $userCredentials['user_id'];
                    $_SESSION['is_admin'] = $userCredentials['is_admin'];
                    

                    // From here, we can consider our user connected to the app
                    header('Location: index.php?controller=user&action=detail&id=' . $_SESSION["user_id"]);
                    return true;
                }
                else
                {
                    ?>
                    <script type="text/javascript">
                    window.onload = function () { alert("Mot de passe incorrect"); } 
                    </script>
                    <?php 
                }
            }
            else
            {
                ?>
                <script type="text/javascript">
                window.onload = function () { alert("Utilisateur n'existe pas"); } 
                </script>
                <?php 
            }
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

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Handle user account creation
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isUserConnected()) {
            $errors = array();
            $username = $_POST['username'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password-confirm'];

            if ($userModel->userExists($username)) {
                $errors[] = self::ERROR_USERNAME_UNAVAILABLE;
            }

            if ($password != $passwordConfirm) {
                $errors[] = self::ERROR_PASSWORD_MISMATCH;
            }

            if (count($errors) > 0) {
                // Display errors
            } else {
                $userModel->createAccount($username, $hash);
                header("Location: index.php");
            }
        }

        return $content;
    }
}