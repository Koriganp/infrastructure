<?php
/**
 * Creates seed data for the Category class to use on the front end
 *
 * @author Korigan Payne <koriganp@gmail.com>
 **/
use Edu\Cnm\Infrastructure\Category;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
// grab the uuid generator
require_once("uuid.php");
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

$namedCategory = ["Pets & Wildlife", "Water & Sewer", "Trash & Recycling", "Health & Safety ", "Parks & Public Land", "Streets & Sidewalks", "Signs & Lights", "Miscellaneous"];

foreach($namedCategory as $newCategory){
	$category = new Category(generateUuidV4(), $newCategory);
	$category->insert($pdo);
}
