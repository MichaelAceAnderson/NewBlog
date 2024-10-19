<?php
// Inclusion du contrôleur
require_once __DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . 'head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . 'header.php');

// Si la connexion à la base de données n'a pas pu être établie
if (Model::getPdo() == null) {
    // Si l'utilisateur n'est pas sur la page d'installation
    if (!isset($_GET['page']) || $_GET['page'] != 'db_install') {
        // Rediriger vers la page d'installation
        require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'db_install.php');
    }
}
// Si le blog n'est pas installé
elseif (!$blogInstalled) {
    // Si l'utilisateur n'est pas sur la page d'installation
    if (!isset($_GET['page']) || $_GET['page'] != 'install') {
        // Rediriger vers la page d'installation
        require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'install.php');
    }
} elseif (isset($_GET['page'])) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $_GET['page'] . '.php')) {
        require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $_GET['page'] . '.php');
    } else {
        // Imiter le comportement d'un code d'erreur 404
        $_GET['code'] = '404';
        // Inclure la page de traitement des erreurs
        require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'error.php');
    }
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'home.php');
}
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . 'footer.php');