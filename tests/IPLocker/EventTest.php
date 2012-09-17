<?php
/**
*
*/

/**
* https://github.com/zumba/symbiosis/blob/master/Zumba/Symbiosis/Test/Event/EventManagerTest.php
*/
class EventTest extends PHPUnit_Framework_TestCase {
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
	* @expectedException \InvalidArgumentException
	*/
	public function testRegisterUncallable() {
		\IPLocker\Event::clearAll();
		\IPLocker\Event::register('test.fail', "");
	}
	
	/**
	*
	*/
	public function testNoTrigger() {
		\IPLocker\Event::clearAll();
		$this->assertFalse(\IPLocker\Event::trigger('test.noexist'));
	}

	/**
	*
	*/
	public function testTrigger() {
		\IPLocker\Event::register('test.true', function($data) { return true; });
		$this->assertTrue(\IPLocker\Event::trigger('test.true', true));	
	}
	
}
