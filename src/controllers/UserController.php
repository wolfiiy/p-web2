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
}