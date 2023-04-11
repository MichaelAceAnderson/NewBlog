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
                <label for="fBlogName">Nom du blog:</label>
                <input type="text" autocomplete="off" name="fBlogName" placeholder="Nouveau nom du blog" required />
                <input type="submit" value="✔️ Valider" name="fChangeBlogName" />
            </form>
        </div>
    </div>
    <!-- Définir la description du blog -->
    <div class="panel">
        <h1>Changer la description du blog</h1>
        <div class="panel-content">
            <form method="POST" action="">
                <label for="fBlogDesc">Description du blog:</label>
                <textarea style="width: 50%;" name="fBlogDesc" placeholder="Nouvelle description du blog"
                    required></textarea>
                <input type="submit" value="✔️ Valider" name="fChangeBlogDesc" />
            </form>
        </div>
    </div>
    <!-- Définir le logo du blog -->
    <div class="panel">
        <h1>Changer le logo du blog</h1>
        <div class="panel-content">
            <p><b>Note: </b>Laissez tout les champs vides remettra le logo par défaut. Si un fichier est
                uploadé, l'URL sera ignorée.
            </p>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="fLogoURL">URL du logo:</label>
                <input type="text" name="fLogoURL" placeholder="/view/img/logo.jpg" />
                <label for="fLogoFile">Fichier du logo:</label>
                <input type="file" name="fLogoFile" />
                <input type="submit" value="✔️ Valider" name="fChangeLogo" />
            </form>
        </div>
    </div>
    <!-- Définir l'image de fond du blog -->
    <div class="panel">
        <h1>Changer l'image de fond du blog</h1>
        <div class="panel-content">
            <p><b>Note: </b>Laissez tout les champs vides remettra l'image de fond par défaut. Si un fichier est
                uploadé, l'URL sera ignorée.
            </p>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="fBgURL">URL de l'image de fond:</label>
                <input type="text" name="fBgURL" placeholder="/view/img/circuits.jpg" />
                <label for="fBgFile">Fichier de l'image de fond:</label>
                <input type="file" name="fBgFile" />
                <input type="submit" value="✔️ Valider" name="fChangeBgURL" />
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