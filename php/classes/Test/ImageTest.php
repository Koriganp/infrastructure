<?php
namespace Edu\Cnm\Infrastructure\Test;
use Edu\Cnm\Infrastructure\{Image, Report, Category};
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Image class
 *
 * This is a complete PHPUnit test of the Image class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Image
 * @author Korigan Payne <kpayne11@cnm.edu>
 **/
class ImageTest extends InfrastructureTest {

	/**
	 * Category that the Report is associated with; this is for foreign key relations
	 * @var Category $category
	 */
	protected $category = null;

	/**
	 * Report that image is associated with; this is for foreign key relations
	 * @var Report $report
	 **/
	protected $report = null;

	/**
	 * timestamp of the mocked report; starts at null and is assigned later
	 * @var \DateTime $VALID_REPORTDATE
	 */
	protected $VALID_REPORTDATE = null;

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
	protected $VALID_CLOUDINARY;

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
	 * create dependant objects before running each test
	 **/
	public final function setUp() : void {
		//run the default setUp() method first
		parent::setUp();

		//create and insert a mocked category for the mocked report
		$this->category = new Category(generateUuidV4(), "Streets and Roads");
		$this->category->insert($this->getPDO());

		$this->VALID_REPORTDATE = new \DateTime();

		//create and insert a mocked report
		$this->report = new Report(generateUuidV4(), $this->category->getCategoryId(), "there is a hole", $this->VALID_REPORTDATE, $this->VALID_IPADDRESS, $this->VALID_LAT, $this->VALID_LONG, $this->VALID_REPORTSTATUS, "0", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36");


	}

	/**
	 * test inserting a valid Image and verify that the actual mySQL data matches
	 **/
	public function testInsertValidImage() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("image");
		// create a new image and insert to into mySQL
		$imageId = generateUuidV4();
		$image = new Image($imageId, $this->report->getReportId(), $this->VALID_CLOUDINARY, $this->VALID_LAT, $this->VALID_LONG);
		$image->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoImage = Image::getImageByImageId($this->getPDO(), $image->getImageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("image"));
		$this->assertEquals($pdoImage->getImageId(), $imageId);
		$this->assertEquals($pdoImage->getImageReportId(), $this->report->getReportId());
		$this->assertEquals($pdoImage->getImageCloudinary(), $this->VALID_CLOUDINARY);
		$this->assertEquals($pdoImage->getImageLat(), $this->VALID_LAT);
		$this->assertEquals($pdoImage->getImageLong(), $this->VALID_LONG);
	}
}