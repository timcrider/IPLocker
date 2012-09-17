<?php
/**
*
*/

/**
*
*/
class CommandTest extends PHPUnit_Framework_TestCase {
	/**
	*
	*/
	public function setUp() {
	}

	/**
	*
	*/
	public function tearDown() {
	}
	
	/**
	*
	*/
	public function testCreateIPCommands() {
			$c = new \IPLocker\Command("+ ip 127.0.0.1");
			$this->assertTrue($c->valid());

			$c = new \IPLocker\Command("add ip 127.0.0.1");
			$this->assertTrue($c->valid());
	}

	/**
	*
	*/
	public function testCreateAdminCommands() {
			$c = new \IPLocker\Command("+ admin 5551231234 Test User");
			$this->assertTrue($c->valid());
			
			$c = new \IPLocker\Command("add admin 5551231234 Test User");
			$this->assertTrue($c->valid());
			
			$c = new \IPLocker\Command("+ admin 555-123-1234 Test User");
			$this->assertTrue($c->valid());
			
			$c = new \IPLocker\Command("add admin 555-123-1234 Test User");
			$this->assertTrue($c->valid());
	}

	/**
	*
	*/
	public function testRemoveIPCommands() {
		$c = new \IPLocker\Command("- ip 127.0.0.1");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rm ip 127.0.0.1");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rem ip 127.0.0.1");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("remove ip 127.0.0.1");
		$this->assertTrue($c->valid());
	}
	
	/**
	*
	*/
	public function testRemoveAdminCommands() {
		$c = new \IPLocker\Command("- admin 5551231234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rm admin 5551231234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rem admin 5551231234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("remove admin 5551231234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("- admin 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rm admin 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rem admin 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("remove admin 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("- adm 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rm adm 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rem adm 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("remove adm 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("- # 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rm # 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("rem # 555-123-1234");
		$this->assertTrue($c->valid());

		$c = new \IPLocker\Command("remove # 555-123-1234");
		$this->assertTrue($c->valid());
	}
	
	/**
	*
	*/
	public function testToggle() {
		$c = new \IPLocker\Command("127.0.0.1");
		$this->assertTrue($c->valid());

	}
	
	/**
	*
	*/
	public function testErrors() {
		$c = new \IPLocker\Command("+ ip 127.0.0.1");
		$this->assertEquals($c->fetchErrors(), array());
	
		$c = new \IPLocker\Command("+ ip");
		$this->assertEquals($c->fetchErrors(), array("Command stack requires 3 or more pieces"));
	
		$c = new \IPLocker\Command("+ admin bad-num");
		$this->assertEquals($c->fetchErrors(), array("Invalid phone number"));

		$c = new \IPLocker\Command("+ admin 5551231234");
		$this->assertEquals($c->fetchErrors(), array("Phone numbers must have a name associated with it"));
	
		$c = new \IPLocker\Command("+ ip 256.0.0.0");
		$this->assertEquals($c->fetchErrors(), array("Invalid ip address '256.0.0.0'"));

		$c = new \IPLocker\Command("+ unknown 256.0.0.0");
		$this->assertEquals($c->fetchErrors(), array("Unknown command type 'unknown'"));
	
		$c = new \IPLocker\Command("256.0.0.0");
		$this->assertEquals($c->fetchErrors(), array("Invalid toggle for 256.0.0.0"));

		$c = new \IPLocker\Command("+ ip 127.0.0.1");
		$c->fetchCommand('noexist');
		$this->assertEquals($c->fetchErrors(), array("Invalid command part 'noexist'"));

	}
	
	/**
	*
	*/
	public function testFetchCommand() {
		$cmd = "+ ip 127.0.0.1";
		$c = new \IPLocker\Command($cmd);
		
		$expect = array(
			'valid'  => 1,
			'raw'    => $cmd,
			'action' => 'create',
			'type'   => 'ip',
			'params' => array(
				'127.0.0.1'
			)
		);
		
		$this->assertEquals($c->fetchCommand(), $expect);

		$this->assertEquals($c->fetchCommand('valid'), $expect['valid']);
		$this->assertEquals($c->fetchCommand('raw'), $expect['raw']);
		$this->assertEquals($c->fetchCommand('action'), $expect['action']);
		$this->assertEquals($c->fetchCommand('type'), $expect['type']);
		$this->assertEquals($c->fetchCommand('params'), $expect['params']);
	}
	
}
