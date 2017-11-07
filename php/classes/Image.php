<?php
/**
 * Image entity for Infrastructure
 *
 * This is the image entity that stores the images that are uploaded with reports.
 *
 * @author Korigan Payne <kpayne11@cnm.edu>
 * @version 1.0.0
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
	 **/
	private $imageReportId;
	/**
	 * this is the cloudinary id received
	 * @var string $imageCloudinary
	 **/
	private $imageCloudinary;
		/**
	 * this is the longitude of the image
	 * @var integer $imageLong
	 **/
	private $imageLat;
	/**
	 * this is the latitude of the image
	 * @var integer $imageLat
	 **/
	private $imageLong;

	/**
	 * constructor for this Image
	 *
	 * @param string|Uuid $newImageId id of this Image or null if a new image
	 * @param string|Uuid $newImageReportId id of the Report this image is associated with
	 * @param string $newImageCloudinary string containing data from cloudinary
	 * @param float |null $newImageLat latitude of image location
	 * @param float |null $newImageLong longitude of image location
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newImageId, $newImageReportId, string $newImageCloudinary, $newImageLat = null, $newImageLong = null) {
		try {
			$this->setImageId($newImageId);
			$this->setImageReportId($newImageReportId);
			$this->setImageCloudinary($newImageCloudinary);
			$this->setImageLat($newImageLat);
			$this->setImageLong($newImageLong);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for image id
	 *
	 * @return Uuid value of image id
	 **/
	public function getImageId() : Uuid {
		return($this->imageId);
	}

	/**
	 * mutator method for image id
	 *
	 * @param Uuid | string $newImageId new value of image id
	 * @throws \RangeException if $newImageId is not positive
	 * @throws \TypeError if $newTweetId is not a uuid or string
	 **/
	public function setImageId($newImageId) : void {
		try {
			$uuid = self::validateUuid($newImageId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the image id
		$this->imageId = $uuid;
	}

	/**
	 * accessor method for image report id
	 *
	 * @return Uuid value of image report id
	 **/
	public function getImageReportId() : Uuid{
		return($this->imageReportId);
	}

	/**
	 * mutator method for image report id
	 *
	 * @param string | Uuid $newImageReportId value of image report id
	 * @throws \RangeException if $newImageReportId is not positive
	 * @throws \TypeError if $newImageReportId is not an integer
	 **/
	public function setImageReportId($newImageReportId) : void {
		try {
			$uuid = self::validateUuid($newImageReportId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the report id
		$this->imageReportId = $uuid;
	}

	/**
	 * accessor method for image cloudinary content
	 *
	 * @return string value of image cloudinary content
	 **/
	public function getImageCloudinary() :string {
		return($this->imageCloudinary);
	}

	/**
	 * mutator method for image cloudinary content
	 *
	 * @param string $newImageCloudinary new value of image cloudinary content
	 * @throws \InvalidArgumentException if $newImageCloudinary is not a string or insecure
	 * @throws \RangeException if $newImageCloudinary is > 64 characters
	 * @throws \TypeError if $newImageCloudinary is not a string
	 **/
	public function setImageCloudinary(string $newImageCloudinary) : void {
		// verify the image cloudinary content is secure
		$newImageCloudinary = trim($newImageCloudinary);
		$newImageCloudinary = filter_var($newImageCloudinary, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newImageCloudinary) === true) {
			throw(new \InvalidArgumentException("image cloudinary content is empty or insecure"));
		}
		// verify the image cloudinary content will fit in the database
		if(strlen($newImageCloudinary) > 64) {
			throw(new \RangeException("image cloudinary content too large"));
		}
		// store the image cloudinary content
		$this->imageCloudinary = $newImageCloudinary;
	}

	/**
	 * accessor method for image latitude
	 *
	 * @return float value of image latitude
	 **/
	public function getImageLat() : float {
		return($this->imageLat);
	}

	/**
	 * mutator method for image latitude
	 *
	 * @param float $newImageLat new value image latitude
	 * @throws \InvalidArgumentException if $newImageLat is not a valid latitude or insecure
	 * @throws \RangeException if $newImageLat is > 12 characters
	 * @throws \TypeError if $newImageLat is not a float
	 **/
	public function setImageLat($newImageLat): void {
		// verify the float is secure
		$newImageLat = trim($newImageLat);
		$newImageLat = filter_var($newImageLat, FILTER_VALIDATE_FLOAT);
		if(empty($newImageLat) === true) {
			throw(new \InvalidArgumentException("latitude is empty or insecure"));
		}
		// verify the float will fit in the database
		if(strlen($newImageLat) > 12) {
			throw(new \RangeException("latitude is too large"));
		}
		// store the latitude
		$this->imageLat = $newImageLat;
	}

	/**
	 * accessor method for image longitude
	 *
	 * @return float value of image longitude
	 **/
	public function getImageLong() : float {
		return($this->imageLong);
	}

	/**
	 * mutator method for image longitude
	 *
	 * @param float $newImageLong new value image longitude
	 * @throws \InvalidArgumentException if $newImageLong is not a valid longitude or insecure
	 * @throws \RangeException if $newImageLong is > 12 characters
	 * @throws \TypeError if $newImageLong is not a float
	 **/
	public function setImageLong($newImageLong): void {
		// verify the float is secure
		$newImageLong = trim($newImageLong);
		$newImageLong = filter_var($newImageLong, FILTER_VALIDATE_FLOAT);
		if(empty($newImageLong) === true) {
			throw(new \InvalidArgumentException("longitude is empty or insecure"));
		}
		// verify the float will fit in the database
		if(strlen($newImageLong) > 12) {
			throw(new \RangeException("longitude is too large"));
		}
		// store the longitude
		$this->imageLong = $newImageLong;
	}

	/**
	 * formats the state variables for JSON serialize
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["imageId"] = $this->imageId->toString();
		$fields["imageReportId"] = $this->imageReportId->toString();
		return($fields);
	}
}
