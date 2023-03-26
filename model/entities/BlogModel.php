<?php
final class Blog
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    private static Model|null $model;

    /* MÉTHODES */

    /* Insertions */
    // Installer le blog (À faire)
    public static function installDB(): bool | PDOException
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
                try {
                    // Installer la base de données via le fichier SQL

                    // Récupérer le contenu du fichier SQL d'installation de la BDD
                    $sqlFile = file_get_contents(__DIR__ . "\..\NewBlogDB_install.sql");
                    // Exécuter le contenu du fichier SQL
                    self::$model->getPdo()->exec($sqlFile);
                    // Si l'installation a réussi
                    $result = true;
                } catch (PDOException $e) {
                    // Si une erreur survient, on stocke l'erreur dans le résultat et on le renvoie
                    throw new PDOException("L'installation de la base de données a échoué !");
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
    // Insertions des informations du blog
    public static function insertBlog(string $blogName, string $description, string $bgURL = "", int $adminId): bool | PDOException
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
                if (empty($bgURL)) {
                    self::$model->setStmt(self::$model->getPdo()->prepare(
                        // Si l'image de fond n'est pas définie, on ne l'insère pas dans la requête
                        "INSERT INTO newblog.nb_blog (blog_name, description, id_user_owner)
                        VALUES (:blog_name, :description, :id_user_owner);"
                    ));
                } else {
                    // Si l'image de fond est définie, on l'insère dans la requête
                    self::$model->setStmt(
                        self::$model->getPdo()->prepare(
                            "INSERT INTO newblog.nb_blog (blog_name, description, background_url, id_user_owner)
                    VALUES (:blog_name, :description, :background_url, :id_user_owner);"
                        )
                    );
                    // Définir l'image de fond
                    self::$model->getStmt()->bindParam('background_url', $bgURL, PDO::PARAM_STR);
                }
                // Définir le nom du blog
                self::$model->getStmt()->bindParam('blog_name', $blogName, PDO::PARAM_STR);
                // Définir la description
                self::$model->getStmt()->bindParam('description', $description, PDO::PARAM_STR);
                // Définir l'id de l'utilisateur propriétaire du blog (préalablement inséré)
                self::$model->getStmt()->bindParam('id_user_owner', $adminId, PDO::PARAM_INT);

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
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        } finally {
            //Terminer la connexion
            self::$model = null;
        }
        return $result;
    }

    /* Récupérations */
    // Récupérer le premier blog
    public static function selectBlog(): array | PDOException
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
        // Résultat initial = false
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
        // Résultat initial = false
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
        // Résultat initial = false
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
