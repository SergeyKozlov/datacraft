<?php

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();
//$s3 = new S3();
//$ffmpegConv = new FfmpegConv();
//$fs = new FileSteward();
$sendmail = new sendmail();


//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

//$pgGetItemsNoPreVTT['table'] = $_REQUEST['table'];
//$pgGetItemsNoPreVTT['limit'] = $welcome->setLimit();

$all_data = $pg->pgElaGetItemsNoTags();
echo "\n\rpgElaGetItemsNoTags all_data --------------\n\r";
print_r($all_data);
$tagsArray = json_decode($all_data['tags']);
//$tagsArray = $all_data['tags'];

echo "\n\rpgElaGetItemsNoTags tagsArray --------------\n\r";
print_r($tagsArray);

foreach ($tagsArray as $key => $value) {
    echo "\n\rforeach tags value --------------\n\r";
    print_r($value);

    $itemRes = $pg->pgOneDataByColumn([
        'table' => 'el_items_tags',
        'find_column' => 'el_it_id',
        'find_value' => $all_data['item_id']]);
    //echo "\npgItemCountAdder itemRes\n";
    //print_r($itemRes);
    // wrong if (empty($itemRes['item_count_show'])) {
    if (empty($itemRes['item_id'])) {
        // Ещё нет такого
        $user_items_tags['uit_set_id'] = $welcome->trueRandom();
        $user_items_tags['user_id'] = $all_data['owner_id'];
        $user_items_tags['item_id'] = $all_data['item_id'];
        $user_items_tags['tag'] = $value;
        echo "\n\rpgElaGetItemsNoTags user_items_tags --------------\n\r";
        print_r($user_items_tags);
        $pg->pgAddData($pg->table_users_items_tags_sets, $user_items_tags);

        $Items_tags['it_id'] = $welcome->trueRandom();
        $Items_tags['item_id'] = $all_data['item_id'];
        $Items_tags['tag'] = $value;
        echo "\n\rpgElaGetItemsNoTags Items_tags --------------\n\r";
        print_r($Items_tags);
        $pg->pgAddData($pg->table_items_tags, $Items_tags);

        /*$users_tags['ut_id'] = $welcome->trueRandom();
        $users_tags['user_id'] = $all_data['owner_id'];
        $users_tags['tag'] =$value;
        //$pg->pgAddData($pg->table_users_tags, $users_tags);*/

    } else {
        echo "\n\rempty --------------\n\r";
    }
}

$ela_tags['el_it_id'] = $all_data['item_id'];
echo "\n\rpgElaGetItemsNoTags ela_tags --------------\n\r";
print_r($ela_tags);
$pg->pgAddData('el_items_tags', $ela_tags);
