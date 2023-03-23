<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 06.03.18
 * Time: 13:55
 */

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

print_r($_REQUEST);

$limit = $welcome->setLimit();
//$keyU = $_GET['key'];

$all_sel = $pg->pgDataAll(['table' => $_GET['table'], 'limit' => $limit]);
print_r($all_sel);

foreach ($all_sel as $key => $value) {
    /*echo "\n\rforeach key ----------------\n\r";
    print_r($all_sel[$key]);
    echo "\n\rforeach value --------------\n\r";
    print_r($value);*/
    echo "\n\rforeach value created_at --------------\n\r";
    print_r($value['created_at']);
   /* echo "\n\rforeach value array_shift --------------\n\r";
    print_r(array_shift($value));
    echo "\n\rforeach value key --------------\n\r";
    print_r(key($value));

    $keys = array_keys($value);
    $firstKey = $keys[0];

    echo "\n\rforeach value firstKey --------------\n\r";
    print_r($firstKey);*/
    $trueDate = strtotime($value['created_at']);
    $contrDate = strtotime('2018-04-00');
    //echo "\n\rforeach trueDate --------------\n\r";
    //print_r($trueDate);
    if ($trueDate < $contrDate) {
        echo " <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<";
        echo "\n\rforeach value table --------------\n\r";
        //echo "\n\r" . $_GET['table'] . $_GET['key'] . $value["$keyU"] . "\n\r";
        echo $pg->pgDelete($_GET['table'], $_GET['key'], $value[$_GET['key']]);
    }

}