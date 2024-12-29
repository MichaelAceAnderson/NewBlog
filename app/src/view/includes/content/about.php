<?php
if (isset($_POST['submit'])) {
	if (empty($_POST['mail']))
		$error = 'The email address cannot be empty!';
	elseif (empty($_POST['msg']))
		$error = 'The message must have content!';
	else {
		$mail = $_POST['mail'];
		$msg = $_POST['msg'];
		$error = false;
	}
}
?>

<section class="main" id="main">
	<div class="title outlined">
		<h1>About</h1>
		<hr>
	</div>

	<div class="content">
		<p class="outlined">
			This CMS was entirely made with HTML5, CSS3, JavaScript ES 2020. Without any framework. By hand. And that's
			classy.
		</p>
	</div>

</section>