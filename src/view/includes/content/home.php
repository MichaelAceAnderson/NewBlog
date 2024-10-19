<!-- Contenu de la page -->
<section class="main" id="main">
    <?php
    // Code pour la future mise à jour dynamique de la page
    // if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'liveUpdate.js')) {
    //     if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'axios.js')) {
    //         echo '<script src="/controller/js/lib/axios.js"></script>';
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
        // Vérifier si une erreur a été stockée par le contrôleur
        if (Controller::getState()['state'] == STATE_ERROR) {
            // Si le contrôleur a stocké une erreur, l'afficher
            echo '<h1 class="notification error">' . Controller::getState()['message'] . '</h1>';
        }
        // Vérifier si un succès a été stocké par le contrôleur
        if (Controller::getState()['state'] == STATE_SUCCESS) {
            // Si le contrôleur a stocké un succès, l'afficher
            echo '<h1 class="notification success">' . Controller::getState()['message'] . '</h1>';
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
                echo '<div class="post-title">' . $post->title . '</div>';
                echo '<div class="post-tags">';
                foreach (explode(";", $post->tags) as $tag) {
                    echo '#' . $tag . ' ';
                }
                echo '</div>';
                echo '<div class="post-summary">' . $post->summary . '</div>';
                echo '<div class="post-content">';

                // S'il existe un dossier contenant les vidéos du post
                $videoPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $post->id_post;
                if (file_exists($videoPath) && is_dir($videoPath)) {
                    // Si le dossier existe
                    foreach (glob($videoPath . DIRECTORY_SEPARATOR . '*') as $videoFile) {
                        // Pour tous les fichiers dans le dossier
                        // Afficher l'image avec un chemin source reformaté pour être compatible avec le navigateur
                        echo '<video class="post-media" alt="Vidéo du post" preload ="auto" controls autoplay muted loop playsinline>
                                <source src="' . str_replace(array($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), array('', '/'), $videoFile) . '">
                            </video>';
                    }
                }
                // S'il existe un dossier contenant les images du post
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $post->id_post;
                if (file_exists($imagePath) && is_dir($imagePath)) {
                    // Si le dossier existe
                    foreach (glob($imagePath . DIRECTORY_SEPARATOR . '*') as $imageFile) {
                        // Pour tous les fichiers dans le dossier
                        // Afficher l'image avec un chemin source reformaté pour être compatible avec le navigateur
                        echo '<img class="post-media" src="' . str_replace(array($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), array('', '/'), $imageFile) . '" alt="Image du post" decoding="async"/>';
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
<!-- Script permettant d'afficher/cacher le contenu des posts avec un bouton -->
<script src="/view/js/postDetails.js"></script>