<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 24.06.18
 * Time: 1:45
 */

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$pgGetAllReverse['table'] = $_REQUEST['table'];
$pgGetAllReverse['limit'] = $welcome->setLimit();

$all_data = $pg->pgGetAllReverse($pgGetAllReverse);
echo "\n\rpgGetAllReverse --------------\n\r";
print_r($all_data);

foreach ($all_data as $key => $value) {
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
}
echo "\n\r--------------\n\r";