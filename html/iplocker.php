<?php
require_once __DIR__.'/config.php';
try {
	$res = $locker->service($_POST);
	if ($res['Status']) {
		header('Content-type: text/xml');
		print $res['Response'];
	}
} catch (Exception $e) {
	header('Content-type: text/xml');
	print "Error: ".$e->getMessage();
}

