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
	protected $VALID_NAME;

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


}