<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
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
    $pdo = connectEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

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

        if(empty($id) === false) {
            $comment = Comment::getCommentByCommentId($pdo, $id);
            if($comment !== null) {
                $reply->data = $comment;
            } else if(empty($commentProfileId) === false) {

            }
        }
    }
}