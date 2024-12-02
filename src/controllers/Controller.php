<?php
session_start();
/**
 * Auteur : Cindy Hardegger
 * Date: 18.11.2024
 * Contrôleur principal
 */

abstract class Controller {

    /**
     * Méthode permettant d'appeler l'action 
     *
     * @return mixed
     */
    public function display() {

        $page = $_GET['action'] . "Display";
        $this->$page();
    }
}