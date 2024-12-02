<header>

    <nav>

        <a href="index.php"><img src="assets\img\Logo.png" width="70" height="70" alt="Logo of site"></a>

        <button onclick="location.href='index.php?controller=book&action=list';" style="cursor: pointer;" >List des oeuvres</button>

        <button onclick="location.href='index.php?controller=book&action=add';" style="cursor: pointer;" >Ajouter une oeuvre</button>

        <button onclick="location.href='index.php?controller=user&action=detail';" style="cursor: pointer;" >UserDetail Test</button>
    
        <a href="index.php?controller=book&action=detail&id=1">DETAILS DEBUG</a>

        <?php 
        if (isUserConnected()) {?>
        <button onclick="location.href='index.php?controller=user&action=logout';" style="cursor: pointer;" >Deconnexion</button>
        <?php }else{ ?>
        <button onclick="location.href='index.php?controller=user&action=login';" style="cursor: pointer;" >Connexion</button>
        <?php }?>
    </nav>
    <hr>
</header>