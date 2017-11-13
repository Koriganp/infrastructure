<?php
/**
 * Sign-Out API
 *
 * @author Kevin D. Atkins
 */
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
// require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

//verify the xsrf challenge
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare default error message
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the mySQL connection
	$pdo = connectToEncryptedMySQL("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// mock a logged in user by forcing the session. For test purposes and not in live code

	//determine which HTTP method was used
	//$method = array_key_exists("HTTP_");


} catch(\Exception | \TypeError $exception) {

}