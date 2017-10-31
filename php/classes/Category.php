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

	/**
	 * inserts this Category into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		// enforce the categoryId is null (don't insert a category that already exists)
		if($this->categoryId !== null) {
			throw(new \PDOException("not a new category"));
		}
		// create query template
		$query = "INSERT INTO category (categoryId, categoryName) VALUES (:categoryId, :categoryName)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["categoryId" => $this->categoryId->getBytes(), "categoryName" => $this->categoryName];
		$statement->execute($parameters);
	}

	/**
	 * deletes this category from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not null (i.e.. don't delete a profile that does not exist)
	 **/
	public function delete(\PDO $pdo): void {
		//enforce the categoryId is not null (don't delete a category that does not exist)
		if($this->categoryId === null) {
			throw(new \PDOException("unable to delete a category that does not exist"));
		}
		//create query template
		$query = "DELETE FROM category WHERE categoryId = :categoryId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters = ["categoryId" => $this->categoryId];
		$statement->execute($parameters);
	}

	/**
	 * updates this category in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {
		//Enforce the categoryId is not null (don't update a category that does not exist)
		if($this->categoryId === null) {
			throw(new \PDOException("unable to update a category that does not exist"));
		}
		//create query template
		$query = "UPDATE category SET categoryId = :categoryId, categoryName = :categoryName";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters=["categoryId=>$this->categoryId", "categoryName=>$this->categoryName"];
		$statement->execute($parameters);
	}

	/**
	 * gets the Category by category id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid $categoryId profile id to search for
	 * @return Category|null Category or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCategoryByCategoryId(\PDO $pdo, $categoryId) : ?Category {
		//sanitize the category id before searching
		try {
			$categoryId = self::validateUuid($categoryId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//create query template
		$query="SELECT categoryId, categoryName FROM category WHERE categoryId = :categoryId";
		$statement=$pdo->prepare($query);
		//bind the category id to the place holder in the template
		$parameters=["categoryId"=>$categoryId];
		$statement->execute($parameters);
		//grab the category from mySQL
		try{
			$category=null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row=$statement->fetch();
			if($row !== false) {
				$category=new Category($row["categoryId"], $row["categoryName"]);
			}
		}
		catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new\PDOException($exception->getMessage(), 0, $exception));
		}
		return ($category);
	}

	/**
	 * get the Category by name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $categoryName at handle to search for
	 * @return \SPLFixedArray of all profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not correct data type
	 **/
	public static function getCategoryByCategoryName(\PDO $pdo, string $categoryName) : \SPLFixedArray {
		// sanitize the name before searching
		$categoryName = trim($categoryName);
		$categoryName = filter_var($categoryName, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($categoryName) === true) {
			throw(new \PDOException("not a valid category name"));
		}
		// create query template
		$query = "SELECT categoryId, categoryName FROM category WHERE categoryName = :categoryName";
		$statement = $pdo->prepare($query);
		//bind the category name to the place holder in the template
		$parameters = ["categoryName" => $categoryName];
		$statement->execute($parameters);
		$categories = new \SPLFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$category = new Category($row["categoryId"], $row["categoryName"]);
				$categories[$categories->key()] = $category;
				$categories->next();
			} catch (\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($categories);
	}

	/**
	 * formats the state variables for JSON serialize
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["categoryId"] = $this->categoryId->toString();
		return($fields);
	}
}