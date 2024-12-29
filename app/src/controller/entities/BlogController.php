<?php

// If the user is not using this file in a context other than from the index.php page, redirect to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

class BlogController
{
	/* METHODS */

	// Check if the blog is installed
	public static function isInstalled(): bool
	{
		// Try to retrieve blog info from the database
		$result = Blog::selectBlog();
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while checking the blog installation!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the database is installed and the blog info exists
			// Return success/failure based on whether the result is empty or not
			return !empty($result);
		}
	}

	/* Creations */
	// Install the blog
	public static function installBlog(string $adminName, string $adminPass, string $blogName, string $blogDescription, string $bgURL): bool|Exception
	{
		// Create the blog in the database
		$dbInstallStatus = Blog::installDB();
		// If the database installation failed
		if ($dbInstallStatus instanceof Exception) {
			// Return the error
			return $dbInstallStatus;
		} else {
			// If the database installation returned an error
			if (!$dbInstallStatus) {
				// If the database installation succeeded but an unknown error occurs
				// Define the error to return
				$error = new Exception('An unexpected error occurred during the database installation (Name: "' . $blogName . '", Description: "' . $blogDescription . '", Background Image: "' . $bgURL . '", Administrator: "' . $adminPass . '", Admin Password: "' . $adminPass . '")!');
				// Log the error
				Controller::printLog(Model::getError($error));
				// Return the error
				return $error;
			} else {
				// If the database installation succeeded

				// Create the main user, admin, and owner
				if (!UserController::createUser($adminName, $adminPass, true)) {
					// If the user could not be created, stop the installation
					// Define the error to return
					$error = new Exception('The admin user "' . $adminName . '" with the password "' . $adminPass . '" could not be created in the database!');
					// Log the error
					Controller::printLog(Model::getError($error));
					// Return the error
					return $error;
				} else {
					// Retrieve the admin user, owner of the blog
					$adminAccount = UserController::getUserInfoByCredentials($adminName, $adminPass);
					if (!$adminAccount) {
						// If the admin user could not be retrieved from the database
						// Define the error to return
						$error = new Exception('The admin user "' . $adminName . '" with the password "' . $adminPass . '" could not be retrieved from the database!');
						// Log the error
						Controller::printLog(Model::getError($error));
						// Return the error
						return $error;
					}
					// If the admin user has been successfully created and retrieved

					// Insert blog information into the database
					$blogInsertStatus = Blog::insertBlog($blogName, $blogDescription, $adminAccount->id_user, $bgURL);
					if ($blogInsertStatus instanceof Exception) {
						// If the database installation succeeded but an error occurs during the insertion of blog data
						return $blogInsertStatus;
					} else {
						if (!$blogInsertStatus) {
							// If the blog data could not be inserted into the database
							// Define the error to return
							$error = new Exception('The blog data could not be inserted into the database (Name: "' . $blogName . '", Description: "' . $blogDescription . '", Background Image: "' . $bgURL . '", Administrator: "' . $adminPass . '", Admin Password: "' . $adminPass . '")!');
							// Log the error
							Controller::printLog(Model::getError($error));
							// Return the error
							return $error;
						} else {
							// If the blog installation succeeded, notify the NewBlog server via the API page

							// $version = file_get_contents($server . '/model/data/settings/version.txt');
							// $URL = 'http://' . $_SERVER['HTTP_HOST'];
							// $SendInstall = file_get_contents('http://xdev.livehost.fr/creations/web/newblog/bloginstalled.php?url=$URL&version=$version');

							// Return true to indicate that the installation is complete
							return true;
						}
					}
				}
			}
		}
	}
	/* Modifications */
	// Set the blog name
	public static function setBlogName($newBlogName): bool
	{
		// Try to update the blog name in the database
		$result = Blog::updateBlogName($newBlogName);
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while setting the blog name to "' . $newBlogName . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the blog name update succeeded
			// Return success
			return $result;
		}
	}
	// Change the background image URL
	public static function setBackgroundURL($newBackgroundURL): bool
	{
		// Try to update the background image URL
		$result = Blog::updateBackgroundURL($newBackgroundURL);
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while setting the background image URL to "' . $newBackgroundURL . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the background image URL update succeeded
			// Return success
			return $result;
		}
	}
	// Change the logo URL
	public static function setLogoURL($newLogoURL): bool
	{
		// Try to update the logo URL
		$result = Blog::updateLogoURL($newLogoURL);
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while setting the logo URL to "' . $newLogoURL . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the logo URL update succeeded
			// Return success
			return $result;
		}
	}
	// Change the description
	public static function setDescription($newDescription): bool
	{
		// Try to update the blog description in the database
		$result = Blog::updateDescription($newDescription);
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while setting the blog description to "' . $newDescription . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the blog description update succeeded
			// Return success
			return $result;
		}
	}

	/* Retrievals */
	// Get the blog name
	public static function getBlogName(): string|false
	{
		// Try to retrieve the blog info from the database
		$result = Blog::selectBlog();
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the blog name!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return false
			return false;
		} else {
			// If the blog info retrieval succeeded
			// Return the blog name if it exists
			return $result[0]->blog_name ?? false;
		}
	}
	// Get the blog description
	public static function getBlogDescription(): string|false
	{
		// Try to retrieve the blog info from the database
		$result = Blog::selectBlog();
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the blog description!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return false
			return false;
		} else {
			// If the blog info retrieval succeeded
			// Return the blog description if it exists
			return $result[0]->description ?? false;
		}
	}
	// Get the background image URL of the blog
	public static function getBackgroundURL(): string
	{
		$defaultBgURL = '/img/circuits.jpg';
		// Try to retrieve the blog info from the database
		$result = Blog::selectBlog();
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the background image URL of the blog!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return the default URL
			return $defaultBgURL;
		} else {
			// If the blog info retrieval succeeded
			// Return the background image URL if it exists
			if (!is_null($result[0]->background_url)) {
				// Ensure to return a relative path compatible with the browser
				return str_replace(
					// Search for the beginning of the absolute file path
					$_SERVER['DOCUMENT_ROOT'],
					// Replace it with nothing
					'',
					// Search for regular expressions
					preg_replace(
						// Replace the site URL and directory separators with a relative path
						array('/' . 'http(s?):\/\/' . $_SERVER['SERVER_NAME'] . '/', '/\\' . DIRECTORY_SEPARATOR . '/'),
						array('', '/'),
						$result[0]->background_url
					)
					// If the result is null, return the default background image URL
				) ?? $defaultBgURL;
			} else {
				// If there is no background image URL, return the default background image URL
				return $defaultBgURL;
			}
		}
	}
	// Get the logo URL of the blog
	public static function getLogoUrl(): string
	{
		// Try to retrieve the blog info from the database
		$result = Blog::selectBlog();
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the logo URL of the blog!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return the default logo URL
			return '/img/logo.jpg';
		} else {
			// If the blog info retrieval succeeded
			// Return the retrieved logo URL or the default one if no logo exists
			return $result[0]->logo_url ?? '/img/logo.jpg';
		}
	}
	// Get the blog creation date
	public static function getCreationDate(): string|false
	{
		// Try to retrieve the blog info from the database
		$result = Blog::selectBlog();
		// If an error occurs during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the blog creation date!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the blog info retrieval succeeded
			// Return the blog creation date if it exists
			return $result[0]->creation_date ?? false;
		}
	}
}
/* FORM REQUEST HANDLING */

// If the installation form was submitted
if (isset($_POST['fInstall'])) {
	// Check the fields
	if (
		isset($_POST['fUserName']) && $_POST['fUserName'] != ''
		&& isset($_POST['fPass']) && $_POST['fPass'] != ''
		&& isset($_POST['fBlogName']) && $_POST['fBlogName'] != ''
		&& isset($_POST['fBlogDesc']) && $_POST['fBlogDesc'] != ''
	) {
		$installStatus = BlogController::installBlog($_POST['fUserName'], $_POST['fPass'], $_POST['fBlogName'], $_POST['fBlogDesc'], $_POST['fBgURL']);
		if ($installStatus instanceof Exception) {
			// If an error occurred, log the error message and display it to the user
			// Controller::printLog(Model::getError($installStatus));
			Controller::setState(STATE_ERROR, Model::getError($installStatus, HTML));
		} else {
			if ($installStatus) {
				// If the installation succeeded
				if (isset($_SESSION)) {
					// Remove all session variables
					unset($_SESSION);
					// Destroy the session
					session_destroy();
				}
				// Redirect to the homepage
				header('Location: /');
			} else {
				// Should never happen
				Controller::setState(STATE_ERROR, 'An error occurred during the blog installation!');
			}
		}
	} else {
		Controller::setState(STATE_ERROR, 'Please fill in all the fields!');
	}
}

// If the blog name modification form was submitted
if (isset($_POST['fChangeBlogName'])) {
	// Check the fields
	if (isset($_POST['fBlogName']) && $_POST['fBlogName'] != '') {
		$blogNameUpdateStatus = BlogController::setBlogName($_POST['fBlogName']);
		if ($blogNameUpdateStatus) {
			// If the modification succeeded, store a success message
			Controller::setState(STATE_SUCCESS, 'The blog name has been successfully modified!');
		} else {
			// If the modification failed, display an error message
			Controller::setState(STATE_ERROR, 'An error occurred while modifying the blog name!');
		}
	} else {
		Controller::setState(STATE_ERROR, 'Please fill in all the fields!');
	}
}

// If the description modification form was submitted
if (isset($_POST['fChangeBlogDesc'])) {
	// Check the fields
	if (isset($_POST['fBlogDesc']) && $_POST['fBlogDesc'] != '') {
		$blogDescUpdateStatus = BlogController::setDescription($_POST['fBlogDesc']);
		if ($blogDescUpdateStatus) {
			// If the modification succeeded, store a success message
			Controller::setState(STATE_SUCCESS, 'The description has been successfully modified!');
		} else {
			// If the modification failed, display an error message
			Controller::setState(STATE_ERROR, 'An error occurred while modifying the description!');
		}
	} else {
		Controller::setState(STATE_ERROR, 'Please fill in all the fields!');
	}
}

// If the logo modification form was submitted
if (isset($_POST['fChangeLogo'])) {
	// If the logo was uploaded
	if (!empty($_FILES) && $_FILES['fLogoFile']['error'] != UPLOAD_ERR_NO_FILE) {
		// Possible upload error
		$error = $_FILES['fLogoFile']['error'];

		if ($_FILES['fLogoFile']['error'] != UPLOAD_ERR_OK || !$_FILES['fLogoFile']['tmp_name']) {
			// If an error occurred during the upload, store the error message to display
			Controller::setState(STATE_ERROR, 'Error: The file could not be uploaded');
		} elseif (!preg_match('/image\//', $_FILES['fLogoFile']['type'])) {
			// If the file is not an image, store the error message to display
			Controller::setState(STATE_ERROR, 'Your file must be an image or a video!');
		} elseif ($_FILES['fLogoFile']['size'] > 10485760) {
			// If the file size is greater than 10MB, store the error message to display
			Controller::setState(STATE_ERROR, 'The file is too large!');
		} else {
			// Delete the old logo
			foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo.*') as $imgFile) {
				// If it is a file and not a subfolder
				if (is_file($imgFile)) {
					// Delete the file
					unlink($imgFile);
				}
			}
			// Path of the logos
			$logoPath = DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' .
				DIRECTORY_SEPARATOR .
				// File name . extension
				'logo.' . pathinfo($_FILES['fLogoFile']['name'], PATHINFO_EXTENSION);
			// If the uploaded file was successfully moved
			if (
				!move_uploaded_file(
					// Temporary path of the uploaded file
					$_FILES['fLogoFile']['tmp_name'],
					// Destination
					$_SERVER['DOCUMENT_ROOT'] . $logoPath
				)
			) {
				// If an error occurs, store the error message to display
				Controller::setState(STATE_ERROR, 'Unable to upload the file due to a server-side error');
			} else {
				// If the upload succeeded, store a success message
				Controller::setState(STATE_SUCCESS, 'The logo has been successfully modified! If it does not display immediately, clear your cache!');
			}
		}
		// If no error occurs during the upload
		if (Controller::getState()['state'] != STATE_ERROR) {
			// Update the logo URL
			$logoURLUpdateStatus = BlogController::setLogoURL($logoPath);
			if ($logoURLUpdateStatus) {
				// If the modification succeeded, store a success message
				Controller::setState(STATE_SUCCESS, 'The logo has been successfully modified! If it does not display immediately, clear your cache!');
			} else {
				// If the modification failed, display an error message
				Controller::setState(STATE_ERROR, 'An error occurred while modifying the logo!');
			}
		}
	} else {
		// If no logo was uploaded

		// Delete the old logo
		foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo.*') as $imgFile) {
			// If it is a file and not a subfolder
			if (is_file($imgFile)) {
				// Delete the file
				unlink($imgFile);
			}
		}
		// If the URL field is empty, reset to the default URL
		if (!isset($_POST['fLogoURL']) || $_POST['fLogoURL'] == '') {
			$_POST['fLogoURL'] = '/img/logo.jpg';
		}
		$logoURLUpdateStatus = BlogController::setLogoURL($_POST['fLogoURL']);
		if ($logoURLUpdateStatus) {
			// If the modification succeeded, store a success message
			Controller::setState(STATE_SUCCESS, 'The logo URL has been successfully modified! If it does not display immediately, clear your cache!');
		} else {
			// If the modification failed, display an error message
			Controller::setState(STATE_ERROR, 'An error occurred while modifying the logo URL!');
		}
	}
}

// If the background image modification form was submitted
if (isset($_POST['fChangeBgURL'])) {
	// If the background image was uploaded
	if (!empty($_FILES) && $_FILES['fBgFile']['error'] != UPLOAD_ERR_NO_FILE) {
		// Possible upload error
		$error = $_FILES['fBgFile']['error'];

		if ($_FILES['fBgFile']['error'] != UPLOAD_ERR_OK || !$_FILES['fBgFile']['tmp_name']) {
			// If an error occurred during the upload, store the error message to display
			Controller::setState(STATE_ERROR, 'Error: The file could not be uploaded');
		} elseif (!preg_match('/image\//', $_FILES['fBgFile']['type'])) {
			// If the file is not an image, store the error message to display
			Controller::setState(STATE_ERROR, 'Your file must be an image or a video!');
		} elseif ($_FILES['fBgFile']['size'] > 10485760) {
			// If the file size is greater than 10MB, store the error message to display
			Controller::setState(STATE_ERROR, 'The file is too large!');
		} else {
			// Delete the old background image
			foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'background.*') as $imgFile) {
				// If it is a file and not a subfolder
				if (is_file($imgFile)) {
					// Delete the file
					unlink($imgFile);
				}
			}
			// Path of the background images
			$bgPath = '' . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR .
				// File name . extension
				'background.' . pathinfo($_FILES['fBgFile']['name'], PATHINFO_EXTENSION);
			// If the uploaded file was successfully moved
			if (
				!move_uploaded_file(
					// Temporary path of the uploaded file
					$_FILES['fBgFile']['tmp_name'],
					// Destination
					$_SERVER['DOCUMENT_ROOT'] . $bgPath
				)
			) {
				// If an error occurs, store the error message to display
				Controller::setState(STATE_ERROR, 'Unable to upload the file due to a server-side error');
			} else {
				// If the upload succeeded, store a success message
				Controller::setState(STATE_SUCCESS, 'The background image has been successfully modified! If it does not display immediately, clear your cache!');
			}
		}
		// If no error occurs during the upload
		if (Controller::getState()['state'] != STATE_ERROR) {
			// Update the background image URL
			$bgURLUpdateStatus = BlogController::setBackgroundURL($bgPath);
			if ($bgURLUpdateStatus) {
				// If the modification succeeded, store a success message
				Controller::setState(STATE_SUCCESS, 'The background image has been successfully modified! If it does not display immediately, clear your cache!');
			} else {
				// If the modification failed, display an error message
				Controller::setState(STATE_ERROR, 'An error occurred while modifying the background image!');
			}
		}
	} else {
		// If no background image was uploaded

		// Delete the old background image
		foreach (glob($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'background.*') as $imgFile) {
			// If it is a file and not a subfolder
			if (is_file($imgFile)) {
				// Delete the file
				unlink($imgFile);
			}
		}
		// If the URL field is empty, reset to the default URL
		if (!isset($_POST['fBgURL']) || $_POST['fBgURL'] == '') {
			$_POST['fBgURL'] = '/img/circuits.jpg';
		}
		$bgURLUpdateStatus = BlogController::setBackgroundURL($_POST['fBgURL']);
		if ($bgURLUpdateStatus) {
			// If the modification succeeded, store a success message
			Controller::setState(STATE_SUCCESS, 'The background image URL has been successfully modified! If it does not display immediately, clear your cache!');
		} else {
			// If the modification failed, display an error message
			Controller::setState(STATE_ERROR, 'An error occurred while modifying the background image URL!');
		}
	}
}