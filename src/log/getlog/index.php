<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 24.12.15
 * Time: 21:24
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$welcome = new NAD();
$log = new log();

$welcome->staffOnly();

if (isset($_REQUEST["message"])) {
    $message = $_REQUEST["message"];
} else {
    $message = '';
}

$res = $log->getEvent([
    "limit" => $welcome->setLimit(),
    "message" => $message
]);

//$welcome->OutputParseData($res['rows']);
$welcome->outputCBData($res->rows);