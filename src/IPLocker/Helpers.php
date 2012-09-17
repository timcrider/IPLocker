<?php

namespace IPLocker;

use InvalidArgumentException;

/**
 * IPLocker helper functionality
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
class Helpers
{
	/**
	 * Format a phone number into a Twilio friendly format
	 *
	 * @param string $number Phone number to format
	 *
	 * @return string Twilio formatted number, FALSE if number is invalid
	 */
	public static function formatNumber($number) {
		$number = preg_replace('/[^0-9]/', '', $number);

		if (preg_match('/^1[0-9]{10}/', $number)) {
			return "+{$number}";
		} else if (preg_match('/^[0-9]{10}/', $number)) {
			return "+1{$number}";
		}

		return false;
	}

	/**
	 *
	 */
	public static function prettyNumber($number) {
		if ($number != self::formatNumber($number)) {
			$number = self::formatNumber($number);
		}

		$number = preg_replace('/^\+1/', '', $number);
		return "(".substr($number, 0,3).") ".substr($number, 3, 3)."-".substr($number, 6,4);
	}



	/**
	 * Determine if an IP Address is valid
	 *
	 * @todo Allow for IPv6 and IP subnets
	 *
	 * @param string $ip IP address to test
	 *
	 * @return bool TRUE if IP address is valid, FALSE if it is not
	 */
	public static function validIPAddress($ip) {
		if (preg_match('/^((1?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(1?\d{1,2}|2[0-4]\d|25[0-5])$/', $ip)) {
			return true;
		}

		return false;
	}	

	/**
	 * Determine the real IP of a user
	 *
	 * This is useful for determine an IP from behind a load balancer.
	 *
	 * @return mixed IP address if valid, FALSE if not
	 */
	public static function realIP() {
		if (empty($_SERVER['HTTP_X_FORWARDED_FOR']) && empty($_SERVER['REMOTE_ADDR'])) {
			return false;
		}
		
		return (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR']: $_SERVER['REMOTE_ADDR'];
	}
}
