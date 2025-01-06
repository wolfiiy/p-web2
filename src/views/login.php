<!-- 
ETML
Authors: Santiago Escobar Toro, Sebastien Tille
Date: January 1st, 2025
Description: Form used to login to Passion Lecture.
-->

<main>
    <h1>Connexion</h1>
    <div class="submission-container">
        <form enctype = "multipart/form-data" 
            action="index.php?controller=user&action=login" 
            method="post" 
            id="bookForm"
            class="large-form wrap-col">
        
            <div class="md-outlined-input large on-surface-container">    
                <input type="text" name="usernameInput" id="usernameInput" placeholder=" ">
                <label for="usernameInput">Nom d'utilisateur</label>
            </div>
        
            <div class="md-outlined-input large on-surface-container">    
                <input type="passwordInput" name="passwordInput" id="passwordInput" placeholder=" ">
                <label for="passwordInput">Mot de passe</label>
            </div>
        
            <div>
                <input type="submit" value="Connexion" class="md-button primary">
                <a href="index.php?controller=user&action=signup" 
                class="md-button secondary">
                    Créer un compte
                </a>
            </div>
        </form>
    </div>
</main>