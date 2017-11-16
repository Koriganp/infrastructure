<?php

require_once(dirname(__DIR__,3) ."/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__,3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Infrastructure\ {
	Profile
};

/**
 * API for Profile
 *
 * @author Tanisha Purnell
 * version 1.0
 *
 **/

//verify the session, if it is not active start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileUsername = filter_input(INPUT_GET, "profileUsername", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for the methods that require it by content
	if($method === "GET") {
		//set XRSF cookie
		setXsrfCookie();

		if(empty($id) === false) {
			$profile = Profile::getProfileByProfileId($pdo, $id);
			if($profile !== null) {
				$reply->data = $profile;
			}
		} else if(empty($profileUsername) === false) {
			$profile = Profile::getProfileByProfileUsername($pdo, $profileUsername);
			if($profile !== null) {
				$reply->data = $profile;
			}
		} else if(empty($profileEmail) === false) {
			$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
			if($profile !== null) {
				$reply->data = $profile;
			}
		}
	} else if ($method === "PUT") {
		//enforce that the XSRF token is in the header
		verifyXsrf();

		//enforce the end user has JWT token
		//ValidateJWTHeader();

		//enforce the user is signed in and only trying and only trying to edit their profile
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 403));
		}

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//retrieve the profile to be updated
		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw(new \RuntimeException("Profile does not exist", 404));
		}

		//profile Username
		if(empty($requestObject->profileUsername) === true) {
			throw(new \InvalidArgumentException("No profile Username present", 405));
		}

		$profile->setProfileUsername($requestObject->profileUsername);
		$profile->setProfileEmail($requestObject->profileEmail);
		$profile->update($pdo);

		// update reply
		$reply->message = "Profile information updated";

	} else if ($method === "DELETE") {

		//Verify XRSF token
		verifyXsrf();

		//enforce the end user has a JWT token
		//validateJwtHeader();

		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw (new \RuntimeException("Profile does not exist"));
		}
		//enforce the user is signed in and only trying to edit their own profile
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $profile->getProfileId()) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 403));
		}

		//delete the post from the database
		$profile->delete($pdo);
		$reply->message = "Profile Deleted";

	} else {
		throw (new \InvalidArgumentException(("Invalid HTTP request"), 400));
	}
} catch (\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);