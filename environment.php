<?php
error_reporting(E_ALL);	
ini_set('display_errors','On');

/**
 * Setup our environment
 */
define('BASEDIR', __DIR__.'/');
define('PATHSEP', (!defined('PHP_OS') || !preg_match('/^WIN/', PHP_OS)) ? ':' : ';');

$pathing = array(
	BASEDIR.'src/',
	BASEDIR.'vendor/Twilio/',
	BASEDIR.'vendor/PEAR/',
);

ini_set('include_path', implode(PATHSEP, $pathing));

/**
 * Autoloader courtesy of PHP-FIG
 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 */
function autoload($className) {
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strripos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	require $fileName;
}

spl_autoload_register('autoload');

if (file_exists(BASEDIR.'config/configuration.php')) {
	require_once BASEDIR.'config/configuration.php';
} else {
	print "Unable to load configuration: ".BASEDIR.'config/configuration.php';
	exit;
}
