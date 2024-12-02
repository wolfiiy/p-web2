<?php
/**
 * ETML
 * Auteur : Valentin Pignat
 * Date: 18.11.2024
 * Controler pour les pages liées aux utilisateurs
 */



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
                // TODO : Il faut stocker les mots de passe hashés
                if($userCredentials['username'] === $_POST['userAttemp'] && $userCredentials['pass'] === $_POST['passAttemp'])
                {
                    // Stocke les infos de l'user dans la session
                    $_SESSION['username'] = $userCredentials['username'];
                    $_SESSION['pass'] = $userCredentials['pass'];
                    $_SESSION['user_id'] = $userCredentials['user_id'];
                    $_SESSION['pass'] = $userCredentials['pass'];
                    $_SESSION['sign_up_date'] = $userCredentials['is_admin']; // soit on utilise la value sur helpers/utils.php
                    // A partir d'ici on peut considérer notre user connecté à l'app
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

    private function logoutAction()
    {
        session_destroy();
        $view = file_get_contents('../views/login.php'); 
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }
}