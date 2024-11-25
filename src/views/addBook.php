<h1>Ajout d'un livre</h1>
<form action="index.php?controller=book&action=insert" method="post" id="bookForm">

<div class="label-input">    
    <label for="authorFirstName">Nom de l'auteur: </label>
    <input type="text" name="authorFirstName" id="authorFirstName">
</div>

<div class="label-input">
    <label for="authorLastName">Prénom de l'auteur: </label>
    <input type="text" name="authorLastName" id="authorLastName">
</div>

<div class="label-input">
    <label for="bookTitle">Titre du livre: </label>
    <input type="text" name="bookTitle" id="bookTitle">
</div>

<div class="label-input">
    <label for="bookEditor">Editeur: </label>
    <input type="text" name="bookEditor" id="bookEditor">
</div>

<div class="label-input">
    <label for="bookEditionYear">Année d'édition: </label>
    <input type="number" name="bookEditionYear" id="bookEditionYear">
</div>

<div class="label-input">
    <label for="bookPageNb">Nombre de page: </label>
    <input type="number" name="bookPageNb" id="bookPageNb">
</div>

<div class="label-input">
    <label for="bookGenre">Genre: </label>
<!-- $genres sont récupéré dans le bookController et passé à cette vue -->
    <select name="bookGenre" id="bookGenre">
        <?php
        foreach($genres as $genre){
            echo "<option value='".$genre["category_id"]."'>" . $genre["name"] ."</option>";
        }
        ?>
    </select>
</div>

<div class="label-input">
    <label for="coverImage">Image de couverture: </label>
    <input type="file" name="coverImage" id="coverImage">
</div>

<div class="label-input">
    <label for="snippetLink">Lien vers un extrait: </label>
    <input type="text" name="snippetLink" id="snippetLink">
</div>

<div class="label-input">
    <label for="bookSummary">Résumé: </label>
    <textarea name="bookSummary" id="bookSummary"></textarea>
</div>
<div>
    <input type = "submit" value="Ajouter">
    <button type = "button" onclick="document.getElementById('bookForm').reset();">Effacer</button>
</div>

    
</form>