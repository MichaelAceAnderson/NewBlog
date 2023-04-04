<div class="main">
    <!-- <iframe width="50%" height="350px" src="" frameborder="0" allowFullScreen="">
        Bientôt » Vidéo d'installation de la base de données !
    </iframe> -->
    <?php
    // Si la connexion à la BDD a pu être établie
    if (Model::getPdo() != null) {
        // Rediriger vers l'accueil
        header('Location: /');
    } else {
        echo '<h1 class="notification warning">Assurez-vous d\'avoir une base de données PostGreSQL nommée "newblog" en cours d\'exécution !</h1>';

        if (isset($formError)) {
            echo '<h1 class="notification error">' . $formError . '</h1>';
        }
    }
    ?>
</div>