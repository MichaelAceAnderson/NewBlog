<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
	// If the user is not an admin, redirect them to the homepage
	header('Location: /');
}
?>
<style>
.button {
	color: white;
	background: #333;
	border: 5px solid var(--lighter);
	border-radius: 0;
	font-size: 20px;
	padding: 30px;
	display: inline;
	margin: 5px auto 5px auto;
	transition: 0.5s;
	font-family: "Agency FB", sans-serif;
}

.button:hover {
	background: rgb(23 24 25);
	border: 5px solid var(--lighter2);
	cursor: pointer;
	transition: 1s;
}

.button:active {
	background: radial-gradient(rgb(40 40 40), rgb(23 24 25));
	transition: 1s;
}

.main .content p {
	font-size: 30px;
}
</style>
<!-- Page content -->
<section class="main" id="main">
	<div class="title outlined">
		<h1>Debug value test via text field:</h1>
		<hr>
	</div>
	<div class="content">
		<form action="<?php echo '/?page=..' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'debug'; ?>"
			method="POST">
			<input type="text" name="textField">
			<textarea type="text" name="textArea"></textarea>
			<input type="submit" class="button" value="Test button"></submit>
		</form>
		<div class="title outlined">
			<h1>Other test pages:</h1>
			<hr>
		</div>
		<?php
		echo "<p>";
		$files = scandir(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests');
		foreach ($files as $file) {
			if ($file != '.' && $file != '..' && $file != 'index.php') {
				$file = str_replace('.php', '', $file);
				echo '<a href="/?page=..' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . $file . '">' . $file . '</a><br>';
			}
		}
		echo "</p>";
		?>
	</div>
</section>