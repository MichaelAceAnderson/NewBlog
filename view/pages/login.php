<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/head.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/header.php'); ?>
<?php

$userfile = $_SERVER['DOCUMENT_ROOT'] . '/model/data/settings/user.txt';
$passfile = $_SERVER['DOCUMENT_ROOT'] . '/model/data/settings/pass.txt';

$installcheck = $_SERVER['DOCUMENT_ROOT'] . '/model/data/settings/installcheck.txt';

if (file_exists($installcheck)) {
	if (isset($_POST['adminco'])) {
		if (isset($_POST['adminuser']) and isset($_POST['adminpass'])) {
			$adminuser = $_POST['adminuser'];
			$adminpass = $_POST['adminpass'];

			$getuser = file_get_contents($userfile);
			$getpass = file_get_contents($passfile);

			if ($adminuser == $getuser and $adminpass == $getpass) {
				header('Location: /view/pages/admin-panel.php?adminuser=' . $adminuser . '&adminpass=' . $adminpass . '');
			} else {
				echo '<p>Pseudo ou mot de passe incorrect !</p>
					<form method="post" enctype="multipart/form-data">
					<p>Identifiant:</p><input type="text" name="adminuser" />
					<br/>
					<p>Mot de passe:</p><input type="password" name="adminpass" />
					<br/>
					<input type="submit" class="button" onfocus="this.blur()" value="Connexion" name="adminco" />
					</form>';
			}
		}
	} else {
		echo '<p>Connectez vous:</p><br/>
		<form method="post" enctype="multipart/form-data">
		<p>Identifiant:</p><input type="text" name="adminuser" />
		<br/>
		<p>Mot de passe:</p><input type="password" name="adminpass" />
		<br/>
		<input type="submit" class="button" onfocus="this.blur()" value="Connexion" name="adminco" />
		</form>';
	}
} else {
	include_once($_SERVER['DOCUMENT_ROOT'] . "/view/pages/install.php");
}
?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/footer.php'); ?>