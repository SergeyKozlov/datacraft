<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 22.06.16
 * Time: 13:02
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$article = new Article();
$log = new log();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$bucket = $welcome->autoConnectToBucket(["bucket" => "user"]);

echo "<br>\n\r removeCBElementUser <br>\n\r";

$res = $bucket->get($_REQUEST["document"]);

$doc = $welcome->ConvParseData($res->value);

unset($doc[$_REQUEST["element"]]);

// Перезапись документа новыми строками, ID сохраняется
//$cbPaddingDocument = $this->paddingCBData($cbRebildDocument);
//$bucket = $this->autoConnectToBucket(["bucket" => $cbBucket["bucket"]]);

$resRemove = $bucket->replace($doc[$welcome->userEmail], $doc);

print_r($resRemove);