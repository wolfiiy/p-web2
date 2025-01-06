<?php

/**
 * ETML
 * Author: Santiago Escobar, Abigaël Périsset
 * Date: December 17th, 2024
 */

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