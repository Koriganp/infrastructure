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
 * @author KOrigan Payne <koriganp@gmail.com>
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
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$imageReportId = filter_input(INPUT_GET, "imageReportId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$imageCloudinary = filter_input(INPUT_GET, "imageCloudinary", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$imageLat = filter_input(INPUT_GET, "imageLat", FILTER_VALIDATE_FLOAT);
	$imageLong = filter_input(INPUT_GET, "imageLong", FILTER_VALIDATE_FLOAT);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}
	// handle GET request - if id is present, that image is returned, otherwise all images are returned
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
			$image = IMage::getImageByImageReportId($pdo, $_SESSION["report"]->getReportId())->toArray();
			if($image !== null) {
				$reply->data = $image;
			}
		} else {
			$images = Image::getAllImages($pdo)->toArray();
			if ($images === null) {
				echo "no images in report";
			}
			if($images !== null) {
				$reply->data = $images;
			}
		}
	} else if($method === "PUT" || $method === "POST") {
		verifyXsrf();

		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestContent = file_get_contents("php://input");

		// This Line Then decodes the JSON package and stores that result in $requestObject
		$requestObject = json_decode($requestContent);

		//make sure image cloudinary is available (required field)
		if(empty($requestObject->imageCloudinary) === true) {
			throw(new \InvalidArgumentException ("No cloudinary for Image.", 405));
		}

		//perform the actual put or post
		if($method === "PUT") {
			// retrieve the image to update
			$image = Image::getImageByImageId($pdo, $id);
			if($image === null) {
				throw(new RuntimeException("Image does not exist", 404));
			}

			//enforce the image updated is on the correct report
			if(empty($_SESSION["report"]) === true || $_SESSION["report"]->getReportId()->toString() !== $image->getimageReportId()->toString()) {
				throw(new \InvalidArgumentException("this isn't the correct report for this image", 403));
			}
			// update all attributes
			$image->setImageCloudinary($requestObject->imageCloudinary);
			$image->setImageLat($requestObject->imageLat);
			$image->setImageLong($requestObject->imageLong);
			$image->update($pdo);

			// update reply
			$reply->message = "Image added OK";
		} else if($method === "POST") {

			// enforce a report is started
			if(empty($_SESSION["report"]) === true) {
				throw(new \InvalidArgumentException("you must create a report to add images", 403));
			}
			// create new image and insert into the database
			$image = new Image(generateUuidV4(), $_SESSION["report"]->getReportId(), $requestObject->imageCloudinary, $requestObject->imageLat, $requestObject->imageLong);

			$image->insert($pdo);
			// update reply
			$reply->message = "Image added OK";
		}
	} else if($method === "DELETE") {
		//enforce that the end user has a XSRF token.
		verifyXsrf();
		// retrieve the Image to be deleted
		$image = Image::getImageByImageId($pdo, $id);
		if($image === null) {
			throw(new RuntimeException("Image does not exist", 404));
		}

		//enforce the image is on the correct report
		if(empty($_SESSION["report"]) === true || $_SESSION["report"]->getReportId()->toString() !== $image->getImageReportId()->toString()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this image", 403));
		}
		// delete image
		$image->delete($pdo);

		// update reply
		$reply->message = "Image deleted OK";
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

