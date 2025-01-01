<main>
    <h1>
        Profil utilisateur
    </h1>

    <div class="profile-overview wrap-row very-rounded">
        <img src="assets\img\placeholders\user-placeholder.png" 
                alt="Image de couverture"
                class="profile-picture"
                onclick="window.location.href='<?=$book['cover_image'];?>'">

        <div class="wrap">
            <h2>
                <?=$user['username'];?>
            </h2>
        
            <p>
                <?=$signupDateLabel;?>
            </p>
            
            <p>
                <?=$labelAdditions;?>
            </p>
            
            <p>
                <?=$labelReviews;?>
            </p>

        </div>
    </div>

    <?php
        if (count($books) == 0){
            echo "<p>Aucun livre noté</p>";
        }

        HtmlWriter::writeCompactBooksPreviewGrade($books);

        if (count($books) == 0){
            echo "<p>Aucun livre noté</p>";
        }

        HtmlWriter::writeCompactBooksPreview($publishedBooks);
    ?>
</main>
