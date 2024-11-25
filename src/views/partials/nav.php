<header>

    <nav>

        <a href="index.php"><img src="assets\img\Logo.png" width="90" height="90" alt="Logo of site"></a>

        <button onclick="location.href='index.php?controller=book&action=list';" style="cursor: pointer;">List des oeuvres</button>

        <button onclick="location.href='index.php?controller=book&action=add';" style="cursor: pointer;">Ajouter une oeuvre</button>
    

        <form>
            <label for="username">Nom d'utilisateur: </label>
            <input type="text" name="username" id="username" required>
            <label for="password">Mot de passe: </label>
            <input type="password" name="password" id="password" required>
            <input type ="submit" value="Connexion"/>       
        </form>
    </nav>
    <hr>
</header>