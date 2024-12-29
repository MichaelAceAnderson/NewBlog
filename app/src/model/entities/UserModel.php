<?php

// If the user is not using this file in a context other than from the index.php page, redirect to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

class User
{
	/* METHODS */

	/* Insertions */
	// Create a user in the database
	public static function insertUser(string $nickname, string $password, bool $is_mod): bool|Exception
	{
		// Try to add a user
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'INSERT INTO newblog.nb_user (nickname, password, is_mod) VALUES (:nickname, :password, :is_mod)'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user addition query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Bind the nickname parameter to the prepared query
				if (!Model::getStmt()->bindParam('nickname', $nickname, PDO::PARAM_STR)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The nickname "' . $nickname . '" could not be bound to the query!');
				}
				// Hash the password
				$password = password_hash($password, PASSWORD_ARGON2ID);
				if (!$password) {
					// If the hash could not be created
					throw new Exception('Unable to create a hash for the password!');
				}
				// Bind the password parameter to the prepared query
				if (!Model::getStmt()->bindParam('password', $password, PDO::PARAM_STR)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The password "' . $password . '" could not be bound to the query!');
				}
				// Bind the admin boolean parameter to the prepared query
				if (!Model::getStmt()->bindParam('is_mod', $is_mod, PDO::PARAM_BOOL)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The admin boolean (' . $is_mod ? 'true' : 'false' . ') could not be bound to the query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If an error occurs during query execution
					throw new Exception('An error occurred during the execution of the query!');
				} else {
					// If insertion succeeded
					if (Model::getStmt()->rowCount() > 0) {
						// Return success
						return true;
					} else {
						// If insertion did not succeed
						// Throw an error that will be caught below
						throw new Exception('The user insertion could not be completed!');
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
	// Retrieve the array of users
	public static function selectUsers(): array|Exception
	{
		// Try to retrieve the users
		try {
			if (is_null(Model::getPdo())) {
				// If the connection could not be created
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'SELECT * FROM newblog.nb_user'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user retrieval query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('An error occurred during the execution of the user retrieval query!');
				} else {
					// Retrieve the results
					$result = Model::getStmt()->fetchAll();
					// If the result retrieval failed
					if ($result === false) {
						// Throw an error that will be caught below
						throw new Exception('The user retrieval failed!');
					} else {
						// If the result retrieval succeeded
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
	// Retrieve a user's row by their id
	public static function selectUserById(int $id): array|Exception
	{
		// Try to retrieve the user by their id
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'SELECT * FROM newblog.nb_user WHERE newblog.nb_user.id_user = :id'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user data retrieval query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);

				// Bind the user id parameter to the prepared query
				if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_STR)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The user id "' . $id . '" could not be bound to the query!');
				}

				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					throw new Exception('The user data retrieval query for id ' . $id . ' failed!');
				} else {
					// If the query succeeded
					// Retrieve the results
					$result = Model::getStmt()->fetchAll();
					// If the result retrieval failed
					if ($result === false) {
						// Throw an error that will be caught below
						throw new Exception('The user data retrieval query for id ' . $id . ' failed!');
					} else {
						// If the result retrieval succeeded
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
	// Retrieve a user's row by their nickname
	public static function selectUserByName(string $nickname): array|Exception
	{
		// Try to retrieve the user by their nickname
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded
				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					"SELECT *
					FROM newblog.nb_user 
					WHERE newblog.nb_user.nickname = :nickname"
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user data retrieval query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Bind the user nickname parameter to the prepared query
				if (!Model::getStmt()->bindParam('nickname', $nickname, PDO::PARAM_STR)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The user nickname "' . $nickname . '" could not be bound to the user data retrieval query!');
				}
				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('The user data retrieval query for nickname "' . $nickname . '" failed!');
				} else {
					// If the query succeeded
					// Retrieve the results
					$result = Model::getStmt()->fetchAll();
					// If the result retrieval failed
					if ($result === false) {
						// Throw an error that will be caught below
						throw new Exception('The user data retrieval query for nickname "' . $nickname . '" failed!');
					} else {
						// If the result retrieval succeeded
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
	// Change a user's nickname
	public static function updateUserName(int $id, string $newNickname): bool|Exception
	{
		// Try to change a user's nickname
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'UPDATE newblog.nb_user SET nickname = :newNickname WHERE newblog.nb_user.id_user = :id;'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user nickname update query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Bind the user id parameter to the prepared query
				if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The user id "' . $id . '" could not be bound to the user nickname update query!');
				}
				// Bind the new nickname parameter to the prepared query
				if (!Model::getStmt()->bindParam('newNickname', $newNickname, PDO::PARAM_STR)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The new nickname "' . $newNickname . '" could not be bound to the user nickname update query!');
				}
				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('An error occurred during the execution of the user nickname update query!');
				} else {
					// If update succeeded
					if (Model::getStmt()->rowCount() > 0) {
						// Return success
						return true;
					} else {
						// If update did not succeed
						// Throw an error that will be caught below
						throw new Exception('The user nickname update could not be completed!');
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
	// Change a user's password
	public static function updateUserPassword(int $id, string $newPassword): bool|Exception
	{
		// Try to change a user's password
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'UPDATE newblog.nb_user SET password = :newPassword WHERE newblog.nb_user.id_user = :id;'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user password update query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Bind the user id parameter to the prepared query
				if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The user id "' . $id . '" could not be bound to the user password update query!');
				}
				// Hash the password
				$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
				if (!$newPassword) {
					// If the hash could not be created
					throw new Exception('Unable to create a hash for the password!');
				}
				// Bind the new password parameter to the prepared query
				if (!Model::getStmt()->bindParam('newPassword', $newPassword, PDO::PARAM_STR)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The new password "' . $newPassword . '" could not be bound to the user password update query!');
				}
				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('An error occurred during the execution of the user password update query!');
				} else {
					// If update succeeded
					if (Model::getStmt()->rowCount() > 0) {
						// Return success
						return true;
					} else {
						// If update did not succeed
						// Throw an error that will be caught below
						throw new Exception('The user password update could not be completed!');
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

	/* Deletions */
	// Delete a user
	public static function deleteUser(int $id): bool|Exception
	{
		// Try to delete a user
		try {
			// If the connection could not be created
			if (is_null(Model::getPdo())) {
				// Throw an error that will be caught below
				throw new Exception('The connection to the database could not be established!');
			} else {
				// If the connection succeeded

				// Prepare the query
				$stmt = Model::getPdo()->prepare(
					'DELETE FROM newblog.nb_user WHERE newblog.nb_user.id_user = :id;'
				);
				// If the query could not be prepared
				if (!$stmt) {
					// Throw an error that will be caught below
					throw new Exception('The user deletion query could not be prepared!');
				}
				// Set the query to be processed
				Model::setStmt($stmt);
				// Bind the user id parameter to the prepared query
				if (!Model::getStmt()->bindParam('id', $id, PDO::PARAM_INT)) {
					// If the parameter could not be bound
					// Throw an error that will be caught below
					throw new Exception('The user id "' . $id . '" could not be bound to the user deletion query!');
				}
				// Execute the query
				if (Model::getStmt()->execute() === false) {
					// If the query could not be executed
					// Throw an error that will be caught below
					throw new Exception('An error occurred');
				} else {
					// If deletion succeeded
					if (Model::getStmt()->rowCount() > 0) {
						// Return success
						return true;
					} else {
						// If deletion did not succeed
						// Throw an error that will be caught below
						throw new Exception('The user deletion could not be completed!');
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