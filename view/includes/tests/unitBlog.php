<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
    // Si l'utilisateur n'est pas admin, on le redirige vers la page d'accueil
    header('Location: /');
}
?>
<!-- Contenu de la page -->
<section class="main" id="main">
    <div class="title outlined">
        <h1>Tests unitaires relatifs au blog</h1>
        <hr>
    </div>
    <div class="content">
        <?php
        // Installation du blog
        // Modificiation du nom du blog
        // Modification de la description du blog
        // Récupération de la date de création du blog
        // Modification de l'URL de l'image de fond du blog
        ?>
    </div>
</section>