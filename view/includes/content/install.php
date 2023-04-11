<div class="main">
    <!-- <iframe width="50%" height="350px" src="" frameborder="0" allowFullScreen="">
        Bientôt » Vidéo d'installation du CMS !
    </iframe> -->
    <?php
    if ($blogInstalled) {
        echo '<h1 class="notification warning">⚠️ Attention ! Si votre blog est déjà installé, cette nouvelle installation supprimera tous vos posts, vos utilisateurs et votre configuration !</h1>';
    }
    if (isset($formError)) {
        echo '<h1 class="notification error">' . $formError . '</h1>';
    }
    ?>
    <form method="post" action="" autocomplete="off">
        <!-- Définir les identifiants -->
        <div class="panel">
            <h1>Configurer les identifiants</h1>
            <div class="panel-content">
                <label for="fUsername">Identifiant:</label>
                <input type="text" name="fUserName" placeholder="MonNomCool123" autocomplete="new-password"
                    aria-autocomplete="none" required />
                <label for="fPass">Mot de passe:</label>
                <input type="password" name="fPass" placeholder="Mot de passe" autocomplete="new-password"
                    aria-autocomplete="none" required />
            </div>
        </div>
        <!-- Définir le nom du blog -->
        <div class="panel">
            <h1>Définir le nom du blog</h1>
            <div class="panel-content">
                <input type="text" autocomplete="off" name="fBlogName" placeholder="Nom du blog" required />
            </div>
        </div>
        <!-- Définir la description du blog -->
        <div class="panel">
            <h1>Saisissez la description du blog</h1>
            <div class="panel-content">
                <textarea name="fBlogDesc" placeholder="Description" required></textarea>
            </div>
        </div>
        <!-- Définir l'URL de l'image de fond du blog -->
        <div class="panel">
            <h1>Définir l'URL de l'image de fond du blog</h1>
            <div class="panel-content">
                <p>Saisissez l'url de l'image de fond du blog (laissez vide pour laisser celle par défaut)</p>
                <input type="text" name="fBgURL" placeholder="/view/img/circuits.jpg" />
            </div>
        </div>
        <!-- Terminer l'installation -->
        <div class="panel">
            <h1>Terminer l'installation</h1>
            <div class="panel-content">
                <input type="submit" value="✔️ Valider" name="fInstall" />
            </div>
        </div>
    </form>
</div>