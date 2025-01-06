<?php
/**
 * Authors: Santiago Escobar Toro, Sebastien Tile
 * Date: January 1st, 2025
 * Description: The navigation bar. It contains a button to allow for user login
 * which changes to a disconnect button upon login.
 */

 // This is not tied to a specific controller and thus has been left here.
$accountNav = "";
$userId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "";

// If the user is logged in, display their name and a logout button.
if (isUserConnected()) {
    $accountNav .= <<< HTML
        <li>
            <a href="index.php?controller=book&action=add">
                Ajouter une entrée
            </a>
        </li>
        
        <li>
            <a href="index.php?controller=user&action=detail">
                {$_SESSION['username']}
            </a>
        </li>

        <li>
            <a href="index.php?controller=user&action=logout" class="md-button primary">
                Déconnexion
            </a>
        </li>
    HTML;
} else {
    // Otherwise, display a login button
    $accountNav .= <<< HTML
        <li>
            <a href="index.php?controller=user&action=logout" class="md-button primary">
                Connexion
            </a>
        </li>
    HTML;
}
?>

<header>
    <nav id="navbar">
        <div class="nav-wrapper">
            <a href="index.php" class="clickable-image">
                <img src="assets/img/logo.png" alt="Logo de Passion Lecture" id="logo-nav">
            </a>
    
            <ul class="wrap-row nav-links-wrapper">
                <li>
                    <a href="index.php?controller=book&action=list">
                        Liste des oeuvres
                    </a>
                </li>
    
                <?=$accountNav?>
            </ul>
        </div>
    </nav>
</header>