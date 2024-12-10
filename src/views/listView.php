<main>
    <h1>Liste des oeuvres</h1>

    <form id="genreForm" class="filter wrap-row" action="index.php">
    
    <input type="hidden" name="controller" value="book">
    <input type="hidden" name="action" value="list">

    <!-- Book category select -->
    <div class="filter-category">
        <label for="bookGenre">Genre: </label>
        <select onchange ='document.getElementById("genreForm").submit()' name="bookGenre" id="bookGenre">
            <option value="0">Tous</option>
            <?=$categoryDropdown;?>
        </select>
    </div>

    <!-- String input for book name or author's name -->
    <div class="filter-search">
        <input placeholder="Chercher un titre ou auteur" type="text" name="searchName" id="searchName" value="<?php echo $_GET["searchName"]?>">
        <button id="submitSearch" type="submit" class="small-button">
            <img src="assets/img/icons/search.svg" alt="Rechercher">
        </button>
    </div>
    </form>

    <script>
        // https://www.w3schools.com/howto/howto_js_trigger_button_enter.asp
        // Click on the submit button when enter is pressed in the text input
        var input = document.getElementById("searchName");
        input.addEventListener("keypress", function(event){
            if (event.key === "Enter"){
                event.preventDefault();
                error_log("Hell");
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