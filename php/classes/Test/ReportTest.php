<?php
/**
 * Unit Test for 'Report' Class
 *
 * @author Kevin Atkins
 **/
namespace Edu\Cnm\Infrastructure\Test;

use Edu\Cnm\Infrastructure\{Profile, Report, Category};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

//// grab the uuid generator
//require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Report Class
 *
 * This is a complete PHPUnit test of the Tweet class.
 * It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both valid and invalid inputs
 *
 * @see Report
 **/
class ReportTest extends InfrastructureTest {
	/**
	 * Profile that views the Report; this is for foreign key relations
	 * @var Profile profile
	 */
	protected $profile = null;

	/**
	 * valid profile hash to create the profile object to own the test
	 * @var $VALID_HASH
	 **/
	protected $VALID_PROFILE_HASH;

	/**
	 * valid salt to use to create the profile object to own the test
	 * @var string $VALID_SALT
	 **/
	protected $VALID_PROFILE_SALT;

	/**
	 * content of the Report
	 * @var string $VALID_REPORTCONTENT
	 */
	protected $VALID_REPORTCONTENT = "There's trash all over the park!";

	/**
	 * content of the updated Report
	 * @var string $VALID_UPDATEDREPORTCONTENT
	 **/
	protected $VALID_APPENDREPORTCONTENT = "There's trash all over the park! ADMIN-UPDATE: This issue should be resolved by X Department.";

	/**
	 * timestamp of the Report; this starts as null and is assigned later
	 * @var \DateTime $VALID_REPORTDATETIME
	 **/
	protected $VALID_REPORTDATETIME = null;

	/**
	 * valid ip address attached to report made
	 * @var string $VALID_IPADDRESS
	 **/
	protected $VALID_IPADDRESS = "1001::dead:beef:cafe";

	/**
	 * float value of latitude (Range: -90 - 90)
	 * @var float $VALID_REPORTLAT
	 **/
	protected $VALID_REPORTLAT = 89.98754;

	/**
	 * float value of longitude (Range: -180 - 180)
	 * @var float $VALID_REPORTLONG
	 **/
	protected $VALID_REPORTLONG = 175.87778;

	/**
	 * status of report made
	 * @var string $VALID_STATUS
	 **/
	protected $VALID_STATUS = "REPORTED";

	/**
	 * urgency of report made
	 * @var int $VALID_URGENCY
	 */
	protected $VALID_URGENCY = 1;

	/**
	 * Valid timestamp to use as a sunriseReportDateTime
	 **/
	protected $VALID_SUNRISEDATETIME = null;

	/**
	 * Valid timestamp to use as sunsetReportDateTime
	 **/
	protected $VALID_SUNSETDATETIME = null;

	/**
	 * valid user agent
	 * @var string $VALID_USERAGENT
	 */
	protected $VALID_USERAGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36";

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_SALT = bin2hex(random_bytes(32));
		$this->VALID_PROFILE_HASH = hash_pbkdf2("sha512", $password, $this->VALID_PROFILE_SALT, 216144);

		// create and insert a Profile to own the test Report
		$this->profile = new Profile(generateUuidV4(), null,"admin1", "test@phpunit.de", $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_SALT);

		$this->profile->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_REPORTDATETIME = new \DateTime();

		// format the sunrise date to use for testing
		$this->VALID_SUNRISEDATETIME = new \DateTime();
		$this->VALID_SUNRISEDATETIME->sub(new \DateInterval("P10D"));

		// format the sunset date to use for testing
		$this->VALID_SUNSETDATETIME = new \DateTime();
		$this->VALID_SUNSETDATETIME->add(new \DateInterval("P10D"));
	}

	/**
	 * test inserting a valid tweet and verify that the actual mySQL data matches
	 **/
	public function testInsertValidReport() : void {
	// count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("report");

	// create a new Report and insert to into mySQL
	$report = new Report(null, null, $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLONG, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
	$report->insert($this->getPDO());

	// grab the date from mySQL and enforce the fields match our expectations
	$pdoReport = Report::getReportByReportId($this->getPDO(), $report->getReportId());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
	$this->assertEquals($pdoReport->getReportCategoryId(), $this->profile->getProfileId());
	$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
	// format the date to seconds since the beginning of time to avoid round off error
	$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
	}

	/**
	 * test inserting a Report that already exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidReport() : void {
		// create a Report with a non null tweet id and watch it fail
		$report = new Report(InfrastructureTest::INVALID_KEY, $this->VALID_REPORTCONTENT, );
		$report->insert($this->getPDO());
	}
}