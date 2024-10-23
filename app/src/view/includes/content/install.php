<div class="main">
	<!-- <iframe width="50%" height="350px" src="" frameborder="0" allowFullScreen="">
		Coming soon » CMS installation video!
	</iframe> -->
	<?php
	if ($blogInstalled) {
		echo '<h1 class="notification warning">⚠️ Warning! If your blog is already installed, this new installation will delete all your posts, users, and configuration!</h1>';
	}
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
	<form method="post" action="" autocomplete="off">
		<!-- Set credentials -->
		<div class="panel">
			<h1>Configure credentials</h1>
			<div class="panel-content">
				<label for="fUsername">Username:</label>
				<input type="text" name="fUserName" placeholder="CoolName123" autocomplete="new-password"
					aria-autocomplete="none" required />
				<label for="fPass">Password:</label>
				<input type="password" name="fPass" placeholder="Password" autocomplete="new-password"
					aria-autocomplete="none" required />
			</div>
		</div>
		<!-- Set blog name -->
		<div class="panel">
			<h1>Set blog name</h1>
			<div class="panel-content">
				<input type="text" autocomplete="off" name="fBlogName" placeholder="Blog name" required />
			</div>
		</div>
		<!-- Set blog description -->
		<div class="panel">
			<h1>Enter blog description</h1>
			<div class="panel-content">
				<textarea name="fBlogDesc" placeholder="Description" required></textarea>
			</div>
		</div>
		<!-- Set blog background image URL -->
		<div class="panel">
			<h1>Set blog background image URL</h1>
			<div class="panel-content">
				<p>Enter the URL of the blog background image (leave empty to use the default one)</p>
				<input type="text" name="fBgURL" placeholder="/img/circuits.jpg" />
			</div>
		</div>
		<!-- Complete installation -->
		<div class="panel">
			<h1>Complete installation</h1>
			<div class="panel-content">
				<input type="submit" value="✔️ Submit" name="fInstall" />
			</div>
		</div>
	</form>
</div>