<?php
/**
 * Created by IntelliJ IDEA.
 * User: Пользователь2
 * Date: 24.01.2017
 * Time: 0:12
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$log = new log();

$welcome->staffOnly();

$res = $log->getInfo(["limit" => $welcome->setLimit()]);

$welcome->outputCBData($res->rows);