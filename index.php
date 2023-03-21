<?php
// Inclusion du contrôleur
require_once __DIR__ . '\controller\controller.php';

require_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/structure/head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/structure/header.php');
if (isset($_GET['page'])) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/view/includes/content/' . $_GET['page'] . '.php')) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/content/' . $_GET['page'] . '.php');
    } else {
        $_GET['code'] = '404';
        require_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/content/error.php');
    }
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/content/home.php');
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/structure/footer.php');
