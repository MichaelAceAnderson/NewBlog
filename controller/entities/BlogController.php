<?php
class BlogController
{
    /* MÉTHODES */

    // Vérifier si le blog est installé
    public static function isInstalled(): bool
    {
        // Tenter de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la BDD est installée et que les infos du blog existent, on renvoie vrai
            return !empty($result);
        }
    }

    /* Créations */
    // Installer le blog
    public static function installBlog(string $adminName, string $adminPass, string $blogName, string $blogDescription, string $bgURL): bool|Exception
    {
        // Création du blog en base de données
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
                    $blogInsertStatus = Blog::insertBlog($blogName, $blogDescription, $adminAccount->id_user, $bgURL);
                    if ($blogInsertStatus instanceof PDOException) {
                        // Si l'installation de la BDD a réussi mais qu'une erreur survient lors de l'insertion des données du blog
                        return $blogInsertStatus;
                    } else {
                        if (!$blogInsertStatus) {
                            // Si les données du blog n'ont pas pu être insérées dans la base de données
                            return new Exception('Les données du blog n\'ont pas pu être insérées en base de données !');
                        } else {
                            // Si l'installation du blog a réussi, notifier le serveur de NewBlog via la page d'API

                            // $version = file_get_contents($server . '/model/data/settings/version.txt');
                            // $URL = "http://" . $_SERVER['HTTP_HOST'];
                            // $SendInstall = file_get_contents("http://xdev.livehost.fr/creations/web/newblog/bloginstalled.php?url=$URL&version=$version");

                            // On renvoie true pour indiquer que l'installation est complète
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
        // On tente de mettre à jour le nom du blog en base de données
        $result = Blog::updateBlogName($newBlogName);
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la mise à jour du nom du blog a réussi, on renvoie vrai
            return $result;
        }
    }
    // Changer l'image image de fond 
    public static function setBackgroundURL($newBackgroundURL): bool
    {
        // On tente de mettre à jour l'URL de l'image de fond
        $result = Blog::updateBackgroundURL($newBackgroundURL);
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la mise à jour de l'URL de l'image de fond a réussi, on renvoie vrai
            return $result;
        }
    }
    // Changer la description 
    public static function setDescription($newDescription): bool
    {
        // On tente de mettre à jour la description du blog en base de données
        $result = Blog::updateDescription($newDescription);
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la mise à jour de la description du blog a réussi, on renvoie vrai
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer le nom du blog
    public static function getBlogName(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            // Si une erreur survient, on renvoie faux et on logge l'erreur
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la récupération des infos du blog a réussi, on renvoie le nom du blog s'il y en a un
            return $result[0]->blog_name ?? false;
        }
    }
    // Récupérer l'URL de l'image de fond du blog
    public static function getBackgroundURL(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la récupération des infos du blog a réussi, on renvoie l'URL de l'image de fond s'il y en a une
            return $result[0]->background_url ?? false;
        }
    }
    // Récupérer la description du blog
    public static function getBlogDescription(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la récupération des infos du blog a réussi, on renvoie la description du blog s'il y en a une
            return $result[0]->description ?? false;
        }
    }
    // Récupérer la date de création du blog
    public static function getCreationDate(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur survient, on renvoie faux et on logge l'erreur
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Si la récupération des infos du blog a réussi, on renvoie la date de création du blog s'il y en a une
            return $result[0]->creation_date ?? false;
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
                // Si l'installation a bien réussi
                if (isset($_SESSION)) {
                    // Retirer toutes les variables de la session
                    unset($_SESSION);
                    // Détruire la session
                    session_destroy();
                }
                // On redirige vers l'accueil
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

// Si le formulaire de modification du nom du blog a été soumis
if (isset($_POST['fChangeBlogName'])) {
    // Vérification des champs
    if (isset($_POST['fBlogName']) && $_POST['fBlogName'] != "") {
        $blogNameUpdateStatus = BlogController::setBlogName($_POST['fBlogName']);
        if ($blogNameUpdateStatus) {
            // Si la modification a réussi, on stocke un message de succès
            $formSuccess = 'Le nom du blog a bien été modifié !';
        } else {
            // Si la modification a échoué, on affiche un message d'erreur
            $formError = 'Une erreur est survenue lors de la modification du nom du blog !';
        }
    } else {
        $formError = 'Veuillez remplir tous les champs !';
    }
}

// Si le formulaire de modification de la description a été soumis
if (isset($_POST['fChangeBlogDesc'])) {
    // Vérification des champs
    if (isset($_POST['fBlogDesc']) && $_POST['fBlogDesc'] != "") {
        $blogDescUpdateStatus = BlogController::setDescription($_POST['fBlogDesc']);
        if ($blogDescUpdateStatus) {
            // Si la modification a réussi, on stocke un message de succès
            $formSuccess = 'La description a bien été modifiée !';
        } else {
            // Si la modification a échoué, on affiche un message d'erreur
            $formError = 'Une erreur est survenue lors de la modification de la description !';
        }
    } else {
        $formError = 'Veuillez remplir tous les champs !';
    }
}

// Si le formulaire de modification de l'image de fond a été soumis
if (isset($_POST['fChangeBgURL'])) {
    // Vérification des champs
    if (!isset($_POST['fBgURL']) || $_POST['fBgURL'] == "") {
        $_POST['fBgURL'] = "/common/img/background.jpg";
    }
    $bgURLUpdateStatus = BlogController::setBackgroundURL($_POST['fBgURL']);
    if ($bgURLUpdateStatus) {
        // Si la modification a réussi, on stocke un message de succès
        $formSuccess = 'L\'URL de l\'image de fond a bien été modifiée !';
    } else {
        // Si la modification a échoué, on affiche un message d'erreur
        $formError = 'Une erreur est survenue lors de la modification de l\'image de fond !';
    }
}