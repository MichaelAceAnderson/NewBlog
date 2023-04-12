<div class="main">
    <div class="content">
        <div class="panel">
            <h1>Connectez vous:</h1>
            <div class="panel-content">
                <?php
                // Vérifier si une erreur a été stockée par le contrôleur
                if (Controller::getState()['state'] == STATE_ERROR) {
                    // Si le contrôleur a stocké une erreur, l'afficher
                    echo '<h1 class="notification error">' . Controller::getState()['message'] . '</h1>';
                }
                // Vérifier si un succès a été stocké par le contrôleur
                elseif (Controller::getState()['state'] == STATE_SUCCESS) {
                    // Si le contrôleur a stocké un succès, l'afficher
                    echo '<h1 class="notification success">' . Controller::getState()['message'] . '</h1>';
                }
                ?>
                <form method="post" action="">
                    <label for="fUsername">Identifiant:</label>
                    <input type="text" name="fUserName" placeholder="Nom d'utilisateur" required />
                    <label for="fUsername">Mot de passe:</label>
                    <input type="password" name="fPass" placeholder="Mot de passe" required />
                    <input type="submit" value="Connexion" name="fLogin" />
                </form>
            </div>
        </div>
    </div>
</div>