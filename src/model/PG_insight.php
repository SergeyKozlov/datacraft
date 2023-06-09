<?php
namespace VideMe\Datacraft\model;

//=============================use model\PostgreSQL;
use VideMe\Datacraft\tm;

use VideMe\Datacraft\nad;
//use VideMe\Datacraft;
$tm = new TM();

$welcome = new NAD();
//use VideMe\Datacraft\model\PostgreSQL;
use model;

use Dotenv;

class PG_insight extends PostgreSQL
{
    public function pgConnect()
    {
        $dotenv = Dotenv\Dotenv::createImmutable('/tmp/');
        $dotenv->load();

        $host = $_ENV['pg_host'];
        $port = $_ENV['pg_port'];
        $username = $_ENV['pg_username'];
        $password = $_ENV['pg_password'];
        $database = $_ENV['pg_database_insight'];
        try {
            $conn = pg_pconnect("host=$host port=$port dbname=$database user=$username password=$password") or die("No base connect");
            return $conn;
        } catch (Exception $e) {
            echo 'No DB. ' . $e;
            return false;
        }
    }
}