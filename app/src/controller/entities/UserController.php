<?php

// If the user is not using this file in another context
// than from the index.php page, redirect to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

class UserController
{
	/* METHODS */

	/* Insertions */
	// Add a user
	public static function createUser(string $nickname, string $password, bool $is_mod = false): bool
	{
		// Attempt to add the user to the database
		$result = User::insertUser($nickname, $password, $is_mod);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while creating the user "' . $nickname . '" with the password "' . $password . '" (admin: "' . $is_mod ? 'true' : 'false' . '") !');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return the success/failure of the operation
			return $result;
		}
	}

	/* Modifications */
	// Change a user's nickname
	public static function changeUsername(int $id, string $newNickname): bool
	{
		// Attempt to change the nickname in the database
		$result = User::updateUsername($id, $newNickname);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while changing the nickname of user "' . $id . '" to $newNickname !');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return the success/failure of the operation
			return $result;
		}
	}
	// Change a user's password
	public static function changeUserPassword(int $id, string $newPassword): bool
	{
		// Attempt to change the password in the database
		$result = User::updateUserPassword($id, $newPassword);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while changing the password of user "' . $id . '" to "' . $newPassword . '" !');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return the success/failure of the operation
			return $result;
		}
	}

	/* Retrievals */
	// Retrieve all users
	public static function getAllUsers(): array|false
	{
		// Attempt to retrieve users from the database
		$result = User::selectUsers();
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving users!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If there is at least one user
			if (count($result) > 0) {
				// Return the array of users
				return $result;
			} else {
				// Otherwise, return false
				// Return failure
				return false;
			}
		}
	}
	// Retrieve a user's nickname by their ID
	public static function getUserNameById(int $userId): string|false
	{
		// Attempt to retrieve the user from the database by their ID
		$result = User::selectUserById($userId);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the nickname of user "' . $userId . '" !');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If there is at least one user
			if (count($result) > 0) {
				// Return the user's nickname
				return $result[0]->nickname;
			} else {
				// If no user was found
				// Return failure
				return false;
			}
		}
	}

	// Retrieve a user's ID by their nickname and password
	public static function getUserInfoByCredentials(string $username, string $password): object|false
	{
		// Attempt to retrieve the user from the database by their nickname
		$result = User::selectUserByName($username);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the information of user "' . $username . '" with the password "' . $password . '" !');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} elseif ($result) {
			// If the user exists

			// Check if $password matches the password
			if (password_verify($password, $result[0]->password)) {
				// If the passwords match
				// Return the user object
				return $result[0];
			} else {
				// If the password does not match
				// Return failure
				return false;
			}
		} else {
			// The user does not exist
			// Return failure
			return false;
		}
	}

	/* Deletions */
	// Delete a user
	public static function deleteUser(int $userId): bool
	{
		// Attempt to delete the user from the database by their ID
		$result = User::deleteUser($userId);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while deleting user "' . $userId . '" !');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the user was successfully deleted
			// Return success
			return true;
		}
	}
}
/* FORM HANDLING */

// If the user submits a login form
if (isset($_POST['fLogin'])) {
	// Check that the fields are filled
	if (
		isset($_POST['fUserName']) && isset($_POST['fPass'])
		&& !empty($_POST['fUserName']) && !empty($_POST['fPass'])
	) {
		// Attempt to retrieve the user's information
		$user = UserController::getUserInfoByCredentials($_POST['fUserName'], $_POST['fPass']);
		if (!$user) {
			// If the user does not exist or the password is incorrect, store the error
			Controller::setState(STATE_ERROR, 'Login failed, please check your credentials.');
		} else {
			// If the user exists and the password is correct, store the information in the session
			$_SESSION['id_user'] = $user->id_user;
			$_SESSION['nickname'] = $user->nickname;
			$_SESSION['is_mod'] = $user->is_mod;
			// Redirect to the homepage
			header('Location: /');
		}
	} else {
		// If all fields are not filled, store the error
		Controller::setState(STATE_ERROR, 'Please fill in all fields.');
	}
}
// If the user logs out
if (isset($_POST['fLogOut'])) {
	// Remove all session variables
	unset($_SESSION);
	// Destroy the session
	session_destroy();
	// Redirect to the homepage
	header('Location: /');
}

// If the user submits a username change form
if (isset($_POST['fChangeUserName'])) {
	if (!isset($_POST['fUserName']) || empty($_POST['fUserName'])) {
		// If the user has not filled in their current username, store the error to display in the view
		Controller::setState(STATE_ERROR, 'Please first enter your current username!');
	} else {
		if ($_POST['fUserName'] != $_SESSION['nickname']) {
			// If the current username does not match the session username, store the error to display in the view
			Controller::setState(STATE_ERROR, 'The current username you entered does not match your account!');
		} else {
			if (
				!isset($_POST['fNewUserName']) || empty($_POST['fNewUserName'])
				|| !isset($_POST['fNewUserNameConfirm']) || empty($_POST['fNewUserNameConfirm'])
			) {
				// If the user has not filled in all required fields, store the error to display in the view
				Controller::setState(STATE_ERROR, 'Please enter your new username and confirm it below.');
			} else {
				if ($_POST['fNewUserName'] != $_POST['fNewUserNameConfirm']) {
					// If the two new username fields do not match, store the error to display in the view
					Controller::setState(STATE_ERROR, 'The two usernames do not match.');
				}
				// If there is no error, attempt to change the username
				if (Controller::getState()['state'] != STATE_ERROR) {
					// Attempt to change the user's username
					$result = UserController::changeUsername($_SESSION['id_user'], $_POST['fNewUserName']);
					if ($result) {
						$_SESSION['nickname'] = $_POST['fNewUserName'];
						// If the username was successfully changed, display the success message
						Controller::setState(STATE_SUCCESS, 'Your username has been successfully changed!');
					} else {
						Controller::setState(STATE_ERROR, 'An error occurred, please try again.');
					}
				}
			}
		}
	}
}

// If the user submits a password change form
if (isset($_POST['fChangePassword'])) {
	if (!isset($_POST['fPass']) || empty($_POST['fPass'])) {
		// If the user has not filled in their current password, store the error to display in the view
		Controller::setState(STATE_ERROR, 'Please first enter your current password!');
	} else {
		// If the user has filled in their current password, verify that it is correct
		if (!UserController::getUserInfoByCredentials($_SESSION['nickname'], $_POST['fPass'])) {
			// If the current password does not match the session password, store the error to display in the view
			Controller::setState(STATE_ERROR, 'The current password you entered is incorrect!');
		} else {
			// If the user has filled in all required fields, verify that the two new password fields match
			if (
				!isset($_POST['fNewPass']) || empty($_POST['fNewPass'])
				|| !isset($_POST['fNewPassConfirm']) || empty($_POST['fNewPassConfirm'])
			) {
				// If the user has not filled in all required fields, store the error to display in the view
				Controller::setState(STATE_ERROR, 'Please enter your current password, your new password, and confirm it below.');
			} else {
				if ($_POST['fNewPass'] != $_POST['fNewPassConfirm']) {
					// If the two new password fields do not match, store the error to display in the view
					Controller::setState(STATE_ERROR, 'The password confirmation does not match your new password.');
				}
				// If there is no error, attempt to change the password
				if (Controller::getState()['state'] != STATE_ERROR) {
					// Attempt to change the user's password
					$passChanged = UserController::changeUserPassword($_SESSION['id_user'], $_POST['fNewPass']);
					if (!$passChanged) {
						// If the password could not be changed, store the error to display in the view
						Controller::setState(STATE_ERROR, 'An error occurred while changing your password, please try again.');
					} else {
						// If the password was successfully changed, display the success message
						Controller::setState(STATE_SUCCESS, 'Your password has been successfully changed!');
					}
				}
			}
		}
	}
}