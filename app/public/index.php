<?php
// Include the controller
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'controller.php';

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . 'head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . 'header.php');

// If the database connection could not be established
if (Model::getPdo() == null) {
	// If the user is not on the installation page
	if (!isset($_GET['page']) || $_GET['page'] != 'db_install') {
		// Redirect to the installation page
		require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'db_install.php');
	}
}
// If the blog is not installed
elseif (!$blogInstalled) {
	// If the user is not on the installation page
	if (!isset($_GET['page']) || $_GET['page'] != 'install') {
		// Redirect to the installation page
		require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'install.php');
	}
} elseif (isset($_GET['page'])) {
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $_GET['page'] . '.php')) {
		require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $_GET['page'] . '.php');
	} else {
		// Mimic the behavior of a 404 error code
		$_GET['code'] = '404';
		// Include the error handling page
		require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'error.php');
	}
} else {
	require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'home.php');
}
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . 'footer.php');