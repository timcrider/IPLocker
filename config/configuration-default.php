<?php
/**
 * Error reporting for debugging
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
 * Admin Overrides
 */
$adminIP    = '';
$adminPhone = '';

/**
 * Load in a simple environment/bootstrap for this example
 */
require_once dirname(__DIR__).'/environment.php';
