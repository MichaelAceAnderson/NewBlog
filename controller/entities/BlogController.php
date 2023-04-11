<?php
class BlogController
{
    /* MÉTHODES */

    // Vérifier si le blog est installé
    public static function isInstalled(): bool
    {
        // Tenter de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur survient lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la vérification de l'installation du blog !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si la BDD est installée et que les infos du blog existent
            // On renvoie un succès/échec selon si le résultat est vide ou non
            return !empty($result);
        }
    }

    /* Créations */
    // Installer le blog
    public static function installBlog(string $adminName, string $adminPass, string $blogName, string $blogDescription, string $bgURL): bool|Exception
    {
        // Supprimer toutes les images de post dans le cas d'une réinstallation
        foreach (glob('blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . '*') as $img) {
            // Si c'est un fichier et pas un sous-dossier
            if (is_file($img)) {
                // Supprimer le fichier
                unlink($img);
            }
        }
        // Supprimer toutes les vidéos de post dans le cas d'une réinstallation
        foreach (glob('blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . '*') as $video) {
            // Si c'est un fichier et pas un sous-dossier
            if (is_file($video)) {
                // Supprimer le fichier
                unlink($video);
            }
        }


        // Création du blog en base de données
        $dbInstallStatus = Blog::installDB();
        // Si l'installation en base de données a échoué
        if ($dbInstallStatus instanceof Exception) {
            // On renvoie l'erreur
            return $dbInstallStatus;
        } else {
            // Si l'installation en base de données a renvoyé une erreur
            if (!$dbInstallStatus) {
                // Si l'installation de la BDD a réussi mais qu'une erreur inconnue survient
                // On définit l'erreur à renvoyer
                $error = new Exception('Une erreur inattendue est survenue lors de l\'installation de la base de données (Nom: ' . $blogName . ', Description: ' . $blogDescription . ', Image de fond: ' . $bgURL . ', Administrateur: ' . $adminPass . ', Mot de passe admin: ' . $adminPass . ') !');
                // On logge l'erreur
                Controller::printLog(Controller::getError($error));
                // On renvoie l'erreur
                return $error;
            } else {
                // Si l'installation de la base de données a réussi

                // Créer l'utilisateur principal, admin et propriétaire
                if (!UserController::createUser($adminName, $adminPass, true)) {
                    // Si l'utilisateur n'a pas pu être créé, on arrête l'installation
                    // On définit l'erreur à renvoyer
                    $error = new Exception("L'utilisateur administrateur $adminName avec le mot de passe $adminPass n'a pas pu être créé en base de données !");
                    // On logge l'erreur
                    Controller::printLog(Controller::getError($error));
                    // On renvoie l'erreur
                    return $error;
                } else {
                    // Récupération de l'utilisateur admin, propriétaire du blog
                    $adminAccount = UserController::getUserInfoByCredentials($adminName, $adminPass);
                    if (!$adminAccount) {
                        // Si l'utilisateur admin n'a pas pu être récupéré en base de données
                        // On définit l'erreur à renvoyer
                        $error = new Exception("L'utilisateur administrateur $adminName avec le mot de passe $adminPass n'a pas pu être récupéré en base de données !");
                        // On logge l'erreur
                        Controller::printLog(Controller::getError($error));
                        // On renvoie l'erreur
                        return $error;
                    }
                    // Si l'utilisateur admin a bien été créé et récupéré

                    // Insertion des informations du blog en base de données
                    $blogInsertStatus = Blog::insertBlog($blogName, $blogDescription, $adminAccount->id_user, $bgURL);
                    if ($blogInsertStatus instanceof Exception) {
                        // Si l'installation de la BDD a réussi mais qu'une erreur survient lors de l'insertion des données du blog
                        return $blogInsertStatus;
                    } else {
                        if (!$blogInsertStatus) {
                            // Si les données du blog n'ont pas pu être insérées dans la base de données
                            // On définit l'erreur à renvoyer
                            $error = new Exception("Les données du blog n'ont pas pu être insérées en base de données  (Nom: ' . $blogName . ', Description: ' . $blogDescription . ', Image de fond: ' . $bgURL . ', Administrateur: ' . $adminPass . ', Mot de passe admin: ' . $adminPass . ') !");
                            // On logge l'erreur
                            Controller::printLog(Controller::getError($error));
                            // On renvoie l'erreur
                            return $error;
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
        // Si une erreur survient lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la définition du nom du blog en $newBlogName !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si la mise à jour du nom du blog a réussi
            // On renvoie un succès
            return $result;
        }
    }
    // Changer l'image de fond 
    public static function setBackgroundURL($newBackgroundURL): bool
    {
        // On tente de mettre à jour l'URL de l'image de fond
        $result = Blog::updateBackgroundURL($newBackgroundURL);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la définition de l'URL de l'image de fond en $newBackgroundURL !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si la mise à jour de l'URL de l'image de fond a réussi
            // On renvoie un succès
            return $result;
        }
    }
    // Changer l'image de fond 
    public static function setLogoURL($newLogoURL): bool
    {
        // On tente de mettre à jour l'URL du logo
        $result = Blog::updateLogoURL($newLogoURL);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la définition de l'URL du logo en $newLogoURL !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si la mise à jour de l'URL du logo
            // On renvoie un succès
            return $result;
        }
    }
    // Changer la description 
    public static function setDescription($newDescription): bool
    {
        // On tente de mettre à jour la description du blog en base de données
        $result = Blog::updateDescription($newDescription);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la définition de la description du blog en $newDescription !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si la mise à jour de la description du blog a réussi
            // On renvoie vrai
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer le nom du blog
    public static function getBlogName(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la récupération du nom du blog !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie faux
            return false;
        } else {
            // Si la récupération des infos du blog a réussi
            // On renvoie le nom du blog s'il y en a un
            return $result[0]->blog_name ?? false;
        }
    }
    // Récupérer la description du blog
    public static function getBlogDescription(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la récupération de la description du blog !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie faux
            return false;
        } else {
            // Si la récupération des infos du blog a réussi
            // On renvoie la description du blog s'il y en a une
            return $result[0]->description ?? false;
        }
    }
    // Récupérer l'URL de l'image de fond du blog
    public static function getBackgroundURL(): string
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la récupération de l'URL de l'image de fond du blog !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On retourne l'URL par défaut
            return "/view/img/circuits.jpg";
        } else {
            // Si la récupération des infos du blog a réussi, on renvoie l'URL de l'image de fond s'il y en a une
            // Sinon, on renvoie l'URL de l'image de fond par défaut
            return $result[0]->background_url ?? "/view/img/circuits.jpg";
        }
    }
    // Récupérer l'URL du logo du blog
    public static function getLogoUrl(): string
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la récupération de l'URL du logo du blog !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie l'URL du logo par défaut
            return "/view/img/logo.jpg";
        } else {
            // Si la récupération des infos du blog a réussi
            // On renvoie l'URL du logo récupérée ou celle par défaut en cas d'absence de logo
            return $result[0]->logo_url ?? "/view/img/logo.jpg";
        }
    }
    // Récupérer la date de création du blog
    public static function getCreationDate(): string|false
    {
        // On tente de récupérer les infos du blog en base de données
        $result = Blog::selectBlog();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception("Une erreur est survenue lors de la récupération de la date de création du blog !");
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si la récupération des infos du blog a réussi
            // On renvoie la date de création du blog s'il y en a une
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
            // Controller::printLog(Controller::getError($installStatus));
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

// Si le formulaire de modification du logo a été soumis
if (isset($_POST['fChangeLogo'])) {
    // Si le logo a été uploadé
    if (!empty($_FILES) && $_FILES['fLogoFile']['error'] != UPLOAD_ERR_NO_FILE) {
        // Erreur éventuelle de l'upload
        $error = $_FILES['fLogoFile']['error'];

        if ($_FILES['fLogoFile']['error'] != UPLOAD_ERR_OK || !$_FILES['fLogoFile']['tmp_name']) {
            // Si une erreur est survenue lors de l'upload, on stocke le message d'erreur à afficher
            $formError = 'Erreur: Le fichier n\'a pas pu être uploadé';
        } elseif (!preg_match("/image\//", $_FILES['fLogoFile']['type'])) {
            // Si le fichier n'est pas une image, on stocke le message d'erreur à afficher
            $formError = 'Votre fichier doit être une image ou une vidéo !';
        } elseif ($_FILES['fLogoFile']['size'] > 10485760) {
            // Si la taille du fichier est supérieure à 10Mo, on stocke le message d'erreur à afficher
            $formError = 'Le fichier est trop volumineux !';
        } else {
            // On supprime l'ancien logo
            foreach (glob($_SERVER['DOCUMENT_ROOT'] . '/blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo.*') as $imgFile) {
                // Si c'est un fichier et pas un sous-dossier
                if (is_file($imgFile)) {
                    // Supprimer le fichier
                    unlink($imgFile);
                }
            }
            // Chemin des logos
            $logoPath = DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' .
                DIRECTORY_SEPARATOR .
                // Nom du fichier . extension
                'logo.' . pathinfo($_FILES['fLogoFile']['name'], PATHINFO_EXTENSION);
            // Si on a réussi à déplacer le fichier uploadé
            if (
                !move_uploaded_file(
                    // Chemin temporaire du fichier uploadé
                    $_FILES['fLogoFile']['tmp_name'],
                    // Destination
                    $_SERVER['DOCUMENT_ROOT'] . $logoPath
                )
            ) {
                // Si une erreur survient, on stocke le message d'erreur à afficher
                $formError = 'Impossible d\'uploader le fichier en raison d\'une erreur côté serveur';
            } else {
                // Si l'upload a réussi, on stocke un message de succès
                $formSuccess = 'Le logo a bien été modifié !';
            }
        }
        // Si on ne rencontre aucune erreur pendant l'upload
        if (!isset($formError)) {
            // On met à jour l'URL du logo
            $logoURLUpdateStatus = BlogController::setLogoURL($logoPath);
            if ($logoURLUpdateStatus) {
                // Si la modification a réussi, on stocke un message de succès
                $formSuccess = 'Le logo a bien été modifié !';
            } else {
                // Si la modification a échoué, on affiche un message d'erreur
                $formError = 'Une erreur est survenue lors de la modification du logo !';
            }
        }
    } else {
        // Si aucun logo n'a été uploadé

        // On supprime l'ancien logo
        foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo.*') as $imgFile) {
            // Si c'est un fichier et pas un sous-dossier
            if (is_file($imgFile)) {
                // Supprimer le fichier
                unlink($imgFile);
            }
        }
        // Si le champ d'URL est vide, on remet l'URL par défaut
        if (!isset($_POST['fLogoURL']) || $_POST['fLogoURL'] == "") {
            $_POST['fLogoURL'] = "/view/img/logo.jpg";
        }
        $logoURLUpdateStatus = BlogController::setLogoURL($_POST['fLogoURL']);
        if ($logoURLUpdateStatus) {
            // Si la modification a réussi, on stocke un message de succès
            $formSuccess = 'L\'URL du logo a bien été modifié !';
        } else {
            // Si la modification a échoué, on affiche un message d'erreur
            $formError = 'Une erreur est survenue lors de la modification du logo !';
        }
    }
}

// Si le formulaire de modification de l'image de fond a été soumis
if (isset($_POST['fChangeBgURL'])) {
    // Si l'image de fond a été uploadée
    if (!empty($_FILES) && $_FILES['fBgFile']['error'] != UPLOAD_ERR_NO_FILE) {
        // Erreur éventuelle de l'upload
        $error = $_FILES['fBgFile']['error'];

        if ($_FILES['fBgFile']['error'] != UPLOAD_ERR_OK || !$_FILES['fBgFile']['tmp_name']) {
            // Si une erreur est survenue lors de l'upload, on stocke le message d'erreur à afficher
            $formError = 'Erreur: Le fichier n\'a pas pu être uploadé';
        } elseif (!preg_match("/image\//", $_FILES['fBgFile']['type'])) {
            // Si le fichier n'est pas une image, on stocke le message d'erreur à afficher
            $formError = 'Votre fichier doit être une image ou une vidéo !';
        } elseif ($_FILES['fBgFile']['size'] > 10485760) {
            // Si la taille du fichier est supérieure à 10Mo, on stocke le message d'erreur à afficher
            $formError = 'Le fichier est trop volumineux !';
        } else {
            // On supprime l'ancienne image de fond
            foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'background.*') as $imgFile) {
                // Si c'est un fichier et pas un sous-dossier
                if (is_file($imgFile)) {
                    // Supprimer le fichier
                    unlink($imgFile);
                }
            }
            // Chemin des images de fond
            $bgPath = '' . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR .
                // Nom du fichier . extension
                'background.' . pathinfo($_FILES['fBgFile']['name'], PATHINFO_EXTENSION);
            // Si on a réussi à déplacer le fichier uploadé
            if (
                !move_uploaded_file(
                    // Chemin temporaire du fichier uploadé
                    $_FILES['fBgFile']['tmp_name'],
                    // Destination
                    $_SERVER['DOCUMENT_ROOT'] . $bgPath
                )
            ) {
                // Si une erreur survient, on stocke le message d'erreur à afficher
                $formError = 'Impossible d\'uploader le fichier en raison d\'une erreur côté serveur';
            } else {
                // Si l'upload a réussi, on stocke un message de succès
                $formSuccess = 'L\'image de fond a bien été modifiée !';
            }
        }
        // Si on ne rencontre aucune erreur pendant l'upload
        if (!isset($formError)) {
            // On met à jour l'URL de l'image de fond
            $bgURLUpdateStatus = BlogController::setBackgroundURL($bgPath);
            if ($bgURLUpdateStatus) {
                // Si la modification a réussi, on stocke un message de succès
                $formSuccess = 'L\'image de fond a bien été modifiée !';
            } else {
                // Si la modification a échoué, on affiche un message d'erreur
                $formError = 'Une erreur est survenue lors de la modification de l\'image de fond !';
            }
        }
    } else {
        // Si aucune image de fond n'a été uploadée

        // On supprime l'ancienne image de fond
        foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'background.*') as $imgFile) {
            // Si c'est un fichier et pas un sous-dossier
            if (is_file($imgFile)) {
                // Supprimer le fichier
                unlink($imgFile);
            }
        }
        // Si le champ d'URL est vide, on remet l'URL par défaut
        if (!isset($_POST['fBgURL']) || $_POST['fBgURL'] == "") {
            $_POST['fBgURL'] = "/view/img/circuits.jpg";
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
}