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
define("DB_HOST", "localhost");
// Nom de la base à utiliser
define("DB_NAME", "newblog");
// 0: Aucune erreur affichée/loggée, 
// 1: Erreurs destinées à l'utilisateur, 
// 2: Provenance directe des erreurs (développeurs), 
// 3: Retraçace complet de la provenance (développeurs)
define("LOGLEVEL", 3);
// Constante pour le type d'affichage des erreurs
define("RAW", 1);
define("HTML", 2);
class Model
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    // Connexion à la base de données
    private ?PDO $pdo = null;
    // Requête à traiter
    private mixed $stmt = null;

    /* CONSTRUCTEUR */
    function __construct(string $role)
    {
        // Par défaut, le mot de passe est erronné
        $pass = null;
        // Selon le nom de l'utilisateur ($role), on définit le mot de passe à utiliser
        switch (strtolower($role)) {
            case "postgres": {
                    $pass = "PG770rwx";
                    break;
                }
            case "nb_reader": {
                    $pass = "PGlr4--";
                    break;
                }
            case "nb_writer": {
                    $pass = "PGlw2--";
                    break;
                }
            case "nb_editor": {
                    $pass = "PGlrw6--";
                    break;
                }
            default: {
                    // Si l'utilisateur spécifié n'existe pas en base de connées, on détruit la connexion
                    $this->pdo = null;
                    self::printLog('Connexion à la base de données impossible avec l\'utilisateur spécifié (' . $role . ')');
                    throw new Exception("L'utilisateur spécifié n'existe pas en base de données");
                }
        }
        try {
            // Utilisation de PDO (PHP Data Objects) pour se connecter à la base de données
            // PDO a l'avantage d'être compatible avec plusieurs SGBD (MySQL, PostgreSQL, SQLite, etc.)
            $this->pdo = new \PDO(
                'pgsql:host=' . DB_HOST . ';port=5432;dbname=' . DB_NAME . '',
                $role,
                $pass,
                [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_TIMEOUT => 3,
                    // \PDO::ATTR_EMULATE_PREPARES => true,
                    // \PDO::ATTR_PERSISTENT => true,
                    // \PDO::ATTR_STRINGIFY_FETCHES => true,
                ]
            );
        } catch (PDOException $e) {
            // Si une erreur survient, on détruit la connexion
            $this->pdo = null;
        }
        // Si la connexion est établie, on logge le message
        if ($this->pdo != null) {
            self::printLog("Connexion à la base de données réussie");
        }
    }

    /* MÉTHODES */

    /* Setters */
    // Définir la requête à traiter
    public function setStmt(mixed $query): void
    {
        $this->stmt = $query;
    }
    // Définir la connexion à utiliser
    public function setPdo(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /* Getters */
    // Récupérer la requête à traiter
    public function getStmt(): mixed
    {
        return $this->stmt;
    }
    // Récupérer la connexion à utiliser
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /* AUTRES MÉTHODES */
    public static function getError(Exception $error, int $mode = RAW)
    {
        $errorMsg = "";
        if (LOGLEVEL >= 1) {
            $errorMsg = "Erreur: " . $error->getMessage() . "<br>";
        }
        if (LOGLEVEL >= 2) {
            $errorMsg .= "Provenance de l'erreur: " . $error->getFile() . " (<b>Ligne " . $error->getLine() . "</b>)<br>";
        }
        if (LOGLEVEL >= 3) {
            $errorMsg .= "Trace d'erreur (string): " . $error->getTraceAsString() . "<br>";
            $errorMsg .= "Code d'erreur: " . $error->getCode();
        }
        if ($mode == RAW) {
            // Définition des regex pour le formatage
            $formatting = array(array("/\<br\>|\<br\/\>/", "/\<b\>|\<\/b\>/"), array("\n", ""));
            // Formater le message d'erreur pour remplacer les sauts de ligne bruts par des sauts de ligne HTML
            $errorMsg = preg_replace($formatting[0], $formatting[1], $errorMsg);
        }

        return $errorMsg;
    }
    public static function printLog(string $msg): bool
    {
        $date = new DateTime();
        $date = $date->format("y-m-d h:i:s");
        if (LOGLEVEL < 1) return false;
        $logFile = fopen(__DIR__ . '\..\common\files\log.log', 'a+');
        if (!$logFile) return false;
        if (!fwrite($logFile, "\n[" . $date . "]: " . $msg)) return false;
        if (!fclose($logFile)) return false;
        return true;
    }


    /* DESTRUCTEUR (appelé automatiquement via un garbage collector quand objet inutile et inaccessible) */
    public function __destruct()
    {
        if ($this->stmt !== null) {
            $this->stmt = null;
        }
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }
}

require_once __DIR__ . '\entities\BlogModel.php';
require_once __DIR__ . '\entities\UserModel.php';
require_once __DIR__ . '\entities\PostModel.php';
