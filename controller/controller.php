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
}

include_once __DIR__ . '\entities\UserController.php';

include_once __DIR__ . '\entities\BlogController.php';

include_once __DIR__ . '\entities\PostController.php';
