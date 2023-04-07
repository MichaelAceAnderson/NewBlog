<?php
final class Blog
{
    /* MÉTHODES */

    /* Insertions */
    // Installer le blog (À faire)
    public static function installDB(): bool|Exception
    {
        // Résultat initial = échec
        $result = false;
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                try {
                    // Installer la base de données via le fichier SQL

                    // Récupérer le contenu du fichier SQL d'installation de la BDD
                    $sqlFile = file_get_contents(__DIR__ . "\..\NewBlogDB_install.sql");
                    // Exécuter le contenu du fichier SQL
                    Model::getPdo()->exec($sqlFile);
                    // Si l'installation a réussi
                    $result = true;
                } catch (Exception $e) {
                    // Si une erreur survient, on stocke l'erreur dans le résultat et on le renvoie
                    throw new Exception("L'installation de la base de données a échoué !");
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
    // Insertions des informations du blog
    public static function insertBlog(string $blogName, string $description, int $adminId, string $bgURL = ""): bool|Exception
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
                if (empty($bgURL)) {
                    Model::setStmt(
                        Model::getPdo()->prepare(
                            // Si l'image de fond n'est pas définie, on ne l'insère pas dans la requête
                            "INSERT INTO newblog.nb_blog (blog_name, description, id_user_owner)
                        VALUES (:blog_name, :description, :id_user_owner);"
                        )
                    );
                } else {
                    // Si l'image de fond est définie, on l'insère dans la requête
                    Model::setStmt(
                        Model::getPdo()->prepare(
                            "INSERT INTO newblog.nb_blog (blog_name, description, background_url, id_user_owner)
                    VALUES (:blog_name, :description, :background_url, :id_user_owner);"
                        )
                    );
                    // Définir l'image de fond
                    Model::getStmt()->bindParam('background_url', $bgURL, PDO::PARAM_STR);
                }
                // Définir le nom du blog
                Model::getStmt()->bindParam('blog_name', $blogName, PDO::PARAM_STR);
                // Définir la description
                Model::getStmt()->bindParam('description', $description, PDO::PARAM_STR);
                // Définir l'id de l'utilisateur propriétaire du blog (préalablement inséré)
                Model::getStmt()->bindParam('id_user_owner', $adminId, PDO::PARAM_INT);

                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    throw new Exception("Une erreur est survenue");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si insertion effectuée
                        $result = true;
                    } else {
                        // Si insertion pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        return $result;
    }

    /* Récupérations */
    // Récupérer le premier blog
    public static function selectBlog(): array|Exception
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
                        "SELECT * 
                    FROM newblog.nb_blog 
                    LIMIT 1"
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
    // Récupérer le nom du blog
    public static function selectBlogByName(string $blogName): array|Exception
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
                        "SELECT * 
                    FROM newblog.nb_blog 
                    WHERE newblog.nb_blog.blog_name = :blogname"
                    )
                );
                Model::getStmt()->bindParam('blogname', $blogName, PDO::PARAM_STR);

                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    $result = Model::getStmt()->fetchAll();
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }

    /* Modifications */
    // Changer le nom du blog 
    public static function updateBlogName(string $newBlogName): bool|Exception
    {
        // Résultat initial = false
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
                        "UPDATE newblog.nb_blog 
                    SET blog_name = :newBlogName"
                    )
                );
                Model::getStmt()->bindParam('newBlogName', $newBlogName, PDO::PARAM_STR);

                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour du nom du blog a échoué !");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
    // Changer la description 
    public static function updateDescription(string $newDescription): bool|Exception
    {
        // Résultat initial = false
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
                        "UPDATE newblog.nb_blog 
                    SET description = :newDescription"
                    )
                );
                Model::getStmt()->bindParam('newDescription', $newDescription, PDO::PARAM_STR);

                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour de la description du blog a échoué !");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
    // Changer l'image image de fond 
    public static function updateLogoURL(string $newLogoURL): bool|Exception
    {
        // Résultat initial = false
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
                        "UPDATE newblog.nb_blog 
                SET logo_url = :newLogo"
                    )
                );
                Model::getStmt()->bindParam('newLogo', $newLogoURL, PDO::PARAM_STR);

                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour du logo du blog a échoué !");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
    // Changer l'image image de fond 
    public static function updateBackgroundURL(string $newBackground): bool|Exception
    {
        // Résultat initial = false
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
                        "UPDATE newblog.nb_blog 
                SET background_url = :newBackground"
                    )
                );
                Model::getStmt()->bindParam('newBackground', $newBackground, PDO::PARAM_STR);

                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour de l'image du fond du blog a échoué !");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
}