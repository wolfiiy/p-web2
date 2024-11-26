<header>

    <nav>

        <a href="index.php"><img src="assets\img\Logo.png" width="70" height="70" alt="Logo of site"></a>

        <button onclick="location.href='index.php?controller=book&action=list';" style="cursor: pointer;" >List des oeuvres</button>

        <button onclick="location.href='index.php?controller=book&action=add';" style="cursor: pointer;" >Ajouter une oeuvre</button>

        <button onclick="location.href='index.php?controller=user&action=detail';" style="cursor: pointer;" >UserDetail Test</button>
    
        <a href="index.php?controller=book&action=detail&id=1">DETAILS DEBUG</a>

        <form>
            <label for="username">Nom d'utilisateur: </label>
            <input type="text" name="username" id="username" required>
            <label for="password">Mot de passe: </label>
            <input type="password" name="password" id="password" required>
            <input type ="submit" style="cursor: pointer;" value="Connexion"/>       
        </form>
        <button onclick="location.href='login.php?controller=user&action=login';">Connexion</button>
    </nav>
    <hr>
</header>