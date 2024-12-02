<main>
    <h1>Passion Lecture</h1>
    <p>
        Liste des oeuvres
        </p>
        <div class="label-input">

    <form id="genreForm" action="index.php">
    <input type="hidden" name="controller" value="book">
    <input type="hidden" name="action" value="list">
    <label for="bookGenre">Genre: </label>
    <!-- $genres sont récupéré dans le bookController et passé à cette vue -->
    <select onchange ='document.getElementById("genreForm").submit()' name="bookGenre" id="bookGenre">
        <option value="0">Tous</option>
        <?php

        foreach($genres as $genre){           
            echo "<option value='".$genre["category_id"] . "' "; 
            if (isset($_SESSION["genreFilter"])){
                if ($genre["category_id"] == $_SESSION["genreFilter"]) {echo "selected";}
            }
            echo ">" . $genre["name"] ."</option>";
        }
        ?>
    </select>
    </form>
    
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
<form action=""></form>
<button onclick='window.location.href="index.php?controller=book&action=list<?php if ($page>1) echo "&page=" . $page-1?>"'><</button>
<?php echo $page?> 
<button onclick='window.location.href="index.php?controller=book&action=list<?php echo "&page=" . $page+1?>"'>></button>



    </main>