<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 30.03.16
 * Time: 15:54
 */
/*
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/sync/parsesync.php');

$welcome = new NAD();
$article = new Article();
$log = new log();
$ps = new parseSync();*/

//error_reporting(0); // Turn off error reporting
//error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

//$limit = 5;

for ($i = 0; $i < 5; $i++) {
    echo "\n\r <br> Interaton #" . $i . "<br>\n\r";
    //include_once($_SERVER['DOCUMENT_ROOT'] . '/system/elaboration/articleDraft.php');
    //include($_SERVER['DOCUMENT_ROOT'] . '/system/elaboration/articleUserId.php');
    //include($_SERVER['DOCUMENT_ROOT'] . '/system/elaboration/getTrashWithOutCreatedAt.php');
    //include($_SERVER['DOCUMENT_ROOT'] . '/system/elaboration/getTrashUserName.php');
}
