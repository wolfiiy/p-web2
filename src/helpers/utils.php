<?php

/**
 * ETML
 * Author: Santiago Escobar, Abigaël Périsset
 * Date: December 17th, 2024
 */

// TODO
// - Turn into a proper static class
// - Use constants for regex
// Complete documentation

/**
 * Checks whether the user is logged in by peeking at the session variable.
 * @return bool True if the user is logged in, false otherwise.
 */
function isUserConnected() {
    return isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== "";
}

/**
 * Checks whether the logged user is an administrator.
 * @return bool True if the logged user is an administrator, false otherwise.
 */
function isAdminConnectedUser() {
    if (isUserConnected()) {
        return $_SESSION["is_admin"];
    }
}

/**
 * Check name entry
 * @return bool false if not fit into the criteria
 */
function textController($name) {
    //vérifier le champs 
    if (isset($name)) {
        $name = trim($name);

        if (empty($name))
            return false;
        elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s\-]+$/", $name))
            return false;
        
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
function yearController($year) {
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
 * Verify that a number is a positive integer.
 * @return int|false The number if it is a positive integer, false otherwise.
 */
function pageController($number) {
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
 * Checks an URL's validity.
 * @return bool false if is not a url
 */
function urlController($url) {
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
function resumeController($summary) {
    if (isset($summary)) {
        $url = trim($summary);

        if (empty($summary)) {
            return false;
        } elseif (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s\-\.,:;()?!']+$/", $summary)) {
            return false;
        }
        $summary = htmlspecialchars($summary);
        return $summary;
    } else {
        return false;
    }
}
