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
 * This is a complete PHPUnit test of the Report class.
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
	 * Category that the Report is associated with; this is for foreign key relations
	 * @var Category $category
	 */
	protected $category = null;

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
	protected $VALID_REPORTLAT = 41.40338;

	/**
	 * float value of longitude (Range: -180 - 180)
	 * @var float $VALID_REPORTLONG
	 **/
	protected $VALID_REPORTLONG = 2.17403;

	/**
	 * status of report made
	 * @var string $VALID_STATUS
	 **/
	protected $VALID_STATUS = "REPORTED";

	/**
	 * Valid timestamp to use as a sunriseReportDateTime
	 **/
	protected $VALID_SUNRISEDATETIME = null;

	/**
	 * Valid timestamp to use as sunsetReportDateTime
	 **/
	protected $VALID_SUNSETDATETIME = null;

	/**
	 * urgency of report made
	 * @var int $VALID_URGENCY
	 */
	protected $VALID_URGENCY = 1;

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

		//create and insert a mocked category for the mocked report
		$categoryId = generateUuidV4();
		$this->category = new Category($categoryId, "Streets and Roads");
		$this->category->insert($this->getPDO());

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
	 * test inserting a valid report and verify that the actual mySQL data matches
	 **/
	public function testInsertValidReport() : void {
	// count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("report");

	$reportId = generateUuidV4();
	// create a new Report and insert to into mySQL
	$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
	$report->insert($this->getPDO());

	// grab the date from mySQL and enforce the fields match our expectations
		$pdoReport = Report::getReportByReportId($this->getPDO(), $report->getReportId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportId(), $reportId);
		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		// format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
	}

	/**
	 * test inserting a valid appended report and verify that the actual mySQL data matches
	 **/
	public function testInsertAppendReport() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// create a new Report and insert to into mySQL
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_APPENDREPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());

		// edit the report and update it in mySQL
		$report->setReportContent($this->VALID_APPENDREPORTCONTENT);
		$report->update($this->getPDO());

		// grab the date from mySQL and enforce the fields match our expectations
		$pdoReport = Report::getReportByReportId($this->getPDO(), $report->getReportId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportId(), $report->getReportId());
		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_APPENDREPORTCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
	}


//	/**
//	 * test inserting a Report that already exists
//	 *
//	 * @expectedException \PDOException
//	 **/
//	public function testInsertInvalidReport() : void {
//		//$reportId = generateUuidV4();
//		// create a Report with a non null report id and watch it fail
//		$report = new Report(InfrastructureTest::INVALID_KEY, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
//		$report->insert($this->getPDO());
//		self::assertEquals($this->getPDO(), );
//	}

//	/**
//	 * test updating a Report that does not exist
//	 *
//	 * @expectedException \PDOException
//	 **/
//	public function testUpdateValidReport() : void {
//		$reportId = generateUuidV4();
//		$reportCategoryId = generateUuidV4();
//		// create a new Report with a non null report id and watch it fail
//		$report = new Report($reportId, $reportCategoryId, $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
//		$report->$this->update($this->getPDO());
//	}

	/**
	 * test creating a Report and then deleting it
	 **/
	public function testDeleteValidReport() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// create a new Report and insert into mySQL
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());

		// delete the Report from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$report->delete($this->getPDO());

		// grab the date from mySQL and enforce the report does not exist
		$pdoReport = Report::getReportByReportId($this->getPDO(), $report->getReportId());
		$this->assertNull($pdoReport);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("report"));
		//$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
	}

//	/**
//	 * test deleting a Report that does not exist
//	 * @expectedException \PDOException
//	 **/
//	public function testDeleteInvalidReport() : void {
//		// $reportId = generateUuidV4();
//		//$reportCategoryId = generateUuidV4();
//		// create a Report and try to delete it without actually inserting it
//		$report = new Report(null, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
//		$report->delete($this->getPDO());
//	}

	/**
	 * test inserting a Report and regrabbing it from mySQL
	 **/
	public function testGetValidReportByReportCategoryId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// $reportCategoryId = generateUuidV4();
		// create a new Report and insert into mySQL
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Report::getReportByReportCategoryId($this->getPDO(), $report->getReportCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Report", $results);

		// grab the result from the array and validate it
		$pdoReport = $results[0];
		$this->assertEquals($pdoReport->getReportId(), $report->getReportId());
		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
	}

	/**
	 * test grabbing a Report that does not exist
	 **/
	public function testGetInvalidReportByCategoryId() {
		$reportId = generateUuidV4();
		// grab a category id that exceeds the maximum allowable profile id
		$report = Report::getReportByReportId($this->getPDO(), $reportId);
		$this->assertNull($report);
	}

//	/**
//	 * test grabbing a Report by report content
//	 **/
//	public function testGetValidReportByReportContent() : void {
//		// count the number of rows and save it for later
//		$numRows = $this->getConnection()->getRowCount("report");
//
//		// create a new Report and insert to into mySQL
//		$report = new Report(null, null, $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
//		$report->insert($this->getPDO());
//
//		// grab the data from mySQL and enforce the fields match our expectations
//		$results = Report::getReportByReportContent($this->getPDO(), $report->getReportContent());
//		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
//		$this->assertCount(1, $results);
//
//		// enforce no other objects are bleeding into the test
//		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Report", $results);
//
//		// grab the result from the array and validate it
//		$pdoReport = $results[0];
//		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
//		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
//		//format the date too seconds since the beginning of time to avoid round off error
//		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
//
//	}

//	/**
//	 * test grabbing a Report by content that does not exist
//	 **/
//	public function testGetInvalidReportByReportContent() : void {
//		// grab a report by content that does not exist
//		$report = Report::getReportByReportContent($this->getPDO(), "Why is there so many problems!!!");
//		$this->assertCount(0, $report);
//	}

	/**
	 * test grabbing a valid Report by sunset and sunrise date
	 **/
	public function testGetValidReportByDateTime() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// $reportCategoryId = generateUuidV4();
		// create a new Report and insert it into the database
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());

		// grab the report from the database and see if it matches expectations
		$results = Report::getReportByReportDateTime($this->getPDO(), $this->VALID_SUNRISEDATETIME, $this->VALID_SUNSETDATETIME);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertCount(1,$results);

		//enforce that no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Report", $results);

		//use the first result to make sure that the inserted report meets expectations
		$pdoReport = $results[0];
		$this->assertEquals($pdoReport->getReportId(), $report->getReportId());
		$this->assertEquals($pdoReport->getReportCategoryId(), $report->getReportCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $report->getReportContent());
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
	}

/**
* test grabbing Reports by status
**/
	public function testGetAllValidReportsByStatus() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// $reportCategoryId = generateUuidV4();
		// create a new Report and insert to into mySQL
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Report::getReportByReportStatus($this->getPDO(), $this->VALID_STATUS);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Report", $results);
		// grab the result from the array and validate it
		$pdoReport = $results[0];
		$this->assertEquals($pdoReport->getReportId(), $report->getReportId());
		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
	}

	/**
	 * test grabbing Reports by status
	 **/
	public function testGetAllValidReportsByUrgency() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// $reportCategoryId = generateUuidV4();
		// create a new Report and insert to into mySQL
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Report::getReportByReportUrgency($this->getPDO(), $this->VALID_URGENCY);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Report", $results);

		// grab the result from the array and validate it
		$pdoReport = $results[0];
		$this->assertEquals($pdoReport->getReportId(), $report->getReportId());
		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
	}

	/**
	 * test grabbing all Reports
	 **/
	public function testGetAllValidReports() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("report");

		$reportId = generateUuidV4();
		// create a new Report and insert to into mySQL
		$report = new Report($reportId, $this->category->getCategoryId(), $this->VALID_REPORTCONTENT, $this->VALID_REPORTDATETIME, $this->VALID_IPADDRESS, $this->VALID_REPORTLAT, $this->VALID_REPORTLONG, $this->VALID_STATUS, $this->VALID_URGENCY, $this->VALID_USERAGENT);
		$report->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Report::getAllReports($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Infrastructure\\Report", $results);

		// grab the result from the array and validate it
		$pdoReport = $results[0];
		$this->assertEquals($pdoReport->getReportId(), $report->getReportId());
		$this->assertEquals($pdoReport->getReportCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoReport->getReportContent(), $this->VALID_REPORTCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoReport->getReportDateTime()->getTimestamp(), $this->VALID_REPORTDATETIME->getTimestamp());
		$this->assertEquals($pdoReport->getReportIpAddress(), $this->VALID_IPADDRESS);
		$this->assertEquals($pdoReport->getReportLat(), $this->VALID_REPORTLAT);
		$this->assertEquals($pdoReport->getReportLong(), $this->VALID_REPORTLONG);
		$this->assertEquals($pdoReport->getReportStatus(), $this->VALID_STATUS);
		$this->assertEquals($pdoReport->getReportUrgency(), $this->VALID_URGENCY);
		$this->assertEquals($pdoReport->getReportUserAgent(), $this->VALID_USERAGENT);
	}
}