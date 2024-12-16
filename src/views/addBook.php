<main>
<h1>Ajout d'un livre</h1>
<form enctype = "multipart/form-data" action="<?php echo $actionURL ?>" method="post" id="bookForm">

<div class="label-input">    
    <label for="authorFirstName">Nom de l'auteur: </label>
    <input type="text" name="authorFirstName" id="authorFirstName" value="<?php if (isset($author["first_name"])){echo $author["first_name"];}?>">
</div>

<div class="label-input">
    <label for="authorLastName">Prénom de l'auteur: </label>
    <input type="text" name="authorLastName" id="authorLastName" value="<?php if (isset($author["last_name"])){echo $author["last_name"];}?>">
</div>

<div class="label-input">
    <label for="bookTitle">Titre du livre: </label>
    <input type="text" name="bookTitle" id="bookTitle" value="<?php if (isset($book["title"])){echo $book["title"];}?>">
</div>

<div class="label-input">
    <label for="bookEditor">Editeur: </label>
    <input type="text" name="bookEditor" id="bookEditor"  value="<?php if (isset($publisher["name"])){echo $publisher["name"];}?>">
</div>

<div class="label-input">
    <label for="bookEditionYear">Année d'édition: </label>
    <input type="date" name="bookEditionYear" id="bookEditionYear" value="<?php if (isset($book["release_date"])){echo $book["release_date"];}?>">
</div>

<div class="label-input">
    <label for="bookPageNb">Nombre de page: </label>
    <input type="number" name="bookPageNb" id="bookPageNb" value="<?php if (isset($book["number_of_pages"])){echo $book["number_of_pages"];}?>">
</div>

<div class="label-input">
    <label for="bookGenre">Genre: </label>
<!-- $genres sont récupéré dans le bookController et passé à cette vue -->
    <select name="bookGenre" id="bookGenre">
        <?php
        foreach($genres as $genre){
            echo "<option value='". $genre["category_id"] . "'";
            if (isset($book["category_fk"])){
                if ($genre["category_id"] == $book["category_fk"]){echo "selected";}
            }
            echo  ">" . $genre["name"] . "</option>";
        }
        ?>
    </select>
</div>

<div class="label-input">
    <label for="coverImage">Image de couverture: </label>
    <input type="file" name="coverImage" id="coverImage">    
</div>
<?php if ($_GET["action"] == "modify"){ echo "<p>Charger un nouveau fichier pour changer l'image</p>";}?> 

<div class="label-input">
    <label for="snippetLink">Lien vers un extrait: </label>
    <input type="text" name="snippetLink" id="snippetLink" value="<?php if (isset($book["excerpt"])){echo $book["excerpt"];}?>">
</div>

<div class="label-input">
    <label for="bookSummary">Résumé: </label>
    <textarea name="bookSummary" id="bookSummary" ><?php if (isset($book["summary"])){echo $book["summary"];}?></textarea>
</div>
<div>
    <input type = "submit" value="Ajouter">
    <button type = "button" onclick="document.getElementById('bookForm').reset();">Effacer</button>
</div>

    
</form>
</main>