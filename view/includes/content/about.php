<?php
if (isset($_POST["submit"])) {
	if (empty($_POST["mail"])) $error = "L'adresse mail ne peut pas être vide !";
	elseif (empty($_POST["msg"])) $error = "Le message doit comporter un contenu !";
	else {
		$mail = $_POST["mail"];
		$msg = $_POST["msg"];
		$error = false;
	}
}
?>

<section class="main" id="main">
    <div class="title outlined">
        <h1>À propos</h1>
        <hr>
    </div>

    <div class="content">
        <p class="outlined">
            Ce site a été réalisé entièrement avec HTML5, CSS3, JavaScript ES 2020. Sans Framework. À la mano. Et ça
            c'est la classe.
        </p>
    </div>

</section>