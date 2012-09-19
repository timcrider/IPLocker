IPLocker
========
Website IP restriction with sms management capabilities.


## Requirements
PHP 5.3+
Active Twilio Account

## Demo Site Setup

### Configuration
Copy config/configuration-default.php to config/configuration.php and change the appropriate values.

```php
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
$adminName  = '';

/**
* Include event listeners
*/
if (file_exists(BASEDIR.'config/events.php')) {
        require_once BASEDIR.'config/events.php';
}
```

### Make the data directory web server writable
The demo site uses data/ip.json and data/admin.json as the example data stores, these will both need to be web writable.

Thanks
======
I would like to thank the following people for helping me get this ready to launch:

Chris Saylor [github](http://github.com/cjsaylor/): Unit testing and event handler

Keith Casey  [github](http://github.com/caseysoftware/): Introduction to Twilio
