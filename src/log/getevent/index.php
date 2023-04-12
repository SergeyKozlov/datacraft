<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 24.12.15
 * Time: 21:24
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$log = new log();

$welcome->staffOnly();

$res = $log->getEvent(["limit" => $welcome->setLimit()]);

//$welcome->OutputParseData($res['rows']);
$welcome->outputCBData($res->rows);