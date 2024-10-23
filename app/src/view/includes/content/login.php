<div class="main">
	<div class="content">
		<div class="panel">
			<h1>Log in:</h1>
			<div class="panel-content">
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
				<form method="post" action="">
					<label for="fUsername">Username:</label>
					<input type="text" name="fUserName" placeholder="Username" required />
					<label for="fUsername">Password:</label>
					<input type="password" name="fPass" placeholder="Password" required />
					<input type="submit" value="Log in" name="fLogin" />
				</form>
			</div>
		</div>
	</div>
</div>