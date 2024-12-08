<?php
// TODO move this to controller & helper
$accountNav = "";

if (isUserConnected()) {
    $accountNav .= <<< HTML
        <li>
            <a href="index.php?controller=user&action=detail">
                Mon compte
            </a>
        </li>

        <li>
            <a href="index.php?controller=user&action=logout">
                Déconnexion
            </a>
        </li>
    HTML;
} else {
    $accountNav .= <<< HTML
        <li>
            <a href="index.php?controller=user&action=logout">
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
    
                <li>
                    <a href="index.php?controller=book&action=add">
                        Ajouter une entrée
                    </a>
                </li>
    
                <?=$accountNav?>
            </ul>
        </div>
    </nav>
</header>