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
class Profile implements \JsonSerializable {
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
	/**
	 * accessor method for profile id
	 *
	 * @return Uuid value of profile id
	 **/
	public function getProfileId() : Uuid {
		return($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param Uuid/string $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not a uuid or string
	 **/
	public function setProfileId( $newProfileId) : void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->profileId = $uuid;
	}

	/**
	 *mutator method for account activation token
	 *
	 * @param string $newProfileActivationToken
	 * @throws \InvalidArgumentException if the token is not a string or insecure
	 * @throws \RangeException if token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setProfileActivationToken(?string $newProfileActivationToken): void {
		if($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}
		$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
		if(ctype_xdigit($newProfileActivationToken) === false) {
			throw(new\RangeException("user activation is not valid"));
		}
		//make sure user activation token is only 32 characters
		if(strlen($newProfileActivationToken) !== 32) {
			throw(new\RangeException("user activation token has to be 32"));
		}
		$this->profileActivationToken = $newProfileActivationToken;
	}

	/**
	 * accessor method for activation token
	 * @return string value of the activation token
	 **/
	public function getProfileActivationToken(): string {
		return ($this->profileActivationToken);
	}


	/**
	 * accessor method for username
	 *
	 * @return string value of username
	 */
	public function getUsername():string {
		return $this->username;
	}

	/**
	 * mutator method for username
	 *
	 * @return username
	 **/
	public function setUsername() {
		return($this->Username);
	}

	/**
	 * mutator for profileEmail
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("profile email is too large"));
		}
		// store the email
		$this->profileEmail = $newProfileEmail;
	}


	/**
	 * accessor method for profileEmail
	 *
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}

	/**
	 *mutator method for profileHash
	 *
	 * param string $newProfileHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 **/
	public function setProfileHash($newProfileHash): void {
		//enforce that the hash is properly formatted
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new \InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the hash is exactly 128 characters.
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}
		//store the hash
		$this->profileHash = $newProfileHash;
	}

	/**
	 * accessor method for profileHash
	 *
	 * @return string value of hash
	 **/
	public function getProfileHash(): string {
		return $this->profileHash;
	}

	/**
	 * mutator method for profileSalt
	 *
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt
	 * @throws \InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if the profile salt is not a string
	 **/
	public function setProfileSalt($newProfileSalt): void {
		//enforce that the salt is properly formatted
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);
		//enforce that the salt is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the salt is exactly 64 characters.
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("profile salt must be 128 characters"));
		}
		//store the hash
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * accessor method for profileSalt
	 *
	 * @return string representation of the salt hexadecimal
	 **/
	public function getProfileSalt(): string {
		return $this->profileSalt;
	}

	/**
	* formats the state variables for JSON serialization
	*
	* @return array resulting state variables to serialize
	**/
	public function jsonSerialize() {
		return(get_object_vars($this));
	}
	/**
	 * inserts this profile into mySQl
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQl related errors occur
	 *
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(PDO $pdo) : void {
		//create query template
		$query = "INSERT INTO profile(ProfileId, ProfileActivationToken, ProfileUserName, ProfileEmail, ProfileHash, ProfileSalt) VALUES(:profileId, :profileActivationToken, :profileUsername, :profileEmail, :profileHash, :profileSalt)";
		$statement = $pdo->prepare($query);
		$parameters = ["profileId"=> $this->profileId-> getBytes(),"profileActivationToken"=> $this->getProfileActivationToken(), "profileUsername"=> $this->getProfileUsername(), "profileEmail"=> $this->getProfileEmail(), "profileHash"=> $this->getProfileHash(), "profileSalt"=> $this->getProfileSalt()];
		$statement->execute($parameters);
	}
	/**
	 * deletes this profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		//create query template
		$query = "DELETE FROM profile WHERE profileId = : profileId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place in the template
		$parameters = ["profileId"=> $this->profileId->getBytes()];
		$statement->execute($parameters);
	}
/**
 * updates this profile in mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not PDO connection object
 **/

}

