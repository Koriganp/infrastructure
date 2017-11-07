<?php
/**
 * Unit Test for 'Report' Class
 *
 * @author Kevin Atkins
 **/
namespace Edu\Cnm\Infrastructure\Test;

use Edu\Cnm\Infrastructure\{Profile, Report};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

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
	protected $VALID_UPDATEDREPORTCONTENT = "There's trash all over the park! ADMIN-UPDATE: This issue should be resolved by X Department.";

	/**
	 * timestamp of the Report; this starts as null and is assigned later
	 * @var \DateTime $VALID_REPORTDATETIME
	 **/
	protected $VALID_REPORTDATETIME = null;

	/**
	 * Valid timestamp to use as a sunriseReportDateTime
	 **/
	protected $VALID_SUNRISEDATETIME = null;

	/**
	 * Valid timestamp to use as sunsetReportDateTime
	 **/
	protected $VALID_SUNSETDATETIME = null;

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
}

/**
 * test inserting a valid tweet and verify that the actual mySQL data matches
 **/
public function testInsertValidReport() : void {
	// count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("report");

	// create a new Report and insert to into mySQL
	$reportId = generateUuidV4();
	$report = new Report($reportId, $this->report->getProfileId);

}