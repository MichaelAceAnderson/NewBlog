<?php
final class Blog
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    private static Model|null $model;

    /* MÉTHODES */

    /* Insertions */
    // Installer le blog (À faire)
    public static function install()
    {
    }

    /* Récupérations */
    // Récupérer le premier blog
    public static function selectBlog(): array | PDOException
    {
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
                self::$model->setStmt(self::$model->getPdo()->prepare(
                    "SELECT * 
                    FROM newblog.nb_blog 
                    LIMIT 1"
                ));
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
    // Récupérer le nom du blog
    public static function selectBlogByName($blogName): array | PDOException
    {
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
                self::$model->setStmt(self::$model->getPdo()->prepare(
                    "SELECT * 
                    FROM newblog.nb_blog 
                    WHERE newblog.nb_blog.blog_name = :blogname"
                ));
                self::$model->getStmt()->bindParam('blogname', $blogName, PDO::PARAM_STR);

                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new PDOException("La requête de récupération des données du blog a échoué !");
                } else {
                    $result = self::$model->getStmt()->fetchAll();
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

    /* Modifications */
    // Changer le nom du blog 
    public static function updateBlogName(string $newBlogName): bool | PDOException
    {
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
                self::$model->setStmt(self::$model->getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                    SET blog_name = :newBlogName"
                ));
                self::$model->getStmt()->bindParam('newBlogName', $newBlogName, PDO::PARAM_STR);

                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new PDOException("La requête de mise à jour du nom du blog a échoué !");
                } else {
                    if (self::$model->getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
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
    // Changer la description 
    public static function updateDescription(string $newDescription): bool | PDOException
    {
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
                self::$model->setStmt(self::$model->getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                    SET description = :newDescription"
                ));
                self::$model->getStmt()->bindParam('newDescription', $newDescription, PDO::PARAM_STR);

                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new PDOException("La requête de mise à jour de la description du blog a échoué !");
                } else {
                    if (self::$model->getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
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
    // Changer l'image image de fond 
    public static function updateBackgroundURL(string $newBackground): bool | PDOException
    {
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
                self::$model->setStmt(self::$model->getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                SET background_url = :newBackground"
                ));
                self::$model->getStmt()->bindParam('newBackground', $newBackground, PDO::PARAM_STR);

                // Exécuter la requête
                if (!self::$model->getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new PDOException("La requête de mise à jour de l'image du fond du blog a échoué !");
                } else {
                    if (self::$model->getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
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
}