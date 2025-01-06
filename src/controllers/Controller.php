<?php
session_start();

/**
 * Author: Cindy Hardegger
 * Date: November 18th, 2024
 */

/**
 * Controller base.
 */
abstract class Controller {

    /**
     * Méthode permettant d'appeler l'action 
     * @return mixed A callback to a function.
     */
    public function display() {
        $page = $_GET['action'] . "Display";
        $this->$page();
    }
}