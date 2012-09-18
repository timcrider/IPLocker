<?php

namespace IPLocker\IPStore;

use \Invalid\ArgumentExeption,
	\IPLocker\Exception;

/**
 * IPLocker IP Json storage
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
class Json implements \IPLocker\IPStore\StorageInterface
{
	/**
	 * @var string $ipFile Location of the IP json file
	 */
	protected $ipFile;

	/**
	 * @var array $ipData Stack of IP addresses
	 */
	protected $ipData = array();
	
	/**
	 *
	 */
	protected $errors = array();
	

	/**
	 * 
	 *
	 * @param string $ipFile Location of the ip json file
	 */
	public function __construct($ipFile, $create=false) {
		$this->ipFile = $ipFile;
		
		if ($create && !file_exists($ipFile)) {
			$this->resetData();
		}
		
		
		$this->ipData = $this->decodeData();
	}

	/**
	 * Reset the IP data to an empty array and store the empty data set
	 *
	 * @return mixed Returns bytes written to file, FALSE if write failed
	 */
	public function resetData() {
		$this->ipData = array();
		return (bool)@file_put_contents($this->ipFile, json_encode($this->ipData));
	}

	/**
	 * Test to make sure the current setup is usable
	 * 
	 * @return bool TRUE if the IP data file and data are usable, FALSE if either are not
	 */
	public function valid() {
		return (!$this->validIPFile() || !$this->validIPData()) ? false : true;
	}

	/**
	 * Test the usability of the IP data file
	 *
	 * @return bool TRUE if the IP data file exists, is readable, and writable; FALSE if it is not
	 */
	public function validIPFile() {

		if (!file_exists($this->ipFile)) {
			$this->errors[] = "IP data file does not exist: {$this->ipFile}";
			return false;
		}
		
		if (!is_readable($this->ipFile)) {
			$this->errors[] = "IP data file is not readable: {$this->ipFile}";
			return false;
		}
		
		if (!is_writable($this->ipFile)) {
			$this->errors[] = "IP data file is not writable: {$this->ipFile}";
			return false;
		}

		return true;
	}
	
	/**
	 * Test to make sure the local IP data is a valid array
	 *
	 * @return bool TRUE if the IP data is an array, FALSE if it is not
	 */
	public function validIPData() {
		if (!is_array($this->ipData)) {
			$this->ipData = $this->decodeData();
		}
		
		return is_array($this->ipData);
	}
	
	/**
	 * Decode the data set located in the IP json file
	 *
	 * @return array Returns stack of IP data on success, FALSE on failure
	 * @throws \IPLocker\Exception Data exists but is not decodable
	 */
	public function decodeData() {
		if (!$this->validIPFile()) {
			return false;
		}

		$data = @file_get_contents($this->ipFile);
		
		if (empty($data)) {
			$this->resetData();
			$this->save();
		}

		$ipData = json_decode($data, true);
		return (!is_array($ipData)) ? false : $ipData;
	}

	/**
	 * Create an IP address
	 *
	 * @param string $ip IP address to add to the stack
	 *
	 * @return bool Returns TRUE if creation or update succeeds, FALSE if it does not
	 */
	public function create($ip) {
		if (!$this->validIPData() || !\IPLocker\Helpers::validIPAddress($ip)) {
			return false;
		}

		if (!in_array($ip, $this->ipData)) {
			$this->ipData[] = $ip;
		}

		return $this->save();
	}

	/**
	 * Delete an admin user
	 *
	 * @param string $number Phone number of the admin user
	 *
	 * @return bool Returns TRUE if delete was successful, FALSE if it was not
	 */
	public function delete($ip) {
		if (!\IPLocker\Helpers::validIPAddress($ip)) {
			return false;
		}
		
		$ips = array();
		
		foreach ((array)$this->ipData AS $cip) {
			if ($cip != $ip) {
				$ips[] = $cip;
			}
		}
		
		$this->ipData = $ips; 
		return $this->save();
	}

	/**
	 * Save the current IP allowed stack
	 *
	 * @return bool Returns TRUE if save was successful, FALSE if it was not
	 */
	public function save() {
		if ($this->valid()) {
			return (bool)@file_put_contents($this->ipFile, json_encode($this->ipData));
		}

		return false;
	}

	/**
	 * Determine if an IP is in the allowed stack
	 *
	 * @param string $ip IP to check
	 *
	 * @return bool TRUE if the ip is allowed, FALSE if it is not
	 */
	public function ipAllowed($ip) {
		return in_array($ip, (array)$this->ipData);
	}

	/**
	 * Fetch the stack of all the current IPs
	 *
	 * @return array Stack of admin
	 */
	public function fetchAll() {
		return ($this->valid()) ? $this->ipData : false;
	}
	
	/**
	*
	*/
	public function fetchErrors() {
		return $this->errors;
	}

	/**
	*
	*/
	public function exec(\IPLocker\Command $command) {
		$com = $command->fetchCommand();

        if ($com['action'] == 'toggle') {
            $com['action'] = ($this->ipAllowed($com['params'][0])) ? 'remove' : 'create';
        }

        if ($com['action'] == 'create') {
            return ($this->create($com['params'][0])) ?
                array('Status'  => true,'Message' => "Successfully added ip: {$com['params'][0]}") :
                array('Status'  => false,'Message' => "Unable to add ip: {$com['params'][0]}");
        } else {
            return ($this->delete($com['params'][0])) ?
                array('Status'  => true, 'Message' => "Successfully removed ip: {$com['params'][0]}") :
                array('Status'  => false, 'Message' => "Unable to remove ip: {$com['params'][0]}");
        }

	}

}
