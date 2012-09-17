<?php
/**
*
*/

/**
*
*/
class LockerTest extends PHPUnit_Framework_TestCase {
	/**
	*
	*/
	protected $locker;

	/**
	*
	*/
	protected $twilioObj;
	
	/**
	*
	*/
	protected $twilioConf;
	
	/**
	*
	*/
	protected $adminFile;
	
	/**
	*
	*/
	protected $adminNumber;
	
	/**
	*
	*/
	protected $adminName;

	/**
	*
	*/
	protected $admin;
	
	/**
	*
	*/
	protected $ipFile;
	
	/**
	*
	*/
	protected $ipObj;
	
	/**
	*
	*/
	protected $ip;
	
	/**
	*
	*/
	protected $mockRequest;
	
	/**
	*
	*/
	public function setUp() {
		global $twilio;

		$this->locker     = new \IPLocker\Locker;

		$this->twilioConf = $twilio;
		$this->twilio     = new Services_Twilio($twilio['sid'], $twilio['number']);
		
		$this->locker->setTwilio($this->twilio);
		$this->locker->setTwilioSid($twilio['sid']);
		
		$this->adminFile   = BASEDIR."tests/data/admin-lock.json";
		$this->admin       = new \IPLocker\AdminStore\Json($this->adminFile, true);
		$this->adminName   = 'Test User';
		$this->adminNumber = '+5551231234';

		$this->admin->create($this->adminNumber, $this->adminName);
		$this->locker->setAdmin($this->admin);

		$this->ipFile = BASEDIR."tests/data/ip-lock.json";
		$this->ipObj  = new \IPLocker\IPStore\Json($this->ipFile, true);
		$this->ip     = '127.0.0.1';

		$this->ipObj->create($this->ip);		
		$this->locker->setIP($this->ipObj);

		$this->mockRequest = array (
			'AccountSid'    => $twilio['sid'],
			'Body'          => '',
			'ToZip'         => '21204',
			'FromState'     => 'MD',
			'ToCity'        => 'TOWSON',
			'SmsSid'        => 'SM6d42d71929279d1bd7d8108d2e1b2f72',
			'ToState'       => 'MD',
			'To'            => $twilio['number'],
			'ToCountry'     => 'US',
			'FromCountry'   => 'US',
			'SmsMessageSid' => 'SM6d42d71929279d1bd7d8108d2e1b2f72',
			'ApiVersion'    => '2010-04-01',
			'FromCity'      => 'BALTIMORE',
			'SmsStatus'     => 'received',
			'From'          => $this->adminNumber,
			'FromZip'       => '21401'
		);
		
	}

	/**
	*
	*/
	public function tearDown() {
		chmod($this->ipFile, 0600);
		unlink($this->ipFile);

		chmod($this->adminFile, 0600);
		unlink($this->adminFile);
	}
	
	/**
	*
	*/
	public function testGetTwilioSID() {
		$locker = new \IPLocker\Locker();
		$locker->setTwilioSid($this->twilioConf['sid']);
		$this->assertEquals($locker->getTwilioSid(), $this->twilioConf['sid']);
	}
	

	/**
	*
	*/
	public function testFailExecInvalidAdmin() {
		$locker = new \IPLocker\Locker();
		$locker->setTwilioSid($this->twilioConf['sid']);
		$this->assertFalse($locker->exec(new \IPLocker\Command("+ ip 127.0.0.1")));
	}
	
	/**
	*
	*/
	public function testFailServiceTrapConditional() {
		$mock = $this->mockRequest;
		$mock['Body'] = "+ ip 127.0.0.1x";
		$this->assertFalse($this->locker->service($mock));
	}
	
	/**
	*
	*/
	public function testVersion() {
		$this->assertNotNull($this->locker->getVersion());
	}
	
	/**
	*
	*/
	public function testValidSetup() {
		$this->assertEquals($this->locker->getAdmin(), $this->admin);
		$this->assertEquals($this->locker->getIP(), $this->ipObj);
		$this->assertEquals($this->locker->getTwilio(), $this->twilio);
	
	}
	
	/**
	*
	*/
	public function testAuthenticateIP() {
		$this->assertTrue($this->locker->authenticateIP($this->ip));
	}
	
	/**
	*
	*/
	public function testAuthenticateIPFail() {
		$this->assertFalse($this->locker->authenticateIP('256.0.0.0'));
		$this->assertEquals($this->locker->fetchErrors(), array("Invalid IP"));
	}


	/**
	*
	*/
	public function testAuthenticateIPStoreFail() {
		$locker = new \IPLocker\Locker;
		$this->assertFalse($locker->authenticateIP($this->ip));
		$this->assertEquals($locker->fetchErrors(), array("IP store required"));
	}

	/**
	*
	*/
	public function testEmptyErrors() {
		$this->assertEquals($this->locker->fetchErrors(), array());
	}

	
	/**
	* @expectedException InvalidArgumentException
	*/
	public function testValidAdminObjectFail() {
		$bad = "bad";
		$this->locker->setAdmin($bad);
	}
	
	/**
	* @expectedException InvalidArgumentException
	*/
	public function testValidIPObjectFail() {
		$bad = "bad";
		$this->locker->setIP($bad);
	}
	
	/**
	* @expectedException InvalidArgumentException
	*/
	public function testValidTwilioObjectFail() {
		$bad = "bad";
		$this->locker->setTwilio($bad);
	}
	
	/**
	*
	*/
	public function testRunAdminCommand() {
		$mockRequest = $this->mockRequest;

		$mockRequest['Body'] = "+ admin 5551231234 Test User";
		$result = $this->locker->exec(new \IPLocker\Command($mockRequest['Body']));
		$this->assertTrue($result);

		chmod($this->adminFile, 0000);
		$result = $this->locker->exec(new \IPLocker\Command($mockRequest['Body']));
		$this->assertFalse($result);

		$result = $this->locker->exec(new \IPLocker\COmmand("- admin 5551231234"));
		$this->assertFalse($result);

		chmod($this->adminFile, 0600);

		$result = $this->locker->exec(new \IPLocker\Command("- admin 5551231234"));
		$this->assertTrue($result);

		$result = $this->locker->exec(new \IPLocker\Command("- admin 5551231234"));
		$this->assertTrue($result);

	}

	/**
	*
	*/
	public function testRunIPCommand() {
		$mockRequest = $this->mockRequest;

		$mockRequest['Body'] = "+ ip {$this->ip}";
		$result = $this->locker->exec(new \IPLocker\Command($mockRequest['Body']));
		$this->assertTrue($result);

		$mockRequest['Body'] = "{$this->ip}";
		$result = $this->locker->exec(new \IPLocker\Command($mockRequest['Body']));
		$this->assertTrue($result);

		chmod($this->ipFile, 0000);
		$result = $this->locker->exec(new \IPLocker\Command("- ip {$this->ip}"));
		$this->assertFalse($result);

		$result = $this->locker->exec(new \IPLocker\Command("+ ip {$this->ip}"));
		$this->assertFalse($result);
		chmod($this->ipFile, 0600);

	}

	/**
	*
	*/
	public function testResponse() {
		$this->assertNotNull($this->locker->respond("out"));
	}

	/**
	*
	*
	public function testFailExecuteOnInvalidAdapters() {
		$locker = new \IPLocker\Locker;
		$result = $locker->exec("+ ip {$this->ip}");
		$this->assertFalse($result['Status']);
	}
	*/
	
	/**
	*
	*/
	public function testRunService() {
		$mockRequest = $this->mockRequest;
		$mockRequest['Body'] = '127.0.0.1';

		$result = $this->locker->service($mockRequest);
		$this->assertTrue($result);

		$result = $this->locker->service(new \IPLocker\Command("- slip slide"));
		$this->assertFalse($result);
		
		$tmpRequest = $mockRequest;
		unset($tmpRequest['AccountSid']);
		$this->locker->service($tmpRequest);
		$this->assertFalse($result);
	
		$tmpRequest = $mockRequest;
		unset($tmpRequest['From']);
		$result = $this->locker->service($tmpRequest);
		$this->assertFalse($result);

	}
	
}
