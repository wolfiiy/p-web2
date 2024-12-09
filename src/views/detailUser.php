<main>
<img src="assets\img\placeholders\user-placeholder.png" width="70" height="70" alt="Phto profil user">
<p>
    Profil de <?= $user['username'] ?>
</p>
<p>
    Membre depuis le <?= $user["sign_up_date"] ?>
</p>
<p>
    <?= $userPublishedBook ?> ajoutés à la base de données
</p>
<p>
    A donné son avis sur <?= $userReviewedBook ?> livres
</p>
</main>

