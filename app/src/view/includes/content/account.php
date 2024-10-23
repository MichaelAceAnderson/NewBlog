<?php
if (!isset($_SESSION['id_user'])) {
	header('Location: /?page=login');
	exit();
} else {
	?>
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
	<!-- Change username -->
	<div class="panel">
		<h1>Change Username</h1>
		<div class="panel-content">
			<form method="POST" action="">
				<label for="fUsername">Current Username:</label>
				<input type="text" autocomplete="off" name="fUserName" placeholder="Username" required />
				<label for="fNewUserName">New Username:</label>
				<input type="text" autocomplete="off" name="fNewUserName" placeholder="New Username" required />
				<label for="fNewUserName">Confirm New Username:</label>
				<input type="text" autocomplete="off" name="fNewUserNameConfirm" placeholder="Confirm Username" required />
				<input type="submit" value="✔️ Submit" name="fChangeUserName" />
			</form>
		</div>
	</div>
	<!-- Change password -->
	<div class="panel">
		<h1>Change Password</h1>
		<div class="panel-content">
			<form method="POST" action="">
				<label for="fPass">Current Password:</label>
				<input type="password" autocomplete="off" name="fPass" placeholder="Password" required />
				<label for="fNewPass">New Password:</label>
				<input type="password" autocomplete="off" name="fNewPass" placeholder="New Password" required />
				<label for="fNewPassConfirm">Confirm New Password:</label>
				<input type="password" autocomplete="off" name="fNewPassConfirm" placeholder="Confirm New Password" required />
				<input type="submit" value="✔️ Submit" name="fChangePassword" />
			</form>
		</div>
	</div>
	<!-- Add a post -->
	<div class="panel">
		<h1>Add a Post</h1>
		<div class="panel-content">
			<p>
				<b>Warning</b>: <i>Posts cannot be edited once submitted!</i>
			</p>
			<form method="POST" action="" enctype="multipart/form-data">
				<label for="fPostTitle">Post Title:</label>
				<input type="text" autocomplete="off" name="fPostTitle" placeholder="Post Title" required />
				<label for="fPostTags">Post Tags (separated by ;):</label>
				<input type="text" autocomplete="off" name="fPostTags" placeholder="Post Tags" required />
				<label for="fPostSummary">Post Summary:</label>
				<textarea autocomplete="off" name="fPostSummary" placeholder="Textual summary of your post" required></textarea>
				<label for="fPostContent">Post Content:</label>
				<textarea autocomplete="off" name="fPostContent" placeholder="Textual content of your post" required></textarea>

				<label for="fPostMedia">Photo/Video (optional):</label>
				<input type="file" name="fPostMedia" />
				<input type="submit" value="✔️ Submit" name="fPost" />
			</form>
		</div>
	</div>
</div>
<?php
}
?>
