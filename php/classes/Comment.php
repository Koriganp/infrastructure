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
     * @param Uuid $newCommentId id of the comment
     * @param Uuid $newCommentProfileId id of the profile that posted the comment
     * @param Uuid $newCommentReportId id of the report the comment was posted on
     * @param string $newCommentContent the text written in the comment
     * @param \DateTime|string|null $newCommentDateTime the
     * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
     * @throws \TypeError if data types violate type hints
     * @throws \Exception if some other exception occurs
	  * @Documentation https://php.net/manual/en/language.oop5.decon.php
     **/
    public function __construct($newCommentId, $newCommentProfileId, $newCommentReportId, string $newCommentContent, $newCommentDateTime = null) {
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

	/**
	 * mutator method for comment id
	 *
	 * @param Uuid/string $newCommentId new value of comment id
	 * @throws \RangeException if $newCommentId is not positive
	 * @throws \TypeError if $newCommentId is not a uuid or string
	 **/
    public function setCommentId($newCommentId) : void {
        try {
            $uuid = self::validateUuid($newCommentId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
        //convert and store the comment id
        $this->commentId = $uuid;
    }

    /**
     * accessor method for commentProfileId
     *
     * @return Uuid of commentProfileId
     **/
    public function getCommentProfileId() : Uuid {
        return $this->commentProfileId;
    }

    /**
	  * mutator method for comment profile Id
	  *
     * @param string/Uuid $newCommentProfileId new value of commentProfileId
     * @throws \InvalidArgumentException if data types are invalid
     * @throws \RangeException if string values are too long
     * @throws \TypeError if data types are invalid
     * @throws \Exception for any other exception
     **/
    public function setCommentProfileId($newCommentProfileId) : void {
        try {
            $uuid = self::validateUuid($newCommentProfileId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
		 // convert and store the profile id
        $this->commentProfileId = $uuid;
    }

    /**
     * accessor method for commentReportId
     *
     * @return Uuid of commentReportId
     **/
    public function getCommentReportId() : Uuid {
        return $this->commentReportId;
    }

    /**
	  * mutator method for comment report id
	  *
     * @param string $newCommentReportId new value of commentReportId
     * @throws \InvalidArgumentException if data types are invalid
     * @throws \RangeException if string values are too long
     * @throws \TypeError if data types are invalid
     * @throws \Exception for any other exception
     **/
    public function setCommentReportId($newCommentReportId) : void {
        try {
            $uuid = self::validateUuid($newCommentReportId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
        $this->commentReportId = $uuid;
    }

    /**
     * accessor method for commentContent
     *
     * @return string returns the comment content
     **/
    public function getCommentContent() :string {
        return($this->commentContent);
    }

    /**
	  * mutator method for comment content
	  *
     * @param string $newCommentContent new value of commentContent
     * @throws \InvalidArgumentException if data types are invalid
     * @throws \RangeException if string values are too long
     * @throws \TypeError if $newCommentContent is not a string
     * @throws \Exception for any other exception
     **/
    public function setCommentContent($newCommentContent) : void {
        $newCommentContent = trim($newCommentContent);
        $newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        if(empty($newCommentContent) === true) {
            throw(new \InvalidArgumentException("commentContent is empty or insecure"));
        }
        if(strlen($newCommentContent) > 3000) {
            throw(new \RangeException("commentContent is too large"));
        }
        $this->commentContent = $newCommentContent;
    }

	/**
	 * accessor method for comment date
	 *
	 * @return \DateTime value of comment date
	 **/
	public function getCommentDateTime() : \DateTime {
		return($this->commentDateTime);
	}

	/**
	 * mutator method for comment date
	 *
	 * @param \DateTime|string|null $newCommentDateTime comment date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newCommentDateTime is not a valid object or string
	 * @throws \RangeException if $newCommentDateTime is a date that does not exist
	 **/
	public function setCommentDateTime($newCommentDateTime = null) : void {
		// base case: if the date is null, use the current date and time
		if($newCommentDateTime === null) {
			$this->CommentDateTime = new \DateTime();
			return;
		}
		// store the like date using the ValidateDate trait
		try {
			$newCommentDateTime = self::validateDateTime($newCommentDateTime);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->commentDateTime = $newCommentDateTime;
	}

    /**
     * Inserts this Comment into MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function insert(\PDO $pdo) :void {

        $query = "INSERT INTO comment(commentId, commentProfileId, commentReportId, commentContent, commentDateTime) VALUES(:commentId, :commentProfileId, :commentReportId, :commentContent, :commentDateTime)";
        $statement = $pdo->prepare($query);
        $formattedDate = $this->commentDateTime->format("Y-m-d H:i:s.u");
        $parameters = ["commentId" => $this->commentId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentReportId" => $this->commentReportId->getBytes(), "commentContent" => $this->commentContent, "commentDateTime" => $formattedDate];
        $statement->execute($parameters);
    }

    /**
     * Updates this Comment in MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function update(\PDO $pdo) {
        if($this->commentId === null) {
            throw(new("Unable to update nonexistent comment"));
        }
        $query = "UPDATE `comment` SET commentId = :commentId, commentProfileId = :commentProfileId, commentReportId = :commentReportId, commentContent = :commentContnt, commentDateTime = :commentDateTime";
        $statement = $pdo->prepare($query);
        $parameters = ["commentId" => $this->commentId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentProfileId" => $this->commentReportId->getBytes(), "commentContent" => $this->commentContent, "commentDateTime" => $this->commentDateTime];
        $statement->execute($parameters);
    }

    /**
     * Deletes this Comment from MySQL
     *
     * @param \PDO $pdo PDO connection object
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function delete(\PDO $pdo): void {
        //enforce the commentId is not null (don't delete a comment that does not exist)
        if($this->commentId === null) {
            throw(new \PDOException("unable to delete a comment that does not exist"));
        }
        //create query template
        $query = "DELETE FROM comment WHERE commentId = :commentId";
        $statement = $pdo->prepare($query);
        //bind the member variables to the place holders in the template
        $parameters = ["commentId" => $this->commentId];
        $statement->execute($parameters);
    }
    /**
     * gets the Comment by the comment's id
     *
     * @param \PDO $pdo PDO connection object
     * @param Uuid $commentId comment id to search for
     * @return Comment|null Comment or null if not found
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when variables are not the correct data type
     **/
    public static function getCommentByCommentId(\PDO $pdo, $commentId) : ?Comment {
        try {
            $commentId = self::validateUuid($commentId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            throw (new \PDOException($exception->getMessage(), 0, $exception));
        }
        $query = "SELECT commentId FROM comment WHERE commentId = :commentId";
        $statement = $pdo->prepare($query);
        $parameters = ["commentId" = $commentId];
        $statement->execute($parameters);
        try {
            $comment = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if($row !== false) {
                $comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentReportId"]);
        } catch(\Exception $exception) {
                throw(new\PDOException($exception->getMessage(), 0, $exception));
            }
		return ($category);
    }


    public function jsonSerialize() {
        $fields = get_object_vars($this);

        $fields["commentId"] = $this->commentId->toString();
        $fields["commentProfileId"] = $this->commentProfileId->toString();
        $fields["commentReportId"] = $this->commentReportId->toString();
			 //format the date so that the front end can consume it
			 $fields["commentDate"] = round(floatval($this->commentDate->format("U.u")) * 1000);
			 return($fields);
    }
}
}