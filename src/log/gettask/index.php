<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 30.11.16
 * Time: 18:51
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$log = new log();

$welcome->staffOnly();

$res = $log->getTask(["limit" => $welcome->setLimit()]);

//$welcome->OutputParseData($res['rows']);
$outputConv = $welcome->ConvParseData($res->rows);
if (!empty($_GET['videmecallback'])) {
    echo $_GET['videmecallback'] . "(" . json_encode($outputConv) . ")";
} else {
    echo json_encode($outputConv);
}