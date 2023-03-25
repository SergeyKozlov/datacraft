<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 24.12.15
 * Time: 18:43
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$log = new log();

$welcome->staffOnly();
$welcome->outputCBData($log->setEvent(["event" => ["type" => "eventCommon",
    "val" => $_GET['event']]]));