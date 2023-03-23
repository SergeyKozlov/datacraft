<?php

use model\PG_demo_el;
use model\PG_demo_insight;

include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/model/PG_demo_insight.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/src/model/PG_demo_el.php');

require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class NAD
{
    public function __construct(/*log $log*/)
    {
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
        $datetime = new DateTime($date);
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
        $trueTimeObj = new DateTime();
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
}