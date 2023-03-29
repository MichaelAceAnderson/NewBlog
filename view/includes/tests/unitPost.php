<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
    // Si l'utilisateur n'est pas admin, on le redirige vers la page d'accueil
    header('Location: /');
}
?>
<!-- Contenu de la page -->
<section class="main" id="main">
    <div class="title outlined">
        <h1>Tests unitaires relatifs aux posts</h1>
        <hr>
    </div>
    <div class="content">
        <?php
        // Créer un post
        // Récupérer un post
        // Récupérer tous les posts
        // Supprimer un post
        ?>
    </div>
</section>