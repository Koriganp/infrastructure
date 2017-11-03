<?php
namespace Edu\Cnm\Infrastructure\Test;
use Edu\Cnm\Infrastructure\{Category};
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Category class
 *
 * This is a complete PHPUnit test of the Category class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Category
 * @author Korigan Payne <kpayne11@cnm.edu>
 **/
class CategoryTest extends InfrastructureTest {
	/**
	 * valid name to use
	 * @var string $VALID_NAME
	 **/
	protected $VALID_NAME = "holes in the ground";
	/**
	 * second valid name to use
	 * @var string $VALID_NAME2
	 **/
	protected $VALID_NAME2 = "water everywhere";

	/**
	 * test inserting a valid Category and verify that the actual mySQL data matches
	 **/
	public function testInsertValidCategory() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		//create a uuid
		$categoryId = generateUuidV4();
		// create a new Category and insert to into mySQL
		$category = new Category($categoryId, $this->VALID_NAME);
		$category->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryId(), $categoryId);
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_NAME);
	}

	/**
	 * test inserting a Category, editing it, and then updating it
	 **/
	public function testUpdateValidCategory() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		///create a uuid
		$categoryId = generateUuidV4();
		// create a new Category and insert to into mySQL
		$category = new Category($categoryId, $this->VALID_NAME);
		$category->insert($this->getPDO());
		// edit the Category and update it in mySQL
		$category->setCategoryName($this->VALID_NAME2);
		$category->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryId(), $categoryId);
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_NAME);
	}

	/**
	 *
	 * /**
	 * test updating a Category that does not exist
	 *
	 * @expectedException \PDOException
	 **/
	public function testUpdateInvalidCategory() {
		//create a uuid
		$categoryId = generateUuidV4();
		// create a new Category and insert to into mySQL
		$category = new Category($categoryId, $this->VALID_NAME);
		$category->update($this->getPDO());
	}

	/**
	 * test creating a Category and then deleting it
	 **/
	public function testDeleteValidCategory() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		//create a uuid
		$categoryId = generateUuidV4();
		// create a new Category and insert to into mySQL
		$category = new Category($categoryId, $this->VALID_NAME);
		$category->insert($this->getPDO());
		// delete the Category from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$category->delete($this->getPDO());
		// grab the data from mySQL and enforce the Category does not exist
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertNull($pdoCategory);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("category"));
	}

	/**
	 * test inserting a Category and re-grabbing it from mySQL
	 **/
	public function testGetValidCategoryByCategoryId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		//create a uuid
		$categoryId = generateUuidV4();
		// create a new Category and insert to into mySQL
		$category = new Category($categoryId, $this->VALID_NAME);
		$category->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_NAME);
	}

	/**
	 * test getting a Category that does not exist
	 **/
	public function testGetInvalidCategoryByCategoryId() : void {
		// get a category id that exceeds the maximum allowable category id
		$category = Category::getCategoryByCategoryId($this->getPDO(), generateUuidV4());
		$this->assertNull($category);
	}

	/**
	 * test getting a Category by name
	 **/
	public function testGetValidCategoryByName() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		//create a uuid
		$categoryId = generateUuidV4();
		// create a new Category and insert to into mySQL
		$category = new Category($categoryId, $this->VALID_NAME);
		$category->insert($this->getPDO());
		//grab the data from MySQL
		$results = Category::getCategoryByCategoryName($this->getPDO(), $this->VALID_NAME);
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("category"));
		//enforce no other objects are bleeding into category
		$this->assertContainsOnlyInstancesOf("Edu\\CNM\\Infrastructure\\Category", $results);
		//enforce the results meet expectations
		$pdoCategory = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_NAME);
	}

	/**
	 * test grabbing a Category by at name that does not exist
	 **/
	public function testGetInvalidCategoryName() : void {
		// grab a name that does not exist
		$category = Category::getCategoryByCategoryName($this->getPDO(), "dog abducted by aliens");
		$this->assertCount(0, $category);
	}

}