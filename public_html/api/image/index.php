<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\Infrastructure\{
	Image,
	// we only use the report and category class for testing purposes
	Report, Category
};

/**
 * API for the Image class
 *
 * @author Korigan Payne <koriganp@gmail.com>
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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$imageReportId = filter_input(INPUT_GET, "imageReportId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$imageCloudinary = filter_input(INPUT_GET, "imageCloudinary", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$imageLat = filter_input(INPUT_GET, "imageLat", FILTER_VALIDATE_FLOAT);
	$imageLong = filter_input(INPUT_GET, "imageLong", FILTER_VALIDATE_FLOAT);

	$config = readConfig("/etc/apache2/capstone-mysql/abqreport.ini");
	$cloudinary = json_decode($config["cloudinary"]);
	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);

	// handle GET request - if id is present, that image is returned, otherwise all images for that report are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//get a specific image or all images and update reply
		if(empty($id) === false) {
			$image = Image::getImageByImageId($pdo, $id);
			if($image !== null) {
				$reply->data = $image;
			}
		} else if(empty($imageReportId) === false) {
			// grab all the images for that report based on what report it is
			$image = Image::getImageByImageReportId($pdo, $_SESSION["report"]->getReportId())->toArray();
			if($image !== null) {
				$reply->data = $image;
			}
		}
	} else if($method === "POST") {
		//verify the xsrf token
		verifyXsrf();

		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestContent = file_get_contents("php://input");

		// This line then decodes the JSON package and stores that result in $requestObject
		$requestObject = json_decode($requestContent);

		//create a temporary report to attach image to
		$tempReport = $_FILES["image"]["tmp_name"];

		//upload the image to cloudinary
		$cloudinaryResult = \Cloudinary\Uploader::upload($tempReport, array("width" => 500, "crop" => "scale"));


		//make sure image cloudinary is available (required field)
		if(empty($requestObject->imageCloudinary) === true) {
			throw(new \InvalidArgumentException ("No cloudinary for Image.", 405));
		}

		// create new image and insert into the database
		$image = new Image(generateUuidV4(), $requestObject->imageReportId, $cloudinaryResult["secure_url"], $requestObject->imageLat, $requestObject->imageLong);

		$image->insert($pdo);
		// update reply
		$reply->message = "Image added OK";

	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request", 418));
	}
// update the $reply->status $reply->message
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

