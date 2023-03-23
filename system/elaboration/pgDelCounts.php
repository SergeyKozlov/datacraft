<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 09.03.18
 * Time: 9:17
 */

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$limit = $welcome->setLimit();

$all_video = $pg->pgGetNewVideoCountsReverse($limit);
echo "\n\rpgShowNewVideo --------------\n\r";
print_r($all_video);

foreach ($all_video as $key => $value) {
    echo "\n\rforeach value created_at --------------\n\r";
    print_r($value['created_at']);
    $trueDate = strtotime($value['created_at']);
    if ($trueDate < strtotime('2018-06-24')) {
        echo " <<<<<<<<<<<<<<<<";
        echo "\n\rforeach value table --------------\n\r";
        //echo "\n\r" . $_GET['table'] . $_GET['key'] . $value["$keyU"] . "\n\r";
        print_r($pg->pgDelete($pg->table_items_counts, 'count_item_id', $value['count_item_id']));
        //print_r($pg->pgDelete($pg->table_posts, 'post_id', $value['post_id']));
    }
}
echo "\n\r--------------\n\r";