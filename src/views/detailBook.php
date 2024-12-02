<main>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <h1>Détails de l'oeuvre</h1>
    <div id="details-content-wrap" class="content-wrap">
        <div class="cover wrap">
            <img src="assets/img/placeholders/cover-placeholder.png" 
                 alt="Image de couverture">
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

            <p>
                Votre note:
                TODO
                <button type="button">Valider</button>
            </p>
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
                
                <p class="value">
                    <a href="" class="button red">Supprimer</a>
                </p>
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