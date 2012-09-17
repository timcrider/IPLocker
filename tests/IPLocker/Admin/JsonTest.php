<?php
/**
*
*/

/**
*
*/
class AdminJsonTest extends PHPUnit_Framework_TestCase {
	/**
	*
	*/
	protected $admin;
	
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
	protected $adminNum;
	
	/**
	*
	*/
	protected $adminName;
	
	
	/**
	*
	*/
	public function setUp() {
		$this->fileLocation     = BASEDIR."tests/data/admin.json";
		$this->admin            = new \IPLocker\AdminStore\Json($this->fileLocation, true);
		$this->adminNum         = '5551231234';
		$this->adminName        = 'Test Admin';
		$this->deadFileLocation = BASEDIR."tests/data/nowrite.json";

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
		$this->assertEquals($this->admin->validAdminFile(), true);
		$this->assertEquals($this->admin->validAdminData(), true);
		$this->assertEquals($this->admin->valid(), true);
	}

	/**
	*
	*/
	public function testAdminStoreFetch() {
		$this->assertTrue($this->admin->create($this->adminNum, $this->adminName));

		$number = \IPLocker\Helpers::formatNumber($this->adminNum);
		$this->assertEquals($this->admin->fetchAll(), array($number => $this->adminName));
	}
	
	/**
	*
	*/
	public function testAdminStore() {
		$this->assertFalse($this->admin->isAdmin($this->adminNum));
		$this->assertTrue($this->admin->create($this->adminNum, $this->adminName));
		$this->assertEquals($this->admin->isAdmin($this->adminNum), $this->adminName);
		$this->assertTrue($this->admin->delete($this->adminNum));
		$this->assertFalse($this->admin->isAdmin($this->adminNum));
	}
	
	/**
	*
	*/
	public function testInvalidData() {
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		$this->assertFalse($admin->valid());
	}
	

	/**
	*
	*/
	public function testInvalidDataFileLocation() {
		$admin = new \IPLocker\AdminStore\Json(BASEDIR."tests/data/noexist.txt");
		$this->assertFalse($admin->valid());
	}

	/**
	*
	*/
	public function testDataFileNotReadable() {
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		$this->assertFalse($admin->validAdminFile());
	}
	

	/**
	*
	*/
	public function testDataFileNotWritable() {
		chmod($this->deadFileLocation, 0400);
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		$this->assertFalse($admin->validAdminFile());
	}
	
	/**
	*
	*/
	public function testDataNotValid() {
		chmod($this->deadFileLocation, 0600);
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		$this->assertFalse($admin->validAdminData());
	}

	/**
	*
	*/
	public function testDataNotDecoded() {
		chmod($this->deadFileLocation, 0600);
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);

		$this->assertFalse($admin->validAdminData());
	}

	/**
	*
	*/
	public function testDataNotDecodable() {
		chmod($this->deadFileLocation, 0600);
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);

		$this->assertFalse($admin->decodeData());
	}
	
	/**
	*
	*/
	public function testResetDataFail() {
		chmod($this->deadFileLocation, 0600);
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);

		$this->assertFalse($admin->resetData());
	}

	/**
	*
	*/
	public function testSaveFail() {
		chmod($this->deadFileLocation, 0600);
		$admin = new \IPLocker\AdminStore\Json($this->deadFileLocation);
		chmod($this->deadFileLocation, 0000);
		$this->assertFalse($admin->create('5551231234', 'Test User'));
	}
	
	/**
	*
	*/
	public function testFetchErrors() {
		$this->assertEquals($this->admin->fetchErrors(), array());
	}
}
