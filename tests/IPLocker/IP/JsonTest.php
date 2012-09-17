<?php
/**
*
*/

/**
*
*/
class IPJsonTest extends PHPUnit_Framework_TestCase {
	/**
	*
	*/
	protected $ipObj;
	
	/**
	*
	*/
	protected $fileLocation;
	
	/**
	*
	*/
	protected $deadFileLocation;
	
	/**
	*
	*/
	protected $ip;
	
	/**
	*
	*/
	public function setUp() {
		$this->fileLocation     = BASEDIR."tests/data/ip.json";
		$this->ipObj            = new \IPLocker\IPStore\Json($this->fileLocation, true);
		$this->ip               = '127.0.0.1';
		$this->deadFileLocation = BASEDIR."tests/data/nowrite-ip.json";

		if (file_exists($this->deadFileLocation)) {
			chmod($this->deadFileLocation, 0600);
			unlink($this->deadFilelocation);
		}

		file_put_contents($this->deadFileLocation, "");
		chmod($this->deadFileLocation, 0000);
	}
	
	/**
	*
	*/
	public function tearDown() {
		unlink($this->fileLocation);
		chmod($this->deadFileLocation, 0600);
		unlink($this->deadFileLocation);
	}
	
	/**
	*
	*/
	public function testValid() {
		$this->assertTrue($this->ipObj->validIPFile());
		$this->assertTrue($this->ipObj->validIPData());
		$this->assertTrue($this->ipObj->valid());
	}

	/**
	*
	*/
	public function testIPStoreFetch() {
		$this->assertTrue($this->ipObj->create($this->ip));
		$this->assertEquals($this->ipObj->fetchAll(), array($this->ip));
	}
	
	/**
	*
	*/
	public function testIPStore() {
		$this->assertFalse($this->ipObj->ipAllowed($this->ip));
		$this->assertTrue($this->ipObj->create($this->ip));
		$this->assertTrue($this->ipObj->ipAllowed($this->ip));
		$this->assertTrue($this->ipObj->create('255.255.255.0'));
		$this->assertTrue($this->ipObj->delete($this->ip));
//		$this->assertFalse($this->ipObj->ipAllowed($this->ip));
		$this->assertFalse($this->ipObj->create('256.0.0.0'));
		$this->assertFalse($this->ipObj->ipAllowed('256.0.0.0'));
		$this->assertFalse($this->ipObj->delete('256.0.0.0'));
	}
	
	/**
	*
	*/
	public function testInvalidData() {
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		$this->assertFalse($ip->valid());
		$this->assertFalse($ip->ipAllowed($this->ip));
	}
	

	/**
	*
	*/
	public function testInvalidDataFileLocation() {
		$ip = new \IPLocker\IPStore\Json(BASEDIR."tests/data/noexist.txt");
		$this->assertFalse($ip->valid());
	}

	/**
	*
	*/
	public function testDataFileNotReadable() {
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		$this->assertFalse($ip->validIPFile());
	}
	

	/**
	*
	*/
	public function testDataFileNotWritable() {
		chmod($this->deadFileLocation, 0400);
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		$this->assertFalse($ip->validIPFile());
	}
	
	/**
	* 
	*/
	public function testDataNotValid() {
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		$this->assertFalse($ip->validIPData());
	}

	/**
	* 
	*/
	public function testDataNotSavable() {
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		$this->assertFalse($ip->save());
	}

	/**
	*
	*/
	public function testDataNotDecoded() {
		chmod($this->deadFileLocation, 0400);
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);

		$this->assertFalse($ip->validIPData());
	}

	/**
	*
	*/
	public function testDataNotDecodable() {
		chmod($this->deadFileLocation, 0600);
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);

		$this->assertFalse($ip->decodeData());
	}
	
	/**
	*/
	public function testResetDataFail() {
		chmod($this->deadFileLocation, 0600);
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);

		$this->assertFalse($ip->resetData());
	}

	/**
	*
	*/
	public function testSaveFail() {
		chmod($this->deadFileLocation, 0600);
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);
		$this->assertFalse($ip->create($this->ip));
	}

	/**
	*
	*/
	public function testDeleteFail() {
		chmod($this->deadFileLocation, 0600);
		$ip = new \IPLocker\IPStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);
		$this->assertFalse($ip->delete($this->ip));
	}
	
	/**
	*
	*/
	public function testFetchErrors() {
		$this->assertEquals($this->ipObj->fetchErrors(), array());
	}
	
}
