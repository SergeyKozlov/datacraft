<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 18.12.17
 * Time: 23:23
 */


//exit('rest');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
//include_once($_SERVER['PWD'] . '/nad/index.php');

//echo 'path - ' . $_SERVER['PWD'];

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$log = new log();
$log->pgSchedulerWork();
echo "\n\rdeleteOld:\n\r" . $log->deleteOld() . "\n\r";