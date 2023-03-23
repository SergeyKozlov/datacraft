<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 24.12.15
 * Time: 18:42
 */

/*
"type":
    "info":
    "error":
    "PEBKAC":
    "success":
    "warning":
    "IDS":
    "attempt":

"message":
    "empty":
    "busy":
    "set":
    "false":
    "forgery":
    "sendmail":
    "checkdone":
    "upcdn":
    "API":
    "OA":
    "free":
    "login":
    "change":
    "cookie":
    "ffmpeg":
    "couchbase":
    "cmtotwitter":
    "cmtofacebook":

"file":

"class":

"funct":

-=- automate -=-

"request":

"ip":


http://api.vide.me/system/log/getevent/

*/

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/DynamoDB.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/FileSteward.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/FfmpegConv.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/FB.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/sendmail/sendmail.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/ContentFilter.php');

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

ini_set("memory_limit", "200M");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

class log
{
    public array $staffMessage;

    /**
     * @param array $staffMessage
     */
    public function setStaffMessage($staffMessage2): void
    {
        $time_now = new DateTime("now");
        $this->staffMessage[] = $time_now->format('Y-m-d\TH:i:s.u') . ';' . $staffMessage2;
    }

    /**
     * @return array
     */
    public function getStaffMessage(): array
    {
        return $this->staffMessage;
    }

    public function __construct()
    {
        //$this->welcome = new NAD(); //<b>Fatal error</b>:  Out of memory (allocated 130023424) (tried to allocate 8192 bytes) in <b>/usr/share/nginx/html/nad/index.php</b> on line <b>167</b><br />
        //$pg = new PostgreSQL();
        //$welcome = new NAD();
        //$this->bucketSync = $welcome->autoConnectToBucket(["bucket" => "logs"]);
    }

    public
    function toFile($toFile)
    {
        $welcome = new NAD();

        file_put_contents($welcome->nadlogs . date("Ymd") . '_' . $toFile['service'] . '_' . $toFile['type'] . '.txt', date("Ymd H:i:s") . ' : ' . $toFile['text'] . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

}