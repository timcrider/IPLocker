<?php

/**
 * Tests how the XML_RPC server handles a parameter with an empty struct without
 * any spaces in the XML after the empty value.
 *
 * PHP versions 4 and 5
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Daniel Convissor <danielc@php.net>
 * @copyright  2005-2010 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License
 * @version    SVN: $Id: empty-value-struct.php 300957 2010-07-02 23:55:00Z danielc $
 * @link       http://pear.php.net/package/XML_RPC
 * @since      File available since Release 1.4.4
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
require_once 'XML/RPC/Server.php';


$GLOBALS['HTTP_RAW_POST_DATA'] = <<<EOPOST
<?xml version="1.0"?>
<methodCall>
 <methodName>allgot</methodName>
  <params>
   <param>
    <value>
     <struct>
      <member>
      <name>fld1</name><value></value></member></struct></value>
   </param>
  </params>
 </methodCall>
EOPOST;

$expect = <<<EOEXP
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
<params>
<param>
<value><string>param 0: array (
  'fld1' =&gt; '',
)
</string></value>
</param>
</params>
</methodResponse>
EOEXP;

include './allgot.inc';
