<div class="main">
    <div class="content">
        <div class="post">
            <h1>Connectez vous:</h1>
            <?php if (isset($_GET['fail'])) {
                echo '<p class="error">Identifiant ou mot de passe incorrect</p>';
            }
            ?>
            <form method="post" action="" enctype="multipart/form-data">
                <label for="fUsername">Identifiant:</label>
                <input type=" text" name="fUserName" />
                <label for="fUsername">Mot de passe:</label>
                <input type="password" name="fPass" />
                <input type="submit" class="button" value="Connexion" name="vLogin" />
            </form>
        </div>
    </div>
</div>