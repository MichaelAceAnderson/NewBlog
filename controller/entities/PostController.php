<?php
// Code s'appuyant sur le modèle et appelé par les formulaires des vues

class PostController
{
    /* MÉTHODES */

    /* Insertions */
    // Ajouter un post 
    public static function createPost(int $authorId, string $content, string $mediaUrl = null): bool
    {
        // On tente d'ajouter le post en base de données
        $result = Post::addPost($authorId, $content, $mediaUrl);
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (vrai/faux)
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer un post
    public static function getPost(int $postId): array|false
    {
        // On tente de récupérer le post en base de données
        $result = Post::selectPost($postId);
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (tableau)
            return $result;
        }
    }
    // Récupérer tous les posts
    public static function getAllPosts(): array|false
    {
        // On tente de récupérer les posts en base de données
        $result = Post::selectPosts();
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (tableau)
            return $result;
        }
    }
    /* Modifications */
    // Changer le pseudo d'un utilisateur
    public static function changeUsername(int $id, string $newNickname): bool
    {
        // On tente de modifier le pseudo en base de données
        $result = User::updateUsername($id, $newNickname);
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (vrai/faux)
            return $result;
        }
    }
    // Changer le mot de passe d'un utilisateur
    public static function changeUserPassword($id, $newPassword): bool
    {
        // On tente de modifier le mot de passe en base de données
        $result = User::updateUserPassword($id, $newPassword);
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (vrai/faux)
            return $result;
        }
    }

    /* Suppressions */
    // Réinitialiser les posts
    public static function clearPosts(): int
    {
        // On tente de supprimer tous les posts en base de données et les fichiers associés
        $result = Post::clearPosts();
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return -1; // Retourner -1 pour indiquer que la requête a échouée
        } else {
            // Sinon, on retourne le nombre de lignes supprimées (0 ou plus)
            return $result;
        }
    }
    // Supprimer un post 
    public static function deletePost(int $postId): bool
    {
        // On tente de supprimer le post en base de données
        $result = Post::deletePost($postId);
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (vrai/faux)
            return $result;
        }
    }
}

// Si un formulaire de création de post est soumis
if (isset($_POST['fPost'])) {
    // On vérifie que le contenu du post n'est pas vide
    if (!isset($_POST['fPostContent']) || empty($_POST['fPostContent'])) {
        // Si le contenu du post est vide, on affiche un message d'erreur
        $formError = 'Le contenu du post est vide';
    } else {
        // Sinon, on tente d'ajouter le post en base de données
        // Si un fichier a bien été uploadé
        if (!empty($_FILES) && $_FILES['fPostMedia']['error'] != UPLOAD_ERR_NO_FILE) {
            // Erreur éventuelle de l'upload
            $error = $_FILES['fPostMedia']['error'];

            if ($_FILES['fPostMedia']['error'] != UPLOAD_ERR_OK || !$_FILES['fPostMedia']['tmp_name']) {
                // Si une erreur est survenue lors de l'upload, on stocke le message d'erreur à afficher
                $formError = 'Erreur: Le fichier n\'a pas pu être uploadé';
            } elseif ((!preg_match("/video\//", $_FILES['fPostMedia']['type'])) && !preg_match("/image\//", $_FILES['fPostMedia']['type'])) {
                // Si le fichier n'est pas une image ou une vidéo, on stocke le message d'erreur à afficher
                $formError = 'Votre fichier doit être une image ou une vidéo !';
            } elseif ($_FILES['fPostMedia']['size'] > 1000000000) {
                // Si la taille du fichier est supérieure à 10Mo, on stocke le message d'erreur à afficher
                $formError = 'Le fichier est trop volumineux !';
            } else {
                if (preg_match("/image\//", $_FILES['fPostMedia']['type'])) {
                    $mediaUrl = "/common/files/img/" . $_FILES['fPostMedia']['name'];
                } elseif (preg_match("/video\//", $_FILES['fPostMedia']['type'])) {
                    $mediaUrl = "/common/files/video/" . $_FILES['fPostMedia']['name'];
                }
                if (!move_uploaded_file($_FILES['fPostMedia']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $mediaUrl)) {
                    $formError = 'Impossible d\'uploader le fichier en raison d\'une erreur côté serveur';
                }
            }
            // S'il n'y a aucune erreur liée à l'upload
            if (!isset($formError)) {
                // S'il y a un média à ajouter au post, il sera compris dans la requête
                if (PostController::createPost($_SESSION['id_user'], $_POST['fPostContent'], $mediaUrl)) {
                    // Si l'ajout du post s'est bien déroulé, on stocke le message de succès à afficher
                    $formSuccess = 'Le post a bien été ajouté !';
                } else {
                    // Sinon, on stocke le message d'erreur à afficher
                    $formError = 'Une erreur est survenue lors de l\'ajout du post';
                }
            }
        } else {
            // S'il n'y a aucune erreur liée à l'upload
            if (!isset($formError)) {
                if (PostController::createPost($_SESSION['id_user'], $_POST['fPostContent'])) {
                    // Si l'ajout du post s'est bien déroulé, on stocke le message de succès à afficher
                    $formSuccess = 'Le post a bien été ajouté !';
                } else {
                    // Sinon, on stocke le message d'erreur à afficher
                    $formError = 'Une erreur est survenue lors de l\'ajout du post';
                }
            }
        }
    }
}

// Si un formulaire de suppression de tous les posts est soumis
if (isset($_POST['fClearPosts'])) {
    // On tente de supprimer tous les posts en base de données
    if (PostController::clearPosts() < 0) {
        // Sinon, on stocke le message d'erreur à afficher
        $formError = 'Une erreur est survenue lors de la suppression des posts';
    } else {
        // Si la suppression des posts s'est bien déroulée, on stocke le message de succès à afficher
        $formSuccess = 'Tous les posts ont bien été supprimés !';
    }
}

// Si un formulaire de suppression de tous les posts est soumis
if (isset($_POST['fDeletePostId'])) {
    // On tente de supprimer le post précisé en base de données
    if (!PostController::deletePost($_POST['fDeletePostId'])) {
        // Sinon, on stocke le message d'erreur à afficher
        $formError = 'Une erreur est survenue lors de la suppression du post';
    } else {
        // Si la suppression du post s'est bien déroulée, on stocke le message de succès à afficher
        $formSuccess = 'Le post a bien été supprimé !';
    }
}