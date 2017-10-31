<?php
namespace Edu\Cnm\Infrastructure;

require_once ("autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * Class Report
 * @author Kevin D. Atkins
 */
class Report implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for this report; primary key
	 * @var $reportId
	 */
	private $reportId;
	/**
	 * id  for this report category; foreign key
	 * @var $reportCategoryId
	 */
	private $reportCategoryId;
	/**
	 * content of this report
	 * @var $reportContent
	 */
	private $reportContent;
	/**
	 * date and time for this report
	 * @var $reportDateTime
	 */
	private $reportDateTime;
	/**
	 * ip address to report
	 * @var $reportIpAddress
	 */
	private $reportIpAddress;
	/**
	 * latitide for report
	 * @var $reportLat;
	 */
	private $reportLat;
	/**
	 * longitude for report
	 * @var $reportLong
	 */
	private $reportLong;
	/**
	 * status for report
	 * @var $reportStatus
	 */
	private $reportStatus;
	/**
	 * urgency for this report
	 * @var $reportUrgency
	 */
	private $reportUrgency;
	/**
	 * user agent for report
	 * @var $reportUserAgent
	 */
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

		}
	}


	public function jsonSerialize() {
		// TODO: Implement jsonSerialize() method.
	}
}