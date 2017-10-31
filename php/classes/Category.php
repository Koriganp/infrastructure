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

	/**
	 * accessor method for category id
	 *
	 * @return Uuid value of category id
	 **/
	public function getCategoryId() : Uuid {
		return $this->categoryId;
	}

	/**
	 * mutator method for category id
	 *
	 * @param Uuid $newCategoryId new value of category id
	 * @throws \UnexpectedValueException if $newCategoryId is not a uuid
	 **/
	public function setCategoryId($newCategoryId) : void {
		try {
			$uuid = self::validateUuid($newCategoryId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the category id
		$this->categoryId = $uuid;
	}

	/**
	 * accessor method for category name
	 *
	 * @return string value of category name
	 **/
	public function getCategoryName(): string {
		return ($this->categoryName);
	}

	/**
	 * mutator method for category name
	 *
	 * @param string $newCategoryName new value of category name
	 * @throws \InvalidArgumentException if $newCategoryName is not a string or insecure
	 * @throws \RangeException if $newCategoryName is > 32 characters
	 * @throws \TypeError if $newCategoryName is not a string
	 **/
	public function setCategoryName(string $newCategoryName) : void {
		// verify the category name is secure
		$newCategoryName = trim($newCategoryName);
		$newCategoryName = filter_var($newCategoryName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCategoryName) === true) {
			throw(new \InvalidArgumentException("category name is empty or insecure"));
		}
		// verify the category name will fit in the database
		if(strlen($newCategoryName) > 32) {
			throw(new \RangeException("category name is too large"));
		}
		// store the category name
		$this->categoryName = $newCategoryName;
	}

	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["categoryId"] = $this->categoryId->toString();
		$fields["categoryName"] = $this->categoryName->toString();
	}
}