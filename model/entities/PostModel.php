<?php

class Post
{
    /* MÉTHODES */
    // Création d'un post en BDD
    public static function addPost(int $authorId, string $content): int|Exception
    {
        // Tenter d'ajouter le post en BDD
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "INSERT INTO newblog.nb_post (content, id_user_author) VALUES (:content, :id_user_author);"
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La requête d'insertion du post n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher le contenu du post en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('content', $content, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Impossible d'attacher le contenu du post \"$content\" en paramètre à la requête d'insertion du post !");
                }
                // Attacher l'id de l'auteur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('id_user_author', $authorId, PDO::PARAM_INT)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Impossible d'attacher l'id de l'auteur en paramètre à la requête d'insertion du post !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Une erreur est survenue lors de l'exécution de la requête d'insertion du post !");
                } else {
                    // Si insertion effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // Tenter de récupérer l'id du post
                        $result = self::selectLastPostId();
                        // Si l'id du post n'a pas pu être récupéré
                        if ($result instanceof Exception) {
                            // On lance une erreur qui sera attrapée plus bas
                            throw new Exception("L'id du post inséré n'a pas pu être récupéré !");
                        } else {
                            // Si l'id du post a pu être récupéré
                            // On renvoie l'id du post
                            return $result;
                        }
                    } else {
                        // Si insertion pas effectuée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("L'insertion du post en base de données a échoué !");
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Récupérer le tableau des posts
    public static function selectPosts(): array|Exception
    {
        // Tenter de récupérer les posts
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "SELECT * FROM newblog.nb_post ORDER BY time_stamp DESC;"
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La requête de récupération des posts n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des posts a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                    // Si les résultats n'ont pas pu être récupérés
                    if ($result === false) {
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("La liste des posts n'a pas pu être récupérée !");
                    } else {
                        // Si les résultats ont pu être récupérés
                        // On renvoie les résultats
                        return $result;
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Récupérer la ligne d'un seul post
    public static function selectPost(int $postId): array|Exception
    {
        // Tenter de récupérer le post spécifié
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "SELECT newblog.nb_post.id_user_author, newblog.nb_user.nickname, newblog.nb_post.content, newblog.nb_post.time_stamp
                    FROM newblog.nb_post JOIN newblog.nb_user
                    ON newblog.nb_post.id_user_author=nb_user.id_user
                    WHERE newblog.nb_post.id_post=:id_post"
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La requête de récupération du post n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher l'id du post à la requête de récupération
                if (!Model::getStmt()->bindParam('id_post', $postId, PDO::PARAM_INT)) {
                    // Si l'id du post n'a pas pu être attaché à la requête
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Impossible d'attacher l'id \"$postId\" du post en paramètre à la requête de récupération du post !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération du post a échoué !");
                } else {
                    // Si la requête a réussi
                    if (Model::getStmt()->rowCount() > 0) {
                        // Récupérer les résultats
                        $result = Model::getStmt()->fetchAll();
                        // Si les résultats n'ont pas pu être récupérés
                        if ($result === false) {
                            // On lance une erreur qui sera attrapée plus bas
                            throw new Exception("Le post spécifié n'a pas pu être récupéré !");
                        } else {
                            // Si les résultats ont pu être récupérés
                            // On renvoie les résultats
                            return $result;
                        }
                    } else {
                        // Si la requête a réussi mais qu'il n'y a pas de résultat
                        throw new Exception("Le post spécifié n'existe pas !");
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Récupérer l'id du dernier post créé
    public static function selectLastPostId(): int|Exception
    {
        // Tenter de récupérer l'id du dernier post
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "SELECT pg_sequence_last_value('newblog.post_seq');"
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La requête de récupération de l'id du dernier post inséré n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération de l'id du dernier post inséré a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetch()->pg_sequence_last_value;
                    // Si les résultats n'ont pas pu être récupérés
                    if (is_null($result)) {
                        // On ne sait pas si c'est une erreur ou si la séquence n'est pas initialisée
                        // On retourne 0
                        return 0;
                    } else {
                        // Si les résultats ont pu être récupérés
                        // On renvoie les résultats
                        return $result;
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Création d'un post en BDD
    public static function clearPosts(): int|Exception
    {
        // Tenter de supprimer les posts
        try {
            // Supprimer de façon récursive les vidéos liées aux posts si elles existent
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video/') && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
                if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video/')) {
                    // Si la suppression des fichiers a échoué
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La suppression des vidéos liées aux posts a échoué !");
                }
            }
            // Supprimer de façon récursive les images liées aux posts si elles existent
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img/') && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
                if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
                    // Si la suppression des fichiers a échoué
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La suppression des images liées aux posts a échoué !");
                }
            }

            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "DELETE FROM newblog.nb_post"
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La requête de suppression des posts n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    throw new Exception("Une erreur est survenue lors de l'exécution de la requête de suppression des posts !");
                } else {
                    // Si suppression effectuée, renvoyer le nombre d'éléments supprimés
                    return Model::getStmt()->rowCount();
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }

    // Supprimer un post
    public static function deletePost(int $id): bool|Exception
    {
        // On vérifie si le post existe
        $post = self::selectPost($id);
        // Si une erreur est survenue lors de la récupération du post
        if ($post instanceof Exception) {
            // On renvoie l'erreur
            return new Exception("Une erreur est survenue lors de la récupération du post à supprimer !");
        } else {
            // Si le post existe
            if ($post) {

                // Supprimer de façon récursive les fichiers liés aux posts s'ils existent et sont des dossiers
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $id) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $id)) {
                    Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $id);
                }
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $id) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $id)) {
                    Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'image' . $id);
                }

            } else {
                // Si le post n'existe pas
                // On logge l'erreur
                Model::printLog("Le post que vous souhaitez supprimer n'existe pas !");
                // On renvoie une erreur
                return new Exception("Le post que vous souhaitez supprimer n'existe pas !");
            }

            // Tenter de supprimer le post spécifié
            try {
                // Si la connexion n'a pas pu être créée
                if (is_null(Model::getPdo())) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
                } else {
                    // Si la connexion à réussi
                    // Préparer la requête
                    $stmt = Model::getPdo()->prepare(
                        "DELETE FROM newblog.nb_post WHERE newblog.nb_post.id_post = :id;"
                    );
                    // Si la requête n'a pas pu être préparée
                    if (!$stmt) {
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception("La requête de suppression du post n'a pas pu être préparée !");
                    }
                    // Définir la requête à traiter
                    Model::setStmt($stmt);
                    // Attacher l'id du post à supprimer à la requête préparée
                    if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
                        // Si l'attache du paramètre a échoué
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception("Une erreur est survenue lors de l'attache du paramètre id à la requête de suppression du post !");
                    }

                    // Exécuter la requête
                    if (Model::getStmt()->execute() === false) {
                        // Si la requête n'a pas pu être exécutée
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception("Une erreur est survenue lors de l'exécution de la requête de suppression du post !");
                    } else {
                        // Si suppression effectuée
                        if (Model::getStmt()->rowCount() > 0) {
                            // On renvoie un succès
                            return true;
                        } else {
                            // Si suppression pas effectuée
                            // On lance une erreur qui sera rattrapée plus bas
                            throw new Exception("La suppression du post n'a pas pu être effectuée !");
                        }
                    }
                }
            } catch (Exception $e) {
                // Si une erreur est survenue
                // On logge l'erreur
                Model::printLog(Model::getError($e));
                // On renvoie l'erreur
                return $e;
            }
        }
    }
}