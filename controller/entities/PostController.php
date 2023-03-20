<?php
// Code s'appuyant sur le modèle et appelé par les formulaires des vues
// To-do:
// Récupérer les données postées par formulaire par les vues

class PostController
{
    /* MÉTHODES */

    /* Insertions */
    // Ajouter un post 
    public static function createPost($authorId, $content): bool
    {
        $result = Post::addPost($authorId, $content);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer un post
    public static function getPost(int $postId): array | false
    {
        $result = Post::selectPost($postId);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Récupérer tous les posts
    public static function getAllPosts(): array | false
    {
        $result = Post::selectPosts();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    /* Modifications */
    // Changer le pseudo d'un utilisateur
    public static function changeUsername($id, $newNickname): bool
    {
        $result = User::updateUsername($id, $newNickname);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Changer le mot de passe d'un utilisateur
    public static function changeUserPassword($id, $newPassword): bool
    {
        $result = User::updateUserPassword($id, $newPassword);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }

    /* Suppressions */
    // Réinitialiser les posts
    public static function clearPosts(): bool
    {
        $result = Post::clearPosts();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Supprimer un post 
    public static function deletePost(int $postId): bool
    {
        $result = Post::deletePost($postId);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
}
