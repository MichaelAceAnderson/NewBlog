<?php
//To-do:
// Gestion des erreurs par blocs try/catch

class Post
{
    /* MÉTHODES */
    // Création d'un post en BDD
    public static function addPost(int $authorId, string $content, ?string $mediaUrl): int|Exception
    {
        // Résultat initial = échec
        $result = false;
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "INSERT INTO newblog.nb_post (content, id_user_author) VALUES (:content, :id_user_author);"
                    )
                );
                Model::getStmt()->bindParam('content', $content, PDO::PARAM_STR);
                Model::getStmt()->bindParam('id_user_author', $authorId, PDO::PARAM_INT);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    throw new Exception("Une erreur est survenue");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si insertion effectuée, renvoyer l'id du post
                        $result = Model::getPdo()->lastInsertId();
                    } else {
                        // Si insertion pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
    // Récupérer le tableau des posts
    public static function selectPosts(): array|Exception
    {
        // Résultat initial = tableau vide
        $result = [];
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "SELECT * FROM newblog.nb_post ORDER BY time_stamp DESC;"
                    )
                );
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Récupérer la ligne d'un seul post
    public static function selectPost(int $postId): array|Exception
    {
        // Résultat initial = tableau vide
        $result = [];
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "SELECT newblog.nb_post.id_user_author, newblog.nb_user.nickname, newblog.nb_post.content, newblog.nb_post.time_stamp
                    FROM newblog.nb_post JOIN newblog.nb_user
                    ON newblog.nb_post.id_user_author=nb_user.id_user
                    WHERE newblog.nb_post.id_post=:id_post"
                    )
                );
                Model::getStmt()->bindParam('id_post', $postId, PDO::PARAM_INT);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si la requête a réussi, récupérer les résultats
                        $result = Model::getStmt()->fetchAll();
                    } else {
                        // Si la requête a réussi mais qu'il n'y a pas de résultat
                        throw new Exception("Le post spécifié n'existe pas !");
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Récupérer l'id du prochain post à créer
    public static function selectNextPostId(): int|Exception
    {
        // Résultat initial = échec
        $result = -1;
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "SELECT pg_sequence_last_value('newblog.post_seq');"
                    )
                );
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetch()->pg_sequence_last_value + 1;
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Création d'un post en BDD
    public static function clearPosts(): int|Exception
    {
        // Résultat initial = échec
        $result = -1;

        // Supprimer de façon récursive le contenu des fichiers liés aux posts
        Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . '/blog_data/posts/video/');
        Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . '/blog_data/posts/img/');

        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "DELETE FROM newblog.nb_post"
                    )
                );
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    throw new Exception("Une erreur est survenue");
                } else {
                    // Si suppression effectuée, renvoyer le nombre d'éléments supprimés
                    $result = Model::getStmt()->rowCount();
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }

    // Supprimer un post
    public static function deletePost(int $id): bool|Exception
    {
        // Résultat initial = échec
        $result = false;

        // On vérifie si le post existe
        $post = self::selectPost($id);
        if ($post instanceof Exception) {
            $result = new Exception("Une erreur est survenue lors de la suppression du post !");
        } else {
            if ($post) {

                // Supprimer toutes les vidéos de ce post
                foreach (glob($_SERVER['DOCUMENT_ROOT'] . '/blog_data/posts/video/' . $id . '/*') as $videoFile) {
                    // Si c'est un fichier et pas un sous-dossier
                    if (is_file($videoFile)) {
                        // Supprimer le fichier
                        unlink($videoFile);
                    }
                }
                // Supprimer le dossier parent
                rmdir($_SERVER['DOCUMENT_ROOT'] . '/blog_data/posts/video/' . $id);
                // Supprimer toutes les images de ce post
                foreach (glob($_SERVER['DOCUMENT_ROOT'] . 'blog_data/posts/image/' . $id . '/*') as $imageFile) {
                    // Si c'est un fichier et pas un sous-dossier
                    if (is_file($imageFile)) {
                        // Supprimer le fichier
                        unlink($imageFile);
                    }
                }
                // Supprimer le dossier parent
                rmdir('blog_data/posts/image/' . $id);

            } else {
                return new Exception("Le post que vous souhaitez supprimer n'existe pas !");
            }

            try {
                if (is_null(Model::getPdo())) {
                    // Si la connexion n'a pas pu être créée
                    throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
                } else {
                    // Si la connexion à réussi
                    // Préparer la requête
                    Model::setStmt(
                        Model::getPdo()->prepare(
                            "DELETE FROM newblog.nb_post WHERE newblog.nb_post.id_post = :id;"
                        )
                    );
                    Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT);
                    // Exécuter la requête
                    if (!Model::getStmt()->execute()) {
                        throw new Exception("Une erreur est survenue");
                    } else {
                        if (Model::getStmt()->rowCount() > 0) {
                            // Si suppression effectuée
                            $result = true;
                        } else {
                            // Si suppression pas effectuée
                            $result = false;
                        }
                    }
                }
            } catch (Exception $e) {
                $result = $e;
            }
        }
        return $result;
    }
}