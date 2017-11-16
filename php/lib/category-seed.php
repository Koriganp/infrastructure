<?php
use Edu\Cnm\Infrastructure\Category;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
// grab the uuid generator
require_once("uuid.php");
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");

$namedCategory = ["Animal Welfare", "Parks & Recreation", "Solid Waste", "Transit"];

foreach($namedCategory as $newCategory){
	$category = new Category(generateUuidV4(), $newCategory);
	$category->insert($pdo);
}
