<?php
/**
*
*/
define('SKIP_AUTH', true);
require_once __DIR__.'/config.php';

try {
	header('Content-type: text/xml');
	print $locker->service($_POST);

} catch (Exception $e) {
	header('Content-type: text/xml');
	print "Error: ".$e->getMessage();
}

