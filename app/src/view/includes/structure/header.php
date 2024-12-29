<?php
// By default, the blog description is not set
$blogDescription = false;
// If the database connection could be established
if (Model::getPdo() != null) {
	// If the blog is installed, retrieve the background image URL
	if ($blogInstalled) {
		// Retrieve the blog description
		$blogDescription = BlogController::getBlogDescription();
	}
}
$bgURL = BlogController::getBackgroundURL();
echo '<body style="background-image: url(' . $bgURL . ')">';
?>
<!-- Header -->
<header>
	<?php
	if ((isset($_GET['page']) && $_GET['page'] == 'home') || $_SERVER['REQUEST_URI'] == '/') {
		// If the user is on the homepage, no link
		echo '<a href="#">';
	} else {
		// Otherwise, link to the homepage
		echo '<a href="/">'; 
	}
	// If the blog name is set (see head.php), display it, otherwise display "NewBlog"
	echo $blogName ? '<h1>' . $blogName . '</h1>' : '<h1>NewBlog</h1>';
	// If the blog description is set, display it, otherwise display "Your new blog!"
	echo $blogDescription ? '<h2>' . $blogDescription . '</h2>' : '<h2>Your new blog!</h2>';
	echo '</a>';
	// If the database connection could be established and the blog is installed
	if (Model::getPdo() != null && $blogInstalled) {
		echo '<span class="account">';
		if (isset($_SESSION['id_user'])) {
			echo '<a href="/?page=account"><h3>' . $_SESSION['nickname'] . '</h3></a>';
			if (isset($_SESSION['is_mod']) && $_SESSION['is_mod'] === true)
				echo '<a href="/?page=admin"><h4>Admin page</h4></a>';
			echo '<form action="" method="post"><a><input type="submit" name="fLogOut" title="Logout" value="🚪 Logout"></a></form>';
		} else {
			echo '<a href="/?page=login"><h4>Login</h4></a>';
		}
		echo '</span>';
	}
	?>
</header>