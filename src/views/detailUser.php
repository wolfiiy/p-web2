<main>
<img src="assets\img\placeholders\user-placeholder.png" width="70" height="70" alt="Phto profil user">
<p>
    Profil de <?= $_SESSION['username'] ?>
</p>
<p>
    Membre depuis le <?= $_SESSION["sign_up_date"] ?>
</p>
<p>
    <?= $_SESSION["book_having"] ?> ajoutés à la base de données
</p>
<p>
    A donné son avis sur <?= $_SESSION["book_review"]; ?> livres
</p>
</main>

