<?php
/**
*
*/

/**
*
*/
class HelperTest extends PHPUnit_Framework_TestCase {
	/**
	*
	*/
	protected $oldRemoteAddr;
	
	/**
	*
	*/
	protected $oldForward;
	
	/**
	*
	*/
	public function setUp() {
		$this->oldRemoteAddr = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : NULL;
		$this->oldForward    = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : NULL;
		
		$_SERVER['REMOTE_ADDR']          = '127.0.0.1';
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.1';
	}

	/**
	*
	*/
	public function tearDown() {
		$_SERVER['REMOTE_ADDR'] = $this->oldRemoteAddr;
		$_SERVER['HTTP_X_FORWARDED_FOR'] = $this->oldForward;
	}
	
	/**
	* Test a few number formats and make sure they come out with the proper formatting for Twilio Services
	*/
	public function testFormatNumber() {
		$this->assertFalse(\IPLocker\Helpers::formatNumber("bad number"));
		$this->assertEquals(\IPLocker\Helpers::formatNumber("5551231234"), "+15551231234");
		$this->assertEquals(\IPLocker\Helpers::formatNumber("+15551231234"), "+15551231234");
		$this->assertEquals(\IPLocker\Helpers::formatNumber("(555) 123-1234"), "+15551231234");
		$this->assertEquals(\IPLocker\Helpers::formatNumber("(555) 123 1234"), "+15551231234");
		$this->assertEquals(\IPLocker\Helpers::formatNumber("+1 (555) 123-1234"), "+15551231234");
	}
	
	/**
	*
	*/
	public function testPrettyNumber() {
		$this->assertEquals(\IPLocker\Helpers::prettyNumber("5551231234"), "(555) 123-1234");
		$this->assertEquals(\IPLocker\Helpers::prettyNumber("+15551231234"), "(555) 123-1234");
		$this->assertEquals(\IPLocker\Helpers::prettyNumber("(555) 123-1234"), "(555) 123-1234");
		$this->assertEquals(\IPLocker\Helpers::prettyNumber("555 123-1234"), "(555) 123-1234");
	}
	
	/**
	*
	*/
	public function testValidIPAddress() {
		$this->assertEquals(\IPLocker\Helpers::validIPAddress("127.0.0.1"), true);
		$this->assertEquals(\IPLocker\Helpers::validIPAddress("192.168.1.1"), true);
		$this->assertEquals(\IPLocker\Helpers::validIPAddress("255.255.255.255"), true);
		$this->assertEquals(\IPLocker\Helpers::validIPAddress("10.0.0.1"), true);
		$this->assertEquals(\IPLocker\Helpers::validIPAddress("0.0.0.0"), true);

		$this->assertEquals(\IPLocker\Helpers::validIPAddress("256.256.256.256"), false);
	}
	
	/**
	*
	*/
	public function testRealIP() {
		$this->assertEquals(\IPLocker\Helpers::realIP(), '192.168.1.1');
		
		unset($_SERVER['HTTP_X_FORWARDED_FOR']);
		$this->assertEquals(\IPLocker\Helpers::realIP(), '127.0.0.1');

		unset($_SERVER['REMOTE_ADDR']);
		$this->assertEquals(\IPLocker\Helpers::realIP(), false);
		
	}
	
	/**
	*
	*/
	public function testIP() {
		$this->assertTrue(\IPLocker\Helpers::validIPAddress('127.0.0.1'));
		$this->assertTrue(\IPLocker\Helpers::validIPAddress('129.168.1.1'));
		$this->assertFalse(\IPLocker\Helpers::validIPAddress('256.256.256.256'));
	}
}
