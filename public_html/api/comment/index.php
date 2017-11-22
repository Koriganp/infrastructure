<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Infrastructure\{
    Comment,
    // we only use the profile class for testing purposes
    Report, Category
};

/**
 * API for the Comment class
 *
 * @author Jack Arnold
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
    $pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $commentProfileId = filter_input(INPUT_GET, "commentProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $commentReportId = filter_input(INPUT_GET, "commentReportId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $commentContent = filter_input(INPUT_GET, "commentContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
        throw(new InvalidArgumentException("id cannot be empty or negative", 405));
    }

    if($method === "GET") {
        setXsrfCookie();

        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);

        if(empty($id) === false) {
            $comment = Comment::getCommentByCommentId($pdo, $id);
            if ($comment !== null) {
                $reply->data = $comment;
            }
        } else if(empty($commentProfileId) === false) {
            $comment = Comment::getCommentByCommentProfileId($pdo, $commentProfileId)->toArray();
            if($comment !== null) {
                $reply->data = $comment;
            }
        } else if(empty($commentReportId) === false) {
            $comment = Comment::getCommentByCommentReportId($pdo, $requestObject->commentReportId)->toArray();
            if ($comment !== null) {
                $reply->data = $comment;
            }
        } else if(empty($commentContent) === false) {
            $comments = Comment::getCommentByCommentContent($pdo, $commentContent)->toArray();
            if($comments !== null) {
                $reply->data = $comments;
            }
        }
    } else if($method === "PUT" || $method === "POST") {

        verifyXsrf();

        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);

        //make sure the comment content is available
        if(empty($requestObject->commentContent) === true) {
            throw(new \InvalidArgumentException ("No content for Comment", 405));
        }

        // make sure the comment date is accurate
        if(empty($requestObject->commentDateTime) === true) {
			  // if the date exists, Angular's milliseconds since the beginning of time MUST be converted
			  $commentDateTime = DateTime::createFromFormat("U.u", $requestObject->commentDateTime / 1000);
			  if($commentDateTime === false) {
				  throw(new RuntimeException("invalid comment date", 400));
			  }
			  $requestObject->commentDateTime = $commentDateTime;
        }

        // perform the actual put or post
        if($method === "PUT") {
            $comment = Comment::getCommentByCommentId($pdo, $id);
            if($comment === null) {
                throw(new RuntimeException("Comment does not exist", 404));
            }

            // ensure the user is signed in
            if(empty($_SESSION["profile"]) === true) {
                throw(new \InvalidArgumentException("You are not authorized to edit this comment"));
            }

            validateJwtHeader();

            // update all attributes
            $comment->setCommentDateTime($requestObject->commentDateTime);
            $comment->setCommentContent($requestObject->commentContent);
            $comment->update($pdo);

            $reply->message = "Comment updated OK";

        } else if($method === "POST") {

            if(empty($_SESSION["profile"]) === true) {
                throw(new \InvalidArgumentException("You must be logged in to post comments", 403));
            }

            validateJwtHeader();

            // create new comment and insert it into the database
            $comment = new Comment(generateUuidV4(), $_SESSION["profile"]->getProfileId(), $requestObject->commentReportId, $requestObject->commentContent, $requestObject->commentDateTime);
            $comment->insert($pdo);

            // update reply
            $reply->message = "Comment created OK";
        }
    } else if($method === "DELETE") {

        // ensure that the end user has an XSRF token
        verifyXsrf();

        // retrieve the Comment to be deleted
        $comment = Comment::getCommentByCommentId($pdo, $id);
        if($comment === null) {
            throw(new RuntimeException("Comment does not exist", 404));
        }

        // ensure the user is signed in and only trying to delete their own comment
        if(empty($_SESSION["profile"]) === true) {
            throw(new \InvalidArgumentException("You are not allowed to delete this comment", 403));
        }

        validateJwtHeader();

        // delete comment
        $comment->delete($pdo);
        // update reply
        $reply->message = "Comment deleted OK";
    } else {
        throw (new InvalidArgumentException("Invalid HTTP method request", 418));
    }
} catch(\Exception | \TypeError $exception) {
    $reply->status = $exception->getCode();
    $reply->message = $exception->getMessage();
}

header("Content-type: application/json");

if($reply->data === null) {
    unset($reply->data);
}

echo json_encode($reply);