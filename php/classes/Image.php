<?php
/**
 * Image entity for Infrastructure
 *
 * This is the image entity that stores the images that are uploaded with reports.
 *
 * @author Korigan Payne <kpayne11@cnm.edu>
 * @version 7.1
 **/

namespace Edu\Cnm\Infrastructure;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Image implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this image; this is the primary key
	 * @var Uuid $imageId
	 **/
	private $imageId;
	/**
	 * id for the report this image is on; this is a foreign key
	 * @var Uuid $imageReportId
	 */
	private $imageReportId;
	/**
	 * this is the cloudinary id received
	 * @var string $imageCloudinary
	 */
	private $imageCloudinary;
	/**
	 * this is the latitude of the image
	 * @var integer $imageLat
	 */
	private $imageLong;
	/**
	 * this is the longitude of the image
	 * @var integer $imageLong
	 */
	private $imageLat;

	/**
	 * formats the state variables for JSON serialize
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["imageId"] = $this->imageId->toString();
		return($fields);
	}
}
