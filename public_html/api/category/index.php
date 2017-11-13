<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\Infrastructure\{
	Category
};

/**
 * API for the Category class
 *
 * @author Korigan Payne <koriganp@gmail.com>
 * @version 1.0.0
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/infrastructure.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$categoryName = filter_input(INPUT_GET, "categoryName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, that category is returned, otherwise all categories are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific category or all categories and update reply
		if(empty($id) === false) {
			$category = Category::getCategoryByCategoryId($pdo, $id);
			if($category !== null) {
				$reply->data = $category;
			}
		} else if(empty($categoryName) === false) {
			$categories = Category::getCategoryByCategoryName($pdo, $categoryName)->toArray();
			if($categories !== null) {
				$reply->data = $categories;
			}
		} else {
			$categories = Category::getAllCategories($pdo)->toArray();
			if($categories === null) {
				echo "nothing here";
			}
			if($categories !== null) {
				$reply->data = $categories;
			}
		}
	} else if($method === "PUT" || $method === "POST") {

		//enforce that the XSRF token is present in the header
		verifyXsrf();

		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestContent = file_get_contents("php://input");

		// This Line Then decodes the JSON package and stores that result in $requestObject
		$requestObject = json_decode($requestContent);

		//make sure category name is available (required field)
		if(empty($requestObject->categoryName) === true) {
			throw(new \InvalidArgumentException ("No named Category.", 405));
		}

		//perform the actual put or post
		if($method === "PUT") {
			// retrieve the category to update
			$category = Category::getCategoryByCategoryId($pdo, $id);
			if($category === null) {
				throw(new RuntimeException("Category does not exist", 404));
			}
		}

		//I AM STUCK RIGHT HERE!!!!!
		//OMG PLEASE HELP!!
		//I AM NOT EVEN SURE THE STUFF UP THERE IS RIGHT OR EVEN USEFUL!!

	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);