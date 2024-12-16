<main>
<h1>Ajout d'un livre</h1>
<div class="submission-container">
    <form enctype = "multipart/form-data" 
          action="index.php?controller=book&action=insert" 
          method="post" 
          id="bookForm"
          class="large-form wrap-col">

        <div class="md-outlined-input on-surface-container">
            <input type="text" name="test" placeholder=" ">
            <label for="test">Prénom de l'auteur</label>
        </div>

        <div class="md-outlined-input on-surface-container">
            <input type="text" name="test" placeholder=" ">
            <label for="test">Nom de l'auteur</label>
        </div>

        <div class="md-outlined-input on-surface-container large">
            <input type="text" name="test" placeholder=" ">
            <label for="test">Titre</label>
        </div>

        <div class="md-outlined-input on-surface-container">
            <input type="text" name="test" placeholder=" ">
            <label for="test">Editeur</label>
        </div>

        <div class="label-input">    
            <label for="authorFirstName" class="type">Nom de l'auteur</label>
            <input class="md-input secondary" type="text" name="authorFirstName" id="authorFirstName" value="<?php if (isset($author["first_name"])){echo $author["first_name"];}?>">
        </div>

        <div class="label-input">
            <label for="authorLastName">Prénom de l'auteur</label>
            <input class="md-input secondary" type="text" name="authorLastName" id="authorLastName" value="<?php if (isset($author["last_name"])){echo $author["last_name"];}?>">
        </div>
        
        <div class="label-input">
            <label for="bookTitle">Titre du livre</label>
            <input class="md-input secondary" type="text" name="bookTitle" id="bookTitle" value="<?php if (isset($book["title"])){echo $book["title"];}?>">
        </div>
        
        <div class="label-input">
            <label for="bookEditor">Editeur</label>
            <input class="md-input secondary" type="text" name="bookEditor" id="bookEditor"  value="<?php if (isset($publisher["name"])){echo $publisher["name"];}?>">
        </div>
        
        <div class="label-input">
            <label for="bookEditionYear">Année d'édition</label>
            <input class="md-input secondary" type="date" name="bookEditionYear" id="bookEditionYear" value="<?php if (isset($book["release_date"])){echo $book["release_date"];}?>">
        </div>
        
        <div class="label-input">
            <label for="bookPageNb">Nombre de page</label>
            <input class="md-input secondary" type="number" name="bookPageNb" id="bookPageNb" value="<?php if (isset($book["number_of_pages"])){echo $book["number_of_pages"];}?>">
        </div>
        
        <div class="label-input">
            <label for="bookGenre">Genre</label>
            <!-- $genres sont récupéré dans le bookController et passé à cette vue -->
            <select class="md-select secondary" name="bookGenre" id="bookGenre">
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
            <label for="coverImage">Image de couverture</label>
            <input class="md-input secondary" type="file" name="coverImage" id="coverImage">    
        </div>
<?php if ($_GET["action"] == "modify"){ echo "<p>Charger un nouveau fichier pour changer l'image</p>";}?> 
        
        <div class="label-input">
            <label for="snippetLink">Lien vers un extrait</label>
            <input class="md-input secondary" type="text" name="snippetLink" id="snippetLink" value="<?php if (isset($book["excerpt"])){echo $book["excerpt"];}?>">
        </div>
        
        <div class="label-input">
            <label for="bookSummary">Résumé</label>
            <textarea class="md-textarea secondary" name="bookSummary" id="bookSummary" ><?php if (isset($book["summary"])){echo $book["summary"];}?></textarea>
        </div>
        <div>
            <input type = "submit" value="<?php echo $submitButton ?>" class="md-button primary">
            <button type = "button" onclick="document.getElementById('bookForm').reset();" class="md-button error">Effacer</button>
        </div>
    </form>
</div>
</main>