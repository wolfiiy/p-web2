<!-- 
ETML
Authors: Valentin Pignat, Santiago Escobar Toro, Sebastien Tille
Date: December 3rd, 2024
Description: Home page of Passion Lecture, with a short introduction and a large
    list of the five most recent additions to the database.
-->

<main>
    <h1>Passion Lecture</h1>
    <p>
        Bienvenue sur notre page des lecteurs! Ce site vous permet de partager
        au reste du monde les livres dont vous êtes l'auteur, ou simplement 
        ceux que vous souhaitez recommander.
    </p>

    <p>
        Vous trouverez ci-dessous les cinq dernières oeuvres ajoutées par nos utilisateurs.
    </p>
    <?php
        HtmlWriter::writeBooksPreview($latestBooks);
    ?>
</main>