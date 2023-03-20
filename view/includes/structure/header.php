<?php
$bgURL = BlogController::getBackgroundURL();
echo $bgURL ? '<body style="background: black url(' . $bgURL . ') repeat fixed">' : '<body>';
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
    // Récupérer la description du blog à partir du contrôleur
    $blogDescription = BlogController::getBlogDescription();
    // Si la description du blog est définie, l'afficher, sinon afficher "Votre nouveau blog !"
    echo $blogDescription ? '<h2>' . $blogDescription . '</h2>' : '<h2>Votre nouveau blog !</h2>';
    echo '</a>';
    ?>
    <span class="login">
        <?php
        if (isset($_SESSION['id_user'])) {
            $userName = UserController::getUserName($_SESSION['id_user']);
            echo $userName ? '<h3>' . $userName . '</h3>' : '<h3>Erreur !</h3>';
            if (isset($_SESSION['is_mod']) && $_SESSION['is_mod'] === true) echo '<a href="/?page=admin"><h4>Page admin</h4></a>';
            echo '<a href="/?page=login&logout"><h4>Déconnexion</h4></a>';
        } else {
            echo '<a href="/?page=login"><h4>Connexion</h4></a>';
        }
        ?>
    </span>
</header>