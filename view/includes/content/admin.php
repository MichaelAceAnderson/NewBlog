<div class="main">
    <?php
    if (isset($formError)) {
        // Si le contrôleur a stocké une erreur, l'afficher
        echo '<h1 class="notification error">' . $formError . '</h1>';
    }
    if (isset($formSuccess)) {
        // Si le contrôleur a stocké un succès, l'afficher
        echo '<h1 class="notification success">' . $formSuccess . '</h1>';
    }
    ?>
    <!-- Définir le nom du blog -->
    <div class="panel">
        <h1>Définir le nom du blog</h1>
        <div class="panel-content">
            <form method="POST" action="">
                <input type="text" autocomplete="off" name="fBlogName" placeholder="Nouveau nom du blog" required />
                <input type="submit" value="Valider" name="fChangeBlogName" />
            </form>
        </div>
    </div>
    <!-- Définir la description du blog -->
    <div class="panel">
        <h1>Changer la description du blog</h1>
        <div class="panel-content">
            <form method="POST" action="">
                <textarea style="width: 50%;" name="fBlogDesc" placeholder="Nouvelle description du blog"
                    required></textarea>
                <input type="submit" value="Valider" name="fChangeBlogDesc" />
            </form>
        </div>
    </div>
    <!-- Définir l'image de fond du blog -->
    <div class="panel">
        <h1>Changer l'image de fond du blog</h1>
        <div class="panel-content">
            <p><b>Note: </b>Laissez vide pour remettre l'image de fond par défaut</p>
            <form method="POST" action="">
                <input type="text" name="fBgURL" placeholder="/common/img/background.jpg" />
                <input type="submit" value="Valider" name="fChangeBgURL" />
            </form>
        </div>
    </div>
    <!-- Maintenance -->
    <div class="panel">
        <h1>Maintenance</h1>
        <div class="panel-content">
            <form method="POST" action="">
                <input type="submit" value="❌ Supprimer tous les posts" name="fClearPosts" />
            </form>
            <p><b>⚠️ Note: </b> Vous perdrez votre compte, vos posts et tout votre contenu !</p>
            <a href="/?page=install"><button>⚠️️ Accéder à la page de réinstallation</button></a>
            <a href="/?page=tests"><button>🚧 Accéder à la page de tests</button></a>
            </form>
        </div>
    </div>
</div>