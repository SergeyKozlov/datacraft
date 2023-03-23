<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 30.03.16
 * Time: 8:34
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$article = new Article();
$log = new log();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$limit = 1;


$bucket = $welcome->autoConnectToBucket(["bucket" => "articleDraft"]);
$query = CouchbaseViewQuery::from("articleDraft", "articleDraft")->limit($limit)->order(CouchbaseViewQuery::ORDER_DESCENDING)->stale(CouchbaseViewQuery::UPDATE_BEFORE);
try {
    //return $bucket->query($query);
    //$res = $bucketArticleDraft->query($query);
    $res = $welcome->SharePreParseData($bucket->query($query));

    //return $res["rows"];
} catch (Exception $e) {
    echo  $e;
}
//print_r($res);
// ======================================================================
$oldDocId = $res["rows"][0]["id"];
echo "<br>\n\r #" . $i . " - ". $oldDocId . "<br>\n\r";
$res = $bucketArticleDraft->get($oldDocId);
print_r($res);
// ======================================================================
$articleOld = $welcome->ConvParseData($res->value);
echo "<br>\n\r " . $articleOld["type"] . "<br>\n\r";
$articleOld["type"] = $welcome->articleDraft;
echo "<br>\n\rarticleOld: <br>\n\r";
print_r($articleOld);
// ======================================================================
echo "<br>\n\rremove: <br>\n\r";
$resRemove = $bucketArticleDraft->remove($oldDocId);
print_r($resRemove);
// ======================================================================
echo "<br>\n\rcopy: <br>\n\r";
echo $article->ArticleNew($articleOld);