<main>
    <h1>Détails de l'oeuvre</h1>
    <div id="details-content-wrap" class="content-wrap">
        <div class="details-cover wrap">
            <img src="<?=$book['cover_image'];?>" 
                 alt="Image de couverture"
                 class="shadow shadow-hover"
                 onclick="window.location.href='<?=$book['cover_image'];?>'">
        </div>

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

            <div class="rating">
                <p>
                    Votre note:
                </p>
    
                <form method="post">
                    <?=$dropdown?>
                    <button type="submit" name="rate">Valider</button>
                </form>
            </div>
        </div>

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
                    <?=$user['username']?>
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
                <p class="type">
                    Editer cette page
                </p>

                <p class="value">
                    <a href="" class="button accent">Modifier</a>
                </p>
                <?php if(isAdminConnectedUser()){ ?>
                <p class="value">
                    <a href="" class="button red">Supprimer</a>
                </p>
                <?php }?>
            </div>
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
</main>