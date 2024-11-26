<pre>
<?php var_dump($valUser); ?>
</pre>
Détail user
<p>
    Profil de <?= $valUser["username"] ?>
</p>
<p>
    Membre depuis le <?= $valUser["sign_up_date"] ?>
</p>
<p>
    (nbLivres) ajoutés à la base de données
</p>
<p>
    A donné son avis sur (nbFeedback) livres
</p>

