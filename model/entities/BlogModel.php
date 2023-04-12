<?php
final class Blog
{
    /* MÉTHODES */

    /* Insertions */
    // Installer la base de données du blog
    public static function installDB(): bool|Exception
    {
        // Supprimer toutes les images de post dans le cas d'une réinstallation
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
            if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . '*')) {
                // Si la suppression a échoué
                // On logge l'erreur
                Model::printLog('Impossible de supprimer toutes les images de post !');
                return false;
            }
        }

        // Supprimer toutes les vidéos de post dans le cas d'une réinstallation
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
            if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . '*')) {
                // Si la suppression a échoué
                // On logge l'erreur
                Model::printLog('Impossible de supprimer toutes les vidéos de post !');
                return false;
            }
        }
        // Tenter d'installer la base de données du blog
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("Impossible d'installer le blog en base de données: La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Tenter d'nstaller la base de données via le fichier SQL
                try {
                    // Récupérer le contenu du fichier SQL d'installation de la BDD
                    $sqlFile = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'NewBlogDB_install.sql');
                    // Si le fichier n'a pas pu être lu
                    if (!$sqlFile) {
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Le fichier SQL d'installation de la base de données n'a pas pu être lu !");
                    }
                    // Exécuter le contenu du fichier SQL
                    if (Model::getPdo()->exec($sqlFile) === false) {
                        // Si une erreur survient, on lance une exception qui sera attrapée plus bas
                        throw new Exception("Le fichier SQL d'installation de la base de données n'a pas pu être exécuté !");
                    }

                    // On logge le succès
                    Model::printLog("Installation de la base de données réussie !");
                    // On renvoie un succès
                    return true;
                } catch (Exception $e) {
                    // Si une erreur est survenue
                    // On logge l'erreur
                    Model::printLog(Model::getError($e));
                    // On lance une nouvelle erreur qui sera attrapée plus bas
                    throw new Exception("L'installation de la base de données a échoué !");
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
    // Insertions des informations du blog
    public static function insertBlog(string $blogName, string $description, int $adminId, string $bgURL = ""): bool|Exception
    {
        // Tenter d'insérer les informations du blog en base de données
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                throw new Exception("Impossible d'insérer les informations du blog: la connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Si l'image de fond n'a pas été spécifiée
                if (empty($bgURL)) {
                    // On définit la requête à traiter
                    $stmt = Model::getPdo()->prepare(
                        "INSERT INTO newblog.nb_blog (blog_name, description, id_user_owner)
                            VALUES (:blog_name, :description, :id_user_owner);"
                    );
                    // Si la requête n'a pas pu être préparée
                    if (!$stmt) {
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Impossible de préparer la requête d'insertion des données du blog !");
                    }
                    // On prépare une requête avec toutes les infos sauf l'image de fond
                    Model::setStmt($stmt);
                } else {
                    // Si l'image de fond est définie

                    // On prépare une requête avec toutes les infos
                    $stmt = Model::getPdo()->prepare("INSERT INTO newblog.nb_blog (blog_name, description, background_url, id_user_owner)
                            VALUES (:blog_name, :description, :background_url, :id_user_owner);");
                    // Si la requête n'a pas pu être préparée
                    if (!$stmt) {
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Impossible de préparer la requête d'insertion des données du blog !");
                    }

                    // On définit la requête à traiter
                    Model::setStmt($stmt);

                    // Attacher l'image de fond en paramètre à la requête préparée
                    if (!Model::getStmt()->bindParam('background_url', $bgURL, PDO::PARAM_STR)) {
                        // Si l'image de fond n'a pas pu être attachée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Impossible d'attacher l'image de fond en paramètre à la requête d'insertion des données du blog !");
                    }
                }
                // Attacher le nom du blog en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('blog_name', $blogName, PDO::PARAM_STR)) {
                    // Si le nom du blog n'a pas pu être attaché
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Impossible d'attacher le nom du blog en paramètre à la requête d'insertion des données du blog !");
                }
                // Attacher la description du blog en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('description', $description, PDO::PARAM_STR)) {
                    // Si la description du blog n'a pas pu être attachée
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Impossible d'attacher la description du blog en paramètre à la requête d'insertion des données du blog !");
                }
                // Attacher l'id de l'utilisateur propriétaire du blog (préalablement inséré) en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('id_user_owner', $adminId, PDO::PARAM_INT)) {
                    // Si l'id de l'utilisateur propriétaire du blog n'a pas pu être attaché
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Impossible d'attacher l'id de l'utilisateur propriétaire du blog en paramètre à la requête d'insertion des données du blog !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Une erreur est survenue lors de l'exécution de la requête d'insertion des données du blog !");
                } else {
                    // Si la requête a pu être exécutée

                    if (Model::getStmt()->rowCount() > 0) {
                        // Si insertion effectuée
                        // On logge le succès
                        Model::printLog('Insertion des données du blog en base de données réussie : Nom = ' . $blogName . ', Description = ' . $description . ', ID admin = ' . $adminId . ', Image de fond: ' . $bgURL . '');
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si insertion pas effectuée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("L'insertion des données du blog en base de données a échoué !");
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

    /* Récupérations */
    // Récupérer le premier blog
    public static function selectBlog(): array|Exception
    {
        // Tenter de récupérer le premier blog inséré en base de données
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("Impossible de récupérer les informations du blog: la connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "SELECT * 
                    FROM newblog.nb_blog 
                    LIMIT 1"
                );
                if (!$stmt) {
                    // Si la requête n'a pas pu être préparée
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("La requête de récupération des données du blog n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera attrapée plus bas
                    throw new Exception("Une erreur est survenue lors de la requête de récupération des données du blog !");
                } else {
                    // Si la requête a réussi, renvoyer les résultats
                    $result = Model::getStmt()->fetchAll();
                    // Si les résultats ont pu être récupérés
                    if ($result === false) {
                        // Si les résultats n'ont pas pu être récupérés
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Les informations du blog n'ont pas pu être récupérées !");
                    } else {
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
    // Récupérer le nom du blog
    public static function selectBlogByName(string $blogName): array|Exception
    {
        // Tenter de récupérer le blog dont le nom est passé en paramètre
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("Impossible de récupérer les informations du blog: la connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "SELECT * 
                    FROM newblog.nb_blog 
                    WHERE newblog.nb_blog.blog_name = :blogname"
                );
                if (!$stmt) {
                    // Si la requête n'a pas pu être préparée
                    throw new Exception("La requête de récupération des données du blog n'a pas pu être préparée !");
                }

                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher le nom du blog en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('blogname', $blogName, PDO::PARAM_STR)) {
                    // Si le nom du blog n'a pas pu être attaché
                    throw new Exception("Impossible d'attacher le nom du blog en paramètre à la requête de récupération des données du blog !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    $result = Model::getStmt()->fetchAll();
                    // Si les résultats ont pu être récupérés
                    if ($result === false) {
                        // Si les résultats n'ont pas pu être récupérés
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Les informations du blog n'ont pas pu être récupérées !");
                    } else {
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

    /* Modifications */
    // Changer le nom du blog 
    public static function updateBlogName(string $newBlogName): bool|Exception
    {
        // Tenter de changer le nom du blog
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                    SET blog_name = :newBlogName"
                );
                if (!$stmt) {
                    // Si la requête n'a pas pu être préparée
                    throw new Exception("La requête de mise à jour du nom du blog n'a pas pu être préparée !");
                }

                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher le nouveau nom du blog en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('newBlogName', $newBlogName, PDO::PARAM_STR)) {
                    // Si le nom du blog n'a pas pu être attaché
                    throw new Exception("Impossible d'attacher le nom du blog en paramètre à la requête de mise à jour du nom du blog !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour du nom du blog a échoué !");
                } else {
                    // Si la requête a réussi
                    // Si mise à jour effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On logge le succès
                        Model::printLog('Le nom du blog a été mis à jour en ' . $newBlogName . ' avec succès !');
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si mise à jour pas effectuée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Le nom du blog n'a pas pu être mis à jour !");
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
    // Changer la description 
    public static function updateDescription(string $newDescription): bool|Exception
    {
        // Tenter de mettre à jour la description du blog
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                    SET description = :newDescription"
                );
                if (!$stmt) {
                    // Si la requête n'a pas pu être préparée
                    throw new Exception("La requête de mise à jour de la description du blog n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher la nouvelle description en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('newDescription', $newDescription, PDO::PARAM_STR)) {
                    // Si la description n'a pas pu être attachée
                    throw new Exception("Impossible d'attacher la description \"$newDescription\" en paramètre à la requête de mise à jour de la description du blog !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour de la description du blog a échoué !");
                } else {
                    // Si mise à jour effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On logge le succès
                        Model::printLog('La description du blog a été mise à jour en ' . $newDescription . ' avec succès !');
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si mise à jour pas effectuée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("La description du blog n'a pas pu être mise à jour !");
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
    // Changer le logo du blog
    public static function updateLogoURL(string $newLogoURL): bool|Exception
    {
        // Tenter de mettre à jour le logo du blog
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                    SET logo_url = :newLogo"
                );
                if (!$stmt) {
                    // Si la requête n'a pas pu être préparée
                    throw new Exception("La requête de mise à jour du logo du blog n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher le nouveau logo en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('newLogo', $newLogoURL, PDO::PARAM_STR)) {
                    // Si le logo n'a pas pu être attaché
                    throw new Exception("Impossible d'attacher le logo \"$newLogoURL\" en paramètre à la requête de mise à jour du logo du blog !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour du logo du blog a échoué !");
                } else {
                    // Si mise à jour effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On logge le succès
                        Model::printLog('Le logo du blog a été mis à jour en ' . $newLogoURL . ' avec succès !');
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si mise à jour pas effectuée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("Le logo du blog n'a pas pu être mis à jour !");
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
    // Changer l'image image de fond 
    public static function updateBackgroundURL(string $newBackground): bool|Exception
    {
        // Tenter de mettre à jour l'image de fond du blog
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera attrapée plus bas
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                SET background_url = :newBackground"
                );
                if (!$stmt) {
                    // Si la requête n'a pas pu être préparée
                    throw new Exception("La requête de mise à jour de l'image de fond du blog n'a pas pu être préparée !");
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher la nouvelle image de fond en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('newBackground', $newBackground, PDO::PARAM_STR)) {
                    // Si l'image de fond n'a pas pu être attachée
                    throw new Exception("Impossible d'attacher l'image de fond \"$newBackground\" en paramètre à la requête de mise à jour de l'image de fond du blog !");
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de mise à jour de l'image du fond du blog a échoué !");
                } else {
                    // Si mise à jour effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On logge le succès
                        Model::printLog('L\'image de fond du blog a été mise à jour en ' . $newBackground . ' avec succès !');
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si mise à jour pas effectuée
                        // On lance une erreur qui sera attrapée plus bas
                        throw new Exception("L'image de fond du blog n'a pas pu être mise à jour !");
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