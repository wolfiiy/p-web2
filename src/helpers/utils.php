<?php 

function isUserConnected() {
    return isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== "";
}

function isAdminConnectedUser() {
    if (isUserConnected()) {
        return $_SESSION["is_admin"];
    }
}

