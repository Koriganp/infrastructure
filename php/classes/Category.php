<?php
/**
 * Category entity for Infrastructure
 *
 * This is the category entity that stores the category for the reports.
 *
 * @author Korigan Payne <kpayne11@cnm.edu>
 * @version 7.1
 **/

namespace Edu\Cnm\Infrastructure;

require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Category implements \JsonSerializable {
	use ValidateUuid;

	/**
	 * id for this category; this is the primary key
	 * @var Uuid $categoryId
	 */
	private $categoryId;

	/**
	 * this is the name of the category
	 * @var string $categoryName
	 */
	private $categoryName;

	/**
	 * constructor for category
	 *
	 * @param Uuid $newCategoryId id of this category
	 * @param string $newCategoryName name of this category
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newCategoryId, $newCategoryName) {
		try {
			$this->setCategoryId($newCategoryId);
			$this->setCategoryName($newCategoryName);
		}
			//determine which exception was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	public function jsonSerialize() {
		// TODO: Implement jsonSerialize() method.
		$fields = get_object_vars($this);
		$fields["categoryId"] = $this->categoryId->toString();
		$fields["categoryName"] = $this->categoryName->toString();
	}
}