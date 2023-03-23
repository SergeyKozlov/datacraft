<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 24.06.18
 * Time: 2:00
 */

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$welcome = new NAD();
$pg = new PostgreSQL();

if ($_REQUEST['table'] == 'users') exit;

try {
    $result = pg_query($pg->pgConn, "TRUNCATE " . $_REQUEST['table'] . ";");
    //order by created_at desc");
    //echo "\npgOneDataByColumn \n";
    print_r($result);
} catch (Exception $e) {
    echo 'Pg. ' . $e;
    return false;
    //echo "No file. ";
}
print_r(pg_fetch_all($result));