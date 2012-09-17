<?php

namespace IPLocker;

use InvalidArgumentException;

/**
 * IPLocker command functionality
 *
 * @author Timothy M. Crider <timcrider@gmail.com>
 */
class Command
{
	/**
	 * @var string $rawCommand Uncompiled command string
	 */
	protected $rawCommand;

	/**
	 * @var bool $validCommand Is the processed command valid
	 */
	protected $validCommand;

	/**
	 * @var string $action Action to apply to the type (add, remove, toggle)
	 */
	protected $action;

	/**
	 * @var string $type Type of data to apply the action to (ip or phone)
	 */
	protected $type;

	/**
	 * @var array $parameters Command parameter stack
	 */
	protected $parameters = array();

	/**
	 * @var array $errors Error stack
	 */
	protected $errors = array();

	/**
	 * Command constructor
	 *
	 * @param string $command Command string
	 */
	public function __construct($command) {
		$this->rawCommand = trim($command);
		$this->parseCommand($command);
	}

	/**
	 * Parse a command string into its components
	 *
	 * @return bool TRUE if command parsed correctly, FALSE if it did not
	 */
	protected function parseCommand($command) {
		$command = trim($command);

		if (preg_match('/ /', $command)) {
			// @todo Do the lewp for double '  ' spaces
			$comStack   = preg_split('/ /', $command);
			$stackCount = count($comStack);
			
			if ($stackCount <= 2) {
				$this->errors[] = "Command stack requires 3 or more pieces";
				return false;
			}
			
			// Determine action
			$action = array_shift($comStack);
			switch (strtolower($action)) {
				case '+':
				case 'add':
					$this->action = 'create';
					break;

				case '-':
				case 'rm':
				case 'rem':
				case 'remove':
					$this->action = 'remove';
					break;
			}
			
			// Determine type
			$type = array_shift($comStack);
			switch (strtolower($type)) {
				case '#':
				case 'admin':
				case 'adm':
					$this->type = 'admin';
					
					// Process the rest of the stack
					$phone = array_shift($comStack);
					
					if (!$phone = \IPLocker\Helpers::formatNumber($phone)) {
						$this->errors[] = "Invalid phone number";
						return false;
					}

					if ($this->action == 'create' && empty($comStack)) {
						$this->errors[] = "Phone numbers must have a name associated with it";
						return false;
					}
					
					if (count($comStack) > 1) {
						$name = implode(' ', $comStack);
					} else {
						$name = array_shift($comStack);
					}
					
					$this->parameters[] = $phone;
					$this->parameters[] = $name;
					$this->validCommand = true;
					return true;

				case 'ip':
					$this->type = 'ip';
					
					// Process the rest of the stack
					$ip = array_shift($comStack);

					if (\IPLocker\Helpers::validIPAddress($ip)) {
						$this->parameters[] = $ip;
						$this->validCommand = true;
						return true;
					} else {
						$this->errors[] = "Invalid ip address '{$ip}'";
						return false;
					}
					break;

				default:
					$this->errors[] = "Unknown command type '{$type}'";
					return false;
			}
		} else {
			// Check for toggle
			if (\IPLocker\Helpers::validIPAddress($command)) {
				$this->action       = 'toggle';
				$this->type         = 'ip';
				$this->parameters[] = $command;
				$this->validCommand = true;
				return true;
			} else {
				$this->errors[] = "Invalid toggle for {$command}";
				return false;
			}
		}
	}

	/**
	 * Fetch the stack of command errors
	 *
	 * @return array Stack of command errors
	 */
	public function fetchErrors() {
		return $this->errors;
	}

	/**
	 * Determine if the command is valid or not
	 *
	 * @return bool TRUE if this command is valid, FALSE if the command is not
	 */
	public function valid() {
		return (bool)$this->validCommand;
	}

	/**
	 * Fetch the parsed command structure
	 *
	 * @return array Structured command
	 */
	public function fetchCommand($part=NULL) {
		$out = array(
			'valid'  => $this->valid(),
			'raw'    => $this->rawCommand,
			'action' => $this->action,
			'type'   => $this->type,
			'params' => $this->parameters
		);
		
		if ($part) {
			if (isset($out[$part])) {
				return $out[$part];
			} else {
				$this->errors[] = "Invalid command part '{$part}'";
				return false;
			}
		} else {
			return $out;
		}
		
	}
}
