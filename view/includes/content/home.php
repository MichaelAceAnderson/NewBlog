<!-- Contenu de la page -->
<section class="main" id="main">
    <?php
    // if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/controller/liveUpdate.js")) {
    //     if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/common/js/lib/axios.js")) {
    //         echo '<script src="/common/js/lib/axios.js"></script>';
    //     } else {
    //         echo '<script src="https://unpkg.com/axios/dist/axios.min.js"></script>';
    //     }
    //     echo '<script src="/controller/liveUpdate.js"></script>';
    // } else {
    //     echo '<script>console.error("Script de mise à jour dynamique de la page manquant !");</script>';
    // }
    ?>
    <div class="title outlined">
        <h1>Fil d'actualité</h1>
        <hr>
    </div>
    <div class="content">
        <!-- Afficher un panel pour chaque post  -->
        <?php
        $posts = PostController::getAllPosts();
        if ($posts === []) {
            echo "Il n'y a aucun post à afficher pour le moment !";
        } elseif (!$posts) {
            echo "Une erreur est survenue lors de la récupération des posts !";
        } else {
            foreach ($posts as $post) {
                echo '<div class="post">';
                echo '<h1 class="post author">' . UserController::getUserNameById($post->id_user_author) . '</h1>';
                echo '<div class="post container">';
                echo '<p class="post content">' . $post->content . '</p>';
                echo '<p class="post timestamp">' . $post->time_stamp . '</p>';
                echo '<p class="post id">' . $post->id_post . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</section>