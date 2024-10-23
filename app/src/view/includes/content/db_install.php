<div class="main">
	<!-- <iframe width="50%" height="350px" src="" frameborder="0" allowFullScreen="">
		Coming soon » Database installation video!
	</iframe> -->
	<?php
	// If the connection to the database has been established
	if (Model::getPdo() != null) {
		// Redirect to the homepage
		header('Location: /');
	} else {
		// If the connection to the database could not be established
	
		// Display an error message
		echo '<h1 class="notification warning">Make sure you have a running MariaDB database named "newblog" with the correct credentials configured !</h1>';

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
		<!-- Define the database credentials -->
		<!-- <form method="post" action="" autocomplete="off">
			<div class="panel">
				<h1>Configure database access</h1>
				<div class="panel-content">
					<label for="fUsername">Username:</label>
					<input type="text" name="fUserName" placeholder="CoolName123" autocomplete="new-password"
						aria-autocomplete="none" required />
					<label for="fPass">Password:</label>
					<input type="password" name="fPass" placeholder="Password" autocomplete="new-password"
						aria-autocomplete="none" required />
					<input type="submit" value="✔️ Validate" name="fInstall" />
				</div>
			</div>
		</form> -->
		<?php
	}
	?>
</div>