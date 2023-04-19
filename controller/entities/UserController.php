<?php
class UserController
{
    /* MÉTHODES */

    /* Insertions */
    // Ajouter un utilisateur
    public static function createUser(string $nickname, string $password, bool $is_mod = false): bool
    {
        // On tente d'ajouter l'utilisateur en base de données
        $result = User::insertUser($nickname, $password, $is_mod);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de création de l\'utilisateur "' . $nickname . '" avec le mot de passe "' . $password . '" (admin: "' . $is_mod ? 'true' : 'false' . '") !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On renvoie le succès/l'échec de l'opération
            return $result;
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
            $result = new Exception('Une erreur est survenue lors du changement de pseudo de l\'utilisateur "' . $id . '" en $newNickname !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On renvoie le succès/l'échec de l'opération
            return $result;
        }
    }
    // Changer le mot de passe d'un utilisateur
    public static function changeUserPassword(int $id, string $newPassword): bool
    {
        // On tente de modifier le mot de passe en base de données
        $result = User::updateUserPassword($id, $newPassword);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors du changement de mot de passe de l\'utilisateur "' . $id . '" en "' . $newPassword . '" !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'opération a été effectuée en BDD
            // On renvoie le succès/l'échec de l'opération
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer tous les utilisateurs
    public static function getAllUsers(): array|false
    {
        // On tente de récupérer les utilisateurs en base de données
        $result = User::selectUsers();
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la récupération des utilisateurs !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // S'il n'y a au moins un utilisateur
            if (count($result) > 0) {
                // On renvoie le tableau d'utilisateurs
                return $result;
            } else {
                // Sinon, on renvoie faux
                // On renvoie un échec
                return false;
            }
        }
    }
    // Récupérer le pseudo d'un utilisateur
    public static function getUserNameById(int $userId): string|false
    {
        // On tente de récupérer l'utilisateur en base de données à partir de son id
        $result = User::selectUserById($userId);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la récupération du pseudo de l\'utilisateur "' . $userId . '" !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // S'il n'y a au moins un utilisateur
            if (count($result) > 0) {
                // On renvoie le pseudo de l'utilisateur
                return $result[0]->nickname;
            } else {
                // Si aucun utilisateur n'a été trouvé
                // On renvoie un échec
                return false;
            }
        }
    }

    // Récupérer l'ID d'un utilisateur à partir de son pseudo et de son mot de passe
    public static function getUserInfoByCredentials(string $username, string $password): object|false
    {
        // On tente de récupérer l'utilisateur en base de données à partir de son pseudo
        $result = User::selectUserByName($username);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la récupération des informations de l\'utilisateur "' . $username . '" avec le mot de passe "' . $password . '" !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } elseif ($result) {
            // Si l'utilisateur existe

            // Check si $password correspond au mot de passe
            if (password_verify($password, $result[0]->password)) {
                // Si les mots de passes correspondent
                // Renvoyer l'objet utilisateur
                return $result[0];
            } else {
                // Si le mot de passe ne correspond pas
                // On renvoie un échec
                return false;
            }
        } else {
            // L'utilisateur n'existe pas
            // On renvoie un échec
            return false;
        }
    }

    /* Suppressions */
    // Supprimer un utilisateur 
    public static function deleteUser(int $userId): bool
    {
        // On tente de supprimer l'utilisateur en base de données à partir de son id
        $result = User::deleteUser($userId);
        // Si une erreur est survenue lors de l'appel du modèle
        if ($result instanceof Exception) {
            // On définit l'erreur du contrôleur
            $result = new Exception('Une erreur est survenue lors de la suppression de l\'utilisateur "' . $userId . '" !');
            // On logge l'erreur
            Controller::printLog(Controller::getError($result));
            // On renvoie un échec
            return false;
        } else {
            // Si l'utilisateur a bien été supprimé
            // On renvoie un succès
            return true;
        }
    }
}
/* GESTION DES FORMULAIRES */

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
            Controller::setState(STATE_ERROR, 'Connexion impossible, veuillez vérifier vos identifiants.');
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
        Controller::setState(STATE_ERROR, 'Veuillez remplir tous les champs.');
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

// Si l'utilisateur soumet un formulaire de changement de pseudo
if (isset($_POST['fChangeUserName'])) {
    if (!isset($_POST['fUserName']) || empty($_POST['fUserName'])) {
        // Si l'utilisateur n'a pas rempli son nom d'utilisateur actuel, stocker l'erreur à afficher dans la vue
        Controller::setState(STATE_ERROR, 'Veuillez d\'abord renseigner votre nom d\'utilisateur actuel !');
    } else {
        if ($_POST['fUserName'] != $_SESSION['nickname']) {
            // Si le pseudo actuel ne correspond pas à celui de la session, stocker l'erreur à afficher dans la vue
            Controller::setState(STATE_ERROR, 'Le nom d\'utilisateur actuel que vous avez renseigné ne correspond pas à celui de votre compte !');
        } else {
            if (
                !isset($_POST['fNewUserName']) || empty($_POST['fNewUserName'])
                || !isset($_POST['fNewUserNameConfirm']) || empty($_POST['fNewUserNameConfirm'])
            ) {
                // Si l'utilisateur n'a pas rempli tous les champs requis, stocker l'erreur à afficher dans la vue
                Controller::setState(STATE_ERROR, 'Veuillez saisir votre nouveau nom d\'utilisateur et le confirmer en-dessous.');
            } else {
                if ($_POST['fNewUserName'] != $_POST['fNewUserNameConfirm']) {
                    // Si les deux champs de nouveau pseudo ne correspondent pas, stocker l'erreur à afficher dans la vue
                    Controller::setState(STATE_ERROR, 'Les deux noms d\'utilisateur ne correspondent pas.');
                }
                // S'il n'y a pas d'erreur, tenter de changer le nom d'utilisateur
                if (Controller::getState()['state'] != STATE_ERROR) {
                    // Tenter de changer le nom de l'utilisateur
                    $result = UserController::changeUsername($_SESSION['id_user'], $_POST['fNewUserName']);
                    if ($result) {
                        $_SESSION['nickname'] = $_POST['fNewUserName'];
                        // Si le nom d'utilisateur a bien été changé, afficher le message de succès
                        Controller::setState(STATE_SUCCESS, 'Votre nom d\'utilisateur a bien été changé !');
                    } else {
                        Controller::setState(STATE_ERROR, 'Une erreur est survenue, veuillez réessayer.');
                    }
                }
            }
        }
    }
}

// Si l'utilisateur soumet un formulaire de changement de mot de passe
if (isset($_POST['fChangePassword'])) {
    if (!isset($_POST['fPass']) || empty($_POST['fPass'])) {
        // Si l'utilisateur n'a pas rempli son mot de passe actuel, stocker l'erreur à afficher dans la vue
        Controller::setState(STATE_ERROR, 'Veuillez d\'abord renseigner votre mot de passe actuel !');
    } else {
        // Si l'utilisateur a rempli son mot de passe actuel, vérifier qu'il est correct
        if (!UserController::getUserInfoByCredentials($_SESSION['nickname'], $_POST['fPass'])) {
            // Si le mot de passe actuel ne correspond pas à celui de la session, stocker l'erreur à afficher dans la vue
            Controller::setState(STATE_ERROR, 'Le mot de passe actuel que vous avez renseigné est incorrect !');
        } else {
            // Si l'utilisateur a rempli tous les champs requis, vérifier que les deux champs de nouveau mot de passe correspondent
            if (
                !isset($_POST['fNewPass']) || empty($_POST['fNewPass'])
                || !isset($_POST['fNewPassConfirm']) || empty($_POST['fNewPassConfirm'])
            ) {
                // Si l'utilisateur n'a pas rempli tous les champs requis, stocker l'erreur à afficher dans la vue
                Controller::setState(STATE_ERROR, 'Veuillez saisir votre mot de passe actuel, votre nouveau mot de passe et le confirmer en-dessous.');
            } else {
                if ($_POST['fNewPass'] != $_POST['fNewPassConfirm']) {
                    // Si les deux champs de nouveau mot de passe ne correspondent pas, stocker l'erreur à afficher dans la vue
                    Controller::setState(STATE_ERROR, 'La confirmation de mot de passe ne correspond pas avec votre nouveau mot de passe.');
                }
                // S'il n'y a pas d'erreur, tenter de changer le mot de passe
                if (Controller::getState()['state'] != STATE_ERROR) {
                    // Tenter de changer le mot de passe de l'utilisateur
                    $passChanged = UserController::changeUserPassword($_SESSION['id_user'], $_POST['fNewPass']);
                    if (!$passChanged) {
                        // Si le mot de passe n'a pas pu être changé, stocker l'erreur à afficher dans la vue
                        Controller::setState(STATE_ERROR, 'Une erreur est survenue lors du changement de votre mot de passe, veuillez réessayer.');
                    } else {
                        // Si le mot de passe a bien été changé, afficher le message de succès
                        Controller::setState(STATE_SUCCESS, 'Votre mot de passe a bien été changé !');
                    }
                }
            }
        }
    }
}