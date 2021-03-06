<?php
namespace Edu\Cnm\Infrastructure\Test;
use Edu\Cnm\Infrastructure\{Profile, Report, Comment, Category};
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Comment class
 *
 * This is a complete PHPUnit test of the Comment class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Comment
 * @author <>
 **/
class CommentTest extends InfrastructureTest {
    /**
     * make profile that report is associated with, for foreign keys
     *
     * @var Profile $profile
     **/
    protected $profile = null;

    /**
     * make category that report is associated with, for foreign keys
     *
     * @var Category $category
     **/
    protected $category = null;

    /**
     * make report that comment is associated with, for foreign keys
     *
     * @var Report $report
     **/
    protected $report = null;

    /**
     * placeholder
     *
     * @var string $VALID_ACTIVATION
     **/
    protected $VALID_ACTIVATION;

    /**
     * @var string $VALID_USERNAME;
     **/
    protected $VALID_USERNAME = "George";

    /**
     * @var string $VALID_EMAIL
     **/
    protected $VALID_EMAIL = "holy@shit.com";

    /**
     * @var string $VALID_HASH
     **/
    protected $VALID_HASH;

    /**
     * @var string $VALID_SALT
     **/
    protected $VALID_SALT;

    /**
     * timestamp of the mocked report; starts at null and is assigned later
     * @var \DateTime $VALID_DATE
     */
    protected $VALID_DATE = null;

    /**
     * valid IPAddress for mocked report
     * @var string $VALID_IPADDRESS
     */
    protected $VALID_IPADDRESS = "1001101000110011";

    /**
     * valid report status for report class
     * @var string $VALID_REPORTSTATUS
     */
    protected $VALID_REPORTSTATUS = "Received";

    /**
     * valid cloudinary to use to create an image
     * @var string $VALID_CLOUDINARY;
     **/
    protected $VALID_CLOUDINARY = "https://res.cloudinary.com/demo/image/upload/w_400,h_400,c_crop,g_face,r_max/w_200/lady.jpg";

    /**
     * valid latitude to use to create an image
     * @var float $VALID_LAT;
     **/
    protected $VALID_LAT = 41.40338;

    /**
     * valid longitude to use to create an image
     * @var float $VALID_LAT;
     **/
    protected $VALID_LONG = 2.17403;

    /**
     * valid content to fill
     * @var string $VALID_CONTENT
     **/
    protected $VALID_CONTENT = "a string";

    /**
     * valid content to fill
     * @var string $VALID_CONTENT2
     **/
    protected $VALID_CONTENT2 = "another string";

    /**
     * create dependant objects before running each test
     **/
    public final function setUp() : void {
        //run the default setUp() method first
        parent::setUp();
        //create mocked up profile
        $password = "123";
        $this->VALID_SALT = bin2hex(random_bytes(32));
        $this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
        $this->VALID_ACTIVATION = bin2hex(random_bytes(16));
        $profileId = generateUuidV4();
        $this->profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT);
        $this->profile->insert($this->getPDO());

        //create and insert a mocked category for the mocked report
        $categoryId = generateUuidV4();
        $this->category = new Category($categoryId, "Streets and Roads");
        $this->category->insert($this->getPDO());

        //create and insert a mocked report
        $this->VALID_DATE = new \DateTime();
        $reportId = generateUuidV4();
        $this->report = new Report($reportId, $this->category->getCategoryId(), "there is a hole", $this->VALID_DATE, $this->VALID_IPADDRESS, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_REPORTSTATUS, 1, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36");
        $this->report->insert($this->getPDO());
    }

    /**
     * test inserting a valid comment and make sure the SQL data matches
     **/
    public function testInsertValidComment() : void {
        $numRows = $this->getConnection()->getRowCount("comment");
        $commentId = generateUuidV4();
        $this->VALID_DATE = new \DateTime();
        $comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
        $comment->insert($this->getPDO());
        $pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $this->assertEquals($pdoComment->getCommentId(), $commentId);
        $this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
        $this->assertEquals($pdoComment->getCommentReportId(), $this->report->getReportId());
        $this->assertEquals($pdoComment->getCommentContent(), $this->VALID_CONTENT);
        $this->assertEquals($pdoComment->getCommentDateTime(), $this->VALID_DATE);
    }

    /**
     * test inserting a valid comment, then updating it
     **/
    public function testUpdateValidComment() : void {
        $numRows = $this->getConnection()->getRowCount("comment");
        $commentId = generateUuidV4();
        $this->VALID_DATE = new \DateTime();
        $comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
        $comment->insert($this->getPDO());
        $comment->setCommentContent($this->VALID_CONTENT2);
        $comment->update($this->getPDO());
        $pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $this->assertEquals($pdoComment->getCommentId(), $commentId);
        $this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
        $this->assertEquals($pdoComment->getCommentReportId(), $this->report->getReportId());
        $this->assertEquals($pdoComment->getCommentContent(), $this->VALID_CONTENT2);
        $this->assertEquals($pdoComment->getCommentDateTime(), $this->VALID_DATE);
    }

    /**
     * test deleting a valid comment
     **/
    public function testDeleteValidComment() : void {
        $numRows = $this->getConnection()->getRowCount("comment");
        $commentId = generateUuidV4();
        $this->VALID_DATE = new \DateTime();
        $comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
        $comment->insert($this->getPDO());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $comment->delete($this->getPDO());
        $pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
        $this->assertNull($pdoComment);
        $this->assertEquals($numRows, $this->getConnection()->getRowCount("comment"));
    }

    /**
     * test getting comment by comment id
     **/
    public function testGetValidCommentByCommentId() : void {
        $numRows = $this->getConnection()->getRowCount("comment");
        $commentId = generateUuidV4();
        $this->VALID_DATE = new \DateTime();
        $comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
        $comment->insert($this->getPDO());
        $results = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $this->assertEquals($results->getCommentId(), $comment->getCommentId());
    }

    /**
     * test getting comment that doesn't exist by comment id
     **/
    public function testGetInvalidCommentByCommentId() : void {
        $comment = Comment::getCommentByCommentId($this->getPDO(), generateUuidV4());
        $this->assertNull($comment);
    }

    /**
     * test getting a comment that does exist by the report's id
     **/
    public function testGetValidCommentByCommentReportId() : void {
        $numRows = $this->getConnection()->getRowCount("comment");
        $commentId = generateUuidV4();
        $this->VALID_DATE = new \DateTime();
        $comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
        $comment->insert($this->getPDO());
        $results = Comment::getCommentByCommentReportId($this->getPDO(), $this->report->getReportId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $this->assertCount(1, $results);
        $this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Comment", $results);
        $pdoComment = $results[0];
        $this->assertEquals($pdoComment->getCommentReportId(), $this->report->getReportId());
    }

    /**
     * test getting a comment that does exist by the profile's id
     **/
    public function testGetValidCommentByCommentProfileId() : void {
        $numRows = $this->getConnection()->getRowCount("comment");
        $commentId = generateUuidV4();
        $this->VALID_DATE = new \DateTime();
        $comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
        $comment->insert($this->getPDO());
        $results = Comment::getCommentByCommentProfileId($this->getPDO(), $this->profile->getProfileId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $this->assertCount(1, $results);
        $this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Comment", $results);
        $pdoComment = $results[0];
        $this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
    }

	/**
	 * test grabbing a Comment by comment content
	 **/
	public function testGetValidCommentByCommentContent() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create a new Comment and insert to into mySQL
		$commentId = generateUuidV4();
		$this->VALID_DATE = new \DateTime();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->report->getReportId(), $this->VALID_CONTENT, $this->VALID_DATE);
		$comment->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentContent($this->getPDO(), $comment->getCommentContent());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Comment", $results);
		// grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentReportId(), $this->report->getReportId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_CONTENT);
	}
}