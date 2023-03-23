<div class="main">
    <div class="content">
        <div class="post">
            <h1>Connectez vous:</h1>
            <?php
            if (isset($formError)) {
                echo '<h1 class="notification error">' . $formError . '</h1>';
            }
            ?>
            <form method="post" action="" enctype="multipart/form-data">
                <label for="fUsername">Identifiant:</label>
                <input type="text" name="fUserName" required />
                <label for="fUsername">Mot de passe:</label>
                <input type="password" name="fPass" />
                <input type="submit" class="button" value="Connexion" name="fLogin" required />
            </form>
        </div>
    </div>
</div>