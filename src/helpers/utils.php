<?php 

/**
 * ETML
 * Author: Santiago Escobar
 * Date: December 2nd, 2024
 */

/**
 * Check session variable for connected user
 * @return bool True if a user is connected
 */
function isUserConnected() {
    return isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== "";
}

/**
 * Check session variable for connected admin user
 * @return bool True if an admin user is connected
 */
function isAdminConnectedUser() {
    if (isUserConnected()) {
        return $_SESSION["is_admin"];
    }
}

