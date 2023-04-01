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

                // S'il existe un dossier contenant les vidéos du post
                $videoPath = $_SERVER['DOCUMENT_ROOT'] . '/common/files/video/' . $post->id_post;
                if (file_exists($videoPath) && is_dir($videoPath)) {
                    // Si le dossier existe
                    foreach (glob($videoPath . '/*') as $videoFile) {
                        // Pour tous les fichiers dans le dossier
                        echo '<video class="post-media" alt="Vidéo du post" preload ="auto" controls autoplay loop>
                                <source src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $videoFile) . '">
                            </video>';
                    }
                }
                // S'il existe un dossier contenant les images du post
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/common/files/img/' . $post->id_post;
                if (file_exists($imagePath) && is_dir($imagePath)) {
                    // Si le dossier existe
                    foreach (glob($imagePath . '/*') as $imageFile) {
                        // Pour tous les fichiers dans le dossier
                        echo '<img class="post-media" src="' . str_replace($_SERVER['DOCUMENT_ROOT'], '', $imageFile) . '" alt="Image du post"/>';
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