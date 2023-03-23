<?php

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PostgreSQL.php');

class PG_demo_el extends PostgreSQL
{
    public function pgConnect()
    {
        $dotenv = Dotenv\Dotenv::createImmutable('/tmp/');
        $dotenv->load();

        $host = $_ENV['pg_host'];
        $port = $_ENV['pg_port'];
        $username = $_ENV['pg_username'];
        $password = $_ENV['pg_password'];
        $database = $_ENV['pg_database_el'];
        try {
            $conn = pg_pconnect("host=$host port=$port dbname=$database user=$username password=$password") or die("No base connect");
            return $conn;
        } catch (Exception $e) {
            echo 'No DB. ' . $e;
            return false;
        }
    }
}