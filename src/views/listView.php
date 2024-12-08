<main>
    <h1>Liste des oeuvres</h1>

    <form id="genreForm" action="index.php">
    
    <input type="hidden" name="controller" value="book">
    <input type="hidden" name="action" value="list">

    <!-- Book category select -->
    <label for="bookGenre">Genre: </label>
    <select onchange ='document.getElementById("genreForm").submit()' name="bookGenre" id="bookGenre">
        <option value="0">Tous</option>
        <?php
        foreach($genres as $genre){           
            echo "<option value='".$genre["category_id"] . "' "; 
            if ($genre["category_id"] == $_GET["bookGenre"]) {echo "selected";}
            echo ">" . $genre["name"] ."</option>";
        }
        ?>
    </select>

    <!-- String input for book name --> 
    <input placeholder="Recherche" type="text" name="searchName" id="searchName" value=<?php echo $_GET["searchName"]?>>
    <input type="submit" value="Chercher" id="submitSearch">
    </form>
    <script>
        // https://www.w3schools.com/howto/howto_js_trigger_button_enter.asp
        // Click on the submit button when enter is pressed in the text input
        var input = document.getElementById("searchName");
        input.addEventListener("keypress", function(event){
            if (event.key === "Enter"){
                event.preventDefault();
                document.getElementById("submitSearch").click();
            }
        })
    </script>

<!-- List of books searched -->
<?php
    if (count($books) == 0){
        echo "<p>Aucun résultat</p>";
    }

    HtmlWriter::writeCompactBooksPreview($books);
?>

<!-- Pagination buttons -->
<form action=""></form>
<button onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page>1) {echo "&page=1\"'";} else {echo '\'" disabled ';} ?>><<</button>
<button onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page>1) {echo "&page=" . $page-1 . "\"'";} else {echo '\'" disabled ';} ?>><</button>
<?php echo $page . " / " . $maxPage?> 
<button onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page<$maxPage) {echo "&page=" . $page+1 . "\"'";} else {echo '\'" disabled';} ?>>></button>
<button onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page<$maxPage) {echo "&page=" . $maxPage . "\"'";} else {echo '\'" disabled';} ?>>>></button>

</main>