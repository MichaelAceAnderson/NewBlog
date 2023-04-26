<?php

// Si l'utilisateur n'utilise pas ce fichier dans un autre contexte
// que depuis la page index.php, le rediriger à l'accueil
if ($_SERVER['PHP_SELF'] != '/index.php') {
    echo '<meta http-equiv="refresh" content="0; url=/" />';
    header('Location: /');
    exit();
}

// Code s'appuyant sur le modèle et appelé par les formulaires des vues

class PostController
{
    /* MÉTHODES */

    /* Insertions */
    // Ajouter un post 
    public static function createPost(int $authorId, string $title, string $summary, string $tags, string $content): int
    {
        // On tente d'ajouter le post en base de données
        $result = Post::insertPost($authorId, $title, $summary, $tags, $content);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la création du post "' . $title . '" par l\'utilisateur "' . $authorId . '" avec le contenu "' . $content . '", le sommaire "' . $summary . '" et les tags "' . $tags . '" !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On renvoie le succès/l'échec
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer un post
    public static function getPost(int $postId): array|false
    {
        // On tente de récupérer le post en base de données
        $result = Post::selectPost($postId);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la récupération du post "' . $postId . '" !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le résultat de la requête (tableau)
            return $result;
        }
    }
    // Récupérer tous les posts
    public static function getAllPosts(): array|false
    {
        // On tente de récupérer les posts en base de données
        $result = Post::selectPosts();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la récupération des posts !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le résultat de la requête (tableau)
            return $result;
        }
    }
    // Récupérer l'id du prochain post à créer
    public static function getNextPostId(): int|false
    {
        // On tente de récupérer l'id du prochain post à créer en base de données
        $result = Post::selectLastPostId();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la récupération de l\'id du prochain post !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le résultat de la requête (id du prochain post)
            return $result + 1;
        }
    }
    /* Modifications */
    // Changer le pseudo d'un utilisateur
    public static function changeUsername(int $id, string $newNickname): bool
    {
        // On tente de modifier le pseudo en base de données
        $result = User::updateUsername($id, $newNickname);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la modification du pseudo de l\'utilisateur "' . $id . '" !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le succès/l'échec
            return $result;
        }
    }
    // Changer le mot de passe d'un utilisateur
    public static function changeUserPassword($id, $newPassword): bool
    {
        // On tente de modifier le mot de passe en base de données
        $result = User::updateUserPassword($id, $newPassword);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la modification du mot de passe de l\'utilisateur "' . $id . '" en "' . $newPassword . '" !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le succès/l'échec
            return $result;
        }
    }

    /* Suppressions */
    // Réinitialiser les posts
    public static function clearPosts(): int
    {
        // On tente de supprimer tous les posts en base de données et les fichiers associés
        $result = Post::deletePosts();
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la suppression des posts !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On retourne -1 pour indiquer que la requête a échoué
            return -1;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le nombre de lignes supprimées (0 ou plus)
            return $result;
        }
    }
    // Supprimer un post 
    public static function deletePost(int $postId): bool
    {
        // On tente de supprimer le post en base de données
        $result = Post::deletePost($postId);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la suppression du post "' . $postId . '" !');
            // On logge l'erreur
            Controller::printLog(Model::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On retourne le succès/l'échec
            return $result;
        }
    }
}
/* GESTION DES FORMULAIRES */

// Si un formulaire de création de post est soumis
if (isset($_POST['fPost'])) {
    // On vérifie que le titre du post n'est pas vide
    if (!isset($_POST['fPostTitle']) || empty($_POST['fPostTitle'])) {
        // Si le titre du post est vide
        // On affiche un message d'erreur
        Controller::setState(STATE_ERROR, 'Le titre du post est vide');
    } elseif (strlen($_POST['fPostTitle']) > 64) {
        // Si le titre est trop long
        // On affiche un message d'erreur
        Controller::setState(STATE_ERROR, 'Le titre du post ne peut pas dépasser 64 caractères !');
    } elseif (!isset($_POST['fPostSummary']) || empty($_POST['fPostSummary'])) {
        // Si le sommaire du post est vide
        // On affiche un message d'erreur
        Controller::setState(STATE_ERROR, 'Le sommaire du post ne peut pas être vide !');
    } elseif (!isset($_POST['fPostContent']) || empty($_POST['fPostContent'])) {
        // Si le contenu du post est vide
        // On affiche un message d'erreur
        Controller::setState(STATE_ERROR, 'Le contenu du post ne peut pas être vide !');
    } elseif (!isset($_POST['fPostTags']) || empty($_POST['fPostTags'])) {
        // Si les tags du post sont vides
        // On affiche un message d'erreur
        Controller::setState(STATE_ERROR, 'Vous devez spécifier des tags !');
    } else {
        // On récupère l'id du prochain post à créer
        $postId = PostController::getNextPostId();
        // Si une erreur est survenue lors de la récupération de l'id du prochain post
        if (!$postId) {
            // On affiche un message d'erreur
            Controller::setState(STATE_ERROR, 'Une erreur est survenue lors de la communication avec la base de données');
        } else {
            // Si un fichier a été uploadé
            if (!empty($_FILES) && $_FILES['fPostMedia']['error'] != UPLOAD_ERR_NO_FILE) {
                // Erreur éventuelle de l'upload
                $error = $_FILES['fPostMedia']['error'];
                // Si une erreur est survenue lors de l'upload
                if ($_FILES['fPostMedia']['error'] != UPLOAD_ERR_OK || !$_FILES['fPostMedia']['tmp_name']) {
                    // On stocke le message d'erreur à afficher
                    Controller::setState(STATE_ERROR, 'Erreur: Le fichier n\'a pas pu être uploadé');
                } elseif ((!preg_match('/video\//', $_FILES['fPostMedia']['type'])) && !preg_match('/image\//', $_FILES['fPostMedia']['type'])) {
                    // Si le fichier n'est pas une image ou une vidéo
                    // On stocke le message d'erreur à afficher
                    Controller::setState(STATE_ERROR, 'Votre fichier doit être une image ou une vidéo !');
                } elseif ($_FILES['fPostMedia']['size'] > 1000000000) {
                    // Si la taille du fichier est supérieure à 10Mo
                    // On stocke le message d'erreur à afficher
                    Controller::setState(STATE_ERROR, 'Le fichier est trop volumineux !');
                } else {
                    if (preg_match('/image\//', $_FILES['fPostMedia']['type'])) {
                        // Si le fichier est une vidéo

                        // Si le dossier de stockage des images de post n'existe pas
                        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
                            // Créer le dossier de stockage des images de post
                            mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
                        }
                        // On crée le dossier du post partie image
                        mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $postId);
                        // On le place dans le dossier du post partie image
                        $mediaUrl = DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $postId . DIRECTORY_SEPARATOR . $_FILES['fPostMedia']['name'];
                    } elseif (preg_match('/video\//', $_FILES['fPostMedia']['type'])) {
                        // Si le fichier est une vidéo

                        // Si le dossier de stockage des images de post n'existe pas
                        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
                            // Le créer
                            mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR);
                        }
                        // On crée le dossier du post partie vidéo
                        mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $postId);
                        // On le place dans le dossier du post partie vidéo
                        $mediaUrl = DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $postId . DIRECTORY_SEPARATOR . $_FILES['fPostMedia']['name'];
                    }
                    if (!move_uploaded_file($_FILES['fPostMedia']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $mediaUrl)) {
                        Controller::setState(STATE_ERROR, 'Impossible d\'uploader le fichier en raison d\'une erreur côté serveur');
                    }
                }
            }
        }
    }
    // S'il n'y a eu aucune erreur
    if (Controller::getState()['state'] != STATE_ERROR) {
        // On tente d'ajouter le post en base de données
        $postCreation = PostController::createPost($_SESSION['id_user'], $_POST['fPostTitle'], $_POST['fPostSummary'], $_POST['fPostTags'], $_POST['fPostContent']);
        if (!$postCreation) {
            // Si une erreur survient, on stocke le message d'erreur à afficher
            Controller::setState(STATE_ERROR, 'Une erreur est survenue lors de l\'ajout du post');
        } else {
            // Si l'ajout du post s'est bien déroulé, on stocke le message de succès à afficher
            Controller::setState(STATE_SUCCESS, 'Le post a bien été ajouté !');
        }
    }
}

// Si un formulaire de suppression de tous les posts est soumis
if (isset($_POST['fClearPosts'])) {
    // On tente de supprimer tous les posts en base de données
    if (PostController::clearPosts() < 0) {
        // Sinon, on stocke le message d'erreur à afficher
        Controller::setState(STATE_ERROR, 'Une erreur est survenue lors de la suppression des posts');
    } else {
        // Si la suppression des posts s'est bien déroulée, on stocke le message de succès à afficher
        Controller::setState(STATE_SUCCESS, 'Tous les posts ont bien été supprimés !');
    }
}

// Si un formulaire de suppression de tous les posts est soumis
if (isset($_POST['fDeletePostId'])) {
    // On tente de supprimer le post précisé en base de données
    if (!PostController::deletePost($_POST['fDeletePostId'])) {
        // Sinon, on stocke le message d'erreur à afficher
        Controller::setState(STATE_ERROR, 'Une erreur est survenue lors de la suppression du post');
    } else {
        // Si la suppression du post s'est bien déroulée, on stocke le message de succès à afficher
        Controller::setState(STATE_SUCCESS, 'Le post a bien été supprimé !');
    }
}