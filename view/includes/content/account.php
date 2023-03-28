<?php
if (!isset($_SESSION['id_user'])) {
    header('Location: /?page=login');
    exit();
} else {
    ?>
    <div class="main">
        <?php
        if (isset($formError))
            echo '<h1 class="notification error">' . $formError . '</h1>';
        if (isset($formSuccess))
            echo '<h1 class="notification success">' . $formSuccess . '</h1>';
        ?>
        <!-- Changer de pseudo  -->
        <div class="panel">
            <h1>Changer de nom d'utilisateur</h1>
            <div class="panel-content">
                <form method="POST" action="">
                    <label for="fUsername">Nom d'utilisateur actuel:</label>
                    <input type="text" autocomplete="off" name="fUserName" placeholder="Nom d'utilisateur" required />
                    <label for="fNewUserName">Nouveau nom d'utilisateur:</label>
                    <input type="text" autocomplete="off" name="fNewUserName" placeholder="Nouveau nom d'utilisateur"
                        required />
                    <label for="fNewUserName">Confirmation du nouveau nom d'utilisateur:</label>
                    <input type="text" autocomplete="off" name="fNewUserNameConfirm"
                        placeholder="Confirmation de nom d'utilisateur" required />
                    <input class="button" type="submit" value="Valider" name="fChangeUserName" />
                </form>
            </div>
        </div>
        <!-- Changer de mot de passe -->
        <div class="panel">
            <h1>Changer de mot de passe</h1>
            <div class="panel-content">
                <form method="POST" action="">
                    <label for="fPass">Mot de passe actuel:</label>
                    <input type="password" autocomplete="off" name="fPass" placeholder="Mot de passe" required />
                    <label for="fNewPass">Nouveau mot de passe:</label>
                    <input type="password" autocomplete="off" name="fNewPass" placeholder="Nouveau mot de passe" required />
                    <label for="fNewPassConfirm">Confirmation du de passe actuel:</label>
                    <input type="password" autocomplete="off" name="fNewPassConfirm"
                        placeholder="Confirmation de nouveau mot de passe" required />
                    <input class="button" type="submit" value="Valider" name="fChangePassword" />
                </form>
            </div>
        </div>
        <!-- Ajouter un post -->
        <div class="panel">
            <h1>Ajouter un post</h1>
            <div class="panel-content">
                <p>
                    <b>Attention</b>: <i>Les post ne sont pas modifiables une fois envoyés ! </i>
                </p>
                <form method="POST" action="" enctype="multipart/form-data">
                    <label for="fPostContent">Contenu du post:</label>
                    <textarea autocomplete="off" name="fPostContent" placeholder="Contenu textuel de votre post"
                        required></textarea>
                    <label for="fPostMedia">Photo/vidéo:</label>
                    <input type="file" name="fPostMedia" />
                    <input class="button" type="submit" value="Valider" name="fPost" />
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>