<?php
// If the user is not using this file in another context
// than from the index.php page, redirect to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

// Code relying on the model and called by the views' forms

class PostController
{
	/* METHODS */

	/* Insertions */
	// Add a post
	public static function createPost(int $authorId, string $title, string $summary, string $tags, string $content): int
	{
		// Attempt to add the post to the database
		$result = Post::insertPost($authorId, $title, $summary, $tags, $content);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while creating the post "' . $title . '" by user "' . $authorId . '" with content "' . $content . '", summary "' . $summary . '" and tags "' . $tags . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return success/failure
			return $result;
		}
	}

	/* Retrievals */
	// Retrieve a post
	public static function getPost(int $postId): array|false
	{
		// Attempt to retrieve the post from the database
		$result = Post::selectPost($postId);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the post "' . $postId . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return the query result (array)
			return $result;
		}
	}
	// Retrieve all posts
	public static function getAllPosts(): array|false
	{
		// Attempt to retrieve the posts from the database
		$result = Post::selectPosts();
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the posts!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return the query result (array)
			return $result;
		}
	}
	// Retrieve the id of the next post to create
	public static function getNextPostId(): int|false
	{
		// Attempt to retrieve the id of the next post to create from the database
		$result = Post::selectLastPostId();
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while retrieving the id of the next post!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return the query result (id of the next post)
			return $result + 1;
		}
	}
	/* Modifications */
	// Change a user's username
	public static function changeUsername(int $id, string $newNickname): bool
	{
		// Attempt to change the username in the database
		$result = User::updateUsername($id, $newNickname);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while changing the username of user "' . $id . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return success/failure
			return $result;
		}
	}
	// Change a user's password
	public static function changeUserPassword($id, $newPassword): bool
	{
		// Attempt to change the password in the database
		$result = User::updateUserPassword($id, $newPassword);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while changing the password of user "' . $id . '" to "' . $newPassword . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return success/failure
			return $result;
		}
	}

	/* Deletions */
	// Reset posts
	public static function clearPosts(): int
	{
		// Attempt to delete all posts from the database and associated files
		$result = Post::deletePosts();
		// If an error occurred, display and log it
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while deleting the posts!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return -1 to indicate that the request failed
			return -1;
		} else {
			// If the operation was performed in the database
			// Return the number of rows deleted (0 or more)
			return $result;
		}
	}
	// Delete a post
	public static function deletePost(int $postId): bool
	{
		// Attempt to delete the post from the database
		$result = Post::deletePost($postId);
		// If an error occurred during the model call
		if ($result instanceof Exception) {
			// Define the controller error
			$result = new Exception('An error occurred while deleting the post "' . $postId . '"!');
			// Log the error
			Controller::printLog(Model::getError($result));
			// Return failure
			return false;
		} else {
			// If the operation was performed in the database
			// Return success/failure
			return $result;
		}
	}
}
/* FORM HANDLING */

// If a post creation form is submitted
if (isset($_POST['fPost'])) {
	// Check that the post title is not empty
	if (!isset($_POST['fPostTitle']) || empty($_POST['fPostTitle'])) {
		// If the post title is empty
		// Display an error message
		Controller::setState(STATE_ERROR, 'The post title is empty');
	} elseif (strlen($_POST['fPostTitle']) > 64) {
		// If the title is too long
		// Display an error message
		Controller::setState(STATE_ERROR, 'The post title cannot exceed 64 characters!');
	} elseif (!isset($_POST['fPostSummary']) || empty($_POST['fPostSummary'])) {
		// If the post summary is empty
		// Display an error message
		Controller::setState(STATE_ERROR, 'The post summary cannot be empty!');
	} elseif (!isset($_POST['fPostContent']) || empty($_POST['fPostContent'])) {
		// If the post content is empty
		// Display an error message
		Controller::setState(STATE_ERROR, 'The post content cannot be empty!');
	} elseif (!isset($_POST['fPostTags']) || empty($_POST['fPostTags'])) {
		// If the post tags are empty
		// Display an error message
		Controller::setState(STATE_ERROR, 'You must specify tags!');
	} else {
		// Retrieve the id of the next post to create
		$postId = PostController::getNextPostId();
		// If an error occurred while retrieving the id of the next post
		if (!$postId) {
			// Display an error message
			Controller::setState(STATE_ERROR, 'An error occurred while communicating with the database');
		} else {
			// If a file was uploaded
			if (!empty($_FILES) && $_FILES['fPostMedia']['error'] != UPLOAD_ERR_NO_FILE) {
				// Possible upload error
				$error = $_FILES['fPostMedia']['error'];
				// If an error occurred during the upload
				if ($_FILES['fPostMedia']['error'] != UPLOAD_ERR_OK || !$_FILES['fPostMedia']['tmp_name']) {
					// Store the error message to display
					Controller::setState(STATE_ERROR, 'Error: The file could not be uploaded');
				} elseif ((!preg_match('/video\//', $_FILES['fPostMedia']['type'])) && !preg_match('/image\//', $_FILES['fPostMedia']['type'])) {
					// If the file is not an image or a video
					// Store the error message to display
					Controller::setState(STATE_ERROR, 'Your file must be an image or a video!');
				} elseif ($_FILES['fPostMedia']['size'] > 1000000000) {
					// If the file size is greater than 10MB
					// Store the error message to display
					Controller::setState(STATE_ERROR, 'The file is too large!');
				} else {
					if (preg_match('/image\//', $_FILES['fPostMedia']['type'])) {
						// If the file is an image

						// If the post image storage folder does not exist
						if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
							// Create the post image storage folder
							mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR);
						}
						// Create the post image folder
						mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $postId);
						// Place it in the post image folder
						$mediaUrl = DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $postId . DIRECTORY_SEPARATOR . $_FILES['fPostMedia']['name'];
					} elseif (preg_match('/video\//', $_FILES['fPostMedia']['type'])) {
						// If the file is a video

						// If the post video storage folder does not exist
						if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
							// Create it
							mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR);
						}
						// Create the post video folder
						mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $postId);
						// Place it in the post video folder
						$mediaUrl = DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $postId . DIRECTORY_SEPARATOR . $_FILES['fPostMedia']['name'];
					}
					if (!move_uploaded_file($_FILES['fPostMedia']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $mediaUrl)) {
						Controller::setState(STATE_ERROR, 'Unable to upload the file due to a server-side error');
					}
				}
			}
		}
	}
	// If there were no errors
	if (Controller::getState()['state'] != STATE_ERROR) {
		// Attempt to add the post to the database
		$postCreation = PostController::createPost($_SESSION['id_user'], $_POST['fPostTitle'], $_POST['fPostSummary'], $_POST['fPostTags'], $_POST['fPostContent']);
		if (!$postCreation) {
			// If an error occurs, store the error message to display
			Controller::setState(STATE_ERROR, 'An error occurred while adding the post');
		} else {
			// If the post was successfully added, store the success message to display
			Controller::setState(STATE_SUCCESS, 'The post has been successfully added!');
		}
	}
}

// If a form to delete all posts is submitted
if (isset($_POST['fClearPosts'])) {
	// Attempt to delete all posts from the database
	if (PostController::clearPosts() < 0) {
		// Otherwise, store the error message to display
		Controller::setState(STATE_ERROR, 'An error occurred while deleting the posts');
	} else {
		// If the posts were successfully deleted, store the success message to display
		Controller::setState(STATE_SUCCESS, 'All posts have been successfully deleted!');
	}
}

// If a form to delete a specific post is submitted
if (isset($_POST['fDeletePostId'])) {
	// Attempt to delete the specified post from the database
	if (!PostController::deletePost($_POST['fDeletePostId'])) {
		// Otherwise, store the error message to display
		Controller::setState(STATE_ERROR, 'An error occurred while deleting the post');
	} else {
		// If the post was successfully deleted, store the success message to display
		Controller::setState(STATE_SUCCESS, 'The post has been successfully deleted!');
	}
}