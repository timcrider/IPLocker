<?php
/**
 * Load in a simple environment/bootstrap for this example
 */
require_once dirname(__DIR__).'/environment.php';

/**
 * Setup the IPLocker and associated services
 */
try {
    // Create instances
    $locker = new \IPLocker\Locker;
    $twcli  = new Services_Twilio($twilio['sid'], $twilio['number']);
    $admin  = new \IPLocker\AdminStore\Json(BASEDIR.'data/admin.json', true);
	$iplist = new \IPLocker\IPStore\Json(BASEDIR.'data/ip.json', true);

    // Tie the instances to the ip locker
    $locker->setTwilio($twcli)->setAdmin($admin)->setIP($iplist)->setTwilioSid($twilio['sid']);

    if (!defined('SKIP_AUTH')) {
       	// Authenticate ip
        if (!$locker->authenticateIP(\IPLocker\Helpers::realIP())) {
            // If our admin sees this, add them and next load they won't
            $iplist->create($adminIP);
            $admin->create($adminPhone, $adminName);
            
            // Handle access denied
            define('IPLOCK_AUTH', false);
       	} else {
            define('IPLOCK_AUTH', true);
       	}
    } else {
       	define('IPLOCK_AUTH', true);
    }
} catch (\IPLocker\Exception $e) {
    require_once BASEDIR.'/html/exceptions.php';
    exit;
}
