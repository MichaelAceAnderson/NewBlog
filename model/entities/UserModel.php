<?php

class User
{
    /* MÉTHODES */

    /* Insertions */
    // Création d'un utilisateur en BDD
    public static function addUser(string $nickname, string $password, bool $is_mod): bool|Exception
    {
        // Tenter d'ajouter un utilisateur
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera rattrapée plus bas
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    'INSERT INTO newblog.nb_user (nickname, password, is_mod) VALUES (:nickname, :password, :is_mod)'
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête d\'ajout d\'un utilisateur n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher le pseudo en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('nickname', $nickname, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Le pseudo "' . $nickname . '" n\'a pas pu être attaché à la requête de changement de pseudo !');
                }
                // Hasher le mot de passe
                $password = password_hash($password, PASSWORD_ARGON2ID);
                if (!$password) {
                    // Si le hash n'a pas pu être créé
                    throw new Exception('Impossible de créer un hash pour le mot de passe !');
                }
                // Attacher le mot de passe en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('password', $password, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Le mot de passe "' . $password . '" n\'a pas pu être attaché à la requête de changement de pseudo !');
                }
                // Attacher le booléen administrateur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('is_mod', $is_mod, PDO::PARAM_BOOL)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Le booléen administrateur (' . $is_mod ? 'true' : 'false' . ')n\'a pas pu être attaché à la requête de changement de pseudo !');
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si une erreur survient lors de l'exécution de la requête
                    throw new Exception('Une erreur est survenue lors de l\'exécution de la requête de changement de pseudo');
                } else {
                    // Si insertion effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si insertion pas effectuée
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('L\'insertion de l\'utilisateur n\'a pas pu être effectuée !');
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }

    /* Récupérations */
    // Récupérer le tableau des utilisateurs
    public static function selectUsers(): array|Exception
    {
        // Tenter de récupérer les utilisateurs
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    'SELECT * FROM newblog.nb_user'
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de récupération des utilisateurs n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception('Une erreur est survenue lors de l\'exécution de la requête de récupération des utilisateurs !');
                } else {
                    // Récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                    // Si la récupération des résultats a échoué
                    if ($result === false) {
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('La récupération des utilisateurs a échoué !');
                    } else {
                        // Si la récupération des résultats a réussi
                        // On renvoie les résultats
                        return $result;
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Récupérer la ligne d'un utilisateur à partir de son id
    public static function selectUserById(int $id): array|Exception
    {
        // Tenter de récupérer l'utilisateur à partir de son id
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera rattrapée plus bas
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    'SELECT * FROM newblog.nb_user WHERE newblog.nb_user.id_user = :id'
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de récupération des données de l\'utilisateur n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);

                // Attacher l'id utilisateur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('L\'id utilisateur "' . $id . '" n\'a pas pu être attaché à la requête de récupération des données de l\'utilisateur !');
                }

                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception('La requête de récupération des données du blog a échoué !');
                } else {
                    // Si la requête a réussi
                    // Récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                    // Si la récupération des résultats a échoué
                    if ($result === false) {
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('La récupération des données de l\'utilisateur a échoué !');
                    } else {
                        // Si la récupération des résultats a réussi
                        // On renvoie les résultats
                        return $result;
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Récupérer la ligne d'un utilisateur à partir de son pseudo
    public static function selectUserByName(string $nickname): array|Exception
    {
        // Tenter de récupérer l'utilisateur à partir de son pseudo
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera rattrapée plus bas
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    "SELECT *
                    FROM newblog.nb_user 
                    WHERE newblog.nb_user.nickname = :nickname"
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de récupération des données de l\'utilisateur n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher le pseudo utilisateur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('nickname', $nickname, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Le pseudo utilisateur "' . $nickname . '" n\'a pas pu être attaché à la requête de récupération des données de l\'utilisateur !');
                }
                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de récupération des données du blog a échoué !');
                } else {
                    // Si la requête a réussi
                    // Récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                    // Si la récupération des résultats a échoué
                    if ($result === false) {
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('La récupération des données de l\'utilisateur a échoué !');
                    } else {
                        // Si la récupération des résultats a réussi
                        // On renvoie les résultats
                        return $result;
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }

    /* Modifications */
    // Changer le pseudo d'un utilisateur
    public static function updateUserName(int $id, string $newNickname): bool|Exception
    {
        // Tenter de modifier le pseudo d'un utilisateur
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera rattrapée plus bas
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    'UPDATE newblog.nb_user SET nickname = :newNickname WHERE newblog.nb_user.id_user = :id;'
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de modification du pseudo de l\'utilisateur n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher l'id utilisateur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('L\'id utilisateur "' . $id . '" n\'a pas pu être attaché à la requête de modification du pseudo de l\'utilisateur !');
                }
                // Attacher le nouveau pseudo en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('newNickname', $newNickname, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Le nouveau pseudo "' . $newNickname . '" n\'a pas pu être attaché à la requête de modification du pseudo de l\'utilisateur !');
                }
                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Une erreur est survenue lors de l\'exécution de la requête de modification du pseudo de l\'utilisateur !');
                } else {
                    // Si mise à jour effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si mise à jour pas effectuée
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('La mise à jour du pseudo de l\'utilisateur n\'a pas pu être effectuée !');
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
    // Changer le mot de passe d'un utilisateur
    public static function updateUserPassword(int $id, string $newPassword): bool|Exception
    {
        // Tenter de modifier le mot de passe d'un utilisateur
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera rattrapée plus bas
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    'UPDATE newblog.nb_user SET password = :newPassword WHERE newblog.nb_user.id_user = :id;'
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de modification du mot de passe de l\'utilisateur n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher l'id utilisateur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('L\'id utilisateur "' . $id . '" n\'a pas pu être attaché à la requête de modification du mot de passe de l\'utilisateur !');
                }
                // Hasher le mot de passe
                $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                if (!$newPassword) {
                    // Si le hash n'a pas pu être créé
                    throw new Exception('Impossible de créer un hash pour le mot de passe !');
                }
                // Attacher le nouveau mot de passe en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('newPassword', $newPassword, PDO::PARAM_STR)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Le nouveau mot de passe "' . $newPassword . '" n\'a pas pu être attaché à la requête de modification du mot de passe de l\'utilisateur !');
                }
                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Une erreur est survenue lors de l\'exécution de la requête de modification du mot de passe de l\'utilisateur !');
                } else {
                    // Si mise à jour effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si mise à jour pas effectuée
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('La mise à jour du mot de passe de l\'utilisateur n\'a pas pu être effectuée !');
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }

    /* Supressions */
    // Supprimer un utilisateur
    public static function deleteUser(int $id): bool|Exception
    {
        // Tenter de supprimer un utilisateur
        try {
            // Si la connexion n'a pas pu être créée
            if (is_null(Model::getPdo())) {
                // On lance une erreur qui sera rattrapée plus bas
                throw new Exception('La connexion avec la base de données n\'a pas pu être établie !');
            } else {
                // Si la connexion à réussi

                // Préparer la requête
                $stmt = Model::getPdo()->prepare(
                    'DELETE FROM newblog.nb_user WHERE newblog.nb_user.id_user = :id;'
                );
                // Si la requête n'a pas pu être préparée
                if (!$stmt) {
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('La requête de suppression de l\'utilisateur n\'a pas pu être préparée !');
                }
                // Définir la requête à traiter
                Model::setStmt($stmt);
                // Attacher l'id utilisateur en paramètre à la requête préparée
                if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
                    // Si le paramètre n'a pas pu être attaché
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('L\'id utilisateur "' . $id . '" n\'a pas pu être attaché à la requête de suppression de l\'utilisateur !');
                }
                // Exécuter la requête
                if (Model::getStmt()->execute() === false) {
                    // Si la requête n'a pas pu être exécutée
                    // On lance une erreur qui sera rattrapée plus bas
                    throw new Exception('Une erreur est survenue');
                } else {
                    // Si suppression effectuée
                    if (Model::getStmt()->rowCount() > 0) {
                        // On renvoie un succès
                        return true;
                    } else {
                        // Si suppression pas effectuée
                        // On lance une erreur qui sera rattrapée plus bas
                        throw new Exception('La suppression de l\'utilisateur n\'a pas pu être effectuée !');
                    }
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue
            // On logge l'erreur
            Model::printLog(Model::getError($e));
            // On renvoie l'erreur
            return $e;
        }
    }
}