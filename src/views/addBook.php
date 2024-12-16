<main>
<h1>Ajout d'un livre</h1>
<div class="submission-container">
    <form enctype = "multipart/form-data" 
          action="index.php?controller=book&action=insert" 
          method="post" 
          id="bookForm"
          class="large-form wrap-col">

        <div class="md-outlined-input on-surface-container">    
            <input type="text" name="authorFirstName" id="authorFirstName" value="<?php if (isset($author["first_name"])){echo $author["first_name"];}?>" placeholder=" ">
            <label for="authorFirstName">Nom de l'auteur</label>
        </div>

        <div class="md-outlined-input on-surface-container">
            <input type="text" name="authorLastName" id="authorLastName" value="<?php if (isset($author["last_name"])){echo $author["last_name"];}?>" placeholder=" ">
            <label for="authorLastName">Prénom de l'auteur</label>
        </div>
        
        <div class="md-outlined-input on-surface-container">
            <input type="text" name="bookTitle" id="bookTitle" value="<?php if (isset($book["title"])){echo $book["title"];}?>" placeholder=" ">
            <label for="bookTitle">Titre du livre</label>
        </div>
        
        <div class="md-outlined-input on-surface-container">
            <input type="text" name="bookEditor" id="bookEditor"  value="<?php if (isset($publisher["name"])){echo $publisher["name"];}?>" placeholder=" ">
            <label for="bookEditor">Editeur</label>
        </div>
        
        <div class="label-input">
            <label for="bookEditionYear">Année d'édition</label>
            <input class="md-input secondary" type="date" name="bookEditionYear" id="bookEditionYear" value="<?php if (isset($book["release_date"])){echo $book["release_date"];}?>">
        </div>
        
        <div class="md-outlined-input on-surface-container">
            <input type="number" name="bookPageNb" id="bookPageNb" value="<?php if (isset($book["number_of_pages"])){echo $book["number_of_pages"];}?>" placeholder=" ">
            <label for="bookPageNb">Nombre de page</label>
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
        
        <div class="md-outlined-input on-surface-container">
            <input type="text" name="snippetLink" id="snippetLink" value="<?php if (isset($book["excerpt"])){echo $book["excerpt"];}?>" placeholder=" ">
            <label for="snippetLink">Lien vers un extrait</label>
        </div>
        
        <div class="md-outlined-textarea on-surface-container">
            <textarea class="md-textarea secondary" name="bookSummary" id="bookSummary" placeholder=" "><?php if (isset($book["summary"])){echo $book["summary"];}?></textarea>
            <label for="bookSummary">Résumé</label>
        </div>
        <div>
            <input type = "submit" value="<?php echo $submitButton ?>" class="md-button primary">
            <button type = "button" onclick="document.getElementById('bookForm').reset();" class="md-button error">Effacer</button>
        </div>
    </form>
</div>
</main>