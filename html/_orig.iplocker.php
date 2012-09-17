<?php
require_once __DIR__.'/config.php';

$mockRequest = array (
    'AccountSid'    => $twilio['sid'],
    'Body'          => '+ admin 5551231234 Test User',
    'ToZip'         => '21204',
    'FromState'     => 'MD',
    'ToCity'        => 'TOWSON',
    'SmsSid'        => 'SM6d42d71929279d1bd7d8108d2e1b2f72',
    'ToState'       => 'MD',
    'To'            => $twilio['number'],
    'ToCountry'     => 'US',
    'FromCountry'   => 'US',
    'SmsMessageSid' => 'SM6d42d71929279d1bd7d8108d2e1b2f72',
    'ApiVersion'    => '2010-04-01',
    'FromCity'      => 'BALTIMORE',
    'SmsStatus'     => 'received',
    'From'          => '+14432243205',
    'FromZip'       => '21401'
);

// @todo remove debug stuff
$_POST = $mockRequest;
@file_put_contents(BASEDIR.'html/out.txt', var_export($_POST, true));
\IPLocker\Event::trigger('log', "-- Starting IPLock RCV ({$_SERVER['REMOTE_ADDR']}) --");

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

