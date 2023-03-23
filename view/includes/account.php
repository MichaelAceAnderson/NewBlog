<div class="main">
    <!-- Définir les identifiants -->
    <div class="panel">
        <h1>Configurer les identifiants</h1>
        <div class="panel-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" autocomplete="off" name="fUsername" />
                <input type="password" autocomplete="off" name="fPass" />
                <input class="button" type="submit" value="Valider" name="fChangeLogin" />
            </form>
        </div>
    </div>
    <!-- Ajouter un post écrit -->
    <div class="panel">
        <h1>Ajouter un post écrit</h1>
        <div class="panel-content">
            <p>
                <b>Attention</b>: <i>Les post ne sont pas modifiables une fois envoyés ! </i>
            </p>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" autocomplete="off" name="fPostContent" />
                <input class="button" type="submit" value="Valider" name="fPost" />
            </form>
        </div>
    </div>
</div>