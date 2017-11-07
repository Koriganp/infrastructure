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
	protected $VALID_IPADDRESS;

	/**
	 * valid cloudinary to use to create an image
	 * @var string $VALID_CLOUDINARY;
	 **/
	protected $VALID_CLOUDINARY;

	/**
	 * valid latitude to use to create an image
	 * @var float $VALID_LAT;
	 **/
	protected $VALID_LAT;

	/**
	 * valid longitude to use to create an image
	 * @var float $VALID_LAT;
	 **/
	protected $VALID_LONG;

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
		$this->report = new Report(generateUuidV4(), $this->category->getCategoryId(), "there is a hole", $this->VALID_REPORTDATE, );
	}
}