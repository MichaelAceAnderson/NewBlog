<?php
// Code à inclure dans le contrôleur

declare(strict_types=1);

// Si l'utilisateur n'est pas sur index.php, le rediriger à l'accueil
if ($_SERVER['PHP_SELF'] != '/index.php') {
    header('Location: /');
    exit();
}

// Si la session du client n'est pas démarrée, la démarrer
if (!isset($_SESSION)) {
    session_start();
}


// Hôte de la base de données
define('DB_HOST', 'localhost');
// Nom de la base à utiliser
define('DB_NAME', 'newblog');
// Nom de l'utilisateur de BDD
define('DB_USER', 'postgres');
// Nom de l'utilisateur de BDD
define('DB_PASS', 'PG770rwx');
// 0: Aucune erreur affichée/loggée, 
// 1: Erreurs destinées à l'utilisateur, 
// 2: Provenance directe des erreurs (développeurs), 
// 3: Retraçace complet de la provenance (développeurs)
define('LOGLEVEL', 3);
// Constante pour le type d'affichage des erreurs
define('RAW', 1);
define('HTML', 2);

// Définir les constantes d'état
define('STATE_NONE', -1);
define('STATE_SUCCESS', 1);
define('STATE_ERROR', 0);
class Model
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    // Connexion à la base de données
    private static ?PDO $pdo = null;
    // Requête à traiter
    private static mixed $stmt = null;

    /* MÉTHODES */

    /* Setters */
    // Définir la requête à traiter
    public static function setStmt(mixed $query): void
    {
        self::$stmt = $query;
    }
    // Définir la connexion à utiliser
    public static function setPdo(PDO|null $pdo): void
    {
        self::$pdo = $pdo;
    }

    /* Getters */
    // Récupérer la requête à traiter
    public static function getStmt(): mixed
    {
        return self::$stmt;
    }
    // Récupérer la connexion à utiliser
    public static function getPdo(): ?PDO
    {
        return self::$pdo;
    }

    /* AUTRES MÉTHODES */
    // Formater l'erreur d'une exception
    public static function getError(Exception $error, int $mode = RAW): string
    {
        $errorMsg = '';
        if (LOGLEVEL >= 1) {
            $errorMsg = 'Erreur: ' . $error->getMessage();
        }
        if (LOGLEVEL >= 2) {
            $errorMsg .= '<br>Provenance de l\'erreur: ' . $error->getFile() . ':' . $error->getLine();
        }
        if (LOGLEVEL >= 3) {
            $errorMsg .= '<br>Trace d\'erreur (string): ' . $error->getTraceAsString();
            $errorMsg .= '<br>Code d\'erreur: ' . $error->getCode();
        }
        if ($mode == RAW) {
            // Définition des regex pour le formatage
            $formatting = array(array('/\<br\>|\<br\/\>/', '/\<b\>|\<\/b\>/'), array(PHP_EOL, ''));
            // Formater le message d'erreur pour remplacer les sauts de ligne bruts par des sauts de ligne HTML
            $errorMsg = preg_replace($formatting[0], $formatting[1], $errorMsg);
        }

        return $errorMsg;
    }
    // Écrire dans un fichier log
    public static function printLog(string $msg): bool
    {
        $date = new DateTime();
        $date = $date->format('d-m-y h:i:s');
        if (LOGLEVEL < 1) {
            // Si le niveau de log est inférieur à 1, on ne logge pas
            return false;
        }
        $logFile = fopen(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'model.log', 'a+');
        if (!$logFile) {
            // S'il est impossible d'ouvrir le fichier de log
            return false;
        }
        if (!fwrite($logFile, PHP_EOL . '[' . $date . '] Modèle: ' . $msg)) {
            // S'il est impossible d'écrire dans le fichier de log
            return false;
        }
        if (!fclose($logFile)) {
            // S'il est impossible de fermer le fichier de log
            return false;
        }
        return true;
    }
    public static function rmdir_r(string $path): bool
    {
        if (!is_dir($path)) {
            // Si ce n'est pas un dossier
            // On logge l'erreur
            Model::printLog('Impossible de supprimer l\'élément ' . $path . ' car ce n\'est pas un dossier');
            // On renvoie un échec
            return false;
        } else {
            // S'il s'agit d'un dossier
            // On scanne le contenu du dossier
            $objects = scandir($path);
            // Pour chaque élément du dossier
            foreach ($objects as $object) {
                // S'il ne s'agit ni du dossier courant, ni du dossier parent
                if ($object != '.' && $object != '..') {
                    // Si l'élément est un dossier
                    if (is_dir($path . DIRECTORY_SEPARATOR . $object) && !is_link($path . DIRECTORY_SEPARATOR . $object)) {
                        // On relance la fonction sur ce sous-dossier
                        self::rmdir_r($path . DIRECTORY_SEPARATOR . $object);
                    } else {
                        // S'il s'agit d'un fichier, on supprime l'élément
                        if (!unlink($path . DIRECTORY_SEPARATOR . $object)) {
                            // S'il est impossible de supprimer l'élément
                            // On logge l'erreur
                            Model::printLog('Impossible de supprimer l\'élément ' . $path . DIRECTORY_SEPARATOR . $object);
                            return false;
                        }
                    }
                }
            }
            if (!rmdir($path)) {
                // S'il est impossible de supprimer le dossier
                // On logge l'erreur
                Model::printLog('Impossible de supprimer le dossier ' . $path);
                return false;
            }
        }
        // On renvoie un succès
        return true;
    }
}

if (!extension_loaded('PDO')) {
    Model::printLog('L\'extension PDO n\'est pas installée sur le serveur PHP !');
    Controller::setState(STATE_ERROR, 'L\'extension PDO n\'est pas installée sur le serveur PHP !');
} elseif (!extension_loaded('pdo_pgsql')) {
    Model::printLog('L\'extension pdo_pgsql pour PostGreSQL n\'est pas installée sur le serveur PHP !');
    Controller::setState(STATE_ERROR, 'L\'extension pdo_pgsql pour PostGreSQL n\'est pas installée sur le serveur PHP !');
} else {
    // Tenter de créer une connexion à la base de données
    try {
        // Utilisation de PDO (PHP Data Objects) pour se connecter à la base de données
        // PDO a l'avantage d'être compatible avec plusieurs SGBD (MySQL, PostgreSQL, SQLite, etc.)
        Model::setPdo(
            new \PDO(
                'pgsql:host=' . DB_HOST . ';port=5432;dbname=' . DB_NAME . '',
                DB_USER,
                DB_PASS,
                [
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_TIMEOUT => 2,
                    // \PDO::ATTR_EMULATE_PREPARES => true,
                    // \PDO::ATTR_PERSISTENT => true,
                    // \PDO::ATTR_STRINGIFY_FETCHES => true,
                ]
            )
        );
    } catch (PDOException $e) {
        // On logge l'erreur
        Model::printLog(Model::getError($e));
        // Si une erreur survient, on détruit la connexion
        Model::setPdo(null);
    }
    // Si la connexion est établie, on logge le message
    if (Model::getPdo() != null) {
        Model::printLog('Connexion à la base de données réussie');
    }
}
// Inclusion de tous les modèles dans le dossier entities
foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . '*.php') as $filename) {
    if (!include_once $filename) {
        Model::printLog('Impossible d\'inclure le fichier ' . $filename);
    }
}