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
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" autocomplete="off" name="fBlogName" />
                <input class="button" type="submit" value="Valider" name="fChangeBlogName" />
            </form>
        </div>
    </div>
    <!-- Définir la description du blog -->
    <div class="panel">
        <h1>Changer la description du blog</h1>
        <div class="panel-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <textarea style="width: 50%;" name="fBlogDesc"></textarea>
                <input class="button" type="submit" value="Valider" name="fChangeBlogDesc" />
            </form>
        </div>
    </div>
    <!-- Définir l'image de fond du blog -->
    <div class="panel">
        <h1>Changer l'image de fond du blog</h1>
        <div class="panel-content">
            <p><b>Note: </b>Laissez vide pour remettre l'image de fond par défaut</p>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" name="fBgURL" />
                <input class="button" type="submit" value="Valider" name="fChangeBgURL" />
            </form>
        </div>
    </div>