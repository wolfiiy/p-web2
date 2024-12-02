<main>
    <h1>Passion Lecture</h1>
    <p>
        Liste des oeuvres
        </p>
        <div class="label-input">
    <label for="bookGenre">Genre: </label>
<!-- $genres sont récupéré dans le bookController et passé à cette vue -->
    <select name="bookGenre" id="bookGenre">
        <option value="all">Tous</option>
        <?php
        foreach($genres as $genre){
            echo "<option value='".$genre["category_id"]."'>" . $genre["name"] ."</option>";
        }
        ?>
    </select>
</div>
<?php

    foreach($books as $book){
        echo "<div class = 'book-preview'>";
        echo "<img src='https://developers.elementor.com/docs/assets/img/elementor-placeholder-image.png'>";
        echo "<div>". $book["title"] . '<br>'. $book["author_name"]. "<br>". $book["category_name"]."</div>";
        echo "<div>". $book["average_rating"] . '<br>'. $book["username_name"]. "<br>". "<a href = 'index.php?controller=book&action=detail&id=". $book["book_id"] ."'>Détails</a>"."</div>";

        echo "</div>";
    }
?>

    </main>