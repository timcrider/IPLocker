<?php

namespace IPLocker;

use InvalidArgumentException,
	\IPLocker\Helpers;

/**
 * IPLocker main class
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
class Locker 
{
	/**
	 * @var string $version Current version of IPLocker
	 */
	protected $version = '0.0.9';

	/**
	 * @var Services_Twilio $twilio Instance of Twilio service api
	 */
	protected $twilio;

	/**
	 * @var string Twilio number the app is currently bound to
	 * @todo look at making this a list to allow for multiple numbers
	 */
	protected $twilioNumber;
	
	/**
	 *
	 */
	protected $twilioSid;

	/**
	 * @var \IPLocker\AdminStore\StorageInterface $admin Instance of an admin storage interface
	 */
	protected $admin;
	 
	/**
	 * @var \IPLocker\IPStore\StorageInterface $ip Instance of an ip storage interface
	 */
	protected $ip;
	
	/**
	 *
	 */
	protected $errors = array();
	
	/**
	 *
	 */
	public function getVersion() {
		return $this->version;
	}
	
	/**
	*
	*/
	public function setTwilioSid($sid) {
		$this->twilioSid = $sid;
	}
	
	/**
	*
	*/
	public function getTwilioSid() {
		return $this->twilioSid;
	}
	
	
	/**
	 * @param \IPLocker\AdminStore\Interface $admin AdminStore object
	 *
	 * @throws InvalidArgumentException
	 */
	public function setAdmin(&$admin) {
		if (!is_a($admin, '\IPLocker\AdminStore\StorageInterface')) {
			throw new InvalidArgumentException("Invalid admin type");
		}

		$this->admin =& $admin;
		
		return $this;
	}

	/**
	 * Fetch the current admin storage handler
	 *
	 * @return \IPLocker\AdminStore\StorageInterface
	 */
	public function getAdmin() {
		return $this->admin;
	}

	/**
	 * @param \IPLocker\IPStore\Interface $ip IPStore object
	 *
	 * @throws InvalidArgumentException
	 */
	public function setIP(&$ip) {
		if (!is_a($ip, '\IPLocker\IPStore\StorageInterface')) {
			throw new InvalidArgumentException("Invalid IP type");
		}

		$this->ip =& $ip;
		
		return $this;
	}

	/**
	 * Fetch the current IP storage handler
	 *
	 * @return \IPLocker\IPStore\StorageInterface
	 */
	public function getIP() {
		return $this->ip;
	}

	/**
	 * @param Services_Twilio $twilio Twilio Service Object
	 *
	 * @throws InvalidArgumentException
	 */
	public function setTwilio(&$twilio) {
		if (!is_a($twilio, 'Services_Twilio')) {
			throw new InvalidArgumentException("Invalid Twilio object");
		}

		$this->twilio =& $twilio;
		
		return $this;
	}

	/**
	 * Fetch the current Twilio handler
	 *
	 * @return Services_Twilio
	 */
	public function getTwilio() {
		return $this->twilio;	
	}
	
	/**
	 *
	 *
	 * @return bool TRUE if ip address is in the list, FALSE if not
	 * @throws \InvalidArgumentException Invalid IP
	 */
	public function authenticateIP($ip) {
		if (!\IPLocker\Helpers::validIPAddress($ip)) {
			$this->errors[] = "Invalid IP";
			return false;
		}
		
		if (!$this->ip) {
			$this->errors[] = "IP store required";
			return false;
		}
		
		return $this->ip->ipAllowed($ip);
	}
	
	/**
	 *
	 */
	 public function exec(\IPLocker\Command $command) {
	 	if (!$command->valid()) {
	 		return false;
	 	}
	 
	 	$type = $command->fetchCommand('type');
	 	
	 	if (!empty($this->$type) && is_callable(array($this->$type, 'exec'))) {
	 		return $this->$type->exec($command);
	 	} else {
	 		return false;
	 	}
	}	 
	/**
	 *
	 */
	 public function respond($response) {
	 	// format and send response
	 	$out = '<?xml version="1.0" encoding="UTF-8" ?>';
	 	$out .= "\n<Response><Sms>".htmlentities($response)."</Sms></Response>";
	 	return $out;
	 }
	 
	/**
	 *
	 */
	 public function service($data) {
	 	if (!is_array($data) || empty($data['Body'])) {
	 		return $this->respond("Invalid Request");
	 	}
	 	
	 	if ((empty($data['AccountSid']) && !empty($this->twilioSid)) || $data['AccountSid'] != $this->twilioSid) {
			return $this->respond("Unable to authenticate Twilio service");
	 	}
	 	
	 	if (empty($data['From']) || !$this->admin->isAdmin($data['From'])) {
	 		return $this->respond("Request Rejected");
	 	}
	 	
	 	$response = $this->exec(new \IPLocker\Command($data['Body']));
	 	
	 	if (!empty($response['Status'])) {
			return $this->respond($response['Message']);
	 	} else {
	 		return $this->respond("Fail");
	 	}
	 }

	/**
	*
	*/
	public function fetchErrors() {
		return $this->errors;
	}
	
}
