<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 20.04.16
 * Time: 14:45
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$article = new Article();
$log = new log();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$bucket = $welcome->autoConnectToBucket(["bucket" => "article"]);
$query = CouchbaseViewQuery::from("article", "getTrashWithOutCreatedAt")->order(CouchbaseViewQuery::ORDER_DESCENDING)->stale(CouchbaseViewQuery::UPDATE_BEFORE);
try {
    //return $bucket->query($query);
    //$res = $bucketArticle->query($query);
    $res = $welcome->SharePreParseData($bucket->query($query));

    //return $res["rows"];
} catch (Exception $e) {
    echo  $e;
}
//print_r($res);
echo "<br>\n\r All getTrashWithOutCreatedAt : " . count($res["rows"]) . " ";
if (count($res["rows"]) < 1) exit ("All done!");

// ======================================================================
$oldDocId = $res["rows"][0]["id"];
echo "<br>\n\r getTrashWithOutCreatedAt oldDocId - ". $oldDocId . "<br>\n\r";
$res = $bucket->get($oldDocId);
$newDoc = $welcome->ConvParseData($res->value);
print_r($newDoc);
echo "<br>\n\r All getTrashWithOutCreatedAt newDoc: <br>\n\r";
//$newDoc[$welcome->createdAt] = DateTime::createFromFormat()
$newDoc[$welcome->createdAt] = strtotime($newDoc["date"] . " " . $newDoc["time"]);

//======================================================================================================================
$newDoc = array_slice($newDoc, 0, 0, true) + // Need position - 1
    [
        $welcome->type => $newDoc[$welcome->type]
                /*,
                $this->welcome->updatedAt => time(),
                "article" => $this->seoUrl($this->safetySubstr($newShareVideo["article"]["title"]))*/
    ] +
    array_slice($newDoc, 0, count($newDoc) - 1, true); // Need position - 2
//======================================================================================================================
// Human time (GMT): Tue, 01 Jan 2008 00:00:00 GMT
if (strtotime($newDoc["date"] . " " . $newDoc["time"]) > 1199145600) {
    $createdAt = time();
} else {
    $createdAt = strtotime($newDoc["date"] . " " . $newDoc["time"]);
}

$newDoc = array_slice($newDoc, 0, 2, true) + // Need position - 1
    [
        $welcome->createdAt => $createdAt
                /*,
                $this->welcome->updatedAt => time(),
                "article" => $this->seoUrl($this->safetySubstr($newShareVideo["article"]["title"]))*/
    ] +
    array_slice($newDoc, 1, count($newDoc) - 1, true); // Need position - 2
//======================================================================================================================//======================================================================================================================
$newDoc = array_slice($newDoc, 0, 3, true) + // Need position - 1
    [
        $welcome->article => $newDoc[$welcome->article]
                /*,
                $this->welcome->updatedAt => time(),
                "article" => $this->seoUrl($this->safetySubstr($newShareVideo["article"]["title"]))*/
    ] +
    array_slice($newDoc, 2, count($newDoc) - 1, true); // Need position - 2
//======================================================================================================================
if (strlen($newDoc[$welcome->docId]) > 12) {
    $newDoc[$welcome->docId] = $welcome->trueRandom();
}

print_r($newDoc);
// ======================================================================
// ======================================================================


// ======================================================================
/*$articleOld = $welcome->ConvParseData($res->value);
echo "<br>\n\r " . $articleOld["type"] . "<br>\n\r";
$articleOld["type"] = $welcome->articleDraft;
echo "<br>\n\rarticleOld: <br>\n\r";
print_r($articleOld);
exit;*/
// ======================================================================
echo "<br>\n\r upsert: <br>\n\r";
$resUpsert = $bucketArticle->replace($oldDocId, $newDoc);
print_r($resUpsert);
// ======================================================================