<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 01.12.16
 * Time: 7:59
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$log = new log();

$welcome->staffOnly();

$welcome->OutputParseData($log->setSchedulerMode(["type" => "modeScheduler",
    "val" => $_GET['mode']]));