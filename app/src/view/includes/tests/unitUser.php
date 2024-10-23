<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
	// If the user is not an admin, redirect them to the homepage
	header('Location: /');
}
?>
<!-- Page content -->
<section class="main" id="main">
	<div class="title outlined">
		<h1>User-related unit tests</h1>
		<hr>
	</div>
	<div class="content">
		<?php
		// Creating a user
		?>
	</div>
</section>