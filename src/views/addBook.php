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
            <input class="md-input secondary" type="text" name="authorFirstName" id="authorFirstName">
        </div>

        <div class="label-input">
            <label for="authorLastName">Prénom de l'auteur</label>
            <input class="md-input secondary" type="text" name="authorLastName" id="authorLastName">
        </div>
        
        <div class="label-input">
            <label for="bookTitle">Titre du livre</label>
            <input class="md-input secondary" type="text" name="bookTitle" id="bookTitle">
        </div>
        
        <div class="label-input">
            <label for="bookEditor">Editeur</label>
            <input class="md-input secondary" type="text" name="bookEditor" id="bookEditor">
        </div>
        
        <div class="label-input">
            <label for="bookEditionYear">Année d'édition</label>
            <input class="md-input secondary" type="number" name="bookEditionYear" id="bookEditionYear">
        </div>
        
        <div class="label-input">
            <label for="bookPageNb">Nombre de page</label>
            <input class="md-input secondary" type="number" name="bookPageNb" id="bookPageNb">
        </div>
        
        <div class="label-input">
            <label for="bookGenre">Genre</label>
            <!-- $genres sont récupéré dans le bookController et passé à cette vue -->
            <select class="md-select secondary" name="bookGenre" id="bookGenre">
                <?php
                foreach($genres as $genre){
                    echo "<option value='".$genre["category_id"]."'>" . $genre["name"] ."</option>";
                }
                ?>
            </select>
        </div>

        <div class="label-input">
            <label for="coverImage">Image de couverture</label>
            <input class="md-input secondary" type="file" name="coverImage" id="coverImage">
        </div>
        
        <div class="label-input">
            <label for="snippetLink">Lien vers un extrait</label>
            <input class="md-input secondary" type="text" name="snippetLink" id="snippetLink">
        </div>
        
        <div class="label-input">
            <label for="bookSummary">Résumé</label>
            <textarea class="md-textarea secondary" name="bookSummary" id="bookSummary"></textarea>
        </div>
        <div>
            <input type = "submit" value="Ajouter" class="md-button primary">
            <button type = "button" onclick="document.getElementById('bookForm').reset();" class="md-button error">Effacer</button>
        </div>
    </form>
</div>
</main>