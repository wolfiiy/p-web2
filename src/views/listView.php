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

    <!-- https://www.w3schools.com/howto/howto_js_collapsible.asp -->
    <button type="button" class="collapsible" id="advancedSearch">+</button>
    <div class="content">
        <label for="bookSummary">Résumé :</label>
        <input type="text" name="bookSummary" id="bookSummary">
        <label for="bookAuthorFirstName">Prénom de l'auteur :</label>
        <input type="text" name="bookAuthorFirstName" id="bookAuthorFirstName">
        <label for="bookAuthorLastName">Nom de l'auteur :</label>
        <input type="text" name="bookAuthorLastName" id="bookAuthorLastName">
        <label for="bookUser">Ajouté par :</label>
        <input type="text" name="bookUser" id="bookUser">
        <label for="bookPublisher">Editeur :</label>
        <input type="text" name="bookPublisher" id="bookPublisher">
        <label for="bookReleaseDate">Date de publication :</label>
        <select name="dateSearch" id="dateSearch">
            <option value="">-</option>
            <option value="precise">Publié le: </option>
            <option value="after">Publié avant: </option>
            <option value="before">Publié après: </option>
        </select>
        <input type="date" name="bookReleaseDate" id="bookReleaseDate">
        
        <hr>
        Sort by TODO

    </div>
    </form>

    <script>

        // https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_collapsible
        var coll = document.getElementById("advancedSearch");

        coll.addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
            content.style.display = "none";
            } else {
            content.style.display = "block";
            }
        });

        
        // https://www.w3schools.com/howto/howto_js_trigger_button_enter.asp
        // Click on the submit button when enter is pressed in the text input
        var input = document.getElementById("searchName");
        input.addEventListener("keydown", function(event){
            if (event.key === "Enter"){
                event.preventDefault();
                const currentValue = this.value;
                document.getElementById("genreForm").submit();
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