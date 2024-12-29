<?php
// If the user is not using this file in another context
// than from the index.php page, redirect to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

class Post
{
	/* METHODS */
	// Create a post in the database
	public static function insertPost(int $authorId, string $title, string $summary, string $tags, string $content): int|Exception
	{
		// Try to add the post to the database
		try {
			// If the connection could not be established
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'INSERT INTO newblog.nb_post (title, summary, tags, content, id_user_author) VALUES (:title, :summary, :tags, :content, :id_user_author);'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The post insertion query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Attach the post title as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('title', $title, PDO::PARAM_INT)) {
					// If the parameter could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the post title "' . $title . '" as a parameter to the post insertion query!');
				}
				// Attach the post summary as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('summary', $summary, PDO::PARAM_INT)) {
					// If the parameter could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the post summary "' . $summary . '" as a parameter to the post insertion query!');
				}
				// Attach the post tags as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('tags', $tags, PDO::PARAM_INT)) {
					// If the parameter could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the post tags "' . $tags . '" as a parameter to the post insertion query!');
				}
				// Attach the post content as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('content', $content, PDO::PARAM_STR)) {
					// If the parameter could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the post content "' . $content . '" as a parameter to the post insertion query!');
				}
				// Attach the author ID as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('id_user_author', $authorId, PDO::PARAM_INT)) {
					// If the parameter could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the author ID "' . $authorId . '" as a parameter to the post insertion query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('An error occurred while executing the post insertion query!');
				} else {
					// If insertion succeeded
					if (Model::getStmt()->rowCount() > 0) {
						// Try to retrieve the post ID
						$result = self::selectLastPostId();
						// If the post ID could not be retrieved
						if ($result instanceof Exception) {
							// Throw an error that will be caught below
							throw new Exception('The inserted post ID could not be retrieved!');
						} else {
							// If the post ID could be retrieved
							// Return the post ID
							return $result;
						}
					} else {
						// If insertion failed
						// Throw an error that will be caught below
						throw new Exception('The post insertion into the database failed!');
					}
				}
			}
		} catch (Exception $e) {
			// If an error occurred
			// Log the error
			Model::printLog(Model::getError($e));
			// Return the error
			return $e;
		}
	}
	// Retrieve the list of posts
	public static function selectPosts(): array|Exception
	{
		// Try to retrieve the posts
		try {
			// If the connection could not be established
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'SELECT * FROM newblog.nb_post ORDER BY time_stamp DESC;'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The post retrieval query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The post retrieval query failed!');
				} else {
					// If the query succeeded, retrieve the results
					$result = Model::getStmt()->fetchAll();
					// If the results could not be retrieved
					if ($result === false) {
						// Throw an error that will be caught below
						throw new Exception('The list of posts could not be retrieved!');
					} else {
						// If the results could be retrieved
						// Return the results
						return $result;
					}
				}
			}
		} catch (Exception $e) {
			// If an error occurred
			// Log the error
			Model::printLog(Model::getError($e));
			// Return the error
			return $e;
		}
	}
	// Retrieve a single post row
	public static function selectPost(int $postId): array|Exception
	{
		// Try to retrieve the specified post
		try {
			// If the connection could not be established
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"SELECT newblog.nb_post.id_user_author, newblog.nb_user.nickname, newblog.nb_post.title, newblog.nb_post.summary, newblog.nb_post.tags, newblog.nb_post.content, newblog.nb_post.time_stamp
					FROM newblog.nb_post JOIN newblog.nb_user
					ON newblog.nb_post.id_user_author=nb_user.id_user
					WHERE newblog.nb_post.id_post=:id_post"
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The post retrieval query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Attach the post ID to the retrieval query
				if (!Model::getStmt()->bindParam('id_post', $postId, PDO::PARAM_INT)) {
					// If the post ID could not be attached to the query
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the post ID "' . $postId . '" as a parameter to the post retrieval query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The post retrieval query failed!');
				} else {
					// If the query succeeded
					if (Model::getStmt()->rowCount() > 0) {
						// Retrieve the results
						$result = Model::getStmt()->fetchAll();
						// If the results could not be retrieved
						if ($result === false) {
							// Throw an error that will be caught below
							throw new Exception('The specified post could not be retrieved!');
						} else {
							// If the results could be retrieved
							// Return the results
							return $result;
						}
					} else {
						// If the query succeeded but there are no results
						throw new Exception('The specified post does not exist!');
					}
				}
			}
		} catch (Exception $e) {
			// If an error occurred
			// Log the error
			Model::printLog(Model::getError($e));
			// Return the error
			return $e;
		}
	}

	// Retrieve the ID of the last created post
	public static function selectLastPostId(): int|Exception
	{
		// Try to retrieve the ID of the last post
		try {
			// If the connection could not be established
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'SELECT pg_sequence_last_value(\'newblog.post_seq\');'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The query to retrieve the ID of the last inserted post could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The query to retrieve the ID of the last inserted post failed!');
				} else {
					// If the query succeeded, retrieve the results
					$result = Model::getStmt()->fetch()->pg_sequence_last_value;
					// If the results could not be retrieved
					if (is_null($result)) {
						// We don't know if it's an error or if the sequence is not initialized
						// Return 0
						return 0;
					} else {
						// If the results could be retrieved
						// Return the results
						return $result;
					}
				}
			}
		} catch (Exception $e) {
			// If an error occurred
			// Log the error
			Model::printLog(Model::getError($e));
			// Return the error
			return $e;
		}
	}
	// Delete all posts from the database
	public static function deletePosts(): int|Exception
	{
		// Try to delete the posts
		try {
			// Recursively delete the videos related to the posts if they exist
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
				if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
					// If the deletion of the files failed
					// Throw an error that will be caught below
					throw new Exception('The deletion of the videos related to the posts failed!');
				}
			}
			// Recursively delete the images related to the posts if they exist
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img') && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
				if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
					// If the deletion of the files failed
					// Throw an error that will be caught below
					throw new Exception('The deletion of the images related to the posts failed!');
				}
			}
			// If the connection could not be established
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'DELETE FROM newblog.nb_post'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The query to delete the posts could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					throw new Exception('An error occurred while executing the query to delete the posts!');
				} else {
					// If deletion succeeded, return the number of deleted items
					return Model::getStmt()->rowCount();
				}
			}
		} catch (Exception $e) {
			// If an error occurred
			// Log the error
			Model::printLog(Model::getError($e));
			// Return the error
			return $e;
		}
	}

	// Delete a post
	public static function deletePost(int $id): bool|Exception
	{
		// Check if the post exists
		$post = self::selectPost($id);
		// If an error occurred while retrieving the post
		if ($post instanceof Exception) {
			// Return the error
			return new Exception('An error occurred while retrieving the post to delete!');
		} else {
			// If the post exists
			if ($post) {

				// Recursively delete the files related to the posts if they exist and are directories
				if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $id) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $id)) {
					Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $id);
				}
				if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $id) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $id)) {
					Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'image' . $id);
				}

			} else {
				// If the post does not exist
				// Log the error
				Model::printLog('The post you want to delete does not exist!');
				// Return an error
				return new Exception('The post you want to delete does not exist!');
			}

			// Try to delete the specified post
			try {
				// If the connection could not be established
				if (is_null(Model::getPdo())) {
					// Throw an error that will be caught below
					throw new Exception('The connection to the database could not be established!');
				} else {
					// If the connection succeeded
					// Prepare the query
					$stmt = Model::getPdo()->prepare(
						'DELETE FROM newblog.nb_post WHERE newblog.nb_post.id_post = :id;'
					);
					// If the query could not be prepared
					if (!$stmt) {
						// Throw an error that will be caught below
						throw new Exception('The post deletion query could not be prepared!');
					}
					// Set the query to be processed
					Model::setStmt($stmt);
					// Attach the post ID to delete to the prepared query
					if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
						// If the parameter attachment failed
						// Throw an error that will be caught below
						throw new Exception('An error occurred while attaching the id parameter to the post deletion query!');
					}

					// Execute the query
					if (Model::getStmt()->execute() === false) {
						// If the query could not be executed
						// Throw an error that will be caught below
						throw new Exception('An error occurred while executing the post deletion query!');
					} else {
						// If deletion succeeded
						if (Model::getStmt()->rowCount() > 0) {
							// Return success
							return true;
						} else {
							// If deletion failed
							// Throw an error that will be caught below
							throw new Exception('The post deletion could not be completed!');
						}
					}
				}
			} catch (Exception $e) {
				// If an error occurred
				// Log the error
				Model::printLog(Model::getError($e));
				// Return the error
				return $e;
			}
		}
	}
}