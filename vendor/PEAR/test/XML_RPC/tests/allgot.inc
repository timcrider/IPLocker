<?php

/**
 * Parses the "return" value from some of our test scripts.
 *
 * PHP versions 4 and 5
 *
 * @category   Web Services
 * @package    XML_RPC
 * @author     Daniel Convissor <danielc@php.net>
 * @copyright  2005-2010 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License
 * @version    SVN: $Id: allgot.inc 293223 2010-01-07 15:32:19Z danielc $
 * @link       http://pear.php.net/package/XML_RPC
 * @since      File available since Release 1.4.4
 */

ob_start();

function returnAllGot($params) {
    $out = '';
    $count = count($params->params);
    for ($i = 0; $i < $count; $i++) {
        $param = $params->getParam($i);
        if (!XML_RPC_Value::isValue($param)) {
            $out .= "parameter $i was error: $param\n";
            continue;
        }
        $got = XML_RPC_Decode($param);
        $out .= "param $i: " . var_export($got, true) . "\n";
    }
    $val = new XML_RPC_Value($out, 'string');
    return new XML_RPC_Response($val);
}

$server = new XML_RPC_Server(
    array(
        'allgot' => array(
            'function' => 'returnAllGot',
        ),
    )
);

$got = ob_get_clean();

if ($got == $expect) {
    echo "passed\n";
} else {
    echo "FAILED\n";
    echo "Expected:\n$expect\n";
    echo "Got:\n$got\n";
}
