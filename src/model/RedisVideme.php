<?php

namespace VideMe\Datacraft\model;
//namespace VideMe\Datacraft;

//use model;

//use RedisVideme;
//use model;
use Predis;
//use Predis\Client;
use Dotenv;

/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 12.11.17
 * Time: 1:22
 */

//include_once($_SERVER['DOCUMENT_ROOT'] . '/sendmail/sendmail.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/REDIS_URL.php');

class RedisVideme
{
    public function redisConnect()
    {
        //include $_SERVER['DOCUMENT_ROOT'] . '/nad/model/REDIS_URL.php';
        $dotenv = Dotenv\Dotenv::createImmutable('/tmp/');
        $dotenv->load();

        $host = $_ENV['redis_url'];
        $redis = new Predis\Client($_ENV['redis_url']); //16012023
        /*$redis = new Predis\Client(array(
            "scheme" => "tcp",
            "host" => "redis-node-1",
            "port" => 6379,
            "password" => "Pilsner1",
            "persistent" => "1"));*/ // 26012023
        //include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/REDIS_URL.php');
        //print_r($redis_url);
        //$redis = new Predis\Client("$redis_url");
        //error_reporting(0); // Turn off error reporting
        //error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors
        //$redis = new Predis\Client($redis_url); // TODO: not work login
        //$redis = new Predis\Client($redis_url); // work


        //print_r($redis);

        /*} catch (Exception $e) {
            //header('HTTP/1.1 500 Internal Server Error', true, 500);
            //echo $e->getMessage();

            echo "\n\r======================================================\n\r";
            echo "\n\rRedis redisConnect Predis\Client error: " . $e . "\n\r";
            echo "\n\r======================================================\n\r";
            /!*$sendmail = new sendmail();
            $sendmail->SendStaffAlert(['message' => "Redis redisConnect Predis\Client error: " . $e]);

            $log = new log();
            $log->toFile(['service' => 'redis', 'type' => 'error', 'text' => $e]);*!/
            exit;
        }*/
        return $redis;
        //return $redis_url;
    }

    public function redisRepair()
    {
        /*$redis_url = file_get_contents('http://vide.herokuapp.com/system/test/redis_var.php');

        $file_reids_url = $_SERVER['DOCUMENT_ROOT'] . '/nad/model/REDIS_URL.php';
        // Открываем файл для получения существующего содержимого
        $current = file_get_contents($file_reids_url);
        //echo $current;
        // Добавляем нового человека в файл
        //$current .= "\n\r" . '$redis_url = "' . $redis_url . '"; //' . date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
        $current .= "\n\r" . '$redis_url = "' . $redis_url . '"; //' . date("D M j G:i:s T Y");
        // Пишем содержимое обратно в файл
        file_put_contents($file_reids_url, $current);*/
    }

}