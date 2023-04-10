<?php

namespace VideMe\Datacraft;
//require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

use VideMe\Datacraft\log\log;
//$tm = new log();
//exit(' root nad start exit ');
use VideMe\Datacraft\model\GeoIP;
use VideMe\Datacraft\model\PG_demo_insight;
use VideMe\Datacraft\model\PostgreSQL;
use VideMe\Datacraft\model\RedisVideme;
//use VideMe\Datacraft\RedisVideme;

//include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/src/model/PG_demo_insight.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/src/model/PG_demo_el.php');


class NAD
{
    public function __construct(/*log $log*/)
    {
        $this->nadtemp = "/usr/share/nginx/nadtemp/";
        $this->nadlogs = "/usr/share/nginx/nadlogs/";
    }
    public function outputDDBData($outputCBData)
    {
        //$start = microtime(true);
        // https://stackoverflow.com/questions/477816/what-is-the-correct-json-content-type
        //header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        if (!empty($outputCBData)) {
            if (!empty($_GET['videmecallback'])) {
                header('Content-Type: application/javascript');
                echo $_GET['videmecallback'] . "(" . json_encode($outputCBData) . ")";
            } else {

                //$time_elapsed_secs = microtime(true) - $start;
                //header("videme-output-time-elapsed-secs: " . $time_elapsed_secs);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($outputCBData);
            }
            return true;

        } else {
            /* Именно так для js нужно чтобы он показал что чего-то нет, иначе будет строить пустой список.
             * ([]) будет пустой список */
            if (!empty($_GET['videmecallback'])) {
                echo $_GET['videmecallback'] . "()";
            } else {
                echo '()';
            }
            return false;
        }
    }

    public function timeShift($date, $vel = 12) // Work, not used? // used 08062022
    {
        $datetime = new \DateTime($date);
        //echo "\n\ttimeShift datetime: " . $datetime;
        //$timeShift= date("Y-m-d H:i:s.uO", strtotime("+1 month", $date));
        //echo "\n\ttimeShift timeShift: " . $timeShift;
        if ($vel > 0) {
            //echo "\n\ttimeShift vel > 0: " . $vel;
            $datetime->modify('+' . $vel . ' day');
        } else {
            //echo "\n\ttimeShift vel < 0: " . $vel;
            //$datetime->modify('-' . $vel . ' day');
            $datetime->modify($vel . ' day');
        }
        return $datetime->format('Y-m-d H:i:s.uO');
    }

    /**
     * @param int $trueRandom
     * @return string
     */
    public function trueRandom($trueRandom = 6)
    {
        // return 6 * 2
        $bytes = openssl_random_pseudo_bytes($trueRandom, $cstrong);
        $hex = bin2hex($bytes);
        return $hex;
    }
    public function rand_date_between($min_date, $max_date) {
        /* Gets 2 dates as string, earlier and later date.
           Returns date in between them.
        https://stackoverflow.com/questions/14186800/random-time-and-date-between-2-date-values
        */
        $min_epoch = strtotime($min_date);
        $max_epoch = strtotime($max_date);
        $rand_epoch = rand($min_epoch, $max_epoch);
        //return date('Y-m-d H:i:s', $rand_epoch);
        //return date('Y-m-d H:i:sO', $rand_epoch);
        return date('Y-m-d H:i:s.uO', $rand_epoch);
    }

    public function pgShowChartByItem1stDays($pgShowChartByItem1stDays)
    {
        //$pg = new PostgreSQL();
        $pgAmazon = new PG_demo_el(); // <----------------------------------------------
        $pgInsight = new PG_demo_insight(); // <----------------------------------------------
        //$userCookie = $this->GetUserCookieValue();
        //==$itemInfo = $pgAmazon->pgOneDataByColumn([
        $itemInfo = $pgAmazon->pgOneDataByColumn([
            'table' => $pgAmazon->table_items,
            'find_column' => 'item_id',
            'find_value' => $pgShowChartByItem1stDays['item_id']]);
        //echo "\n\tpgShowChartByItem1stDays item created_at: " . $itemInfo['created_at'];
        $pgShowChartByItem1stDays['chart_time_at'] = $itemInfo['created_at'];

        $pgShowChartByItem1stDays['where'] = '';

        if (!empty($pgShowChartByItem1stDays['state'])) {
            $pgShowChartByItem1stDays['where'] = "and items_views.state = '" . $pgShowChartByItem1stDays['state'] . "'";
        }

        if (!empty($pgShowChartByItem1stDays['w_start']))
            $pgShowChartByItem1stDays['d_start'] = $pgShowChartByItem1stDays['w_start'] * 7;

        if (!empty($pgShowChartByItem1stDays['w_stop']))
            $pgShowChartByItem1stDays['d_stop'] = $pgShowChartByItem1stDays['w_stop'] * 7;

        if (!empty($pgShowChartByItem1stDays['m_start']))
            $pgShowChartByItem1stDays['d_start'] = $pgShowChartByItem1stDays['m_start'] * 30;

        if (!empty($pgShowChartByItem1stDays['m_stop']))
            $pgShowChartByItem1stDays['d_stop'] = $pgShowChartByItem1stDays['m_stop'] * 30;

        if (!empty($pgShowChartByItem1stDays['item_id'])) {
            //echo "\n\tpgShowChartByItem1stDays pgShowChartByItem1stDays: ";
            //print_r($pgShowChartByItem1stDays);
            if (!empty($pgShowChartByItem1stDays['d_stop'])) {
                //echo "\n\tpgShowChartByItem1stDays d_stop exist";
                if (empty($pgShowChartByItem1stDays['d_start']) and ($pgShowChartByItem1stDays['d_stop'] > 0)) {
                    $pgShowChartByItem1stDays['d_start'] = 0;
                }
                if (empty($pgShowChartByItem1stDays['d_start']) and ($pgShowChartByItem1stDays['d_stop'] < 0)) {
                    //$pgShowChartByItem1stDays['d_start'] = $pgShowChartByItem1stDays['d_stop'] - 1;
                    $pgShowChartByItem1stDays['d_start'] = $pgShowChartByItem1stDays['d_stop'];
                    $pgShowChartByItem1stDays['d_stop'] = 0;
                    $pgShowChartByItem1stDays['chart_time_at'] = $this->getTimeForPG_tz();
                }
                if ($pgShowChartByItem1stDays['d_stop'] > 0 and $pgShowChartByItem1stDays['d_start'] > 0) {
                    //echo "\n\tpgShowChartByItem1stDays d_stop > 0: " . $pgShowChartByItem1stDays['d_stop'];
                    if (empty($pgShowChartByItem1stDays['d_start']) or $pgShowChartByItem1stDays['d_stop'] < 0) $pgShowChartByItem1stDays['d_start'] = 0;
                    //if ($pgShowChartByItem1stDays['d_stop'] < 0) $pgShowChartByItem1stDays['d_stop'] = -1 * abs($pgShowChartByItem1stDays['d_stop']);
                    if ($pgShowChartByItem1stDays['d_start'] > $pgShowChartByItem1stDays['d_stop']) {
                        if ($pgShowChartByItem1stDays['d_stop'] > 1) {
                            //echo "\n\tpgShowChartByItem1stDays d_stop > 1\n\t";
                            $pgShowChartByItem1stDays['d_start'] = $pgShowChartByItem1stDays['d_stop'] - 1;
                        } else {
                            //$pgShowChartByItem1stDays['d_start'] = $pgShowChartByItem1stDays['d_stop'];
                            return false;
                        }
                    }
                    //$pgShowChartByItem1stDays['time_start'] = $this->timeShift($itemInfo['created_at'], $pgShowChartByItem1stDays['d_start']);
                    //$pgShowChartByItem1stDays['time_stop'] = $this->timeShift($itemInfo['created_at'], $pgShowChartByItem1stDays['d_stop']);
                } elseif (($pgShowChartByItem1stDays['d_start'] > 0 and $pgShowChartByItem1stDays['d_stop'] < 0) or ($pgShowChartByItem1stDays['d_start'] < 0 and $pgShowChartByItem1stDays['d_stop'] > 0)) {
                    //echo "\n\t---> pgShowChartByItem1stDays d_stop > 0 and d_start < 0: CONFUSE 1 return false " . $pgShowChartByItem1stDays['d_stop'];
                    return false;
                }
            } elseif (empty($pgShowChartByItem1stDays['d_start'])) {
                //echo "\n\t---> pgShowChartByItem1stDays EMPTY d_stop and d_start: CONFUSE 1.1 return false";
                return false;
            }
            if (!empty($pgShowChartByItem1stDays['d_start'])) {
                //echo "\n\tpgShowChartByItem1stDays d_start exist";
                if (empty($pgShowChartByItem1stDays['d_stop']) and ($pgShowChartByItem1stDays['d_start'] < 0)) {
                    $pgShowChartByItem1stDays['d_stop'] = 0;
                }
                if (empty($pgShowChartByItem1stDays['d_stop']) and ($pgShowChartByItem1stDays['d_start'] > 0)) {
                    $pgShowChartByItem1stDays['d_stop'] = $pgShowChartByItem1stDays['d_start'] + 1;
                }
                if ($pgShowChartByItem1stDays['d_start'] < 0 and $pgShowChartByItem1stDays['d_stop'] <= 0) {
                    //echo "\n\tpgShowChartByItem1stDays d_start < 0: " . $pgShowChartByItem1stDays['d_start'];
                    //if (empty($pgShowChartByItem1stDays['d_stop']) or $pgShowChartByItem1stDays['d_stop'] > 0) $pgShowChartByItem1stDays['d_stop'] = 0;
                    //if ($pgShowChartByItem1stDays['d_stop'] < 0) $pgShowChartByItem1stDays['d_stop'] = -1 * abs($pgShowChartByItem1stDays['d_stop']);
                    if ($pgShowChartByItem1stDays['d_start'] > $pgShowChartByItem1stDays['d_stop']) {
                        if ($pgShowChartByItem1stDays['d_start'] < -1) {
                            //echo "\n\tpgShowChartByItem1stDays d_start < -1\n\t";
                            $pgShowChartByItem1stDays['d_stop'] = $pgShowChartByItem1stDays['d_start'] + 1;
                        } else {
                            //echo "\n\t---> pgShowChartByItem1stDays d_start > -1 return false\n\t";
                            return false;
                        }
                    }
                    $pgShowChartByItem1stDays['chart_time_at'] = $this->getTimeForPG_tz();

                } elseif (($pgShowChartByItem1stDays['d_start'] > 0 and $pgShowChartByItem1stDays['d_stop'] < 0) or ($pgShowChartByItem1stDays['d_start'] < 0 and $pgShowChartByItem1stDays['d_stop'] > 0)) {
                    //echo "\n\t---> pgShowChartByItem1stDays d_start < 0 and d_stop > 0: CONFUSE 2 return false " . $pgShowChartByItem1stDays['d_stop'];
                    return false;
                }
            } elseif (empty($pgShowChartByItem1stDays['d_stop'])) {
                //echo "\n\t---> pgShowChartByItem1stDays EMPTY d_start and d_stop: CONFUSE 2.1 return false";
                return false;
            }
            $pgShowChartByItem1stDays['start_date'] = $this->timeShift($pgShowChartByItem1stDays['chart_time_at'], $pgShowChartByItem1stDays['d_start']);
            $pgShowChartByItem1stDays['stop_date'] = $this->timeShift($pgShowChartByItem1stDays['chart_time_at'], $pgShowChartByItem1stDays['d_stop']);
            //$pgShowChartByItem1stDays['start_date'] = $itemInfo['created_at'];
            //$pgShowChartByItem1stDays['stop_date'] = $this->timeShift($itemInfo['created_at'], $pgShowChartByItem1stDays['days']);
            //echo "\n\tpgShowChartByItem1stDays pgShowChartByItem1stDays: ";
            //
            //print_r($pgShowChartByItem1stDays);
            //echo "\n\tpgShowChartByItem1stDays pgShowChartByItem1stDays['start_date']: " . $pgShowChartByItem1stDays['start_date'];
            //echo "\n\tpgShowChartByItem1stDays pgShowChartByItem1stDays['stop_date']: " . $pgShowChartByItem1stDays['stop_date'];
            return $pgInsight->pgGetChartByItem1stDaysNOA($pgShowChartByItem1stDays);
        } else {
            return false;
        }
    }


    public function getTimeForPG_tz()
    {
        // http://php.net/manual/ru/function.date-default-timezone-set.php
        $trueTimeObj = new \DateTime();
        $trueTime = $trueTimeObj->format('Y-m-d H:i:s.uO');
        //$trueTime = substr($trueTime,0, -2);
        return $trueTime;
    }

    public function pgChartGetPopState($pgShowChartByItem1stDays)
    {
        //$pg = new PostgreSQL();
        $pg = new PG_demo_insight(); // <----------------------------------------------
        if (!empty($pgShowChartByItem1stDays['item_id'])) {
            return $pg->pgGetChartPopStates($pgShowChartByItem1stDays);
        } else {
            return false;
        }
    }

    public function setLimit()
    {
        if (!empty($_REQUEST['limit'])) {
            return $_REQUEST['limit'];
        } else {
            return 16;
        }
    }

    public function memcachedSetKey($memcachedSetKey)
    {
        //echo "\r\n<hr>memcachedSetKey<br>";
        //print_r($memcachedSetKey);
        /*$bucket = $this->autoConnectToBucket(["bucket" => "tickets"]);
        $bucket->upsert($memcachedSetKey["key"], $memcachedSetKey["value"], ["expiry" => 60 * 60 * 24 * 14]);
        $getmc = new GetMemcached();
        $mc = $getmc->getMemcached();*/
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        try {
            //$mc->set($memcachedSetKey["key"], $memcachedSetKey["value"], 60 * 60 * 24 * 14);
            $redis->set($memcachedSetKey["key"], $memcachedSetKey["value"]);
            $redis->expire($memcachedSetKey["key"], 60 * 60 * 24 * 14);
            return true;
        } catch (Exception $e) {
            //echo "Not found. " . $e->getMessage();
            $log = new log();
            $log->toFile(['service' => 'memcachedSetKey', 'type' => 'error', 'text' => 'memcachedSetKey error: key ' . $memcachedSetKey["key"] . ' value ' . $memcachedSetKey["value"]]);
            return false;
        }
    }
    public function CookieToUserId()
    {
        // redis-cli -h pub-redis-14102.us-east-1-4.1.ec2.garantiadata.com -p 14102 -a 2IIg4aHASXmDpTai
        /*try {
            $redis = new Predis\Client(array(
                'scheme' => 'tcp',
                'host' => 'pub-redis-14102.us-east-1-4.1.ec2.garantiadata.com',
                'port' => 14102,
                'password' => '2IIg4aHASXmDpTai'
            ));
            $userId = $redis->get($UserCookie);
            return $userId;
        } catch (Exception $e) {
            //echo "Not found. "; //. $e->getMessage();
            return false;
        }
        */
        $userCookie = $this->GetUserCookieValue();
        //echo "\n\r---> CookieToUserId:\n\r";
        //print_r($userCookie);
        //$this->setUserId('ertert');

        if (!empty($userCookie)) {
            /*$this->log->setEvent([
                "type" => "attempt",
                "message" => "set",
                "val" => $CookieToUserId,
                "file" => $_SERVER["PHP_SELF"],
                "class" => __CLASS__,
                "funct" => __FUNCTION__
            ]);*/
            $userId = $this->memcachedGetKey(["key" => $userCookie]);
            //echo "\n\r---> CookieToUserId userId\n\r";
            //print_r($userId);
            if (!empty($userId)) {
               // $this->checkUserBlocked($userId);
                //$this->setUserId($userId);
                return $userId;
            } else {
                /*$this->log->setEvent([
                    "type" => "PEBKAC",
                    "message" => "empty",
                    "val" => $CookieToUserId,
                    "file" => $_SERVER["PHP_SELF"],
                    "class" => __CLASS__,
                    "funct" => __FUNCTION__
                ]);*/
                return false;
            }
        } else {
            return false;
        }
    }
    public function GetUserCookieValue()
    {
        $HTTPHeaders = apache_request_headers();
        if (!empty($_POST['usertoken'])) {
            $userId = $_POST['usertoken'];
        } elseif (!empty($HTTPHeaders['X-Videme-User-Token'])) {
            $userId = $HTTPHeaders['X-Videme-User-Token'];
        } elseif (!empty($_COOKIE["vide_nad"])) {
            $userId = $_COOKIE["vide_nad"];
        } elseif (!empty($_POST["nad"])) {
            $userId = $_POST["nad"];
        } elseif (!empty($_GET["nad"])) {
            $userId = $_GET["nad"];
        }
        //echo "GetUserId userId " . $userId . " <--";
        if (!empty($userId)) {
            return $userId;
        }
    }
    public function memcachedGetKey($memcachedGetKey)
    {
        /*try {
            $bucketTickets = $this->autoConnectToBucket(["bucket" => "tickets"]);
            $res = $bucketTickets->get($memcachedGetKey["key"]);
            //unset ($bucketTickets);
            //echo " memcachedGetKey res ";
            //print_r($res);
            return $this->ConvParseData($res->value);
        } catch (Exception $e) {
            //echo "Not found. " . $e->getMessage();
            return false;
        }
        $getmc = new GetMemcached();
        $mc = $getmc->getMemcached();*/
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        try {
            //$res = $mc->get($memcachedGetKey["key"]);
            $res = $redis->get($memcachedGetKey["key"]);
            //echo " memcachedGetKey res ";
            //print_r($res);
            return $res;
        } catch (Exception $e) {
            //echo "Not found. " . $e->getMessage();
            /* Не включать виснут web + studio7
             * $sendmail = new sendmail();
            $sendmail->SendStaffAlert(['message' => "Trends getEvent zRangeByScore error: " . $e]);
            $log = new log();
            $log->toFile(['service' => 'redis', 'type' => 'error', 'text' => 'Trends getEvent zRangeByScore error']);
            $getRredis->redisRepair();*/
            return false;
        }
    }
    public function getHashClientIp()
    {
        /*if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $IPclient = $_SERVER['HTTP_X_FORWARDED_FOR'];

        }*/

        // Если проходит через наш Nginx
        //return preg_replace('#, 83.169.208.155#', '', $IPclient);
        //==return bin2hex(sip_hash("aON1dHrq90SbG8Hx", preg_replace('#, 83.169.208.155#', '', $_SERVER['HTTP_X_FORWARDED_FOR'])));
        //return bin2hex(sip_hash("aON1dHrq90SbG8Hx", $IPclient)); //<-- Need sip_hash
        //return bin2hex($IPclient);
        return md5($this->getClientIp());
        //$ticketid = bin2hex(sip_hash('aON1dHrq90SbG8Hx', $IPclient));
    }
    public function memcachedWebStart($memcachedWebStart)
    {
        /*$this->log->setEvent([
            "type" => "info",
            "message" => "set",
            "val" => $memcachedWebStart["key"],
            "file" => $_SERVER["PHP_SELF"],
            "class" => __CLASS__,
            "funct" => __FUNCTION__
        ]);*/
        //echo "memcachedWebStart:<br>\n\r";
        //print_r($memcachedWebStart);
        /*$bucket = $this->autoConnectToBucket(["bucket" => "tickets"]);
        $bucket->upsert($memcachedWebStart["key"], "1", ["expiry" => 60 * 15]);
        $getmc = new GetMemcached();
        $mc = $getmc->getMemcached();
        $mc->set($memcachedWebStart["key"], "1", 60 * 15);*/
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        $redis->set($memcachedWebStart["key"], "1");
        $redis->expire($memcachedWebStart["key"], 60 * 15);

    }
    public function memcachedOnPublish($memcachedOnPublish)
    {
        $log = new log();
        /*$log->setEvent([
            "type" => "info",
            "message" => $this->set,
            "val" => $memcachedOnPublish["key"],
            //"val" => "ticket: " . $_REQUEST["ticket"] . " ticketId: " . $_REQUEST["ticketId"] . " name: " . $_REQUEST["name"],
            "file" => $_SERVER["PHP_SELF"],
            "class" => __CLASS__,
            "funct" => __FUNCTION__
        ]);*/
        try {
            $key = $this->memcachedGetKey(["key" => $memcachedOnPublish["key"]]);
            if (!empty($key)) {
                /*$this->log->setEvent([
                    "type" => "attempt",
                    "message" => "set",
                    "val" => $memcachedOnPublish["key"],
                    "file" => $_SERVER["PHP_SELF"],
                    "class" => __CLASS__,
                    "funct" => __FUNCTION__
                ]);*/
                return true;
            } else {
                /*$this->log->setEvent([
                    "type" => "attempt",
                    "message" => "empty",
                    //"val" => $memcachedOnPublish["key"],
                    "val" => $key,
                    "file" => $_SERVER["PHP_SELF"],
                    "class" => __CLASS__,
                    "funct" => __FUNCTION__
                ]);*/
                return false;
                //return true;
            }
        } catch (Exception $e) {
            /*$this->log->setEvent([
                "type" => "error",
                "message" => "set",
                "val" => $memcachedOnPublish["key"],
                "file" => $_SERVER["PHP_SELF"],
                "class" => __CLASS__,
                "funct" => __FUNCTION__
            ]);*/
            //echo "Not found. "; //. $e->getMessage();
            //exit(1);
            return false;
        }
    }
    public function memcachedRecorddone($memcachedRecorddone)
    {
        /*$this->log->setEvent([
            "type" => "info",
            "message" => "set",
            "val" => $memcachedRecorddone["key"],
            //"val" => "ticket: " . $_REQUEST["ticket"] . " ticketId: " . $_REQUEST["ticketId"] . " name: " . $_REQUEST["name"],
            "file" => $_SERVER["PHP_SELF"],
            "class" => __CLASS__,
            "funct" => __FUNCTION__
        ]);*/
        //echo "memcachedWebStart:<br>\n\r";
        //print_r($memcachedWebStart);
        /*$bucket = $this->autoConnectToBucket(["bucket" => "tickets"]);
        return $bucket->upsert($memcachedRecorddone["key"], $memcachedRecorddone["value"], ["expiry" => 60 * 10]);
        $getmc = new GetMemcached();
        $mc = $getmc->getMemcached();
        return $mc->set($memcachedRecorddone["key"], $memcachedRecorddone["value"], 60 * 10);*/
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        $redis->set($memcachedRecorddone["key"], $memcachedRecorddone["value"]);
        $redis->expire($memcachedRecorddone["key"], 60 * 10);
        return true;
    }

    public function uploadSetParam($uploadSetParam)
    {
        //echo "\n\ruploadSetParam\n\r";
        //print_r($uploadSetParam);
        $uploadDo['owner_id'] = $this->CookieToUserId();
        if (!empty($uploadSetParam['title'])) {
            $uploadDo['title'] = $uploadSetParam['title'];
        } else {
            $uploadDo['title'] = '';
        }
        if (!empty($uploadSetParam['content'])) {
            $uploadDo['content'] = $uploadSetParam['content'];
        } else {
            $uploadDo['content'] = '';
        }
        if (!empty($uploadSetParam['album_id'])) {
            $uploadDo['album_id'] = $uploadSetParam["album_id"];
        } else {
            $uploadDo['album_id'] = '';
        }
        if (!empty($uploadSetParam['ticket_id'])) $uploadDo['ticket_id'] = $uploadSetParam["ticket_id"]; //??????????????????
//if (!empty($_POST['ticket'])) $retVal['ticket'] = $_POST["ticket"];
        $uploadDo['task_id'] = $this->memcachedGetKey(['key' => $uploadSetParam['ticket_id']]);
        $uploadDo['ticket'] = $this->memcachedGetKey(['key' => $uploadSetParam['ticket_id']]); // TODO: why?
//$retVal['access'] = $_POST['access'] ?? 'private';
        /* desabled because no web button if ($uploadDo['album_id'] == 'public') {
            $uploadDo['access'] = 'public';
        } elseif ($uploadDo['album_id'] == 'friends') {
            $uploadDo['access'] = 'friends';
        } elseif ($uploadDo['album_id'] == 'private') {
            $uploadDo['access'] = 'private';
        } elseif (!empty($uploadDo['album_id'])) {
            $albumInfo = $this->pgAlbumInfoById($uploadDo);
            echo 'albumInfo';
            print_r($albumInfo);
            $uploadDo['access'] = $albumInfo['access'];
        }*/
        $uploadDo['access'] = 'public'; // because no web button

        if (!empty($_POST['upload_type'])) {
            $uploadDo['upload_type'] = $uploadSetParam['upload_type'];
        }
        return $uploadDo;
    }
    public function getClientIp()
    {
        //$IPclient = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // Если проходит через наш Nginx
        //return preg_replace('#, 83.169.208.155#', '', $IPclient);
        //return preg_replace('#, 83.169.208.155#', '', $_SERVER['HTTP_X_FORWARDED_FOR']);
        //==return $_SERVER['HTTP_X_FORWARDED_FOR'];

        $ipAddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipAddress = 'UNKNOWN';
        return $ipAddress;
    }


}