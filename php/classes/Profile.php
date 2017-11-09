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

use InvalidArgumentException;
use PhpParser\Node\Expr\Empty_;
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
	 * this creates the profile User name
	 * @var $username
	 **/
private $profileUsername;
	/**
	 * constructor for this profile
	 * @param string|Uuid $newProfileId id of this Profile or null if a new Profile
	 * @param string $newProfileActivationToken activation token to safe guard against malicious accounts
	 * @param string $newProfileEmail string containing email
	 * @param string $newProfileHash string containing password hash
	 * @param string $newProfileSalt string containing passowrd salt
	 * @param  $newProfileUsername
	 * @throws InvalidArgumentException if data types are not invalid
	 * @throws  \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throw \Exception if some other exception occurs
 	 **/
public function  __construct($newProfileId, $newProfileActivationToken, $newProfileEmail, $newProfileHash, $newProfileSalt, $newProfileUsername) {
	try {
		$this->setProfileId($newProfileId);
		$this->setProfileActivationToken($newProfileActivationToken);
		$this->setProfileEmail($newProfileEmail);
		$this->setProfileHash($newProfileHash);
		$this->setProfileSalt($newProfileSalt);
		$this->setProfileUsername($newProfileUsername);

	} catch( \RangeException | \Exception | \TypeError $exception) {
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
	public function setProfileId($newProfileId) : void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->profileId = $uuid;
	}
	/**
	 * accessor method for activation token
	 * @return string value of the activation token
	 **/
	public function getProfileActivationToken(): string {
		return ($this->profileActivationToken);
	}
	/**
	 *mutator method for account activation token
	 *
	 * @param string $newProfileActivationToken
	 * @throws InvalidArgumentException if the token is not a string or insecure
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
	 * accessor method for profileEmail
	 *
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}
	/**
	 * mutator for profileEmail
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \PDOException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("profile email is too large"));
		}
		// store the email
		$this->profileEmail = $newProfileEmail;
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
	 *mutator method for profileHash
	 *
	 * param string $newProfileHash
	 * @throws InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 **/
	public function setProfileHash($newProfileHash): void {
		//enforce that the hash is properly formatted
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the hash is exactly 128 characters.
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}
		//store the hash
		$this->profileHash = $newProfileHash;
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
	 * mutator method for profileSalt
	 *
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt
	 * @throws InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if the profile salt is not a string
	 **/
	public function setProfileSalt($newProfileSalt): void {
		//enforce that the salt is properly formatted
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);
		//enforce that the salt is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the salt is exactly 64 characters.
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("profile salt must be 64 characters"));
		}
		//store the hash
		$this->profileSalt = $newProfileSalt;
	}
	/**
	 * accessor method for username
	 *
	 * @return string value of username
	 */
	public function getProfileUsername():string {
		return $this->profileUsername;
	}

	/**
	 * mutator method for username
	 *
	 * @param
	 **/
	public function setProfileUsername($newProfileUsername): void {
		$newProfileUsername = trim($newProfileUsername);
		$newProfileUsername = filter_var($newProfileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		 if(empty($newProfileUsername) === true) {
		 	throw(new InvalidArgumentException("Username is empty of insecure"));
		 }
		 //verify the profile content will fit in database
		if(strlen($newProfileUsername) > 32) {
		 	throw(new \RangeException(("Username is too large")));
		}
		//store the username
		$this->profileUsername= $newProfileUsername;
	}

	/**
	 * inserts this profile into mySQl
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQl related errors occur
	 *
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		//create query template
		$query = "INSERT INTO profile(ProfileId, ProfileActivationToken,  ProfileEmail, ProfileHash, ProfileSalt, ProfileUserName) VALUES(:profileId, :profileActivationToken,  :profileEmail, :profileHash, :profileSalt, :profileUsername)";
		$statement = $pdo->prepare($query);
		$parameters = ["profileId"=> $this->profileId-> getBytes(),"profileActivationToken"=> $this->profileActivationToken, "profileUsername"=> $this->profileUsername, "profileEmail"=> $this->profileEmail, "profileHash"=> $this->profileHash, "profileSalt"=> $this->profileSalt];
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
		$query = "DELETE FROM profile WHERE profileId = :profileId";
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
public function update(\PDO $pdo) : void {

	// crate query template
	$query = "UPDATE profile SET profileId = :profileId, profileActivationToken = :profileActivationToken, profileEmail = :profileEmail, profileHash = :profileHash, profileSalt = :profileSalt, profileUsername = :profileUsername WHERE profileId = :profileId";
	$statement = $pdo->prepare($query);
	$parameters = ["profileId" => $this->profileId->getBytes(), "profileActivationToken" => $this->profileActivationToken, "profileEmail" => $this->profileEmail, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt, "profileUsername" => $this->profileUsername];
	$statement->execute($parameters);
	}
	/**
	 * gets the Profile by profile id
	 *
	 * @param \PDO $pdo $pdo PDO connection object
	 * @param string $profileId profile Id to search for
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getProfileByProfileId(\PDO $pdo, string $profileId):?Profile {
		// sanitize the profile id before searching
		try {
			$profileId = self::validateUuid($profileId);
		} catch(InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUsername FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the profile id to the place holder in the template
		$parameters = ["profileId" => $profileId->getBytes()];
		$statement->execute($parameters);
		// grab the Profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}
	/**
	 * gets the Profile by email
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileEmail email to search for
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/

	public static function getProfileByProfileEmail(\PDO $pdo, string $profileEmail): ?Profile {
		// sanitize the email before searching
		$profileEmail = trim($profileEmail);
		$profileEmail = filter_var($profileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($profileEmail) === true) {
			throw(new \PDOException("not a valid email"));
		}
		// create query template
		$query = "SELECT profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUsername FROM profile WHERE profileEmail = :profileEmail";
		$statement = $pdo->prepare($query);
		// bind the profile id to the place holder in the template
		$parameters = ["profileEmail" => $profileEmail];
		$statement->execute($parameters);
		// grab the Profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * gets the Profile by at handle
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileUsername at handle to search for
	 * @return \SPLFixedArray of all profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileUsername(\PDO $pdo, string $profileUsername) : \SPLFixedArray {
		// sanitize the Username before searching
		$profileUsername= trim($profileUsername);
		$profileUsername = filter_var($profileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileUsername) === true) {
			throw(new \PDOException("not a valid Username"));
		}
		// create query template
		$query = "SELECT  profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUsername FROM profile WHERE profileUsername = :profileUsername";
		$statement = $pdo->prepare($query);
		// bind the profile Username to the place holder in the template
		$parameters = ["profileUsername" => $profileUsername];
		$statement->execute($parameters);
		$profiles = new \SPLFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUsername"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($profiles);
	}

	/**
	 * get the profile by profile activation token
	 *
	 * @param string $profileActivationToken
	 * @param \PDO object $pdo
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public
	static function getProfileByProfileActivationToken(\PDO $pdo, string $profileActivationToken) : ?Profile {
		//make sure activation token is in the right format and that it is a string representation of a hexadecimal
		$profileActivationToken = trim($profileActivationToken);
		if(ctype_xdigit($profileActivationToken) === false) {
			throw(new InvalidArgumentException("profile activation token is empty or in the wrong format"));
		}
		//create the query template
		$query = "SELECT  profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUsername FROM profile WHERE profileActivationToken = :profileActivationToken";
		$statement = $pdo->prepare($query);
		// bind the profile activation token to the placeholder in the template
		$parameters = ["profileActivationToken" => $profileActivationToken];
		$statement->execute($parameters);
		// grab the Profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["profileId"] = $this->profileId->toString();
		unset($fields["profileHash"]);
		unset($fields["profileSalt"]);
		return ($fields);
	}
}

