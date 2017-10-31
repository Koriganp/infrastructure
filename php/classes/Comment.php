<?php
/**
 * This is the Comment entity. The Comment entity handles comments on reports.
 *
 * @author Jack Arnold
 * @version 7.1
 **/

namespace Edu\Cnm\Infrastructure;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Comment implements \JsonSerializable {
    use ValidateDate;
    use ValidateUuid;

    /**
     * Primary key, Id for comments posted on reports
     * @var Uuid $commentId
     **/
    private $commentId;

    /**
     * Foreign key, Id for the profile that posted the comment in question
     * @var Uuid $commentProfileId
     **/
    private $commentProfileId;

    /**
     * Foreign key, Id for the report that the comment in question was posted on
     * @var Uuid $commentReportId
     **/
    private $commentReportId;

    /**
     * The content of the comment posted by the user profile
     * @var string $commentContent
     **/
    private $commentContent;

    /**
     * This is the date and time the comment was posted
     * @var \DateTime $commentDateTime
     **/
    private $commentDateTime;

    /**
     * Constructor for the comment
     *
     * @param Uuid $commentId id of the comment
     * @param Uuid $commentProfileId id of the profile that posted the comment
     * @param Uuid $commentReportId id of the report the comment was posted on
     * @param string $commentContent the text written in the comment
     * @param \DateTime|string|null $commentDateTime the
     * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
     * @throws \TypeError if data types violate type hints
     * @throws \Exception if some other exception occurs
     **/
    public function __construct($newCommentId, $newCommentProfileId, $newCommentReportId, string $newCommentContent, $newCommentDate = null) {
       try {
      	 	$this->setCommentId($newCommentId);
				$this->setCommentProfileId($newCommentProfileId);
				$this->setCommentReportId($newCommentReportId);
				$this->setCommentContent($newCommentContent);
				$this->setCommentDate($newCommentDate);
    } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
        $exceptionType = get_class($exception);
        throw(new $exceptionType($exception->getMessage(), 0, $exception));
    }
}

/**
 * accessor method for commentId
 *
 * @return Uuid of commentId
 **/
public function getCommentId() : Uuid {
    return $this->commentId;
}

public function setCommentId() : Uuid {
    try {
        $uuid = self::validateUuid($newCommentId);
    } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
        $exceptionType = get_class($exception);
        throw(new $exceptionType($exception->getMessage(), 0, $exception));
    }
    $this->commentId = $uuid;
}
}