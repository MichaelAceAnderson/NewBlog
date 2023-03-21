<div class="main">
    <!-- Définir les identifiants -->
    <div class="panel">
        <h1>Configurer les identifiants</h1>
        <div class="panel-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" autocomplete="off" name="fUsername" />
                <input type="password" autocomplete="off" name="fPass" />
                <input class="button" type="submit" value="Valider" name="vChangeLogin" />
            </form>
        </div>
    </div>
    <!-- Ajouter un post écrit -->
    <div class="panel">
        <h1>Ajouter un premier post écrit</h1>
        <div class="panel-content">
            <p>
                <b>Attention</b>: <i>Les post ne sont pas modifiables une fois envoyés ! </i>
            </p>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" autocomplete="off" name="fPostContent" />
                <input class="button" type="submit" value="Valider" name="vPost" />
            </form>
        </div>
    </div>
    <!-- Définir le nom du blog -->
    <div class="panel">
        <h1>Définir le nom du blog</h1>
        <div class="panel-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" autocomplete="off" name="fBlogName" />
                <input class="button" type="submit" value="Valider" name="vBlogName" />
            </form>
        </div>
    </div>
    <!-- Définir la description du blog -->
    <div class="panel">
        <h1>Changer la description du blog</h1>
        <div class="panel-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <textarea style="width: 50%;" name="fBlogDesc"></textarea>
                <input class="button" type="submit" value="Valider" name="vBlogDesc" />
            </form>
        </div>
    </div>
</div>
<!-- // $version = file_get_contents($server . '/model/data/settings/version.txt');
// $URL = "http://" . $_SERVER['HTTP_HOST'];
// $SendInstall =
file_get_contents("http://xdev.livehost.fr/creations/web/newblog/bloginstalled.php?url=$URL&version=$version"); -->

<!-- <iframe width="50%" height="350px" src="" frameborder="0" allowFullScreen="">
    Bientôt » Vidéo d'installation du CMS !
</iframe> -->