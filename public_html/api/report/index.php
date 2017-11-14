<?php
/**
 * Report API
 *
 * @author Kevin D. Atkins
 */

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Infrastructure\ {
	Report, Category, Comment, Profile
};

//verify the xsrf challenge
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare default error message
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;


try {

	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/infrastructure.ini");

	// mock a logged in user by forcing the session. For test purposes and not in live code
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$reportCategoryId = filter_input(INPUT_GET, "reportCategoryid", FILTER_VALIDATE_INT);
	$reportContent = filter_input(INPUT_GET, "reportContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new \Nette\InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// handle GET request - if id is present, the report is returned, otherwise all reports are returned
	if($method === "GET") {

		//set XSRF cookie
		setXsrfCookie();

		//get a specific report or all reports and update reply
		if(empty($id) === false) {

			$report = Report::getReportByReportId($pdo, $id);
			if($report !== null) {
				$reply->data = $report;
			}
		} else if(empty($reportCategoryId) === false) {

			// if the category is valid, grab all reports by that category
			$reports = Report::getReportByReportCategoryId($pdo, $report->getReportByCategoryId()->toArray());
			if($reports !== null) {
				$reply->data = $reports;
			} else {

				$reports = Report::getAllReports($pdo)->toArray();
				if($reports === null) {
					echo "There are no reports";
				}

				if($reports !== null) {
					$reply->data = $reports;
				}
			}
		} else if ($method === "POST") {

			verifyXsrf();
			$requestContent = file_get_contents("php://input");
			// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
			$requestObject = json_decode($requestContent);
			// This line decodes the JSON package and stores that result in $requestObject

			//make sure report content is available (required field)
			if(empty($requestObject->reportContent === true)) {
				throw(new \InvalidArgumentException ("No content for Report.", 405));
			}

			// make sure report date is accurate (optional field)
			if(empty($requestObject->reportDateTime) === true) {
				$requestObject->reportDateTime = date("Y-m-d H:i:s.u");
			}

			if(empty($_SESSION["category"]) === true) {
				throw(new \InvalidArgumentException("You must choose a category to submit a report", 403));
			}

			// create new report and insert into database
			$report = new Report(generateUuidV4(), $requestObject->getReportCategoryId(), $requestObject->reportContent, $requestObject->reportDateTime, $_SERVER["REMOTE_ADDR"], $requestObject->reportLat, $requestObject->reportLong, $requestObject->reportStatus, $requestObject->reportUrgency, substr($_SERVER["HTTP_USER_AGENT"], 0, 255));
			$report->insert($pdo);

			// update reply
			$reply->message = "Report Submitted";

		} else if ($method === "DELETE") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			// retrieve the Report to be deleted
			$report = Report::getReportByReportId($pdo, $id);
			if($report === null) {

				throw(new RuntimeException("Report does not exist", 404));

			}

			//enforce the end user has a JWT token
			//validateJwtHeader();

			//enforce the user is signed in and only trying to delete a report
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("You are not allowed to delete this report", 403));
			}

			// delete report
			$report->delete($pdo);
			// update reply
			$reply->message = "Report was deleted";

		} else {

			throw (new InvalidArgumentException("Invalid HTTP method request", 418));

		}
	}

} catch (\Exception | \TypeError $exception) {

	// update the $reply->status $reply->message
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();

}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);