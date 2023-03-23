<?php
class BlogController
{
    /* MÉTHODES */

    // Vérifier si le blog est installé
    public static function isInstalled(): bool
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return !empty($result);
        }
    }

    /* Créations */
    // Installer le blog
    public static function installBlog(string $adminName, string $adminPass, string $blogName, string $blogDescription, string $bgURL): bool | Exception
    {
        // Création du blog
        $dbInstallStatus = Blog::installDB();
        if ($dbInstallStatus instanceof PDOException) {
            // Si l'installation en base de données a échoué, on renvoie l'erreur
            return $dbInstallStatus;
        } else {
            // Si l'installation en base de données a renvoyé une erreur
            if (!$dbInstallStatus) {
                // Si l'installation de la BDD a réussi mais qu'une erreur inconnue survient
                return new Exception('Une erreur inattendue est survenue lors de l\'installation de la base de données !');
            } else {
                // Si l'installation de la base de données a réussi

                // Créer l'utilisateur principal, admin et propriétaire
                if (!UserController::createUser($adminName, $adminPass, true)) {
                    // Si l'utilisateur n'a pas pu être créé, on arrête l'installation
                    return new PDOException("L'utilisateur administrateur n'a pas pu être créé en base de données !");
                } else {
                    // Récupération de l'utilisateur admin, propriétaire du blog
                    $adminAccount = UserController::getUserInfoByCredentials($adminName, $adminPass);
                    if (!$adminAccount) {
                        // Si l'utilisateur admin n'a pas pu être récupéré en base de données
                        return new Exception("Impossible d'utiliser l'utilisateur administrateur pour continuer l'installation !");
                    }
                    // Si l'utilisateur admin a bien été créé et récupéré

                    // Insertion des informations du blog en base de données
                    $blogInsertStatus = Blog::insertBlog($blogName, $blogDescription, $bgURL, $adminAccount->id_user);
                    if ($blogInsertStatus instanceof PDOException) {
                        // Si l'installation de la BDD a réussi mais qu'une erreur survient lors de l'insertion des données du blog
                        return $blogInsertStatus;
                    } else {
                        if (!$blogInsertStatus) {
                            // Si les données du blog n'ont pas pu être insérées dans la base de données
                            return new Exception('Les données du blog n\'ont pas pu être insérées en base de données !');
                        } else {
                            // Si le blog a été inséré, on renvoie true pour indiquer que l'installation est complète
                            return true;
                        }
                    }
                }
            }
        }
    }

    /* Modifications */
    // Définir le nom du blog
    public static function setBlogName($newBlogName): bool
    {
        $result = Blog::updateBlogName($newBlogName);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Changer l'image image de fond 
    public static function setBackgroundURL($newBackgroundURL): bool
    {
        $result = Blog::updateBackgroundURL($newBackgroundURL);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Changer la description 
    public static function setDescription($newDescription): bool
    {
        $result = Blog::updateDescription($newDescription);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer le nom du blog
    public static function getBlogName(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]->blog_name ?? false;
        }
    }
    // Récupérer l'URL de l'image de fond du blog
    public static function getBackgroundURL(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]->background_url ?? false;
        }
    }
    // Récupérer la description du blog
    public static function getBlogDescription(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]->description ?? false;
        }
    }
    // Récupérer la date de création du blog
    public static function getCreationDate(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]['creation_date'] ?? false;
        }
    }
}

/* GESTION DES REQUÊTES PAR FORMULAIRE */

// Si le formulaire d'installation a été soumis
if (isset($_POST['fInstall'])) {
    // Vérification des champs
    if (
        isset($_POST['fUserName']) && $_POST['fUserName'] != ""
        && isset($_POST['fPass']) && $_POST['fPass'] != ""
        && isset($_POST['fBlogName']) && $_POST['fBlogName'] != ""
        && isset($_POST['fBlogDesc']) && $_POST['fBlogDesc'] != ""
    ) {
        $installStatus = BlogController::installBlog($_POST['fUserName'], $_POST['fPass'], $_POST['fBlogName'], $_POST['fBlogDesc'], $_POST['fBgURL']);
        if ($installStatus instanceof Exception) {
            // Si une erreur est survenue, on journalise le message d'erreur et on l'affiche à l'utilisateur
            // Model::printLog(Model::getError($installStatus));
            $formError = Model::getError($installStatus, HTML);
        } else {
            if ($installStatus) {
                // Si l'installation a bien réussi, on redirige vers la page d'accueil
                header('Location: /');
            } else {
                // Ne devrait jamais arriver
                $formError = 'Une erreur est survenue lors de l\'installation du blog !';
            }
        }
    } else {
        $formError = 'Veuillez remplir tous les champs !';
    }
}
