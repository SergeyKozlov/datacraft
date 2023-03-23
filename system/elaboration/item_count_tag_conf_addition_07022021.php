<?php

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();
//$s3 = new S3();
//$ffmpegConv = new FfmpegConv();
//$fs = new FileSteward();
//$sendmail = new sendmail();


//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

//$pgGetItemsNoPreVTT['table'] = $_REQUEST['table'];
//$pgGetItemsNoPreVTT['limit'] = $welcome->setLimit();
//$pgItemSetStar['user_id'] =  $welcome->subscribersTrue[array_rand($welcome->subscribersTrue)];



$rand_item_id = $pg->pgRandItemWithTag();
echo "\n\rpgRandItemWithTag --------------\n\r";
print_r($rand_item_id);

$rand_tag = $pg->pgRandTagOfItem(['item_id' => $rand_item_id]);
echo "\n\rpgRandTagOfItem --------------\n\r";
print_r($rand_tag);

$from_user_id = $welcome->subscribers[array_rand($welcome->subscribers)];
echo "\n\rfrom_user_id --------------\n\r";
print_r($from_user_id);


//exit;


//=============================================================

//=============================================================
$pgItemSetStar['item_id'] = $rand_item_id;
$pgItemSetStar['tag'] = $rand_tag;
$pgItemSetStar['user_id'] = $from_user_id;
//print_r($pgItemSetLike);
//exit;

if (!empty($pgItemSetStar['item_id']
    and !empty($pgItemSetStar['tag'])
    and !empty($pgItemSetStar['user_id']))) {
    $welcome->outputDDBData(($welcome->pgItemSetTagStaff($pgItemSetStar)));
} else {
    echo 'no param';
    header("HTTP/1.0 404 Not Found");
}
