<?php
if (!isset($_SESSION['is_mod']) || $_SESSION['is_mod'] == false) {
	// If the user is not an admin, redirect them to the homepage
	header('Location: /');
}
?>
<!-- Page content -->
<section class="main" id="main">
	<div class="title outlined">
		<h1>Unit tests related to posts</h1>
		<hr>
	</div>
	<div class="content">
		<?php
		// Create a post
		// Retrieve a post
		// Retrieve all posts
		// Delete a post
		?>
	</div>
</section>