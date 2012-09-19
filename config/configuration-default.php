<?php
/**
 * Error reporting for debugging
 */

/**
* Debugging information
*/
error_reporting(E_ALL);
ini_set('display_errors', 'On');

/**
 * Configure twilio
 */
$twilio = array(
    'sid'    => '',
    'token'  => '',
    'number' => ''
);

/**
 * Default Admin and IP Access
 */
$adminIP    = '';
$adminPhone = '';

/**
* Include event listeners
*/
if (file_exists(BASEDIR.'config/events.php')) {
	require_once BASEDIR.'config/events.php';
}
