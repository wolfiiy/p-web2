<?php

/**
 * ETML
 * Auteur : Valentin Pignat
 * Date: 18.11.2024
 * Controler pour les pages liées aux livres
 */

class BookController extends Controller
{

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
     * Display Index Action
     *
     * @return string
     */
    private function detailAction()
    {

        $view = file_get_contents('../views/detailBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    private function addAction()
    {

        $view = file_get_contents('../views/addBook.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
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
    private function yearController($year){
        if (isset($year)){
            $year = trim($year);

            if(empty($year)){
                return false;
            }
            elseif (!preg_match("/^\d{4}$/", $year)){
                return false;
            }
            return $year;
        }
        else{
            return false;
        }
    }
    private function pageController($number){
        
        if (isset($number)){
            $number = trim($number);

            if(empty($number)){
                return false;
            }
            elseif(!is_numeric($number)){
                return false;
            }
            return $number;
        }
        else{
            return false;
        }
    }
    private function urlController($url){
        if (isset($url)){
            $url = trim($url);

            if(empty($url)){
                return false;
            }
            elseif (!preg_match("/(?:http[s]?:\/\/.)?(?:www\.)?[-a-zA-Z0-9@%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/", $url)){
                return false;
            }
            return $url;
        }
        else{
            return false;
        }
    }
    private function resumeController($resume){
        if (isset($resume)){
            $url = trim($resume);

            if(empty($resume)){
                return false;
            }
            elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $resume)){
                return false;
            }
            $resume = htmlspecialchars($resume);
            return $resume;
        }
        else{
            return false;
        }
    }
}
