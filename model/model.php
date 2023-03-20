<?php
// Code à inclure dans le contrôleur

declare(strict_types=1);

define("DB_HOST", "localhost");
define("DB_NAME", "NewBlog");
define("LOGLEVEL", 0);
define("RAW", 1);
define("HTML", 2);
class Model
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    private mixed $pdo = null;
    private mixed $stmt = null;

    /* CONSTRUCTEUR */
    function __construct(string $role)
    {
        $pass = null;
        switch (strtolower($role)) {
            case "postgres": {
                    $pass = "PG770rwx";
                    break;
                }
            case "reader": {
                    $pass = "PGlr4--";
                    break;
                }
            case "writer": {
                    $pass = "PGlw2--";
                    break;
                }
            case "editor": {
                    $pass = "PGlrw6--";
                    break;
                }
            default: {
                    $this->pdo = null;
                    break;
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
                    // \PDO::ATTR_EMULATE_PREPARES => true,
                    // \PDO::ATTR_PERSISTENT => true,
                    // \PDO::ATTR_STRINGIFY_FETCHES => true,
                ]
            );
        } catch (PDOException $e) {
            $this->pdo = null;
        }
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
    public function getPdo(): PDO | null
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
