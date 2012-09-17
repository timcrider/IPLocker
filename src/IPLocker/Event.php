<?php
namespace IPLocker;

use InvalidArgumentException;

/**
*
*/

/**
*
*/

class Event {
	/**
	*
	*/
	protected static $Registry = array();
	
	/**
	*
	*/
	public static function clearAll() {
		static::$Registry = array();
	}
	
	/**
	*
	*/
	public static function register($events, $callback) {
		if (!is_callable($callback)) {
			throw new \InvalidArgumentException('Registration callback is not callable.');
		}

		$events = (array)$events;

		foreach ($events AS $event) {
			static::$Registry[$event][] = $callback;
		}
		
		return;
	}

	/**
	*
	*/
	public static function trigger($event, $data=array()) {
		if (!isset(static::$Registry[$event])) {
			return false;
		}
		
		foreach (static::$Registry[$event] AS $listener) {
			call_user_func_array($listener, array($data));
		}
		
		return true;
	}


}
