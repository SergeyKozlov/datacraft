<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 24.01.19
 * Time: 16:24
 */

use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sendmail/sendmail.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/ContentFilter.php');


class Trends
{
    public function setEvent($setEvent)
    {
        error_reporting(0); // Turn off error reporting
        //error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors
        //exit();

        $welcome = new NAD();
        //$cf = new ContentFilter();
        //if ($cf->forItem($setEvent['item_id'])) exit('don`t say.');

        $itemInfo = $welcome->pgItemFullInfo($setEvent['item_id']);
        //print_r($itemInfo);
        if ($itemInfo['access'] <> 'public') exit;
        if (empty($itemInfo['item_id'])) exit;
        if (empty($itemInfo['spring'])) exit;
        if (empty($itemInfo['title'])) exit;
        if ($itemInfo['type'] == 'videoEmail') exit;
        if ($itemInfo['type'] == 'article') exit;

        //$setEvent['item_id'] = $_REQUEST['item'];
        $setEvent['event_type'] = 'item_view';
        //$setEvent['item_id'] = $itemInfo['item_id'];
        $setEvent['cover'] = $itemInfo['cover'];
        $setEvent['title'] = $itemInfo['title'];
        $setEvent['user_display_name'] = $itemInfo['user_display_name'];
        $setEvent['user_id'] = $itemInfo['owner_id'];
        $setEvent['spring'] = $itemInfo['spring'];
        $setEvent['user_picture'] = $itemInfo['user_picture'];
        //$setEvent['item_user_picture'] = $itemInfo['item_user_picture'];
        //$setEvent['type_item'] = $itemInfo['type'];
        $setEvent['type'] = $itemInfo['type'];
        $setEvent['video_duration'] = $itemInfo['video_duration'];
        $setEvent['created_at'] = $itemInfo['created_at'];
        $setEvent['item_count_show'] = $itemInfo['item_count_show'];
        //$setEvent['likes_count'] = $itemInfo['likes_count'];
        $setEvent['stars_count'] = $itemInfo['stars_count'];
        $setEvent['reposts_count'] = $itemInfo['reposts_count'];
        $setEvent['pre_v_w320'] = $itemInfo['pre_v_w320'];
        $setEvent['pre_i_w320'] = $itemInfo['pre_i_w320'];

        $getRredis = new RedisVideme();


        $redis = $getRredis->redisConnect();
        try {
            //$res = $mc->get($memcachedGetKey["key"]);
            //==$res = $redis->get($memcachedGetKey["key"]);
            //echo " memcachedGetKey res ";
            //print_r($res);
            //return $res;
            //$set = 'test_z_1';
            $set = 'trends1';
            $keySet = time();
            //$rand = rand(50, 100);
            //$rand = 'no rand';
            //$valArray = ['test' . $rand => $rand];
            //$valJson = "'" . json_encode($valArray) . "'";
            $setEvent['time'] = $keySet;
            $valJson = json_encode($setEvent);
            //echo 'set: ' . $set . ' keySet: ' . $keySet . ' valJson: ' . $valJson;
            $redis->zadd($set, $keySet, $valJson); //<-----------------------------------------------------------------
            //==$redis->zadd($set, $keySet, 3);
            //==$redis->zadd($set, 1614432, 16141614);
            //$redis->zadd($key, $keySet, [ "onesd" => 1, "unwero" => 1, "twdgfdfo" => 2, "thrwersaaee" => 3 ]);
            //$redis->zadd($set, $keySet, $valArray);
            //$redis->zadd($set, $keySet, $valArray);
            //$redis->zadd($key, $keySet, $rand);
            //$redis->expire($key, 10);
            //$values = $redis->hmget($key, array("field:1", "field:2"));
//print_r($values);
            //$redis->zAdd('myzset', 2, 'two', 3, 'three');

// finally
//$data = $redis->hgetall($key);
//print_r($data);
            if (!empty($itemInfo['tags'])) {
                $this->setTagsTrend($itemInfo, $redis);
            } else {
                //echo 'no tags';
                //print_r($itemInfo);

            }

        } catch (Exception $e) {
            echo "Not found. " . $e->getMessage();
            //return false;
        }
    }

    public function setEventForTags($setEventForTags)
    {
        //echo "\nsetEventForTags setEventForTags\n";
        //print_r($setEventForTags);
        //error_reporting(0); // Turn off error reporting
        error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors
        //exit();

        $welcome = new NAD();
        //$cf = new ContentFilter();
        $pg = new PostgreSQL();

        //if ($cf->forItem($setEventForTags['item_id'])) exit('don`t say.');

        $itemInfo = $welcome->pgItemFullInfo($setEventForTags['item_id']);
        //$itemTags = $welcome->pgItemTagsAccess($setEventForTags);
        //echo "\nsetEventForTags itemInfo\n";
        //print_r($itemInfo);
        if ($itemInfo['access'] <> 'public') exit;
        if (empty($itemInfo['item_id'])) exit;
        if (empty($itemInfo['spring'])) exit;
        if (empty($itemInfo['title'])) exit;
        if ($itemInfo['type'] == 'videoEmail') exit;
        if ($itemInfo['type'] == 'article') exit;

        //foreach ($itemTags as $key => $value) {
            $setEvent['it_id'] = $welcome->trueRandom();
            $setEvent['item_id'] = $setEventForTags['item_id'];
            $pg->pgAddData($pg->table_items_trands, $setEvent);
        //}
    }

    public function setTagsTrend($setTagsTrend, $redis)
    {
        if (!empty($setTagsTrend['tags'])) {
            //$getRredis = new Redis();
            //$redis = $getRredis->redisConnect();

            //$keySet = time() . '_tag';
            $keySet = time();
            /*echo "\n\r setTagsTrend keySet " . $keySet;
            echo "\n\r strtotime " . strtotime("+1 week");
            echo "\n\r tags ";
            print_r($setTagsTrend['tags']);*/
            $tags = json_decode($setTagsTrend['tags']);

            //foreach ($setTagsTrend['tags'] as $key => $value) {
            /*foreach ($tags as $key => $value) {
                //echo "\n\r tag " . $value;

                $redis->set($keySet, $value);
                //$redis->expireat($keySet, strtotime("+1 week"));
                //$redis->expire($keySet, 60 * 60 * 24 * 7);

            }*/
            $i = 0;
            //$factor = 1;
            //$minimum_limit = 4;
            /*do {
                if ($i < 5) {
                    echo "i еще недостаточно велико";
                    break;
                }
                $i *= $factor;
                if ($i < $minimum_limit) {
                    break;
                }
                echo "значение i уже подходит";
                echo $i;
                $i++;
                /!* обработка i *!/

            } while (0);*/
            while (list(, $val) = each($tags)) {

                /*if ($val == 'stop') {
                    //break;    /!* You could also write 'break 1;' here. *!/
                }*/
                if ($i > 9) break;
                //echo $keySet . "$i -- $val ----- <br />\n";
                //$redis->set($keySet . $i, $val);
                //$redis->expireat($keySet, strtotime("+1 week"));
                //$redis->expire($keySet . $i, 60 * 60 * 24 * 7);
/* ***************************************************************************** */
                try {
                    $redis->zadd('trends_tags', $keySet . $i, $val); //<-----------------------------------------------------------------
                } catch (Exception $e) {
                    echo "Not found. " . $e->getMessage();
                    //return false;
                }
/* ***************************************************************************** */
                $i++;

            }
        }
    }

    public function getEvent($days = 7)
    {
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        try {
            //$res = $mc->get($memcachedGetKey["key"]);
            //==$res = $redis->get($memcachedGetKey["key"]);
            //echo " memcachedGetKey res ";
            //print_r($res);
            //return $res;
            //$set = 'test_z_1';
            $set = 'trends1';
            //$redis->hmset($key, array("field:1" => "value:1", "field:2" => "value:2"));
            //$redis->expire($key, 3600);
            //==$values = $redis->hmget($key, array("field:1", "field:2"));
            //$values = $redis->hmget($key, array("field:1"));
            /*$values = $redis->zrange($set, 0, 7);
            echo "zrange: </br>";
            var_dump($values);
            echo "</br>";*/
            $keySetStart = time() - 60 * 60 * 24 * $days;
            $keySetStop = time();
            //echo "zRangeByScore: set: " . $set . " keySetStart: " . $keySetStart . " keySetStop: " . $keySetStop . "</br>";


            $values = $redis->zRangeByScore($set, $keySetStart, $keySetStop);
            $res = $this->redisResToArray($values);
            /*echo "zRangeByScore: </br>";
            var_dump($values);
            echo "</br>";*/
            /*echo "redisResToArray: \n\r";
            var_dump($res);
            echo "\n\r";*/

            /*$welcome = new NAD();
            echo "ConvParseData: </br>";
            var_dump($welcome->ConvParseData($values));
            echo "</br>";*/
            //$values2 = $redis->ZRANGEBYSCORE($key);
            //var_dump($values2);


// finally
//$data = $redis->hgetall($key);
//print_r($data);
            return $res;

        } catch (Exception $e) {
            //echo "Not found. " . $e->getMessage();
            //echo "\n\r======================================================\n\r";
            //echo "\n\rTrends getEvent zRangeByScore error: " . $e . "\n\r";
            //echo "\n\r======================================================\n\r";
            $sendmail = new sendmail();
            $sendmail->SendStaffAlert(['message' => "Trends getEvent zRangeByScore error: " . $e]);

            $log = new log();
            $log->toFile(['service' => 'redis', 'type' => 'error', 'text' => 'Trends getEvent zRangeByScore error']);

            $getRredis->redisRepair();

            return false;
        }
    }

    public function getTagsRange()
    {
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        try {
            //$res = $mc->get($memcachedGetKey["key"]);
            //==$res = $redis->get($memcachedGetKey["key"]);
            //echo " memcachedGetKey res ";
            //print_r($res);
            //return $res;
            //$set = 'test_z_1';
            $set = 'trends1';
            //$redis->hmset($key, array("field:1" => "value:1", "field:2" => "value:2"));
            //$redis->expire($key, 3600);
            //==$values = $redis->hmget($key, array("field:1", "field:2"));
            //$values = $redis->hmget($key, array("field:1"));
            /*$values = $redis->zrange($set, 0, 7);
            echo "zrange: </br>";
            var_dump($values);
            echo "</br>";*/
            $keySetStart = time() - 60 * 60 * 24 * 7;
            $keySetStop = time();
            //echo "zRangeByScore: set: " . $set . " keySetStart: " . $keySetStart . " keySetStop: " . $keySetStop . "</br>";


            //$values = $redis->zRangeByScore('trends_tags', $keySetStart . 0, $keySetStop . 9);
            return $redis->zRangeByScore('trends_tags', $keySetStart . 0, $keySetStop . 9);
            //print_r($values);
            //$res = $this->redisResToArray($values);
            /*echo "zRangeByScore: </br>";
            var_dump($values);
            echo "</br>";*/
            /*echo "redisResToArray: \n\r";
            var_dump($res);
            echo "\n\r";*/

            /*$welcome = new NAD();
            echo "ConvParseData: </br>";
            var_dump($welcome->ConvParseData($values));
            echo "</br>";*/
            //$values2 = $redis->ZRANGEBYSCORE($key);
            //var_dump($values2);


// finally
//$data = $redis->hgetall($key);
//print_r($data);
            //return $res;

        } catch (Exception $e) {
            //echo "Not found. " . $e->getMessage();
            //echo "\n\r======================================================\n\r";
            //echo "\n\rTrends getEvent zRangeByScore error: " . $e . "\n\r";
            //echo "\n\r======================================================\n\r";
            $sendmail = new sendmail();
            $sendmail->SendStaffAlert(['message' => "Trends getEvent zRangeByScore error: " . $e]);

            $log = new log();
            $log->toFile(['service' => 'redis', 'type' => 'error', 'text' => 'Trends getEvent zRangeByScore error']);

            $getRredis->redisRepair();

            return false;
        }
    }

    public function redisResToArray($redisResToArray)
    {
        $welcome = new NAD();
        foreach ($redisResToArray as $key => $value) {
            $temp = json_decode($value);
            $par = $welcome->ConvParseData($temp);
            //$ret[$key] = json_decode($value);
            //$ret[$key] = $par;
            $ret[] = $par;
            //$ret[] = $temp;
        }
        return $ret;
        //return $par;
    }

    public function conversionArrayItemsData($conversionArrayItemsData)
    {

        /*$out = array();
        foreach($conversionArrayData as $el){
            $key = serialize($el);
            if (!isset($out[$key]))
                $out[$key]=1;
            else
                $out[$key]++;
        }
        return $out;*/

        /*$counts = array(); // work
        foreach ($conversionArrayData as $key=>$subarr) {
            // Add to the current group count if it exists
            if (isset($counts[$subarr['group']])) {
                $counts[$subarr['item_id']]++;
            }
            // or initialize to 1 if it doesn't exist
            else $counts[$subarr['item_id']] = 1;

            // Or the ternary one-liner version
            // instead of the preceding if/else block
            $counts[$subarr['item_id']] = isset($counts[$subarr['item_id']]) ? $counts[$subarr['item_id']]++ : 1;
        }
        return $counts;*/

        //$counts = array(); // work
        $counts = []; // work
        $item_count = 0;
        foreach ($conversionArrayItemsData as $key => $subarr) {
            //echo "subarr: \n\r";
            //print_r($subarr);
            // Add to the current group count if it exists
            /*if (isset($counts[$subarr['item_id']])) {
                $counts[$subarr['item_id']]++;
            }
            // or initialize to 1 if it doesn't exist
            else $counts[$subarr['item_id']] = 1;

            // Or the ternary one-liner version
            // instead of the preceding if/else block
            $coun = isset($counts[$subarr['item_id']]) ? $counts[$subarr['item_id']]++ : 1;*/
            if (isset($counts[$subarr['item_id']])) {
                //$counts[$subarr['item_id']]++;
                $item_count++;
            }
            // or initialize to 1 if it doesn't exist
            //else $counts[$subarr['item_id']] = 1;
            else $item_count = 1;
            /*echo "conversionArrayData foreach subarr['item_id']: \n\r";
            print_r($subarr['item_id']);
            echo "\n\r";
            echo "conversionArrayData foreach coun: \n\r";
            print_r($coun);
            echo "\n\r";*/

            //$counts[$subarr['item_id']] = $coun;
            //$counts[$subarr['item_id']] = ['coun' => $coun];
            //$counts[] = ['item_id' => $subarr['item_id'], 'user_id' => $subarr['user_id']];
            //$counts[$key] = ['item_id' => $subarr['item_id'], 'user_id' => $subarr['user_id']];
            //$counts[$subarr['item_id']] = ['item_id' => $subarr['item_id'], 'user_id' => $subarr['user_id']];
            $counts[$subarr['item_id']] = [ // work
                'item_id' => $subarr['item_id'],
                'user_id' => $subarr['user_id'],
                'cover' => $subarr['cover'],
                'title' => $subarr['title'],
                'type_item' => $subarr['type_item'], // TODO: error
                'user_display_name' => $subarr['user_display_name'],
                'spring' => $subarr['spring'],
                'user_picture' => $subarr['user_picture'],
                'video_duration' => $subarr['video_duration'],
                'created_at' => $subarr['created_at'],
                'item_count_show' => $subarr['item_count_show'],
                //'likes_count' => $subarr['likes_count'],
                'stars_count' => $subarr['stars_count'],
                'reposts_count' => $subarr['reposts_count'],
                'pre_v_w320' => $subarr['pre_v_w320'],
                'pre_i_w320' => $subarr['pre_i_w320'],
                'type' => $subarr['type'],
                'count_view' => $item_count];
            /*$counts[] = [ // not work
                            'item_id' => $subarr['item_id'],
                            'user_id' => $subarr['user_id'],
                            'count_view' => $item_count];*/
            //--$counts[$subarr['item_id']]['ccc'] = 'ssss';
            //$counts['aaaaaaa'] = 'nnnnnnnn';
            //$counts = array_count_values(array_flip(array_column($conversionArrayData, 'item_id')));
            //$counts[]['sdfsdf'] = 'sdfsd';
            //echo "treash: \n\r";
            //print_r($counts);
        }
        //return $counts;
        /*return usort($counts, function($a, $b) {
            return $a['count_view'] <=> $b['count_view'];
        });*/
        //return $this->concatItemToRedisData($counts, $conversionArrayData);
        return $this->sortMultiArray($counts);
    }

    public function conversionArrayUsersData($conversionArrayUsersData)
    {
        //$counts = array(); // work
        $counts = []; // work
        $item_count = 0;
        foreach ($conversionArrayUsersData as $key => $subarr) {
            if (isset($counts[$subarr['spring']])) {
                $item_count++;
            } else $item_count = 1;
            $counts[$subarr['spring']] = [
                'item_id' => $subarr['item_id'],
                'user_id' => $subarr['user_id'],
                'title' => $subarr['title'],
                'type_item' => $subarr['type_item'],
                'user_display_name' => $subarr['user_display_name'],
                'spring' => $subarr['spring'],
                'user_picture' => $subarr['user_picture'],
                'pre_v_w320' => $subarr['pre_v_w320'],
                'pre_i_w320' => $subarr['pre_i_w320'],
                'count_view' => $item_count];
        }

        //echo "------------- conversionArrayUsersData ------------------ \n\r";
        //print_r($counts);
        return $this->sortMultiArray($counts);
    }

    public function sortMultiArray($sortMultiArray)
    {
        /*$out = array();
        foreach($sortMultiArray as $el){
            $key = serialize($el);
            if (!isset($out[$key]))
                $out[$key]=1;
            else
                $out[$key]++;
        }
        return $out;*/
        /*-------------------------------------------------------------*/

        // Получение списка столбцов
        /*foreach ($sortMultiArray as $key => $row) {
            $volume[$key]  = $row['item_id'];
            $edition[$key] = $row['count_view'];
        }*/

// Начиная с PHP 5.5.0 вы можете использовать array_column() вместо вышеуказанного кода
        /*$volume  = array_column($sortMultiArray, 'item_id');
        $edition = array_column($sortMultiArray, 'count_view');
print_r($volume);
print_r($edition);
// Сортируем данные по volume по убыванию и по edition по возрастанию
// Добавляем $data в качестве последнего параметра, для сортировки по общему ключу
        $res = array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $sortMultiArray);
        return $res;*/

        //return usort($sortMultiArray, "method1");
        /*-------------------------------------------------------------*/
        // Work!!!
        // https://www.codepunker.com/blog/3-solutions-for-multidimensional-array-sorting-by-child-keys-or-values-in-PHP?lang=ru
        //Метод 3: Сделай сам
        $temp = [];
        foreach ($sortMultiArray as $key => $value)
            //$temp[$value[2]["sizes"]["weight"] . "oldkey" . $key] = $value; //добавление уникального идентификатора, чтобы равные веса не перезаписывали друг друга
            $temp[$value["count_view"] . "oldkey" . $key] = $value;
        //ksort($temp); // или ksort($temp, SORT_NATURAL); в абзаце выше пояснено почему
        //krsort($temp); // Work!
        krsort($temp, SORT_NATURAL);
        $array = array_values($temp);
        unset($temp);
        return $array;
    }

    function arrayUnique($arrayUnique)
    {
        //$res = array_map("unserialize", array_unique(array_map("serialize", $arrayUnique)));
        // https://stackoverflow.com/a/42578062/1895392
        /*$result = array_reverse(array_values(array_column(
            array_reverse($arrayUnique),
            null,
            'spring'
        )));
        return $result;*/

        // https://stackoverflow.com/a/54448976/1895392
        array_multisort(array_column($arrayUnique, 'count_view'), SORT_ASC, $arrayUnique);
        foreach($arrayUnique as $v) { $result[$v['spring']] = $v; }
        //$result = array_values($result);
        return $result;
    }
    function method1($a, $b) // TODO: remove
    {
        //return ($a[2]["sizes"]["weight"] <= $b[2]["sizes"]["weight"]) ? -1 : 1;
        return ($a[1]["count_view"] <= $b[1]["count_view"]) ? -1 : 1;
    }

    public function concatItemToRedisData($concatItemToRedisData, $rawData) //TODO: remove
    {
        //echo "concatItemToRedisData rawData: \n\r";
        //print_r($rawData);
        foreach ($concatItemToRedisData as $item) {
            $res[]['item_id'] = $item;
            $res[]['user_id'] = $rawData['user_id'];
            $res[]['event_type'] = $rawData['event_type'];

        }
        return $res;

    }

}