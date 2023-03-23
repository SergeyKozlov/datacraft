<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 19.04.16
 * Time: 7:52
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$article = new Article();
$log = new log();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$limit = 1000;


$bucket = $welcome->autoConnectToBucket(["bucket" => "article"]);
$query = CouchbaseViewQuery::from("article", "getTrash")->limit($limit)->order(CouchbaseViewQuery::ORDER_DESCENDING)->stale(CouchbaseViewQuery::UPDATE_BEFORE);
try {
    //return $bucket->query($query);
    //$res = $bucketArticle->query($query);
    $res = $welcome->SharePreParseData($bucket->query($query));

    //return $res["rows"];
} catch (Exception $e) {
    echo  $e;
}
//print_r($res);
// ======================================================================
echo "<br>\n\r All Trash : " . count($res["rows"]) . " ";
if (count($res["rows"]) < 1) exit ("All done!");
$oldDocId = $res["rows"][0]["id"];
echo "<br>\n\r - ". $oldDocId . "<br>\n\r";
$res = $bucketArticle->get($oldDocId);
print_r($res);
// ======================================================================
/*$articleOld = $welcome->ConvParseData($res->value);
echo "<br>\n\r " . $articleOld["type"] . "<br>\n\r";
$articleOld["type"] = $welcome->articleDraft;
echo "<br>\n\rarticleOld: <br>\n\r";
print_r($articleOld);
exit;*/
// ======================================================================
echo "<br>\n\rremove: <br>\n\r";
$resRemove = $bucketArticle->remove($oldDocId);
print_r($resRemove);
// ======================================================================