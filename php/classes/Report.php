<?php
/**
 *
 **/
namespace Edu\Cnm\Infrastructure;

require_once ("autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * Class Report
 * @author Kevin D. Atkins
 **/
class Report implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for this report; primary key
	 * @var $reportId
	 **/
	private $reportId;
	/**
	 * id  for this report category; foreign key
	 * @var $reportCategoryId
	 **/
	private $reportCategoryId;
	/**
	 * content of this report
	 * @var $reportContent
	 **/
	private $reportContent;
	/**
	 * date and time for this report
	 * @var $reportDateTime
	 **/
	private $reportDateTime;
	/**
	 * ip address to report
	 * @var $reportIpAddress
	 **/
	private $reportIpAddress;
	/**
	 * latitide for report
	 * @var $reportLat;
	 **/
	private $reportLat;
	/**
	 * longitude for report
	 * @var $reportLong
	 **/
	private $reportLong;
	/**
	 * status for report
	 * @var $reportStatus
	 **/
	private $reportStatus;
	/**
	 * urgency for this report
	 * @var $reportUrgency
	 **/
	private $reportUrgency;
	/**
	 * user agent for report
	 * @var $reportUserAgent
	 **/
	private $reportUserAgent;

	public function __construct($newReportId, $newReportCategoryId, string $newReportContent, $newReportDateTime = null,
										 $newReportIpAddress, $newReportLat, $newReportLong, $newReportStatus, $newReportUrgency,
										 $newReportUserAgency) {
		try {
			$this->setReportId($newReportId);
			$this->setReportCategoryId($newReportCategoryId);
			$this->setReportContent($newReportContent);
			$this->setReportDateTime($newReportDateTime);
			$this->setReportIpAddress($newReportIpAddress);
			$this->setReportLat($newReportLat);
			$this->setReportLong($newReportLong);
			$this->setReportStatus($newReportStatus);
			$this->setReportUrgency($newReportUrgency);
			$this->setReportUserAgency($newReportUserAgency);

		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for report id
	 *
	 * @return Uuid value of report id
	 **/
	public function getReportId() : Uuid {
		return($this->reportId);
	}

	/**
	 * mutator method for report id
	 *
	 * @param Uuid/string $newReportId new value of report id
	 * @throws \RangeException if $newReportId is not positive
	 * @throws \TypeError if $newReportId is not a uuid or string
	 **/
	public function setReportId($newReportId) : void {
		try {
			$uuid = self::validateUuid($newReportId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->reportId = $uuid;
	}

	/**
	 * accessor method for report category id
	 *
	 * @return Uuid value of report category id
	 **/
	public function getReportCategoryId() : Uuid {
		return($this->reportCategoryId);
	}

	/**
	 * mutator method for report category id
	 *
	 * @param Uuid/string $newReportCategoryId new value of report category id
	 * @throws \RangeException if $newReportId is not positive
	 * @throws \TypeError if $newReportCategoryId is not an integer
	 **/
	public function setReportCategoryId($newReportCategoryId) : void {
		try {
			$uuid = self::validateUuid($newReportCategoryId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->reportCategoryId = $uuid;
	}
	
	/**
	 * accessor method for report content
	 *
	 * @return string value of report content
	 **/
	public function getReportContent() : string {
		return($this->reportContent);
	}

	/**
	 * mutator method for report content
	 *
	 * @param string $newReportContent new value of report content
	 * @throws \InvalidArgumentException if $newReportContent is not a string or insecure
	 * @throws \RangeException if $newReportContent is > 3000
	 * @throws  \TypeError if $newReportContent is not a string
	 */
	public function setReportContent(string $newReportContent) : void {
		//verify the report content is secure
		$newReportContent = trim($newReportContent);
		$newReportContent = filter_var($newReportContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if(empty($newReportContent) === true) {
			throw(new \InvalidArgumentException("report content is empty or insecure"));
		}

		//verify the report content will fit in the database
		if(strlen($newReportContent) > 3000) {
			throw(new \RangeException("report content too large"));
		}
		$this->reportContent = $newReportContent;
	}
	/**
	 * accessor method for report date/time
	 *
	 * @return \DateTime value of report date/time
	 **/
	public function getReportDateTime() : \DateTime {
		return($this->reportDateTime);
	}

	/**
	 * mutator method for report date/time
	 *
	 * @param \DateTime|string|null $newReportDateTime report date/time as a DateTime object
	 * or string (or null to load the current date/time)
	 * @throws \InvalidArgumentException if $newReportDateTime is not a valid object or string
	 * @throws \RangeException if $newReportDateTime is a date/time that does exist
	 */
	public function setReportDateTime($newReportDateTime = null) : void {
		// base case: if the date/time is null, use the current date/time
		if($newReportDateTime === null) {
			$this->reportDateTime = new \DateTime();
			return;
		}

		// store the like date/time using the Validate trait
		try {
			$newReportDateTime = self::validateDateTime($newReportDateTime);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->reportDateTime = $newReportDateTime;
	}

	/**
	 * accessor method for report ip address
	 *
	 * @return int value of report ip address
	 **/
	public function getReportIpAddress() : int {
		return($this->reportIpAddress);
	}

	/**
	 * @param int $newReportIpAddress
	 * @throws
	 */
	public function setReportIpAddress($newReportIpAddress) {


		$this->reportIpAddress = $newReportIpAddress;
	}

	/**
	 * accessor method for report latitude
	 *
	 * @return int value of report latitude
	 **/
	public function getReportLat() : float {
		return($this->reportLat);
	}

	/**
	 * accessor method for report longitude
	 *
	 * @return int value of report longitude
	 **/
	public function getReportLong() : float {
		return($this->reportLong);
	}

	/**
	 * accessor method for report status
	 *
	 * @return string value of report status
	 **/
	public function getReportStatus() : string {
		return($this->reportStatus);
	}

	/**
	 * mutator method for report status
	 *
	 * @param string $newReportStatus new value of report status
	 * @throws \InvalidArgumentException if $newReportStatus is not a string or insecure
	 * @throws \RangeException if $newReportStatus is > 15 characters
	 * @throws \TypeError if $newReportStatus is not a string
	 */
	public function setReportStatus($newReportStatus) : void {
		// verify the report status is secure
		$newReportStatus = trim($newReportStatus);
		$newReportStatus = filter_var($newReportStatus, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newReportStatus) === true) {
			throw(new \InvalidArgumentException("report status is empty or insecure"));
		}

		// verify the report status will fit in the database
		if(strlen($newReportStatus) > 15) {
			throw(new \RangeException("report status too long"));
		}

		//store the report status
		$this->reportStatus = $newReportStatus;
	}

	/**
	 * accessor method for report urgency
	 *
	 * @return string value of report urgency
	 **/
	public function getReportUrgency() : string {
		return($this->reportUrgency);
	}

	/**
	 * mutator method for report urgency
	 *
	 * @param string $newReportUrgency new value of report status
	 * @throws \InvalidArgumentException if $newReportUrgency is not a string or insecure
	 * @throws \RangeException if $newReportUrgency is > 5 characters
	 * @throws \TypeError if $newReportUrgency is not a string
	 */
	public function setReportUrgency($newReportUrgency) : void {
		// verify the report status is secure
		$newReportUrgency = trim($newReportUrgency);
		$newReportUrgency = filter_var($newReportUrgency, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newReportUrgency) === true) {
			throw(new \InvalidArgumentException("report status is empty or insecure"));
		}

		// verify the report status will fit in the database
		if(strlen($newReportUrgency) > 15) {
			throw(new \RangeException("report status too long"));
		}

		//store the report status
		$this->reportStatus = $newReportUrgency;
	}

	/**
	 * accessor method for report user agent
	 *
	 * @return int value of report user agent
	 **/
	public function getReportUserAgent() : int {
		return($this->reportUserAgent);
	}








	public function jsonSerialize() {
		// TODO: Implement jsonSerialize() method.
	}
}