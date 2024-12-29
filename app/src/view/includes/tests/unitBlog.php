<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
	// If the user is not an admin, redirect them to the homepage
	header('Location: /');
}
?>
<!-- Page content -->
<section class="main" id="main">
	<div class="title outlined">
		<h1>Unit tests related to the blog</h1>
		<hr>
	</div>
	<div class="content">
		<?php
		// Blog installation
		// Modification of the blog name
		// Modification of the blog description
		// Retrieval of the blog creation date
		// Modification of the blog background image URL
		?>
	</div>
</section>