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
$pgItemSetStar['user_id'] =  $welcome->subscribersTrue[array_rand($welcome->subscribersTrue)];

echo "\n\rpgItemSetStar user_id --------------\n\r";
print_r($pgItemSetStar['user_id']);


$rand_item = $pg->pgGetItemRandFromUser($pgItemSetStar);
echo "\n\rpgGetItemRandFromUser --------------\n\r";
print_r($rand_item);
$itemCountInfo = $pg->pgOneDataByColumn([
    'table' => $pg->table_items_counts,
    'find_column' => 'count_item_id',
    'find_value' => $rand_item[0]]);

echo "\n\ritemCountInfo --------------\n\r";
print_r($itemCountInfo);

if (!$itemCountInfo) $itemCountInfo['item_count_show'] = 1;

$itemCountInfo['item_count_show'] = $itemCountInfo['item_count_show'] + 1;
echo "\n\ritemCountInfo['item_count_show']  --------------\n\r";
print_r($itemCountInfo['item_count_show'] );

$itemCountInfoUpdate = $pg->pgUpdateDataArray(
    $pg->table_items_counts,
    $itemCountInfo,
    ['count_item_id' => $rand_item[0]]);
echo "\n\ritemCountInfoUpdate --------------\n\r";
print_r($itemCountInfoUpdate);
//exit();
/*foreach ($all_data as $key => $value) {
    echo "\n\rforeach value created_at --------------\n\r";
    print_r(key($value));
    print_r($value['created_at']);
    $trueDate = strtotime($value['created_at']);
    if ($trueDate < strtotime('2018-06-24')) {
        echo " <<<<<<<<<<<<<<<<";
        echo "\n\rforeach value table --------------\n\r";
        //echo "\n\r" . $_GET['table'] . $_GET['key'] . $value["$keyU"] . "\n\r";
        //print_r($pg->pgDelete($_REQUEST['table'], 'count_item_id', $value['count_item_id']));
        //print_r($pg->pgDelete($pg->table_posts, 'post_id', $value['post_id']));
    }
}*/

//if (!empty($all_data[0]['src'])) {