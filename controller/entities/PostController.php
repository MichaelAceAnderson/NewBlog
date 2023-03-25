<?php
// Code s'appuyant sur le modèle et appelé par les formulaires des vues

class PostController
{
    /* MÉTHODES */

    /* Insertions */
    // Ajouter un post 
    public static function createPost($authorId, $content): bool
    {
        // On tente d'ajouter le post en base de données
        $result = Post::addPost($authorId, $content);
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
    public static function getPost(int $postId): array | false
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
    public static function getAllPosts(): array | false
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
    public static function changeUsername($id, $newNickname): bool
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
    public static function clearPosts(): bool
    {
        // On tente de supprimer tous les posts en base de données
        $result = Post::clearPosts();
        // Si une erreur est survenue, on l'affiche et on la logge
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            // Sinon, on retourne le résultat de la requête (vrai/faux)
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
        if (PostController::createPost($_SESSION['id_user'], $_POST['fPostContent'])) {
            // Si l'ajout du post s'est bien déroulé, on stocke le message de succès à afficher
            $formSuccess = 'Le post a bien été ajouté !';
        } else {
            // Sinon, on stocke le message d'erreur à afficher
            $formError = 'Une erreur est survenue lors de l\'ajout du post';
        }
    }
}
