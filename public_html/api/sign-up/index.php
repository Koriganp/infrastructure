<?php

require_once (dirname(__DIR__, 3)) . "/vendor/autoload.php";
require_once (dirname(__DIR__, 3)) . "/php/classes/autoload.php";
require_once (dirname(__DIR__, 3)) . "/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

//$config = readConfig("/etc/apache2/capstone-mysql/abqreport.ini");

use Edu\Cnm\Infrastructure\ {
	signup
};

/**
 * api for signing up too DDC Twitter
 *
 * @author Tansiha Purnell <tpurnell@cnm.edu>
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
// grab mySQL connection
	$pdo = $pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	if($method === "POST") {
		//decode the json and turn it into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//profile email is a required field
		if(empty($requestObject->profileEmail) === true) {
			throw(new \InvalidArgumentException ("No profile email present", 405));
		}

		//profile username is a required field
		if(empty($requestObject->profileUsername) === true) {
			throw(new \InvalidArgumentException ("No profile Username", 405));
		}

		//verify that the profile password is present
		if(empty($requestObject->profilePassword) === true) {
			throw(new \InvalidArgumentException ("Must input valid password", 405));
		}

		//verify that the confirmed password is present
		if(empty($requestObject->profilePasswordConfirm)=== true) {
			throw(new \InvalidArgumentException("Must input valid password", 405));
		}

		//make sure the password and confirm password match
		if ($requestObject->profilePassword !== $requestObject->profilePasswordConfirm) {
			throw(new \InvalidArgumentException("passwords do not match"));
		}
		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $requestObject->profilePassword, $salt, 262144);

		$profileActivationToken = bin2hex(random_bytes(16));

		//create the profile object and prepare it to insert into database
		$profile = new Profile(null, $profileActivationToken, $requestObject->profileUsername, $requestObject->profileEmail, $hash, $salt);

		//insert profile into database
		$profile->insert($pdo);

		//compose email the email message to send with the activation token
		$messageSubject = "One step closer to account activation";

		//builds activation link that can travel to another server and still work . This is the link that is clicked to confirm the account that was created.
		//make sure URL is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);

		//create path
}
