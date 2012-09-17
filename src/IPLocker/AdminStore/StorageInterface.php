<?php

namespace IPLocker\AdminStore;

/**
 * IPLocker administration user storage interface
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
interface StorageInterface
{
	/**
	 * Create an admin user
	 *
	 * @param string $number Phone number of the admin user
	 * @param string $name Name of the admin user
	 *
	 * @return bool Returns TRUE if creation or update succeeds, FALSE if it does not
	 */
	function create($number, $name);

	/**
	 * Delete an admin user
	 *
	 * @param string $number Phone number of the admin user
	 *
	 * @return bool Returns TRUE if delete was successful, FALSE if it was not
	 */
	function delete($number);

	/**
	 * Save the current administration stack
	 *
	 * @return bool Returns TRUE if save was successful, FALSE if it was not
	 */
	function save();

	/**
	 * Determine if the number is a valid admin
	 *
	 * @param string $number Phone number to check.
	 *
	 * @return string Returns the name of the admin associated with that number, FALSE if no matches
	 */
	function isAdmin($number);

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
	 * Fetch a list of all of the admin users
	 *
	 * @return array Stack of the current admin users
	 */
	public function fetchAll();

}
