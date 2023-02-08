<?php
// Récupérer le statut de l'installation
$getEtape = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/model/data/settings/installcheck.txt');
// Si l'installation est terminée
if ($getEtape == "ok") {
	//Inclure les fichiers de données et d'en-tête de la page
	include_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/head.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/header.php');

	// Récupérer la description du blog
	$descfile = $_SERVER['DOCUMENT_ROOT'] . '/model/data/settings/description.txt';
	$getdesc = file_get_contents($descfile);

	// Considérer une description vide comme un saut de ligne
	if ($getdesc == "") {
		echo '<br/>';
	} else {
		// Si la description existe, l'afficher en tant qu'annonce
		$desc = file($descfile);

		echo '<div class="annonce">';

		for ($i = 0; $i < count($desc); $i++) {
			echo $desc[$i];
		}

		echo '</div>';
	}

	// Récupérer les posts du blog
	$postfile = $_SERVER['DOCUMENT_ROOT'] . '/model/data/settings/post.txt';
	$post = file($postfile);
	$post = array_reverse($post);

	for ($i = 0; $i < count($post); $i++) {
		echo '<div class="panel panel-primary" style="font-family: \'Arial\', sans-serif">
				<div class="panel-heading"><center></center></div>
				<div class="panel-body"><p>' . $post[$i] . '</p></div>
				</div>';
	}
	include_once($_SERVER['DOCUMENT_ROOT'] . '/view/includes/footer.php');
} else {
	include_once($_SERVER['DOCUMENT_ROOT'] . '/view/pages/install.php');
}