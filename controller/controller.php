<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'model.php';

// Code s'appuyant sur le modèle et appelé par les formulaires des vues

class Controller
{
    /* PROPRIÉTÉS/ATTRIBUTS */
    // État de la dernière action effectuée
    private static int $state = STATE_NONE;
    // Message de la dernière action effectuée
    private static string $message = '';

    /* FONCTIONS DE SORTIE DES DONNÉES */
    // Définir le contenu de la page comme étant une sortie Json
    public static function returnJsonHttpResponse(bool $success, string|array $data): void
    {
        // Supprimer toutes les chaînes de caractères
        // susceptibles de créer un JSON invalide
        // telles que les avertissements PHP, les erreurs, journaux...
        ob_clean();

        // Suppression des précédents headers
        header_remove();

        // Définir le type de contenu à JSON en UTF-8 (peut être changé)
        header('Content-Type: application/json; charset=utf-8');

        // Déterminer si la requête doit renvoyer un succès ou non
        // Code succès HTTP: 2xx; code erreur HTTP: 4xx, 5xx
        if ($success) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
        // Encoder le tableau PHP en chaîne JSON
        echo json_encode($data);

        // S'assurer que rien d'autre n'est ajouté à la réponse
        exit();
    }

    // Renvoyer un tableau de données à partir de données Json
    public static function returnJsonFromArray(mixed $dbArray): void
    // Ne fonctionne que lorsque le PDO est configuré en FETCH_ASSOC ?
    {
        $jsonResult = array();
        while ($row = $dbArray->fetchAll()) {
            $jsonResult[] = $row;
        }
        // Gérer erreur si besoin
        self::returnJsonHttpResponse(true, $jsonResult);
    }

    // Récupérer des données JSON obtenues via requête POST
    public static function jsonToVar(): mixed
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    /* AUTRES MÉTHODES */
    // Formater l'erreur d'une Exception
    public static function getError(Exception $error, int $mode = RAW)
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
            $formatting = array(array('/\<br\>|\<br\/\>/', '/\<b\>|\<\/b\>/', '/' . PHP_EOL . '/'), array('\n', '', ''));
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
        $logFile = fopen(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'controller.log', 'a+');
        if (!$logFile) {
            // S'il est impossible d'ouvrir le fichier de log
            return false;
        }
        if (!fwrite($logFile, PHP_EOL . '[' . $date . '] Contrôleur: ' . $msg)) {
            // S'il est impossible d'écrire dans le fichier de log
            return false;
        }
        if (!fclose($logFile)) {
            // S'il est impossible de fermer le fichier de log
            return false;
        }
        return true;
    }
    // Définir l'état à afficher dans les vues
    public static function setState(int $state, string $message): void
    {
        self::$state = $state;
        self::$message = $message;
    }
    // Récupérer l'état de la dernière action effectuée
    public static function getState(): array
    {
        return array('state' => self::$state, 'message' => self::$message);
    }
}

/// NOTE: Il n'est pas possible d'inclure via un foreach, il faut suivre l'ordre selon l'interdépendance des contrôleurs/données

// Inclure le contrôleur d'utilisateurs
require_once __DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'UserController.php';
// Inclure le contrôleur du blog
require_once __DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'BlogController.php';
// Inclure le contrôleur de posts
require_once __DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'PostController.php';