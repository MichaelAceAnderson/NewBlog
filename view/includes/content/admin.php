<div class="main">
    <?php
    if (isset($formError)) {
        echo '<h1 class="notification error">' . $formError . '</h1>';
    }
    if (isset($formSuccess)) {
        echo '<h1 class="notification success">' . $formSuccess . '</h1>';
    }
    ?>
    <!-- Définir le nom du blog -->
    <div class="panel">
        <h1>Définir le nom du blog</h1>
        <div class="panel-content">
            <form method="POST" action="">
                <input type="text" autocomplete="off" name="fBlogName" placeholder="Nouveau nom du blog" required />
                <input class="button" type="submit" value="Valider" name="fChangeBlogName" />
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
                <input class="button" type="submit" value="Valider" name="fChangeBlogDesc" />
            </form>
        </div>
    </div>
    <!-- Définir l'image de fond du blog -->
    <div class="panel">
        <h1>Changer l'image de fond du blog</h1>
        <div class="panel-content">
            <p><b>Note: </b>Laissez vide pour remettre l'image de fond par défaut</p>
            <form method="POST" action="">
                <input type="text" name="fBgURL" placeholder="/common/img/background.jpg" required />
                <input class="button" type="submit" value="Valider" name="fChangeBgURL" />
            </form>
        </div>
    </div>