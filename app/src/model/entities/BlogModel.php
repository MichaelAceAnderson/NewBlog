<?php
// If the user is not using this file in a context other than from the index.php page, redirect to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

final class Blog
{
	/* METHODS */

	/* Insertions */
	// Install the blog database
	public static function installDB(): bool|Exception
	{
		// Delete all post images in case of reinstallation
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
			if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR)) {
				// If deletion failed
				// Log the error
				Model::printLog('Unable to delete all post images!');
				return false;
			}
		}

		// Delete all post videos in case of reinstallation
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR) && is_dir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
			if (!Model::rmdir_r($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR)) {
				// If deletion failed
				// Log the error
				Model::printLog('Unable to delete all post videos!');
				return false;
			}
		}
		// Attempt to install the blog database
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('Unable to install the blog database: The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Attempt to install the database via the SQL file
				try {
					// Get the content of the SQL installation file
					$sqlFile = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'NewBlogDB_install.sql');
					// If the file could not be read
					if (!$sqlFile) {
						// Throw an error that will be caught below
						throw new Exception('The SQL installation file could not be read!');
					}
					// Execute the content of the SQL file
					if (Model::getPdo()->exec($sqlFile) === false) {
						// If an error occurs, throw an exception that will be caught below
						throw new Exception('The SQL installation file could not be executed!');
					}

					// Log the success
					Model::printLog('Database installation successful!');
					// Return success
					return true;
				} catch (Exception $e) {
					// If an error occurred
					// Log the error
					Model::printLog(Model::getError($e));
					// Throw a new error that will be caught below
					throw new Exception('Database installation failed!');
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
	// Insert blog information
	public static function insertBlog(string $blogName, string $description, int $adminId, string $bgURL = ''): bool|Exception
	{
		// Attempt to insert blog information into the database
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				throw new Exception('Unable to insert blog information: The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// If the background image was not specified
				if (empty($bgURL)) {
					// Define the query to be processed
					$stmt = Model::getPdo()->prepare(
						"INSERT INTO newblog.nb_blog (blog_name, description, id_user_owner)
							VALUES (:blog_name, :description, :id_user_owner);"
					);
					// If the query could not be prepared
					if (!$stmt) {
						// Throw an error that will be caught below
						throw new Exception('Unable to prepare the blog data insertion query!');
					}
					// Prepare a query with all the info except the background image
					Model::setStmt($stmt);
				} else {
					// If the background image is defined

					// Prepare a query with all the info
					$stmt = Model::getPdo()->prepare("INSERT INTO newblog.nb_blog (blog_name, description, background_url, id_user_owner)
							VALUES (:blog_name, :description, :background_url, :id_user_owner);");
					// If the query could not be prepared
					if (!$stmt) {
						// Throw an error that will be caught below
						throw new Exception('Unable to prepare the blog data insertion query!');
					}

					// Define the query to be processed
					Model::setStmt($stmt);

					// Attach the background image as a parameter to the prepared query
					if (!Model::getStmt()->bindParam('background_url', $bgURL, PDO::PARAM_STR)) {
						// If the background image could not be attached
						// Throw an error that will be caught below
						throw new Exception('Unable to attach the background image as a parameter to the blog data insertion query!');
					}
				}
				// Attach the blog name as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('blog_name', $blogName, PDO::PARAM_STR)) {
					// If the blog name could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the blog name as a parameter to the blog data insertion query!');
				}
				// Attach the blog description as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('description', $description, PDO::PARAM_STR)) {
					// If the blog description could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the blog description as a parameter to the blog data insertion query!');
				}
				// Attach the blog owner's user ID (previously inserted) as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('id_user_owner', $adminId, PDO::PARAM_INT)) {
					// If the blog owner's user ID could not be attached
					// Throw an error that will be caught below
					throw new Exception('Unable to attach the blog owner\'s user ID as a parameter to the blog data insertion query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('An error occurred while executing the blog data insertion query!');
				} else {
					// If the query was executed

					if (Model::getStmt()->rowCount() > 0) {
						// If insertion was successful
						// Log the success
						Model::printLog('Blog data insertion into the database successful: Name = "' . $blogName . '", Description = "' . $description . '", Admin ID = "' . $adminId . '", Background Image: "' . $bgURL . '"');
						// Return success
						return true;
					} else {
						// If insertion was not successful
						// Throw an error that will be caught below
						throw new Exception('Blog data insertion into the database failed!');
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

	/* Retrievals */
	// Retrieve the first blog
	public static function selectBlog(): array|Exception
	{
		// Attempt to retrieve the first blog inserted into the database
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('Unable to retrieve blog information: The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"SELECT * 
					FROM newblog.nb_blog 
					LIMIT 1"
				);
				if (!$stmt) {
					// If the query could not be prepared
					// Throw an error that will be caught below
					throw new Exception('The blog data retrieval query could not be prepared!');
				}
				// Define the query to be processed
				Model::setStmt($stmt);

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('An error occurred during the blog data retrieval query!');
				} else {
					// If the query succeeded, return the results
					$result = Model::getStmt()->fetchAll();
					// If the results could be retrieved
					if ($result === false) {
						// If the results could not be retrieved
						// Throw an error that will be caught below
						throw new Exception('The blog information could not be retrieved!');
					} else {
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
	// Retrieve the blog by name
	public static function selectBlogByName(string $blogName): array|Exception
	{
		// Attempt to retrieve the blog whose name is passed as a parameter
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// If the connection could not be created
				throw new Exception('Unable to retrieve blog information: The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"SELECT * 
					FROM newblog.nb_blog 
					WHERE newblog.nb_blog.blog_name = :blogname"
				);
				if (!$stmt) {
					// If the query could not be prepared
					throw new Exception('The blog data retrieval query could not be prepared!');
				}

				// Define the query to be processed
				Model::setStmt($stmt);
				// Attach the blog name as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('blogname', $blogName, PDO::PARAM_STR)) {
					// If the blog name could not be attached
					throw new Exception('Unable to attach the blog name as a parameter to the blog data retrieval query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The blog data retrieval query failed!');
				} else {
					$result = Model::getStmt()->fetchAll();
					// If the results could be retrieved
					if ($result === false) {
						// If the results could not be retrieved
						// Throw an error that will be caught below
						throw new Exception('The blog information could not be retrieved!');
					} else {
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
	/* Modifications */
	// Change the blog name
	public static function updateBlogName(string $newBlogName): bool|Exception
	{
		// Attempt to change the blog name
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"UPDATE newblog.nb_blog 
					SET blog_name = :newBlogName"
				);
				if (!$stmt) {
					// If the query could not be prepared
					throw new Exception('The blog name update query could not be prepared!');
				}

				// Define the query to be processed
				Model::setStmt($stmt);
				// Attach the new blog name as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('newBlogName', $newBlogName, PDO::PARAM_STR)) {
					// If the blog name could not be attached
					throw new Exception('Unable to attach the blog name as a parameter to the blog name update query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The blog name update query failed!');
				} else {
					// If the query succeeded
					// If the update was successful
					if (Model::getStmt()->rowCount() > 0) {
						// Log the success
						Model::printLog('The blog name has been successfully updated to "' . $newBlogName . '"!');
						// Return success
						return true;
					} else {
						// If the update was not successful
						// Throw an error that will be caught below
						throw new Exception('The blog name could not be updated!');
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
	// Change the description
	public static function updateDescription(string $newDescription): bool|Exception
	{
		// Attempt to update the blog description
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"UPDATE newblog.nb_blog 
					SET description = :newDescription"
				);
				if (!$stmt) {
					// If the query could not be prepared
					throw new Exception('The blog description update query could not be prepared!');
				}
				// Define the query to be processed
				Model::setStmt($stmt);
				// Attach the new description as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('newDescription', $newDescription, PDO::PARAM_STR)) {
					// If the description could not be attached
					throw new Exception('Unable to attach the description "' . $newDescription . '" as a parameter to the blog description update query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The blog description update query failed!');
				} else {
					// If the update was successful
					if (Model::getStmt()->rowCount() > 0) {
						// Log the success
						Model::printLog('The blog description has been successfully updated to "' . $newDescription . '"!');
						// Return success
						return true;
					} else {
						// If the update was not successful
						// Throw an error that will be caught below
						throw new Exception('The blog description could not be updated!');
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
	// Change the blog logo
	public static function updateLogoURL(string $newLogoURL): bool|Exception
	{
		// Attempt to update the blog logo
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"UPDATE newblog.nb_blog 
					SET logo_url = :newLogo"
				);
				if (!$stmt) {
					// If the query could not be prepared
					throw new Exception('The blog logo update query could not be prepared!');
				}
				// Define the query to be processed
				Model::setStmt($stmt);
				// Attach the new logo as a parameter to the prepared query
				if (!Model::getStmt()->bindParam('newLogo', $newLogoURL, PDO::PARAM_STR)) {
					// If the logo could not be attached
					throw new Exception('Unable to attach the logo "' . $newLogoURL . '" as a parameter to the blog logo update query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The blog logo update query failed!');
				} else {
					// If the update was successful
					if (Model::getStmt()->rowCount() > 0) {
						// Log the success
						Model::printLog('The blog logo has been successfully updated to "' . $newLogoURL . '"!');
						// Return success
						return true;
					} else {
						// If the update was not successful
						// Throw an error that will be caught below
						throw new Exception('The blog logo could not be updated!');
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
    // Change the background image URL
    public static function updateBackgroundURL(string $newBackground): bool|Exception
    {
        // Try to update the blog background
        try {
            // If the database connection couldn't be created
            if (is_null(Model::getPdo())) {
                // Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
            } else {
                // If the connection was successful
                // Prepare the query
                $stmt = Model::getPdo()->prepare(
                    "UPDATE newblog.nb_blog 
                SET background_url = :newBackground"
                );
                if (!$stmt) {
                    // If the query couldn't be prepared
                    throw new Exception('The blog background update query could not be prepared!');
                }
                // Define the query to be processed
                Model::setStmt($stmt);
                // Attach the new background as a parameter to the prepared query
                if (!Model::getStmt()->bindParam('newBackground', $newBackground, PDO::PARAM_STR)) {
                    // If the background couldn't be attached
                    throw new Exception('Unable to attach the background "' . $newBackground . '" as a parameter to the blog background update query!');
                }

                // Execute the query
                if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The blog background update query failed!');
				} else {
					// If the update was successful
					if (Model::getStmt()->rowCount() > 0) {
						// Log the success
						Model::printLog('The blog background has been successfully updated to "' . $newBackground . '"!');
						// Return success
						return true;
					} else {
						// If the update was not successful
						// Throw an error that will be caught below
						throw new Exception('The blog background could not be updated!');
					}
				}
            }
        } catch (Exception $e) {
            // If an error occurred
            // We log the error
            Model::printLog(Model::getError($e));
            // We return the error
            return $e;
        }
    }
}