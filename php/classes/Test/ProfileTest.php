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
		$password = "abc123";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));
	}
}
