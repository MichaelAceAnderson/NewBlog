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
        if (isset($formError)) {
            // Si le contrôleur a stocké une erreur, l'afficher
            echo '<h1 class="notification error">' . $formError . '</h1>';
        }
        if (isset($formSuccess)) {
            // Si le contrôleur a stocké un succès, l'afficher
            echo '<h1 class="notification success">' . $formSuccess . '</h1>';
        }

        $posts = PostController::getAllPosts();
        if ($posts === []) {
            echo "Il n'y a aucun post à afficher pour le moment !";
        } elseif (!$posts) {
            echo "Une erreur est survenue lors de la récupération des posts !";
        } else {
            foreach ($posts as $post) {
                echo '<div class="post">';
                echo '<h1 class="post-author">' . UserController::getUserNameById($post->id_user_author);
                if (isset($_SESSION['is_mod']) && $_SESSION['is_mod'] === true) {
                    echo '<form class="post-delete" action="" method="POST">
                    <input type="hidden" value="' . $post->id_post . '" name="fDeletePostId"/>
                    <input type="submit" title="Supprimer" value="🗑️"/>
                    </form>';
                }
                echo '</h1>';
                echo '<div class="post-container">';
                echo '<div class="post-content">';
                if ($post->media_url) {
                    if (preg_match('/\/video\//', $post->media_url)) {
                        echo '<video class="post-media" alt="Vidéo du post" preload ="auto" controls autoplay loop>
                            <source src="' . $post->media_url . '">
                        </video>';
                    } else {
                        echo '<img class="post-media" src="' . $post->media_url . '" alt="Image du post">';
                    }
                }
                echo '<p>' . $post->content . '</p>';
                echo '</div>';
                echo '<p class="post-timestamp">' . $post->time_stamp . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</section>