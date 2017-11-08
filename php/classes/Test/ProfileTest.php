<?php
/**
 * Created by PhpStorm.
 * User: neena
 * Date: 11/3/17
 * Time: 11:51 AM
 */

namespace Edu\Cnm\Infrastructure\Test;
use Edu\Cnm\Infrastructure\Profile;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Profile class
 *
 * This is a complete PHPUnit test of the Category class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Profile
 * @author Tanisha Purnell <tpurnell@cnm.edu>
 **/
class ProfileTest extends InfrastructureTest {

	/**
	 * placeholder
	 * @var string $VALID_ACTIVATION
	 */
	protected $VALID_ACTIVATION;
	/**
	 * valid email to use
	 * @var string $VALID_EMAIL
	 **/
	protected $VALID_EMAIL = "PHPUnit";
	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 */
	protected $VALID_HASH;
	/**
	 * valid salt to use to create the profile object to own the test
	 * @var string $VALID_SALT
	 */
	protected $VALID_SALT;
	/**
	 * valid Username
	 * @var string $VALID_USERNAME
	 **/
	protected $VALID_USERNAME = "@phpunit";

	/**
	 * run the default setup operation to create salt and hash.
	 */
	public final function setUp(): void {
		parent::setUp();
		//
		$password = "1001";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));
	}

	/**
	 * Test inserting  a valid profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile(): void {
		//count the number or rows and add save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		//create a new profile and insert into mySQL
		$profile = new profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);

		//var_dump($profile)d
		$profile->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_USERNAME);
	}

	/**
	 * test inserting a Profile that already exist
	 **/
	public function testInsertInvalidProfile(): void {
		//create a profile with a non null profileId and watch it fail
		$profile = new Profile(InfrastructureTest::INVALID_KEY, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());
	}
	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());

		// edit the Profile and update it in mySQL
		$profile->setProfileUsername($this->VALID_USERNAME);
		$profile->update($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_USERNAME);
	}
	/**
	 * test updating a Profile that does not exist
	 *
	 * @expectedException \PDOException
	 **/
	public function testUpdateInvalidProfile() : void {
		//create a Profile and try to update it without actually inserting it
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->update($this->getPDO());
	}
	/**
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidProfile() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		//create a new profile and insert into mySQL
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());

		//delete the profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));

		//grab the data from mySQL and enforce the Profile does not exist
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertNull($pdoProfile);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));
	}
	/**
	 * test deleting a profile that does not exist
	 * @expectedException \PDOException
	 **/
	public function testDeleteInvalidProfile() : void {
		//create a Profile and try to delete it without actually inserting it
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->delete($this->getPDO());
	}
	/**
	 * test inserting a Profile and re-grabbing it from mySQL
	 **/
	public function testGetValidProfileByProfileId() : void {
		//count the number if rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		//create a new Profile and insert into mySQL
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_USERNAME);
	}
	/**
	 * test grabbing a Profile that does not exist
	 **/
	public function testGetInvalidProfileByProfileId() : void {
		//grab a profile id that exceeds the maximum allowable profile id
		$profile = profile::getProfileByProfileId($this->getPDO(), InfrastructureTest::INVALID_KEY);
		$this->assertNull($profile);
	}
	public function testGetValidProfileByProfileUsername() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		//create a new profile and insert into mySQL
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());

		//grab the data from mySQL
		$results = Profile::getProfileByProfileUsername($this->getPDO(), $this->VALID_USERNAME);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));

		//enforce the results meet expectations
		$pdoProfile = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_USERNAME);
	}
	/**
	 * test grabbing a Profile by Username that does not exist
	 **/
	public function testGetInvalidProfileUsername() : void {
		//grab a Username that does not exist
		$profile = Profile::getProfileByProfileUsername($this->getPDO(),"Username does not exist");
		$this->assertCount(0, $profile);
	}
	/**
	 * test grabbing a Profile by email
	 **/
	public function testGetValidProfileEmail() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		//create a new Profile and insert into mySQL
		$profile = new Profile(null, $this->VALID_HASH, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileEmail($this->getPDO(), $profile->getProfileEmail());
		$this->assertEquals($numRows + 1,$this->getConnection()->getRowCount("profile"));
		$this->assertCount($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_USERNAME);
	}
	/**
	 * test grabbing a Profile by an email that does not exist
	 **/
	public function testGetInvalidProfileByEmail() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		//create a new Profile and insert into mySQL
		$profile = new Profile(null, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
		$profile->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileActivationToken($this->getPDO(), $profile->getProfileActivationToken());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_USERNAME);
	}
	/**
	 * test grabbing a Profile by an email that does not exist
	 **/
	public function testInvalidProfileActivation() : void {
		//grab an email that does not exist
		$profile = Profile::getProfileByProfileActivationToken($this->getPDO(),"1234566848436393");
		$this->assertNull($profile);
	}
}
