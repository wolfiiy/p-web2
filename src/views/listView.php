<!-- 
ETML
Authors: Valentin Pignat
Date: january 6th, 2025
Description: A list of all the entries found in Passion Lecture's database. The
    list is split into pages of 10 books. It is also possible to search for a 
    book or an author from this page.
-->

<main>
    <h1>Liste des oeuvres</h1>

    <form id="genreForm" class="filter wrap-row" action="index.php">
        <input type="hidden" name="controller" value="book">
        <input type="hidden" name="action" value="list">

        <!-- Book category select -->
        <div class="filter-category wrap-row">
            <label for="bookGenre">Genre: </label>
            <select class="md-select secondary" onchange ='document.getElementById("genreForm").submit()' name="bookGenre" id="bookGenre">
                <option value="0">Tous</option>
                <?=$categoryDropdown;?>
            </select>
        </div>

        <!-- String input for book name or author's name -->
        <div class="filter-search wrap-row">
            <input class="md-input secondary" placeholder="Chercher un titre ou auteur" type="text" name="searchName" id="searchName" value="<?php echo $_GET["searchName"]?>">
            <button id="submitSearch" type="submit" class="md-button secondary icon">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 10.50,10.50 C 10.50,10.50 8.32,8.32 8.32,8.32M 9.50,5.50 C 9.50,7.71 7.71,9.50 5.50,9.50 3.29,9.50 1.50,7.71 1.50,5.50 1.50,3.29 3.29,1.50 5.50,1.50 7.71,1.50 9.50,3.29 9.50,5.50 Z"/>
                </svg>
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
<button class="md-button secondary" onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page>1) {echo "&page=1\"'";} else {echo '\'" disabled ';} ?>><<</button>
<button class="md-button secondary" onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page>1) {echo "&page=" . $page-1 . "\"'";} else {echo '\'" disabled ';} ?>><</button>
<?php echo $page . " / " . $maxPage?> 
<button class="md-button secondary" onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page<$maxPage) {echo "&page=" . $page+1 . "\"'";} else {echo '\'" disabled';} ?>>></button>
<button class="md-button secondary" onclick='window.location.href="index.php?controller=book&action=list&searchName=<?php echo $_GET["searchName"] . "&bookGenre=" . $_GET["bookGenre"]; if ($page<$maxPage) {echo "&page=" . $maxPage . "\"'";} else {echo '\'" disabled';} ?>>>></button>

</main>