<!-- 
ETML
Authors: Sebastien Tille
Date: January 6th, 2025
Description: Displays a book's detail. The layout has been inspired by 
    Anilist's, with a left column consisting of the cover and advanced info and  
    a wider right column with basic info and a summary.
-->

<main>
    <h1>Détails de l'oeuvre</h1>
    <div id="details-content-wrap" class="content-wrap">
        <div class="cover-and-data">
            <img src="<?=$book['cover_image'];?>" 
                    alt="Image de couverture"
                    class="shadow shadow-hover details-cover"
                    onclick="window.location.href='<?=$book['cover_image'];?>'">

            <div class="data wrap">
                <div class="data-set">
                    <p class="type">
                        Moyenne
                    </p>
        
                    <p class="value">
                        <?=$book['average_rating']?>
                    </p>
                </div>

                <div class="data-set">
                    <p class="type">
                        Nombre d'appréciations
                    </p>
        
                    <p class="value">
                        <?=$book['nb_rating']?>
                    </p>
                </div>
        
                <div class="data-set">
                    <p class="type">
                        Genre
                    </p>
        
                    <p class="value">
                        <?=$book['category_name']?>
                    </p>
                </div>
        
                <div class="data-set">
                    <p class="type">
                        Nombre de pages
                    </p>
        
                    <p class="value">
                        <?=$book['number_of_pages']?>
                    </p>
                </div>
        
                <div class="data-set">
                    <p class="type">
                        Ajouté par
                    </p>
        
                    <p class="value">
                        <a href="index.php?controller=user&action=detail&id=<?=$user['user_id']?>"><?=$user['username']?></a>
                    </p>
                </div>
        
                <div class="data-set">
                    <p class="type">
                        Lire un extrait
                    </p>
        
                    <p class="value">
                        <a href="<?=$book['excerpt']?>">Lien externe</a>
                    </p>
                </div>

                <div class="data-set">
                <?php if(isset($_SESSION["user_id"])){
                        if(isAdminConnectedUser() || $book["user_fk"] == $_SESSION["user_id"]){ ?>
                    <p class="type">
                        Editer cette page
                    </p>
                    <p class="value">
                        <a href="index.php?controller=book&action=modify&id=<?php echo $book["book_id"]?>" class="md-button primary wide">Modifier</a>
                    </p>                  
                    <p class="value">
                        <a href="index.php?controller=book&action=delete&id=<?php echo $book["book_id"]?>" class="md-button error wide">Supprimer</a>
                    </p>
                <?php }}?>
                </div>
            </div>
        </div>

        <div class="overview-and-summary">
            <div class="overview wrap">
                <h2>
                    <?=$book['title']?>
                </h2>
            
                <p>
                    Auteur:
                    <?=$book['author_name']?>
                </p>
                
                <p>
                    Date de sortie:
                    <?=$book['release_date'];?>
                </p>
                
                <p>
                    Editeur:
                    <?=$book['publisher']?>
                </p>

                <?php if(isset($_SESSION["user_id"])){ ?>
                <div class="rating">
                    <p>
                        Votre note:
                    </p>
                    
                    <form method="post" action="index.php?controller=book&action=rate&book_id=<?php echo $book["book_id"]?>">
                        <?=$dropdown?>
                        <button type="submit" 
                                name="rate"
                                class="md-button secondary"
                        >
                            Valider
                        </button>
                    </form>
                </div>
                <?php } else {?>
                <p>Veuillez vous <a href="index.php?controller=user&action=logout">connecter</a> pour évaluer ce livre</p>
                <?php }?>
            </div>
            
            <div class="description wrap">
                <h3>
                    Résumé
                </h3>
                
                <p class="summary">
                    <?=$book['summary']?>
                </p>
            </div>
        </div>
    </div>
</main>