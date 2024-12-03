<main>
    <h1>Passion Lecture</h1>
    <p>
    Bienvenue sur notre page des lecteurs. Vous pouvez y partager les livres dont vous êtes l'auteur ou ceux que vous aimeriez recommander.
    </p>
    <p>
        Liste des dernières oeuvres
        </p>
<?php

    HtmlWriter::writeBooksPreview($latestBooks);

    foreach($latestBooks as $book){
        echo "<div class = 'book-preview'>";
        echo "<img src='assets/img/placeholders/cover-placeholder.png'>";
        echo "<div>". $book["title"] . '<br>'. $book["author_name"]. "<br>". $book["category_name"]."</div>";
        echo "<div>". $book["average_rating"] . '<br>'. $book["username_name"]. "<br>". "<a href = 'index.php?controller=book&action=detail&id=". $book["book_id"] ."'>Détails</a>"."</div>";

        echo "</div>";
    }
?>

    </main>