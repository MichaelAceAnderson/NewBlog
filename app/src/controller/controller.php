<?php

// If the user is not using this file in another context
// than from the index.php page, redirect them to the homepage
if ($_SERVER['PHP_SELF'] != '/index.php') {
	echo '<meta http-equiv="refresh" content="0; url=/" />';
	header('Location: /');
	exit();
}

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'model.php';

// Code relying on the model and called by the views' forms

class Controller
{
	/* PROPERTIES/ATTRIBUTES */
	// State of the last performed action
	private static int $state = STATE_NONE;
	// Message of the last performed action
	private static string $message = '';

	/* DATA OUTPUT FUNCTIONS */
	// Set the page content as a Json output
	public static function returnJsonHttpResponse(bool $success, string|array $data): void
	{
		// Remove all strings
		// likely to create an invalid JSON
		// such as PHP warnings, errors, logs...
		ob_clean();

		// Remove previous headers
		header_remove();

		// Set the content type to JSON in UTF-8 (can be changed)
		header('Content-Type: application/json; charset=utf-8');

		// Determine if the request should return a success or not
		// HTTP success code: 2xx; HTTP error code: 4xx, 5xx
		if ($success) {
			http_response_code(200);
		} else {
			http_response_code(500);
		}
		// Encode the PHP array into a JSON string
		echo json_encode($data);

		// Ensure nothing else is added to the response
		exit();
	}

	// Return an array of data from Json data
	public static function returnJsonFromArray(mixed $dbArray): void
	// Only works when PDO is configured in FETCH_ASSOC?
	{
		$jsonResult = array();
		while ($row = $dbArray->fetchAll()) {
			$jsonResult[] = $row;
		}
		// Handle error if needed
		self::returnJsonHttpResponse(true, $jsonResult);
	}

	// Retrieve JSON data obtained via POST request
	public static function jsonToVar(): mixed
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	/* OTHER METHODS */
	// Write to a log file
	public static function printLog(string $msg): bool
	{
		$date = new DateTime();
		$date = $date->format('d-m-y h:i:s');
		if (LOGLEVEL < 1) {
			// If the log level is less than 1, do not log
			return false;
		}
		$logFile = fopen($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'controller.log', 'a+');
		if (!$logFile) {
			// If it is impossible to open the log file
			return false;
		}
		if (!fwrite($logFile, PHP_EOL . '[' . $date . '] Controller: ' . $msg)) {
			// If it is impossible to write to the log file
			return false;
		}
		if (!fclose($logFile)) {
			// If it is impossible to close the log file
			return false;
		}
		return true;
	}
	// Set the state to be displayed in the views
	public static function setState(int $state, string $message): void
	{
		self::$state = $state;
		self::$message = $message;
	}
	// Retrieve the state of the last performed action
	public static function getState(): array
	{
		return array('state' => self::$state, 'message' => self::$message);
	}
}

/// NOTE: It is not possible to include via a foreach, the order must be followed according to the interdependence of controllers/data

// Include the user controller
require_once __DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'UserController.php';
// Include the blog controller
require_once __DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'BlogController.php';
// Include the post controller
require_once __DIR__ . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'PostController.php';