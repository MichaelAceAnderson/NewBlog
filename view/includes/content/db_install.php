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
        ?>
    <!-- Définir les identifiants de la BDD -->
    <!-- <form method="post" action="" autocomplete="off">
            <div class="panel">
                <h1>Configurer l'accès à la base de données</h1>
                <div class="panel-content">
                    <label for="fUsername">Identifiant:</label>
                    <input type="text" name="fUserName" placeholder="MonNomCool123" autocomplete="new-password"
                        aria-autocomplete="none" required />
                    <label for="fPass">Mot de passe:</label>
                    <input type="password" name="fPass" placeholder="Mot de passe" autocomplete="new-password"
                        aria-autocomplete="none" required />
                    <input type="submit" value="✔️ Valider" name="fInstall" />
                </div>
            </div>
        </form> -->
    <?php
    }
    ?>
</div>