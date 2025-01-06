<?php

/**
 * ETML
 * Author: Santiago Escobar, Abigaël Périsset
 * Date: December 17, 2024
 */

/**
 * Check session variable for connected user
 * @return bool True if a user is connected
 */
function isUserConnected()
{
    return isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== "";
}

/**
 * Check session variable for connected admin user
 * @return bool True if an admin user is connected
 */
function isAdminConnectedUser()
{
    if (isUserConnected()) {
        return $_SESSION["is_admin"];
    }
}

/**
 * Check name entry
 * @return bool false if not fit into the criteria
 */
function textController($name)
{
    //vérifier le champs 
    if (isset($name)) {
        $name = trim($name); //suppression des espaces en début et fin

        if (empty($name)) {
            return false; // si le champs est vide
        } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/", $name)) {
            return false;
        }
        $name = htmlspecialchars($name);
        return $name;
    } else {
        return false;
    }
}

/**
 * Check year entry
 * @return bool false if noot 4 number
 */
function yearController($year)
{
    if (isset($year)) {
        $year = trim($year);

        if (empty($year)) {
            return false;
        } elseif (!preg_match("/^\d{4}$/", $year)) {
            return false;
        }
        return $year;
    } else {
        return false;
    }
}

/**
 * Check number entry
 * @return bool False if not a number
 */
function pageController($number)
{

    if (isset($number)) {
        $number = trim($number);

        if (empty($number)) {
            return false;
        } elseif (!is_numeric($number)) {
            return false;
        }
        return $number;
    } else {
        return false;
    }
}

/**
 * Check url entry
 * @return bool false if is not a url
 */
function urlController($url)
{
    if (isset($url)) {
        $url = trim($url);

        if (empty($url)) {
            return false;
        } elseif (!preg_match("/(?:http[s]?:\/\/.)?(?:www\.)?[-a-zA-Z0-9@%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)/", $url)) {
            return false;
        }
        return $url;
    } else {
        return false;
    }
}

/**
 * Check text entry
 * @return bool false if is special characters
 */
function resumeController($resume)
{
    if (isset($resume)) {
        $url = trim($resume);

        if (empty($resume)) {
            return false;
        } elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $resume)) {
            return false;
        }
        $resume = htmlspecialchars($resume);
        return $resume;
    } else {
        return false;
    }
}
