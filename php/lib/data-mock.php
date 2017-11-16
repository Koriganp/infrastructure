<?php
use Edu\Cnm\Infrastructure\Profile;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("uuid.php");
// grab the uuid generator
require_once( "uuid.php");
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/abqreport.ini");
$password ="abc123";
$SALT = bin2hex(random_bytes(32));
$HASH = hash_pbkdf2("sha512", $password, $SALT, 262144);

$profile = new Profile(generateUuidV4(),null,"joe mama", "holy@shit.com", $HASH,$SALT);
$profile->insert($pdo);