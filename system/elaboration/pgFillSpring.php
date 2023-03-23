<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 08.07.18
 * Time: 17:22
 */

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
$welcome = new NAD();
$pg = new PostgreSQL();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

//$limit = $welcome->setLimit();
//$keyU = $_GET['key'];

//$all_sel = $pg->pgDataAll(['table' => $_GET['table'], 'limit' => $limit]);
//$all_video = $pg->pgGetNewVideoReverse($limit);

try {
    $result = pg_query($pg->pgConn, "select * from users where spring is null limit 10;");
} catch (Exception $e) {
    echo 'Pg. ' . $e;
    return false;
    //echo "No file. ";
}
//pg_close($this->pgConn);
if ($result) {
    $users = pg_fetch_all($result);
} else {
    $users = false;
}

//echo "\n\rusers --------------\n\r";
//print_r($users);

foreach ($users as $key => $value) {
    //echo "\n\rforeach key ----------------\n\r";
    //print_r($all_sel[$key]);
    echo "\n\rforeach value --------------\n\r";
    print_r($value);
    /*echo "\n\rforeach value created_at --------------\n\r";
    print_r($value['created_at']);
    echo "\n\rforeach value display_user_name --------------\n\r";
    print_r($value['display_user_name']);*/
    /* echo "\n\rforeach value array_shift --------------\n\r";
     print_r(array_shift($value));
     echo "\n\rforeach value key --------------\n\r";
     print_r(key($value));

     $keys = array_keys($value);
     $firstKey = $keys[0];

     echo "\n\rforeach value firstKey --------------\n\r";
     print_r($firstKey);*/
    /*$trueDate = strtotime($value['created_at']);
    $contrDate = strtotime('2018-03-00');
    //echo "\n\rforeach trueDate --------------\n\r";
    //print_r($trueDate);
    if ($trueDate < $contrDate) {
        echo " <<<<<<<<<<<<<<<<";
        echo "\n\rforeach value table --------------\n\r";
        //echo "\n\r" . $_GET['table'] . $_GET['key'] . $value["$keyU"] . "\n\r";
        print_r($pg->pgDelete($pg->table_items, 'item_id', $value['item_id']));
        print_r($pg->pgDelete($pg->table_posts, 'post_id', $value['post_id']));
    }*/
    $itemTemp['spring'] = $welcome->emailToSpring($value);
    $itemNew = array_merge($value, $itemTemp);
    $itemTrue = $pg->pgPaddingItems($itemNew);
    echo "\n\rforeach itemTrue --------------\n\r";

    print_r($itemTrue);

    $res = $pg->pgUpdateDataArray($pg->table_users, $itemTrue, ['user_id' => $value['user_id']]);
}
echo "\n\r--------------\n\r";
