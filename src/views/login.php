<main>
<h1>Connexion</h1>
<div class="submission-container">
    <form enctype = "multipart/form-data" 
          action="index.php?controller=user&action=login" 
          method="post" 
          id="bookForm"
          class="large-form wrap-col">
    
        <div class="md-outlined-input large on-surface-container">    
            <input type="text" name="userAttemp" id="userAttemp" placeholder=" ">
            <label for="userAttemp">Nom d'utilisateur</label>
        </div>
    
        <div class="md-outlined-input large on-surface-container">    
            <input type="password" name="passAttemp" id="passAttemp" placeholder=" ">
            <label for="passAttemp">Mot de passe</label>
        </div>
    
        <div>
            <input type="submit" value="Connexion" class="md-button primary">
            <a href="index.php_controller=user&action=signup" 
               class="md-button secondary">
                Créer un compte
            </a>
        </div>
    </form>
</div>
</main>