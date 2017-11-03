<?php
namespace Edu\Cnm\Infrastructure\Test;
use Edu\Cnm\Infrastructure\{Image, Report};
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
	 * Report that image is associated with; this is for foreign key relations
	 * @var Report $report
	 **/
	protected $report;

	/**
	 * valid cloudinary to use to create an image
	 * @var string $VALID_CLOUDINARY;
	 **/
	protected $VALID_CLOUDINARY;

	/**
	 * valid latitude to use to create an image
	 * @var float $VALID_LAT;
	 **/
}