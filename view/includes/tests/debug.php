<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
    // Si l'utilisateur n'est pas admin, on le redirige vers la page d'accueil
    header('Location: /');
}
?>

<style>
    b {
        text-shadow: 0px 0px 5px black, 2px 2px 0px black;
        color: red;
    }

    span {
        line-break: anywhere;
    }

    * {
        font-size: 25px;
    }
</style>
<?php
// Définitions de variables arbitraires à afficher par le debug
$_POST['postVar'] = "Une valeur de formulaire";
$test = "Chaîne de test";
// Définition des regex pour le formatage
$formatting = array(array("/\\n/", "/\[(\w+)\]/"), array("<br>", '<b>[${1}]</b>'));

// Variables de PHP
echo "<b>PHP Vars: </b><br>\n";
if (!isset($_SESSION)) {
    session_start();
}
echo "<b>Valeurs de SESSION: </b><span>" . preg_replace($formatting[0], $formatting[1], print_r($_SESSION, true)) . "</span><br>";

if (isset($_ENV)) {
    echo "<b>Valeurs de ENV: </b><span>" . preg_replace($formatting[0], $formatting[1], print_r($_ENV, true)) . "</span><br>";
}
echo '<b>Valeurs de HTTP_RAW_POST_DATA (désormais php://input): </b>' . file_get_contents("php://input") . '<br>';
if (isset($http_response_header)) {
    echo "<b>Valeurs de http_response_header: </b><span>" . preg_replace($formatting[0], $formatting[1], print_r($http_response_header, true)) . "</span><br>";
}
// Variables définies
echo "<b>Defined vars: </font></b><span>" . preg_replace($formatting[0], $formatting[1], print_r(get_defined_vars(), true)) . "</span><br>\n";
// Constantes définies
echo "<b>Defined constants: </font></b><span>" . preg_replace($formatting[0], $formatting[1], print_r(get_defined_constants(), true)) . "</span><br>\n";
// Chemin du fichier de travail (page en cours)
echo "<b>Current working directory: </font></b><span>" . print_r(getcwd(), true) . "</span><br>\n";
// Chemin du présent fichier (debug.php, différent du cwd si appelé depuis un autre fichier)
echo "<b>__DIR__: </font></b><span>" . __DIR__ . "</span><br>\n";
// Dernière erreur renvoyée par PHP
echo "<b>Valeurs de php_errormsg: </b><span>" . preg_replace($formatting[0], $formatting[1], print_r(error_get_last(), true)) . "</span><br>";

// Variables personnalisées
echo "<b>Custom Vars:</b><br>";
$conflen = strlen('SCRIPT');
$B = substr(__FILE__, 0, strrpos(__FILE__, '/'));
$A = substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
$C = substr($B, strlen($A));
$posconf = strlen($C) - $conflen - 1;
$D = substr($C, 1, $posconf);
$host = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $D;

echo "<b>Host: </b><span>" . $host . "</span><br>\n";

// PHP Info
// echo "<b>PHP Info: </font></b><span>" . phpinfo() . "</span><br>\n";
// PHP Info 32
// echo "<b>PHP Info 32: </font></b><span>" . phpinfo(32) . "</span><br>\n";

?>