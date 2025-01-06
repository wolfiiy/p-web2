<!-- 
ETML
Authors: Abigael Perisset, Valentin Pignat
Date: January 6th, 2025
Description: Form used to add or modify an entry.
-->

<main>
<h1><?=$h1?></h1>
<div class="submission-container">
    <form enctype = "multipart/form-data" 
          action="<?=$actionURL?>" 
          method="post" 
          id="bookForm"
          class="large-form wrap-col">

        <div class="md-outlined-input on-surface-container">    
            <input type="text" name="authorFirstName" id="authorFirstName" value="<?=$author["first_name"];?>" placeholder=" ">
            <label for="authorFirstName">Prénom de l'auteur</label>
        </div>

        <div class="md-outlined-input on-surface-container">
            <input type="text" name="authorLastName" id="authorLastName" value="<?=$author["last_name"];?>" placeholder=" ">
            <label for="authorLastName">Nom de l'auteur</label>
        </div>
        
        <div class="md-outlined-input on-surface-container">
            <input type="text" name="bookTitle" id="bookTitle" value="<?=$book["title"];?>" placeholder=" ">
            <label for="bookTitle">Titre du livre</label>
        </div>
        
        <div class="md-outlined-input on-surface-container">
            <input type="text" name="bookEditor" id="bookEditor"  value="<?=$publisher["name"];?>" placeholder=" ">
            <label for="bookEditor">Editeur</label>
        </div>
        
        <div class="label-input">
        <label for="bookEditionYear">Année d'édition</label>
            <input class="md-input secondary small" type="date" name="bookEditionYear" id="bookEditionYear" value="<?=$book["release_date"];?>">
            
        </div>
        
        <div class="md-outlined-input on-surface-container">
            <input type="number" name="bookPageNb" id="bookPageNb" value="<?=$book["number_of_pages"];?>" placeholder=" ">
            <label for="bookPageNb">Nombre de page</label>
        </div>
        
        <div class="label-input">
            <label for="bookGenre">Genre</label>
            <!-- Categories ($genre) gathered from the BookController -->
            <select class="md-select secondary" name="bookGenre" id="bookGenre">
                <?php
                foreach($genres as $genre){
                    echo "<option value='". $genre["category_id"] . "'";
                    if (isset($book["category_fk"])){
                        if ($genre["category_id"] == $bookGenre){echo "selected";}
                    }
                    echo  ">" . $genre["name"] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="label-input">
            <label for="coverImage">Image de couverture</label>
            <input class="md-input secondary large" type="file" name="coverImage" id="coverImage">    
        </div>
<?php if ($_GET["action"] == "modify"){ echo "<p>Charger un nouveau fichier pour changer l'image</p>";}?> 
        
        <div class="md-outlined-input on-surface-container">
            <input type="text" name="snippetLink" id="snippetLink" value="<?=$book["excerpt"];?>" placeholder=" ">
            <label for="snippetLink">Lien vers un extrait</label>
        </div>
        
        <div class="md-outlined-textarea on-surface-container">
            <label for="bookSummary">Résumé</label>
            <textarea class="md-textarea secondary" name="bookSummary" id="bookSummary" ><?=$book["summary"];?></textarea>
        </div>
        <div>
            <input type = "submit" value="<?php echo $submitButton ?>" class="md-button primary">
            <button type = "button" onclick="document.getElementById('bookForm').reset();" class="md-button error">Effacer</button>
        </div>
    </form>
</div>
</main>