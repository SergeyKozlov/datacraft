<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 01.04.16
 * Time: 15:37
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/sync/parsesync.php');

$welcome = new NAD();
$article = new Article();
$log = new log();
$ps = new parseSync();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$limit = 1;

$skip = $ps->getSkip(["type" => "articleSkip-article"]);
/*
echo " === skip: ---> ";
print_r($skip);
echo " === limit: ---> ";
print_r($limit);
echo " === sum: ---> " . ((int)$skip + (int)$limit);*/


$ps->setSkip(["type" => "articleSkip-article",
    "val" => (int)$skip + (int)$limit]);

$bucket = $welcome->autoConnectToBucket(["bucket" => "article"]);
$query = CouchbaseViewQuery::from("article_by_date", "article_by_date")->skip($skip)->limit($limit)->order(CouchbaseViewQuery::ORDER_DESCENDING)->stale(CouchbaseViewQuery::UPDATE_BEFORE);
try {
    //return $bucket->query($query);
    //$res = $bucketArticle->query($query);
    $res = $welcome->SharePreParseData($bucket->query($query));

    //return $res["rows"];
} catch (Exception $e) {
    echo  $e;
}
print_r($res);
// ======================================================================
$oldDocId = $res["rows"][0]["id"];
echo "<br>\n\r oldDocId - ". $oldDocId . "<br>\n\r";
$res = $bucketArticle->get($oldDocId);
print_r($res);
// ======================================================================
$articleOld = $welcome->ConvParseData($res->value);
echo "<br>\n\r old userId: " . $articleOld[$welcome->userId] . "<br>\n\r";
$articleOld[$welcome->userId] = $ps->getConformity(["type" => "conformity" . "-" . $articleOld[$welcome->userId]]);
unset($articleOld["userName"]);
$userInfo = $welcome->cbUserInfo($articleOld[$welcome->userId]);
$articleOld = array_slice($articleOld, 0, 9, true) +
    [
        $welcome->userDisplayName => $userInfo[$welcome->userDisplayName]
    ] +
    array_slice($articleOld, 8, count($articleOld) - 1, true);

$articleOld[$welcome->userDisplayName] = $userInfo[$welcome->userDisplayName];
if (empty($articleOld[$welcome->userId])) exit ("No user conformity");
echo "<br>\n\r article NEW: <br>\n\r";
print_r($articleOld);
// ======================================================================
echo "<br>\n\r copy: <br>\n\r";
$bucketArticle->replace($articleOld["date"] . "/" . $articleOld["article"], $articleOld);
