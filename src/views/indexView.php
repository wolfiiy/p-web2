<main>
    <h1>Passion Lecture</h1>
    <p>
        Description de l'application
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