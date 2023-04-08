<?php
require_once __DIR__ . '\..\model\model.php';

// Code s'appuyant sur le modèle et appelé par les formulaires des vues

class Controller
{
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
        header("Content-Type: application/json; charset=utf-8");

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
        return json_decode(file_get_contents("php://input"), true);
    }

    /* AUTRES MÉTHODES */
    // Formater l'erreur d'une Exception
    public static function getError(Exception $error, int $mode = RAW)
    {
        $errorMsg = "";
        if (LOGLEVEL >= 1) {
            $errorMsg = "Erreur: " . $error->getMessage();
        }
        if (LOGLEVEL >= 2) {
            $errorMsg .= "<br>Provenance de l'erreur: " . $error->getFile() . ":" . $error->getLine();
        }
        if (LOGLEVEL >= 3) {
            $errorMsg .= "<br>Trace d'erreur (string): " . $error->getTraceAsString();
            $errorMsg .= "<br>Code d'erreur: " . $error->getCode();
        }
        if ($mode == RAW) {
            // Définition des regex pour le formatage
            $formatting = array(array("/\<br\>|\<br\/\>/", "/\<b\>|\<\/b\>/"), array("\n", ""));
            // Formater le message d'erreur pour remplacer les sauts de ligne bruts par des sauts de ligne HTML
            $errorMsg = preg_replace($formatting[0], $formatting[1], $errorMsg);
        }

        return $errorMsg;
    }
    // Écrire dans un fichier log
    public static function printLog(string $msg): bool
    {
        $date = new DateTime();
        $date = $date->format("y-m-d h:i:s");
        if (LOGLEVEL < 1) {
            // Si le niveau de log est inférieur à 1, on ne logge pas
            return false;
        }
        $logFile = fopen(__DIR__ . '\..\blog_data\logs\log.log', 'a+');
        if (!$logFile) {
            // S'il est impossible d'ouvrir le fichier de log
            return false;
        }
        if (!fwrite($logFile, "\n[" . $date . "] Contrôleur: " . $msg)) {
            // S'il est impossible d'écrire dans le fichier de log
            return false;
        }
        if (!fclose($logFile)) {
            // S'il est impossible de fermer le fichier de log
            return false;
        }
        return true;
    }
}

include_once __DIR__ . '\entities\UserController.php';

include_once __DIR__ . '\entities\BlogController.php';

include_once __DIR__ . '\entities\PostController.php';