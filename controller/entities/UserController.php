<?php
class UserController
{
    /* MÉTHODES */

    /* Insertions */
    // Ajouter un utilisateur
    public static function createUser(string $nickname, string $password, bool $is_mod = false): bool
    {
        $result = User::addUser($nickname, $password, $is_mod);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }

    /* Modifications */
    // Changer le pseudo d'un utilisateur
    public static function changeUsername(int $id, string $newNickname): bool
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
    public static function changeUserPassword(int $id, string $newPassword): bool
    {
        $result = User::updateUserPassword($id, $newPassword);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer tous les utilisateurs
    public static function getAllUsers(): array|false
    {
        $result = User::selectUsers();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            if (count($result) > 0) {
                return $result;
            } else {
                return false;
            }
        }
    }
    // Récupérer le pseudo d'un utilisateur
    public static function getUsername(int $userId): string | false
    {
        $result = User::selectUserById($userId);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            if (count($result) > 0) {
                return $result[0]->nickname;
            } else {
                return false;
            }
        }
    }

    /* Suppressions */
    // Supprimer un utilisateur 
    public static function deleteUser(int $userId): bool
    {
        $result = User::deleteUser($userId);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return true;
        }
    }

    /* Autres */
    // Connecter un utilisateur
    public static function connectUser(string $username, string $password): int | false
    {
        $result = User::selectUserByName($username);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } elseif ($result) {
            // Si l'utilisateur existe

            // Check si $password correspond au mot de passe
            if ($password == $result[0]->password) {
                // Si oui, renvoyer son id
                return $result[0]->id_user;
            } else {
                // Si non, renvoyer faux
                return false;
            }
        } else {
            // L'utilisateur n'existe pas
            return false;
        }
    }
}
if (isset($_POST['vLogin'])) {
    if (isset($_POST['fUserName']) && isset($_POST['fPass'])) {
        $user = UserController::connectUser($_POST['fUserName'], $_POST['fPass']);
        if ($user) {
            $_SESSION['id_user'] = $user;
            header('Location: /');
        } else {
            // Cet utilisateur n'existe pas
        }
    } else {
        // Il faut préciser un identifiant et un mot de passe
    }
}
if (isset($_POST['logout'])) {
    unset($_SESSION);
    session_destroy();
    header('Location: /');
}
