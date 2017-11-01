<?php
namespace Edu\Cnm\Infrastructure\Test;
use Edu\Cnm\DataDesign\{Category};
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
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
	 */
	protected $VALID_NAME = "holes in the ground";
	/**
	 * second valid name to use
	 * @var string $VALID_NAME2
	 */
	protected $VALID_NAME2 = "water everywhere";

	/**
	 * test inserting a valid Category and verify that the actual mySQL data matches
	 **/
	public function testInsertValidCategory() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert to into mySQL
		$category = new Category(null, $this->VALID_NAME);
		//var_dump($category);
		$category->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$this->assertEquals($pdoCategory->getCategoryName(), $this->VALID_NAME);
	}

	/**
	 * test inserting a Category that already exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidCategory() : void {
		// create a category with a non null categoryId and watch it fail
		$category = new Category(InfrastructureTest::INVALID_KEY, $this->VALID_NAME);
		$category->insert($this->getPDO());
	}

	/**
	 * test inserting a Category, editing it, and then updating it
	 **/
	public function testUpdateValidCategory() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert to into mySQL
		$category = new Category(null, $this->VALID_NAME);
		$category->insert($this->getPDO());
		// edit the Category and update it in mySQL
		$category->setCategoryName($this->VALID_NAME2);
		$category->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
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
		// create a Category and try to update it without actually inserting it
		$category = new Category(null, $this->VALID_NAME);
		$category->update($this->getPDO());
	}

	/**
	 * test creating a Category and then deleting it
	 **/
	public function testDeleteValidCategory() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("category");
		// create a new Category and insert to into mySQL
		$category = new Category(null, $this->VALID_NAME);
		$category->insert($this->getPDO());
		// delete the Category from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("category"));
		$category->delete($this->getPDO());
		// grab the data from mySQL and enforce the Category does not exist
		$pdoCategory = Category::getCategoryByCategoryId($this->getPDO(), $category->getCategoryId());
		$this->assertNull($pdoCategory);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("category"));
	}
}