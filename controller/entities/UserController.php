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
    public static function getUserNameById(int $userId): string | false
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

    // Récupérer l'ID d'un utilisateur à partir de son pseudo et de son mot de passe
    public static function getUserInfoByCredentials(string $username, string $password): Object | false
    {
        $result = User::selectUserByName($username);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } elseif ($result) {
            // Si l'utilisateur existe

            // Check si $password correspond au mot de passe
            if (password_verify($password, $result[0]->password)) {
                // Si oui, renvoyer son id
                return $result[0];
            } else {
                // Si non, renvoyer faux
                return false;
            }
            if ($password == $result[0]->password) {
                // Si oui, renvoyer son id
                return $result[0];
            } else {
                // Si non, renvoyer faux
                return false;
            }
        } else {
            // L'utilisateur n'existe pas
            return false;
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
}
// Si la session n'est pas démarrée, la démarrer
if (!isset($_SESSION)) {
    session_start();
}

// Si l'utilisateur soumet un formulaire de connexion
if (isset($_POST['fLogin'])) {
    // Vérifier que les champs sont remplis
    if (
        isset($_POST['fUserName']) && isset($_POST['fPass'])
        && !empty($_POST['fUserName']) && !empty($_POST['fPass'])
    ) {
        // Tenter de récupérer les informations de l'utilisateur
        $user = UserController::getUserInfoByCredentials($_POST['fUserName'], $_POST['fPass']);
        if (!$user) {
            // Si l'utilisateur n'existe pas ou que le mot de passe n'est pas bon, stocker l'erreur
            $formError = 'Connexion impossible, veuillez vérifier vos identifiants.';
        } else {
            // Si l'utilisateur existe et que le mot de passe est bon, stocker les informations dans la session
            $_SESSION['id_user'] = $user->id_user;
            $_SESSION['nickname'] = $user->nickname;
            $_SESSION['is_mod'] = $user->is_mod;
            // Rediriger vers la page d'accueil
            header('Location: /');
        }
    } else {
        // Si tous les champs ne sont pas remplis, stocker l'erreur
        $formError = 'Veuillez remplir tous les champs.';
    }
}
// Si l'utilisateur se déconnecte
if (isset($_POST['fLogOut'])) {
    // Retirer toutes les variables de la session
    unset($_SESSION);
    // Détruire la session
    session_destroy();
    // Rediriger vers la page d'accueil
    header('Location: /');
}
