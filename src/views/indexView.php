<main>
    <h1>Passion Lecture</h1>
    <p>
    Bienvenue sur notre page des lecteurs. Vous pouvez y partager les livres dont vous êtes l'auteur ou ceux que vous aimeriez recommander.
    </p>
    <p>
        Liste des dernières oeuvres
        </p>
<?php
    foreach($latestBooks as $book){
        echo "<div class = 'book-preview'>";
        echo "<img src='https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png'>";
        echo "<div>". $book["title"] . '<br>'. $book['author_fk']. "<br>". $book['category_fk']."</div>";
        echo "<div>". $ratings[$book["book_id"]] . '<br>'. $book['user_fk']. "<br>". $book['category_fk']."</div>";

        echo "</div>";
    }
?>

    </main>