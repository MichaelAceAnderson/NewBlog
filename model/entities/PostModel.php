<?php
//To-do:
// Gestion des erreurs par blocs try/catch

class Post
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    private static Model|null $model;

    /* MÉTHODES */
    // Création d'un post en BDD
    public static function addPost(int $authorId, string $content, ?string $mediaUrl): bool|PDOException
    {
        // Résultat initial = échec
        $result = false;
        try {
            // Initialiser la connexion
            self::$model = new Model("postgres");
            if (is_null(self::$model->getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new PDOException("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                if (!empty($mediaUrl)) {
                    // S'il y a un média à ajouter au post
                    self::$model->setStmt(
                        self::$model->getPdo()->prepare(
                            "INSERT INTO newblog.nb_post (content, media_url, id_user_author) VALUES (:content, :media_url, :id_user_author);"
                        )
                    );
                    self::$model->getStmt()->bindParam('media_url', $mediaUrl, PDO::PARAM_STR);
                } else {
                    // S'il n y a pas de média à ajouter au post, on ne l'ajoute pas
                    self::$model->setStmt(
                        self::$model->getPdo()->prepare(
                            "INSERT INTO newblog.nb_post (content, id_user_author) VALUES (:content, :id_user_author);"
                        )
                    );
                }
                self::$model->getStmt()->bindParam('content', $content, PDO::PARAM_STR);
                self::$model->getStmt()->bindParam('id_user_author', $authorId, PDO::PARAM_INT);
                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    throw new PDOException("Une erreur est survenue");
                } else {
                    if (self::$model->getStmt()->rowCount() > 0) {
                        // Si insertion effectuée
                        $result = true;
                    } else {
                        // Si insertion pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (PDOException $e) {
            $result = $e;
        } finally {
            //Terminer la connexion
            self::$model = null;
        }
        return $result;
    }
    // Récupérer le tableau des posts
    public static function selectPosts(): array|PDOException
    {
        // Résultat initial = tableau vide
        $result = [];
        try {
            // Initialiser la connexion
            self::$model = new Model("postgres");
            if (is_null(self::$model->getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new PDOException("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                self::$model->setStmt(
                    self::$model->getPdo()->prepare(
                        "SELECT * FROM newblog.nb_post ORDER BY time_stamp DESC;"
                    )
                );
                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new PDOException("La requête de récupération des données du blog a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = self::$model->getStmt()->fetchAll();
                }
            }
        } catch (PDOException $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        } finally {
            //Terminer la connexion
            self::$model = null;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Récupérer la ligne d'un seul post
    public static function selectPost(int $postId): array|PDOException
    {
        // Résultat initial = tableau vide
        $result = [];
        try {
            // Initialiser la connexion
            self::$model = new Model("postgres");
            if (is_null(self::$model->getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new PDOException("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                self::$model->setStmt(
                    self::$model->getPdo()->prepare(
                        "SELECT newblog.nb_post.id_user_author, newblog.nb_user.nickname, newblog.nb_post.content, newblog.nb_post.media_url, newblog.nb_post.time_stamp
                    FROM newblog.nb_post JOIN newblog.nb_user
                    ON newblog.nb_post.id_user_author=nb_user.id_user
                    WHERE newblog.nb_post.id_post=:id_post"
                    )
                );
                self::$model->getStmt()->bindParam('id_post', $postId, PDO::PARAM_INT);
                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new PDOException("La requête de récupération des données du blog a échoué !");
                } else {
                    if (self::$model->getStmt()->rowCount() > 0) {
                        // Si la requête a réussi, récupérer les résultats
                        $result = self::$model->getStmt()->fetchAll();
                    } else {
                        // Si la requête a réussi mais qu'il n'y a pas de résultat
                        throw new PDOException("Le post spécifié n'existe pas !");
                    }
                }
            }
        } catch (PDOException $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        } finally {
            //Terminer la connexion
            self::$model = null;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Création d'un post en BDD
    public static function clearPosts(): int|PDOException
    {
        // Résultat initial = échec
        $result = -1;

        // Supprimer toutes les images de post
        foreach (glob('common/files/img/*') as $img) {
            // Si c'est un fichier et pas un sous-dossier
            if (is_file($img)) {
                // Supprimer le fichier
                unlink($img);
            }
        }
        // Supprimer toutes les vidéos de post
        foreach (glob('common/files/video/*') as $video) {
            // Si c'est un fichier et pas un sous-dossier
            if (is_file($video)) {
                // Supprimer le fichier
                unlink($video);
            }
        }

        try {
            // Initialiser la connexion
            self::$model = new Model("postgres");
            if (is_null(self::$model->getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new PDOException("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                self::$model->setStmt(
                    self::$model->getPdo()->prepare(
                        "DELETE FROM newblog.nb_post"
                    )
                );
                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    throw new PDOException("Une erreur est survenue");
                } else {
                    // Si suppression effectuée, renvoyer le nombre d'éléments supprimés
                    $result = self::$model->getStmt()->rowCount();
                }
            }
        } catch (PDOException $e) {
            $result = $e;
        } finally {
            //Terminer la connexion
            self::$model = null;
        }
        return $result;
    }

    // Supprimer un post
    public static function deletePost(int $id): bool|PDOException
    {
        // Résultat initial = échec
        $result = false;

        // On vérifie si le post existe
        $post = self::selectPost($id);
        if ($post instanceof PDOException) {
            $result = new PDOException("Une erreur est survenue lors de la suppression du post !");
        } else {
            if ($post) {
                // Si un média est attaché au post
                if (isset($post[0]->media_url) && !is_null($post[0]->media_url)) {
                    // S'il ne s'agit pas d'un fichier ou qu'il n'existe pas
                    if (!is_file($_SERVER['DOCUMENT_ROOT'] . $post[0]->media_url)) {
                        return new PDOException("Le fichier associé au post n'existe pas et ne peut pas être supprimé !");
                    } else {
                        // Si le fichier existe, on le supprime
                        if (!unlink($_SERVER['DOCUMENT_ROOT'] . $post[0]->media_url)) {
                            return new PDOException("Une erreur est survenue lors de la suppression du fichier associé au post !");
                        }
                    }
                }

            } else {
                return new PDOException("Le post que vous souhaitez supprimer n'existe pas !");
            }

            try {
                // Initialiser la connexion
                self::$model = new Model("postgres");
                if (is_null(self::$model->getPdo())) {
                    // Si la connexion n'a pas pu être créée
                    throw new PDOException("La connexion avec la base de données n'a pas pu être établie !");
                } else {
                    // Si la connexion à réussi
                    // Préparer la requête
                    self::$model->setStmt(
                        self::$model->getPdo()->prepare(
                            "DELETE FROM newblog.nb_post WHERE newblog.nb_post.id_post = :id;"
                        )
                    );
                    self::$model->getStmt()->bindParam('id', $id, PDO::PARAM_INT);
                    // Exécuter la requête
                    if (!self::$model->getStmt()->execute()) {
                        throw new PDOException("Une erreur est survenue");
                    } else {
                        if (self::$model->getStmt()->rowCount() > 0) {
                            // Si suppression effectuée
                            $result = true;
                        } else {
                            // Si suppression pas effectuée
                            $result = false;
                        }
                    }
                }
            } catch (PDOException $e) {
                $result = $e;
            } finally {
                //Terminer la connexion
                self::$model = null;
            }
        }
        return $result;
    }
}