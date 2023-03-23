<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 11.05.16
 * Time: 23:27
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
$query = CouchbaseViewQuery::from("article", "getTrashUserName")->order(CouchbaseViewQuery::ORDER_DESCENDING)->stale(CouchbaseViewQuery::UPDATE_BEFORE);
try {
    //return $bucket->query($query);
    //$res = $bucketArticle->query($query);
    $res = $welcome->SharePreParseData($bucket->query($query));

    //return $res["rows"];
} catch (Exception $e) {
    echo  $e;
}
//print_r($res);
echo "<br>\n\r All getTrashUserName : " . count($res["rows"]) . " ";
if (count($res["rows"]) < 1) exit ("All done!");

// ======================================================================
$oldDocId = $res["rows"][0]["id"];
echo "<br>\n\r getTrashUserName oldDocId - ". $oldDocId . "<br>\n\r";
$res = $bucketArticle->get($oldDocId);
$newDoc = $welcome->ConvParseData($res->value);
print_r($newDoc);
echo "<br>\n\r All getTrashUserName newDoc: <br>\n\r";
//$newDoc[$welcome->createdAt] = DateTime::createFromFormat()
$newDoc[$welcome->userDisplayName] = $newDoc["userName"];
unset($newDoc["userName"]);

$PositionUserDisplayName = (array_search($welcome->userDisplayName, array_keys($newDoc))); // get key current position

//======================================================================================================================
$newDoc = array_slice($newDoc, 0, 9, true) + // Need position - 1
    [
        $welcome->userDisplayName => $newDoc[$welcome->userDisplayName]
        /*,
        $this->welcome->updatedAt => time(),
        "article" => $this->seoUrl($this->safetySubstr($newShareVideo["article"]["title"]))*/
    ] +
    array_slice($newDoc, 8, count($newDoc) - 1, true); // Need position - 1 - 1
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