<?php

class User
{
    /* MÉTHODES */

    /* Insertions */
    // Création d'un utilisateur en BDD
    public static function addUser(string $nickname, string $password, bool $is_mod): bool|Exception
    {
        // Résultat initial = échec
        $result = false;
        try {
            // Initialiser la connexion
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "INSERT INTO newblog.nb_user(nickname, password, is_mod) VALUES(:nickname, :password, :is_mod);"
                    )
                );
                // Paramétrer le pseudo
                Model::getStmt()->bindParam('nickname', $nickname, PDO::PARAM_STR);
                // Hasher le mot de passe
                $password = password_hash($password, PASSWORD_ARGON2ID);
                if (!$password) {
                    // Si le hash n'a pas pu être créé
                    throw new Exception("Impossible de créer un hash pour le mot de passe !");
                }
                // Paramétrer le mot de passe
                Model::getStmt()->bindParam('password', $password, PDO::PARAM_STR);
                Model::getStmt()->bindParam('is_mod', $is_mod, PDO::PARAM_BOOL);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si une erreur survient lors de l'exécution de la requête
                    throw new Exception("Une erreur est survenue");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si insertion effectuée
                        $result = true;
                    } else {
                        // Si insertion pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }

    /* Récupérations */
    // Récupérer le tableau des utilisateurs
    public static function selectUsers(): array|Exception
    {
        // Résultat initial = échec
        $result = [];
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "SELECT * FROM newblog.nb_user"
                    )
                );
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Récupérer la ligne d'un utilisateur à partir de son id
    public static function selectUserById(int $id): array|Exception
    {
        // Résultat initial = échec
        $result = [];
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "SELECT *
                    FROM newblog.nb_user 
                    WHERE newblog.nb_user.id_user = :id"
                    )
                );
                Model::getStmt()->bindParam('id', $id, PDO::PARAM_STR);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        // Renvoyer le résultat
        return $result;
    }
    // Récupérer la ligne d'un utilisateur à partir de son pseudo
    public static function selectUserByName(string $nickname): array|Exception
    {
        // Résultat initial = échec
        $result = [];
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "SELECT *
                    FROM newblog.nb_user 
                    WHERE newblog.nb_user.nickname = :nickname"
                    )
                );
                Model::getStmt()->bindParam('nickname', $nickname, PDO::PARAM_STR);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    // Si la requête n'a pas pu être exécutée
                    throw new Exception("La requête de récupération des données du blog a échoué !");
                } else {
                    // Si la requête a réussi, récupérer les résultats
                    $result = Model::getStmt()->fetchAll();
                }
            }
        } catch (Exception $e) {
            // Si une erreur est survenue, la stocker dans le résultat
            $result = $e;
        }
        // Renvoyer le résultat
        return $result;
    }

    /* Modifications */
    // Changer le pseudo d'un utilisateur
    public static function updateUserName(int $id, string $newNickname): bool|Exception
    {
        // Résultat initial = échec
        $result = [];
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "UPDATE newblog.nb_user SET nickname = :newNickname WHERE newblog.nb_user.id_user = :id;"
                    )
                );
                Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT);
                Model::getStmt()->bindParam('newNickname', $newNickname, PDO::PARAM_STR);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    throw new Exception("Une erreur est survenue");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
    // Changer le mot de passe d'un utilisateur
    public static function updateUserPassword(int $id, string $newPassword): bool|Exception
    {
        // Résultat initial = échec
        $result = false;
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "UPDATE newblog.nb_user SET password = :newPassword WHERE newblog.nb_user.id_user = :id;"
                    )
                );
                Model::getStmt()->bindParam(
                    'id',
                    $id,
                    PDO::PARAM_INT
                );
                // Hasher le mot de passe
                $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                if (!$newPassword) {
                    // Si le hash n'a pas pu être créé
                    throw new Exception("Impossible de créer un hash pour le mot de passe !");
                }
                Model::getStmt()->bindParam('newPassword', $newPassword, PDO::PARAM_STR);
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    throw new Exception("Une erreur est survenue");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si mise à jour effectuée
                        $result = true;
                    } else {
                        // Si mise à jour pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }

    /* Supressions */
    // Supprimer un utilisateur
    public static function deleteUser(int $id): bool|Exception
    {
        // Résultat initial = échec
        $result = false;
        try {
            if (is_null(Model::getPdo())) {
                // Si la connexion n'a pas pu être créée
                throw new Exception("La connexion avec la base de données n'a pas pu être établie !");
            } else {
                // Si la connexion à réussi
                // Préparer la requête
                Model::setStmt(
                    Model::getPdo()->prepare(
                        "DELETE FROM newblog.nb_user WHERE newblog.nb_user.id_user = :id;"
                    )
                );
                Model::getStmt()->bindParam(
                    'id',
                    $id,
                    PDO::PARAM_INT
                );
                // Exécuter la requête
                if (!Model::getStmt()->execute()) {
                    throw new Exception("Une erreur est survenue");
                } else {
                    if (Model::getStmt()->rowCount() > 0) {
                        // Si suppression effectuée
                        $result = true;
                    } else {
                        // Si suppression pas effectuée
                        $result = false;
                    }
                }
            }
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
}