<?php

/**
 * Actually performs an XML_RPC request.
 *
 * PHP versions 4 and 5
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Daniel Convissor <danielc@php.net>
 * @copyright  2005-2010 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License
 * @version    SVN: $Id: actual-request.php 300957 2010-07-02 23:55:00Z danielc $
 * @link       http://pear.php.net/package/XML_RPC
 * @since      File available since Release 1.5.3
 */

/*
 * If the package version number is found in the left hand
 * portion of the if() expression below, that means this file has
 * come from the PEAR installer.  Therefore, let's test the
 * installed version of XML_RPC which should be in the include path.
 *
 * If the version has not been substituted in the if() expression,
 * this file has likely come from a SVN checkout or a .tar file.
 * Therefore, we'll assume the tests should use the version of
 * XML_RPC that has come from there as well.
 */
if ('1.5.5' == '@'.'package_version'.'@') {
    ini_set('include_path', '../'
            . PATH_SEPARATOR . '.' . PATH_SEPARATOR
            . ini_get('include_path')
    );
}
require_once 'XML/RPC/Dump.php';


$debug = 0;

$params = array(
    new XML_RPC_Value('php.net', 'string'),
);
$msg = new XML_RPC_Message('domquery', $params);
$client = new XML_RPC_Client('/api/xmlrpc', 'www.adamsnames.com');
$client->setDebug($debug);

$resp = $client->send($msg);
if (!$resp) {
    echo 'Communication error: ' . $client->errstr;
    exit(1);
}
if ($resp->faultCode()) {
    /*
     * Display problems that have been gracefully cought and
     * reported by the xmlrpc.php script
     */
    echo 'Fault Code: ' . $resp->faultCode() . "\n";
    echo 'Fault Reason: ' . $resp->faultString() . "\n";
    exit(1);
}

$val = $resp->value();
XML_RPC_Dump($val);
