<?php

/**
 * Tests how the XML_RPC server handles a bunch of different parameter
 * data types.
 *
 * PHP versions 4 and 5
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Daniel Convissor <danielc@php.net>
 * @copyright  2005-2010 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License
 * @version    SVN: $Id: types.php 300957 2010-07-02 23:55:00Z danielc $
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
   <param><value>default to string</value></param>
   <param><value><string>inside string</string></value></param>
   <param><value><int>8</int></value></param>
   <param><value><datetime.iso8601>20050809T01:33:44</datetime.iso8601></value></param>

   <param>
    <value>
     <array>
      <data>
       <value>
        <string>a</string>
       </value>
       <value>
        <string>b</string>
       </value>
      </data>
     </array>
    </value>
   </param>

   <param>
    <value>
     <struct>
      <member>
       <name>a</name>
       <value>
        <string>ay</string>
       </value>
      </member>
      <member>
       <name>b</name>
       <value>
        <string>be</string>
       </value>
      </member>
     </struct>
    </value>
   </param>

  </params>
 </methodCall>
EOPOST;

$expect = <<<EOEXP
<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
<params>
<param>
<value><string>param 0: 'default to string'
param 1: 'inside string'
param 2: '8'
param 3: '20050809T01:33:44'
param 4: array (
  0 =&gt; 'a',
  1 =&gt; 'b',
)
param 5: array (
  'a' =&gt; 'ay',
  'b' =&gt; 'be',
)
</string></value>
</param>
</params>
</methodResponse>
EOEXP;

include './allgot.inc';
