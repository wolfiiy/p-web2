<main>
<h1>Création d'un compte</h1>

<div class="submission-container">
    <p>
        Veuillez renseigner les champs suivants.
    </p>
    
    <form enctype="multipart/form-data" 
          action="index.php?controller=user&action=signup" 
          method="post"
          class="large-form wrap-col">
        
        <div class="md-outlined-input large on-surface-container">
            <input type="text" name="username" id="username" placeholder=" " required>
            <label for="">Nom d'utilisateur</label>
        </div>

        <div class="md-outlined-input large on-surface-container">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label for="">Mot de passe</label>
        </div>

        <div class="md-outlined-input large on-surface-container">
            <input type="password" name="password-confirm" id="password-confirm" placeholder=" " required>
            <label for="">Mot de passe (confirmation)</label>
        </div>

        <div>
            <input class="md-button primary" 
                   type="submit" 
                   value="Créer le compte"
            >
        </div>
    </form>
</div>
</main>