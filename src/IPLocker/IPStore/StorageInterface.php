<?php

namespace IPLocker\IPStore;

/**
 * IPLocker ip user storage interface
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
interface StorageInterface
{
	/**
	 * Create an ip
	 *
	 * @param string $ip IP to allow
	 *
	 * @return bool Returns TRUE if creation or update succeeds, FALSE if it does not
	 */
	function create($ip);

	/**
	 * Delete an IP
	 *
	 * @param string $ip IP to remove
	 *
	 * @return bool Returns TRUE if delete was successful, FALSE if it was not
	 */
	function delete($ip);

	/**
	 * Save the current ip stack
	 *
	 * @return bool Returns TRUE if save was successful, FALSE if it was not
	 */
	function save();

	/**
	 * Determine if an IP is allowed
	 *
	 * @param string $ip IP address to test
	 *
	 * @return bool TRUE if the IP is allowed, FALSE if it is not
	 */
	function ipAllowed($ip);

	/**
	 * Determine if the current storage resource is usable
	 *
	 * @return bool Returns TRUE if the storage is valid, FALSE if it is not
	 */
	function valid();

	/**
	 *
	 */
	public function exec(\IPLocker\Command $command);

	/**
	 * Fetch a list of all of the IPs
	 *
	 * @return array Stack of the current IPs
	 */
	public function fetchAll();

}
