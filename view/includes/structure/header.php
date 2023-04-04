<?php
// Par défaut, on ne cherche pas le fond d'écran dans la base de données
$bgURL = false;
// Par défaut, la description du blog n'est pas définie
$blogDescription = false;
// Si la connexion à la base de données a pu être établie
if (Model::getPdo() != null) {
    // Si le blog est installé, on récupère l'URL de l'image de fond
    if ($blogInstalled) {
        $bgURL = BlogController::getBackgroundURL();
        // Récupérer la description du blog
        $blogDescription = BlogController::getBlogDescription();
    }
}
echo $bgURL ? '<body style="background: var(--themecolor) url(' . $bgURL . ') repeat fixed">' : '<body>';
?>
<!-- En-tête  -->
<header>
    <?php
    if ((isset($_GET['page']) && $_GET['page'] == 'home') || $_SERVER['REQUEST_URI'] == '/') {
        // Si l'utilisateur est sur la page d'accueil, pas de lien
        echo '<a href="#">';
    } else {
        // Sinon, lien vers la page d'accueil
        echo '<a href="/">';
    }
    // Si le nom du blog est défini (voir head.php), l'afficher, sinon afficher "NewBlog"
    echo $blogName ? '<h1>' . $blogName . '</h1>' : '<h1>NewBlog</h1>';
    // Si la description du blog est définie, l'afficher, sinon afficher "Votre nouveau blog !"
    echo $blogDescription ? '<h2>' . $blogDescription . '</h2>' : '<h2>Votre nouveau blog !</h2>';
    echo '</a>';
    // Si la connexion à la base de données a pu être établie et que le blog est installé
    if (Model::getPdo() != null && $blogInstalled) {
        echo '<span class="account">';
        if (isset($_SESSION['id_user'])) {
            echo '<a href="/?page=account"><h3>' . $_SESSION['nickname'] . '</h3></a>';
            if (isset($_SESSION['is_mod']) && $_SESSION['is_mod'] === true)
                echo '<a href="/?page=admin"><h4>Page admin</h4></a>';
            echo '<form action="" method="post"><a><input type="submit" name="fLogOut" title="Déconnexion" value="🚪 Déconnexion"></a></form>';
        } else {
            echo '<a href="/?page=login"><h4>Connexion</h4></a>';
        }
        echo '</span>';
    }
    ?>
</header>