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
        $pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
        $this->assertEquals($pdoComment->getCommentId(), $commentId);
        $this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
        $this->assertEquals($pdoComment->getCommentReportId(), $this->report->getReportId());
        $this->assertEquals($pdoComment->getCommentContent(), $this->VALID_CONTENT2);
        $this->assertEquals($pdoComment->getCommentDateTime(), $this->VALID_DATE);
    }
}