<div class="main">
	<?php
	// Check if an error has been stored by the controller
	if (Controller::getState()['state'] == STATE_ERROR) {
		// If the controller has stored an error, display it
		echo '<h1 class="notification error">' . Controller::getState()['message'] . '</h1>';
	}
	// Check if a success has been stored by the controller
	elseif (Controller::getState()['state'] == STATE_SUCCESS) {
		// If the controller has stored a success, display it
		echo '<h1 class="notification success">' . Controller::getState()['message'] . '</h1>';
	}
	?>
	<!-- Set the blog name -->
	<div class="panel">
		<h1>Set the blog name</h1>
		<div class="panel-content">
			<form method="POST" action="">
				<label for="fBlogName">Blog name:</label>
				<input type="text" autocomplete="off" name="fBlogName" placeholder="New blog name" required />
				<input type="submit" value="✔️ Validate" name="fChangeBlogName" />
			</form>
		</div>
	</div>
	<!-- Set the blog description -->
	<div class="panel">
		<h1>Change the blog description</h1>
		<div class="panel-content">
			<form method="POST" action="">
				<label for="fBlogDesc">Blog description:</label>
				<textarea style="width: 50%;" name="fBlogDesc" placeholder="New blog description" required></textarea>
				<input type="submit" value="✔️ Validate" name="fChangeBlogDesc" />
			</form>
		</div>
	</div>
	<!-- Set the blog logo -->
	<div class="panel">
		<h1>Change the blog logo</h1>
		<div class="panel-content">
			<p><b>Note: </b>Leaving all fields empty will reset the logo to default. If a file is uploaded, the URL will be ignored.
			</p>
			<form method="POST" action="" enctype="multipart/form-data">
				<label for="fLogoURL">Logo URL:</label>
				<input type="text" name="fLogoURL" placeholder="/img/logo.jpg" />
				<label for="fLogoFile">Logo file:</label>
				<input type="file" name="fLogoFile" />
				<input type="submit" value="✔️ Validate" name="fChangeLogo" />
			</form>
		</div>
	</div>
	<!-- Set the blog background image -->
	<div class="panel">
		<h1>Change the blog background image</h1>
		<div class="panel-content">
			<p><b>Note: </b>Leaving all fields empty will reset the background image to default. If a file is uploaded, the URL will be ignored.
			</p>
			<form method="POST" action="" enctype="multipart/form-data">
				<label for="fBgURL">Background image URL:</label>
				<input type="text" name="fBgURL" placeholder="/img/circuits.jpg" />
				<label for="fBgFile">Background image file:</label>
				<input type="file" name="fBgFile" />
				<input type="submit" value="✔️ Validate" name="fChangeBgURL" />
			</form>
		</div>
	</div>
	<!-- Maintenance -->
	<div class="panel">
		<h1>Maintenance</h1>
		<div class="panel-content">
			<form method="POST" action="">
				<input type="submit" value="❌ Delete all posts" name="fClearPosts" />
			</form>
			<p><b>⚠️ Note: </b> You will lose your account, your posts, and all your content!</p>
			<a href="/?page=install"><button>⚠️️ Access the reinstallation page</button></a>
			<a href="/?page=tests"><button>🚧 Access the tests page</button></a>
			</form>
		</div>
	</div>
</div>
