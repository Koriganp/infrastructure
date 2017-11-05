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

}