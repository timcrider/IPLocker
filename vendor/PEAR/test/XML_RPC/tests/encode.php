<?php

/**
 * Tests encoding values.
 *
 * PHP versions 4 and 5
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Daniel Convissor <danielc@php.net>
 * @copyright  2005-2010 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License
 * @version    SVN: $Id: extra-lines.php 293218 2010-01-07 14:20:08Z danielc $
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
require_once 'XML/RPC.php';


$input = array(10, 11, 12);

$expect = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<methodCall>
<methodName>nada</methodName>
<params>
<param>
<value><array>
<data>
<value><int>10</int></value>
<value><int>11</int></value>
<value><int>12</int></value>
</data>
</array></value>
</param>
</params>
</methodCall>
EOT;

$expect = trim(preg_replace("/\r\n/", "\n", $expect));

$msg = new XML_RPC_Message('nada', array(XML_RPC_encode($input)));
$msg->createPayload();
$actual = trim(preg_replace("/\r\n/", "\n", $msg->payload));
if ($actual == $expect) {
    echo "passed\n";
} else {
    echo "PROBLEM\n";
    echo $actual;
}

$msg = new XML_RPC_Message('nada',
    array(
        new XML_RPC_Value(
            array(
                new XML_RPC_Value(10, 'int'),
                new XML_RPC_Value(11, 'int'),
                new XML_RPC_Value(12, 'int'),
            ),
            'array'
        )
    )
);
$msg->createPayload();
$actual = trim(preg_replace("/\r\n/", "\n", $msg->payload));
if ($actual == $expect) {
    echo "passed\n";
} else {
    echo "PROBLEM\n";
    echo $actual;
}
