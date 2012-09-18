<?php

namespace IPLocker\AdminStore;

use \InvalidArgumentException,
	\IPLocker\Exception;
/**
 * IPLocker administration Json storage
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
class Json implements \IPLocker\AdminStore\StorageInterface
{
	/**
	 * @var string $adminFile Location of the admin users json file
	 */
	protected $adminFile;

	/**
	 * @var array $adminData Stack of admin users
	 */
	protected $adminData = array();

	/**
	 * @var array $errors Stack of instance errors
	 */
	protected $errors = array();

	/**
	 * 
	 *
	 * @param string $adminFile Location of the admin json file
	 */
	public function __construct($adminFile, $create=false) {
		$this->adminFile = $adminFile;
		
		if ($create && !file_exists($adminFile)) {
			$this->resetData();
		}
		
		$this->adminData = $this->decodeData();
	}

	/**
	 * Reset the admin users to an empty array and store the empty data set
	 *
	 * @return mixed Returns bytes written to file, FALSE if write failed
	 */
	public function resetData() {
		$this->adminData = array();

		return (bool)@file_put_contents($this->adminFile, json_encode($this->adminData));
	}

	/**
	 * Test to make sure the current setup is usable
	 * 
	 * @return bool TRUE if the admin file and data are usable, FALSE if either are not
	 */
	public function valid() {
		return (!$this->validAdminFile() || !$this->validAdminData()) ? false : true;
	}

	/**
	 * Test the usability of the admin data file
	 *
	 * @return bool TRUE if the admin file exists, is readable, and writable; FALSE if it is not
	 */
	public function validAdminFile() {
		if (!file_exists($this->adminFile)) {
			$this->errors[] = "Admin file does not exist: {$this->adminFile}";
			return false;
		}
		
		if (!is_readable($this->adminFile)) {
			$this->errors[] = "Admin file is not readable: {$this->adminFile}";
			return false;
		}
		
		if (!is_writable($this->adminFile)) {
			$this->errors[] = "Admin file is not writable: {$this->adminFile}";
			return false;
		}

		return true;
	}
	
	/**
	 * Test to make sure the local admin data is a valid array
	 *
	 * @return bool TRUE if the admin data is an array, FALSE if it is not
	 */
	public function validAdminData() {
		if (!is_array($this->adminData)) {
			$this->adminData = $this->decodeData();
		}

		return is_array($this->adminData);
	}
	
	/**
	 * Decode the data set located in the admin json file
	 *
	 * @return array Returns stack of admin data on success, FALSE on failure
	 */
	public function decodeData() {
		if (!$this->validAdminFile()) {
			return false;
		}

		$data = @file_get_contents($this->adminFile);
		
		if (empty($data)) {
			return false;
		}
		
		$adminData = json_decode($data, true);

		return (is_array($adminData)) ? $adminData : false;
	}

	/**
	 * Create an admin user
	 *
	 * @param string $number Phone number of the admin user
	 * @param string $name Name of the admin user
	 *
	 * @return bool Returns TRUE if creation or update succeeds, FALSE if it does not
	 */
	public function create($number, $name) {
		$number = \IPLocker\Helpers::formatNumber($number);
		$this->adminData[$number] = $name;
		
		return $this->save();
	}

	/**
	 * Delete an admin user
	 *
	 * @param string $number Phone number of the admin user
	 *
	 * @return bool Returns TRUE if delete was successful, FALSE if it was not
	 */
	public function delete($number) {
		$number = \IPLocker\Helpers::formatNumber($number);
		unset($this->adminData[$number]);

		return $this->save();
	}

	/**
	 * Save the current administration stack
	 *
	 * @return bool Returns TRUE if save was successful, FALSE if it was not
	 */
	public function save() {
		if ($this->valid()) {
			$data = json_encode($this->adminData);
			return (bool)@file_put_contents(trim($this->adminFile), $data);
		}

		return false;

	}

	/**
	 * Determine if a number is a valid admin
	 *
	 * @param string $number Phone number to check.
	 *
	 * @return string Returns the name of the admin associated with that number, FALSE if no matches
	 */
	public function isAdmin($number) {
		$number = \IPLocker\Helpers::formatNumber($number);

		if (!isset($this->adminData[$number])) {
			return false;
		}

		return $this->adminData[$number];
	}

	/**
	 * Fetch the stack of all the current admin
	 *
	 * @return array Stack of admin
	 */
	public function fetchAll() {
		return ($this->valid()) ? $this->adminData : false;
	}

	/**
	 * Fetch the stack of admin errors
	 *
	 * @return array Stack of errors
	 */
	public function fetchErrors() {
		return $this->errors;
	}
	
	/**
	*
	*/
	public function exec(\IPLocker\Command $command) {
		$com = $command->fetchCommand();
		
        if ($com['action'] == 'create')  {
        	return ($this->create($com['params'][0], $com['params'][1])) ?
        		array('Status' => true, 'Message' => "Successfully added admin: {$com['params'][0]} ({$com['params'][1]})") :
        		array('Status' => false, 'Message' => "Error adding: {$com['params'][0]} ({$com['params'][1]}");
        } else {
        	return ($this->delete($com['params'][0])) ?
        		array('Status' => true, 'Message' => "Successfully removed admin: {$com['params'][0]}") :
        		array('Status' => false, 'Message' => "Failed to remove admin: {$com['params'][0]}");
        }
	}
}
