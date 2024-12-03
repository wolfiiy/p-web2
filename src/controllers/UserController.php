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
     *
     * @return string
     */
    private function detailAction() {

        include_once('../models/UserModel.php');
       
        $usermodel = new UserModel();
        // Values
        $valUser = $usermodel->getUserById(1);

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
                // TODO: hashed passwords must be stored
                if($userCredentials['username'] === $_POST['userAttemp'] && $userCredentials['pass'] === $_POST['passAttemp'])
                {
                    // Stocke les infos de l'user dans la session
                    $_SESSION['username'] = $userCredentials['username'];
                    $_SESSION['pass'] = $userCredentials['pass'];
                    $_SESSION['user_id'] = $userCredentials['user_id'];
                    $_SESSION['pass'] = $userCredentials['pass'];
                    $_SESSION['sign_up_date'] = $userCredentials['sign_up_date']; 
                    $_SESSION['is_admin'] = $userCredentials['is_admin']; // or use the value on helpers/utils.php
                    // From here, we can consider our user connected to the app
                    header('Location: index.php?controller=user&action=detail');
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
    private function logoutAction()
    {
        session_destroy();
        // Use of the header method because the commonly used display causes a bug with the information of a session that does not exist but shows as if it did.
        header('Location: index.php?controller=user&action=login');  // To show login page
    }
}