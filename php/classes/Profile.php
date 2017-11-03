<?php
/**
 * Profile entity for Infrastructure
 *
 * This is the profile entity that stores the profiles of Administrators. This is a top level entity and holds the keys to other entities.
 *
 * @author Tanisha Purnell @tpurnell@cnm.edu
 * @version 7.1
 **/

namespace Edu\Cnm\Infrastructure;

require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
class Profile implements \JsonSerialize {
	use ValidateUuid;
/**
 * id for this profile, this is a primary key
 * @var Uuid $ProfileId
 **/

private $profileId;
/**
 * this creates the activation token
 * @var $profileActivationToken
 **/
private $profileActivationToken;
/**
 * this creates the profile User name
 * @var $username
 **/
private $username;
/**
 * this is the admin email address for profile
 * @var $profileEmail
 **/
private $profileEmail;
/**
 * this is the hash for profile email
 * @var $profileHash
 **/
private $profileHash;
/**
 * this is the salt for profile email
 * @var $profileSalt
 **/
private $profileSalt;
	/**
 * constructor for this profile
	 *
	 * @throws \InvalidArgumentException if data types are not invalid
	 * @throws  \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throw \Exception if some other exception occurs
 **/
public function  __construct($newProfileId, $newProfileActivationToken, $newProfileUserName, $newProfileEmail, $newProfileHash, $newProfileSalt) {
	try {
		$this->setProfileId($newProfileId);
		$this->setProfileActivationToken($newProfileActivationToken);
		$this->setProfileUserName($newProfileUserName);
		$this->setProfileEmail($newProfileEmail);
		$this->setProfileHash($newProfileHash);
		$this->setProfileSalt($newProfileSalt);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}
}
	public function jsonSerialize() {
	// TODO: Implement jsonSerialize() method.
	}
}

