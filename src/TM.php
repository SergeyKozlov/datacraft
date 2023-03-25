<?php

namespace VideMe\Datacraft;
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

//echo ' tm root use ';

use VideMe\Datacraft\log\log;
//$tm = new log();
//exit(' root tm start exit ');
//use model\GeoIP;
//use VideMe\Datacraft\model\GeoIP;
//use model\GeoIP;
use GeoIP;
// ============================ use model\PG_demo_insight;
//use VideMe\Datacraft\model\PG_insight;
//use model\PG_insight;
use PG_insight;
//use VideMe\Datacraft\model\PG_demo_insight;
//use model\PG_demo_insight;
use PG_demo_insight;

//========================================use model\PostgreSQL;
use VideMe\Datacraft\model\PostgreSQL;


include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PostgreSQL.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_demo_el.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_demo_insight.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/GeoIP.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

class TM {
    public $item_id, $item_created_at, $luft, $range1start, $range1stop, $date1start, $date1stop, $date2start, $date2stop;
    public int $evolutionMidlRangeMin, $evolutionMidlRangeMax, $evolutionHightyRangeMin, $evolutionHightyRangeMax,
    /*$evolutionMidlChance,*/ $evolutionHightyChance;
    public int $riseDaysMin, $riseDaysMax, $fallDaysMin, $fallDaysMax;
    public int $riseDaysMinHighty, $riseDaysMaxHighty, $fallDaysMinHighty, $fallDaysMaxHighty;
    public $riseDaysStop, $fallDaysStop;
    public int $risePercentShow, $fallPercentShow;
    public int $risePercentShowHighty, $fallPercentShowHighty;
    public int $luftPercentShow;
    public int $riseCountShow, $fallCountShow, $sumCountShow;
    public $evolutionCriterion1;
    public $fakeIP;
    public $owner_id, $period_now;
    public int $count_show, $count_show_new;
    public int $countLuftPercentMin, $countLuftPercentMax, $countJustNow;
    public $latestAt;
    public int $usa, $eu, $asia;
    public $city_eu = ['37.26.87.0', '5.180.12.0', '37.45.181.0', '23.14.90.0', '46.36.172.0', '37.157.164.0', '31.45.231.0', '31.30.29.0', '62.135.210.0', '46.22.212.0', '31.6.27.0', '15.188.0.0', '5.28.66.0', '5.54.132.0', '31.46.247.252', '82.112.91.0', '3.250.209.192', '2.227.106.0', '45.128.44.0', '88.82.104.0', '31.44.96.0', '5.149.112.0', '193.228.56.0', '5.56.64.0', '157.167.106.0', '46.33.217.0', '5.2.68.0', '62.162.87.0', '17.77.132.0', '5.60.64.0', '2.16.65.0', '2.20.96.0', '2.16.103.0', '24.135.63.0', '78.99.197.128', '31.15.128.0', '2.16.38.0', '2.22.31.0', '5.144.43.0', '95.67.82.213', '2.22.146.0'];
    public $city_usa = ['66.168.230.0', '23.135.128.255', '68.2.120.0', '12.186.177.255', '23.89.223.0', '73.217.126.18', '71.13.174.206', '68.192.229.42', '71.220.50.167', '166.173.228.56', '172.56.44.111', '75.174.137.117', '23.126.75.150', '168.214.152.244', '168.61.144.13', '68.103.120.236', '204.118.64.66', '174.79.80.98', '174.56.236.141', '100.16.87.148', '198.181.163.109', '207.179.112.18', '156.98.76.130', '74.190.14.96', '104.166.238.42', '184.167.135.122', '97.98.128.43', '47.33.49.66', '73.162.112.127', '69.126.76.248', '181.117.97.6', '72.0.157.36', '174.109.253.142', '198.245.44.6', '174.103.126.30', '136.228.118.63', '24.20.135.0', '71.58.239.178', '173.69.36.67', '66.192.63.255', '24.230.186.0', '165.214.11.73', '198.214.222.3', '168.178.21.65', '75.68.250.128', '76.217.51.9', '67.40.216.0', '74.125.75.225', '144.92.9.70', '93.120.113.2'];
    public $city_asia = ['104.28.106.62', '103.245.223.0', '5.255.27.112', '176.28.128.0', '37.236.107.141', '46.154.225.0', '178.171.66.7', '104.28.106.66', '5.134.55.165', '202.152.70.0', '101.109.255.255', '123.125.109.137', '109.233.16.0', '46.251.201.0', '103.161.130.0', '210.212.78.78', '59.152.102.171', '103.30.115.122', '37.211.255.255', '104.28.106.220', '42.115.123.85', '101.128.64.0', '101.50.93.117', '103.8.12.1', '1.0.64.0', '102.128.166.0', '103.110.53.0', '102.38.241.0', '1.0.127.255', '210.114.225.223', '1.32.0.0', '104.28.51.135', '217.17.240.254', '101.36.100.0', '104.28.106.127', '103.101.16.0', '103.70.153.101', '94.79.91.151', '213.133.90.115', '85.117.127.208', '112.203.15.33', '103.101.80.0', '5.62.56.160', '104.28.106.211', '128.127.192.0', '134.35.127.0', '1.177.255.147', '104.28.106.17', '37.18.11.0', '1.174.208.0', '94.158.62.252', '85.118.100.44', '185.70.61.29', '119.2.105.189', '54.92.75.77', '101.110.34.62', '103.10.20.0', '183.182.114.61'];

    /**
     * @param mixed $item_id
     */
    public function setItemId($item_id): void
    {
        $this->item_id = $item_id;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param mixed $item_created_at
     */
    public function setItemCreatedAt($item_created_at): void
    {
        $this->item_created_at = $item_created_at;
    }

    /**
     * @return mixed
     */
    public function getItemCreatedAt()
    {
        return $this->item_created_at;
    }

    /**
     * @param mixed $luft
     */
    public function setLuft($luft): void
    {
        $this->luft = $luft;
    }

    /**
     * @return mixed
     */
    public function getLuft()
    {
        return $this->luft;
    }

    /**
     * @param mixed $range1start
     */
    public function setRange1start($range1start): void
    {
        $this->range1start = $range1start;
    }

    /**
     * @return mixed
     */
    public function getRange1start()
    {
        return $this->range1start;
    }

    /**
     * @param mixed $range1stop
     */
    public function setRange1stop($range1stop): void
    {
        $this->range1stop = $range1stop;
    }

    /**
     * @return mixed
     */
    public function getRange1stop()
    {
        return $this->range1stop;
    }

    /**
     * @param mixed $date1start
     */
    public function setDate1start($date1start): void
    {
        $this->date1start = $date1start;
    }

    /**
     * @return mixed
     */
    public function getDate1start()
    {
        return $this->date1start;
    }

    /**
     * @param mixed $date1stop
     */
    public function setDate1stop($date1stop): void
    {
        $this->date1stop = $date1stop;
    }

    /**
     * @return mixed
     */
    public function getDate1stop()
    {
        return $this->date1stop;
    }

    /**
     * @param mixed $date2start
     */
    public function setDate2start($date2start): void
    {
        $this->date2start = $date2start;
    }

    /**
     * @return mixed
     */
    public function getDate2start()
    {
        return $this->date2start;
    }

    /**
     * @param mixed $date2stop
     */
    public function setDate2stop($date2stop): void
    {
        $this->date2stop = $date2stop;
    }

    /**
     * @return mixed
     */
    public function getDate2stop()
    {
        return $this->date2stop;
    }

    /**
     * @param mixed $fakeIP
     */
    public function setFakeIP($fakeIP): void
    {
        $this->fakeIP = $fakeIP;
    }

    /**
     * @return mixed
     */
    public function getFakeIP()
    {
        return $this->fakeIP;
    }

    /**
     * @param mixed $owner_id
     */
    public function setOwnerId($owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param mixed $period_now
     */
    public function setPeriodNow($period_now): void
    {
        $this->period_now = $period_now;
    }

    /**
     * @return mixed
     */
    public function getPeriodNow()
    {
        return $this->period_now;
    }

    /**
     * @param string $count_show
     */
    public function setCountShow(string $count_show): void
    {
        $this->count_show = $count_show;
    }

    /**
     * @return int
     */
    public function getCountShow(): int
    {
        return $this->count_show;
    }

    /**
     * @param int $count_show_new
     */
    public function setCountShowNew(int $count_show_new): void
    {
        $this->count_show_new = $count_show_new;
    }

    /**
     * @return int
     */
    public function getCountShowNew(): int
    {
        return $this->count_show_new;
    }

    /**
     * @param mixed $evolutionMidlRangeMin
     */
    public function setEvolutionMidlRangeMin($evolutionMidlRangeMin): void
    {
        $this->evolutionMidlRangeMin = $evolutionMidlRangeMin;
    }

    /**
     * @return mixed
     */
    public function getEvolutionMidlRangeMin()
    {
        return $this->evolutionMidlRangeMin;
    }

    /**
     * @param mixed $evolutionMidlRangeMax
     */
    public function setEvolutionMidlRangeMax($evolutionMidlRangeMax): void
    {
        $this->evolutionMidlRangeMax = $evolutionMidlRangeMax;
    }

    /**
     * @return mixed
     */
    public function getEvolutionMidlRangeMax()
    {
        return $this->evolutionMidlRangeMax;
    }

    /**
     * @param mixed $evolutionHightyRangeMin
     */
    public function setEvolutionHightyRangeMin($evolutionHightyRangeMin): void
    {
        $this->evolutionHightyRangeMin = $evolutionHightyRangeMin;
    }

    /**
     * @return mixed
     */
    public function getEvolutionHightyRangeMin()
    {
        return $this->evolutionHightyRangeMin;
    }

    /**
     * @param mixed $evolutionHightyRangeMax
     */
    public function setEvolutionHightyRangeMax($evolutionHightyRangeMax): void
    {
        $this->evolutionHightyRangeMax = $evolutionHightyRangeMax;
    }

    /**
     * @return mixed
     */
    public function getEvolutionHightyRangeMax()
    {
        return $this->evolutionHightyRangeMax;
    }

    /**
     * @param mixed $evolutionMidlChance
     */
    /*public function setEvolutionMidlChance($evolutionMidlChance): void
    {
        $this->evolutionMidlChance = $evolutionMidlChance;
    }*/

    /**
     * @return mixed
     */
    /*public function getEvolutionMidlChance()
    {
        return $this->evolutionMidlChance;
    }*/

    /**
     * @param mixed $evolutionHightyChance
     */
    public function setEvolutionHightyChance($evolutionHightyChance): void
    {
        $this->evolutionHightyChance = $evolutionHightyChance;
    }

    /**
     * @return mixed
     */
    public function getEvolutionHightyChance()
    {
        return $this->evolutionHightyChance;
    }

    /**
     * @param int $riseDaysMin
     */
    public function setRiseDaysMin(int $riseDaysMin): void
    {
        $this->riseDaysMin = $riseDaysMin;
    }

    /**
     * @return int
     */
    public function getRiseDaysMin(): int
    {
        return $this->riseDaysMin;
    }

    /**
     * @param int $riseDaysMax
     */
    public function setRiseDaysMax(int $riseDaysMax): void
    {
        $this->riseDaysMax = $riseDaysMax;
    }

    /**
     * @return int
     */
    public function getRiseDaysMax(): int
    {
        return $this->riseDaysMax;
    }

    /**
     * @param int $fallDaysMin
     */
    public function setFallDaysMin(int $fallDaysMin): void
    {
        $this->fallDaysMin = $fallDaysMin;
    }

    /**
     * @return int
     */
    public function getFallDaysMin(): int
    {
        return $this->fallDaysMin;
    }

    /**
     * @param int $fallDaysMax
     */
    public function setFallDaysMax(int $fallDaysMax): void
    {
        $this->fallDaysMax = $fallDaysMax;
    }

    /**
     * @return int
     */
    public function getFallDaysMax(): int
    {
        return $this->fallDaysMax;
    }

    /**
     * @param int $riseDaysMinHighty
     */
    public function setRiseDaysMinHighty(int $riseDaysMinHighty): void
    {
        $this->riseDaysMinHighty = $riseDaysMinHighty;
    }

    /**
     * @return int
     */
    public function getRiseDaysMinHighty(): int
    {
        return $this->riseDaysMinHighty;
    }

    /**
     * @param int $riseDaysMaxHighty
     */
    public function setRiseDaysMaxHighty(int $riseDaysMaxHighty): void
    {
        $this->riseDaysMaxHighty = $riseDaysMaxHighty;
    }

    /**
     * @return int
     */
    public function getRiseDaysMaxHighty(): int
    {
        return $this->riseDaysMaxHighty;
    }

    /**
     * @param int $fallDaysMinHighty
     */
    public function setFallDaysMinHighty(int $fallDaysMinHighty): void
    {
        $this->fallDaysMinHighty = $fallDaysMinHighty;
    }

    /**
     * @return int
     */
    public function getFallDaysMinHighty(): int
    {
        return $this->fallDaysMinHighty;
    }

    /**
     * @param int $fallDaysMaxHighty
     */
    public function setFallDaysMaxHighty(int $fallDaysMaxHighty): void
    {
        $this->fallDaysMaxHighty = $fallDaysMaxHighty;
    }

    /**
     * @return int
     */
    public function getFallDaysMaxHighty(): int
    {
        return $this->fallDaysMaxHighty;
    }

    /**
     * @param mixed $riseDaysStop
     */
    public function setRiseDaysStop($riseDaysStop): void
    {
        $this->riseDaysStop = $riseDaysStop;
    }

    /**
     * @return mixed
     */
    public function getRiseDaysStop()
    {
        return $this->riseDaysStop;
    }

    /**
     * @param mixed $fallDaysStop
     */
    public function setFallDaysStop($fallDaysStop): void
    {
        $this->fallDaysStop = $fallDaysStop;
    }

    /**
     * @return mixed
     */
    public function getFallDaysStop()
    {
        return $this->fallDaysStop;
    }

    /**
     * @param mixed $evolutionCriterion1
     */
    public function setEvolutionCriterion1($evolutionCriterion1): void
    {
        $this->evolutionCriterion1 = $evolutionCriterion1;
    }

    /**
     * @return mixed
     */
    public function getEvolutionCriterion1()
    {
        return $this->evolutionCriterion1;
    }

    /**
     * @param int $risePercentShow
     */
    public function setRisePercentShow(int $risePercentShow): void
    {
        $this->risePercentShow = $risePercentShow;
    }

    /**
     * @return int
     */
    public function getRisePercentShow(): int
    {
        return $this->risePercentShow;
    }

    /**
     * @param int $fallPercentShow
     */
    public function setFallPercentShow(int $fallPercentShow): void
    {
        $this->fallPercentShow = $fallPercentShow;
    }

    /**
     * @return int
     */
    public function getFallPercentShow(): int
    {
        return $this->fallPercentShow;
    }

    /**
     * @param int $risePercentShowHighty
     */
    public function setRisePercentShowHighty(int $risePercentShowHighty): void
    {
        $this->risePercentShowHighty = $risePercentShowHighty;
    }

    /**
     * @return int
     */
    public function getRisePercentShowHighty(): int
    {
        return $this->risePercentShowHighty;
    }

    /**
     * @param int $fallPercentShowHighty
     */
    public function setFallPercentShowHighty(int $fallPercentShowHighty): void
    {
        $this->fallPercentShowHighty = $fallPercentShowHighty;
    }

    /**
     * @return int
     */
    public function getFallPercentShowHighty(): int
    {
        return $this->fallPercentShowHighty;
    }

    /**
     * @param int $luftPercentShow
     */
    public function setLuftPercentShow(int $luftPercentShow): void
    {
        $this->luftPercentShow = $luftPercentShow;
    }

    /**
     * @return int
     */
    public function getLuftPercentShow(): int
    {
        return $this->luftPercentShow;
    }

    /**
     * @param int $riseCountShow
     */
    public function setRiseCountShow(int $riseCountShow): void
    {
        $this->riseCountShow = $riseCountShow;
    }

    /**
     * @return int
     */
    public function getRiseCountShow(): int
    {
        return $this->riseCountShow;
    }

    /**
     * @param int $fallCountShow
     */
    public function setFallCountShow(int $fallCountShow): void
    {
        $this->fallCountShow = $fallCountShow;
    }

    /**
     * @return int
     */
    public function getFallCountShow(): int
    {
        return $this->fallCountShow;
    }

    /**
     * @param int $sumCountShow
     */
    public function setSumCountShow(int $sumCountShow): void
    {
        $this->sumCountShow = $sumCountShow;
    }

    /**
     * @return int
     */
    public function getSumCountShow(): int
    {
        return $this->sumCountShow;
    }

    /**
     * @param int $countLuftPercentMin
     */
    public function setCountLuftPercentMin(int $countLuftPercentMin): void
    {
        $this->countLuftPercentMin = $countLuftPercentMin;
    }

    /**
     * @return int
     */
    public function getCountLuftPercentMin(): int
    {
        return $this->countLuftPercentMin;
    }

    /**
     * @param int $countLuftPercentMax
     */
    public function setCountLuftPercentMax(int $countLuftPercentMax): void
    {
        $this->countLuftPercentMax = $countLuftPercentMax;
    }

    /**
     * @return int
     */
    public function getCountLuftPercentMax(): int
    {
        return $this->countLuftPercentMax;
    }

    /**
     * @param int $countJustNow
     */
    public function setCountJustNow(int $countJustNow): void
    {
        $this->countJustNow = $countJustNow;
    }

    /**
     * @return int
     */
    public function getCountJustNow(): int
    {
        return $this->countJustNow;
    }

    /**
     * @param mixed $latestAt
     */
    public function setLatestAt($latestAt): void
    {
        $this->latestAt = $latestAt;
    }

    /**
     * @return mixed
     */
    public function getLatestAt()
    {
        return $this->latestAt;
    }

    /**
     * @param int $usa
     */
    public function setUsa(int $usa): void
    {
        $this->usa = $usa;
    }

    /**
     * @return int
     */
    public function getUsa(): int
    {
        return $this->usa;
    }

    /**
     * @param int $eu
     */
    public function setEu(int $eu): void
    {
        $this->eu = $eu;
    }

    /**
     * @return int
     */
    public function getEu(): int
    {
        return $this->eu;
    }

    /**
     * @param int $asia
     */
    public function setAsia(int $asia): void
    {
        $this->asia = $asia;
    }

    /**
     * @return int
     */
    public function getAsia(): int
    {
        return $this->asia;
    }



    public function __construct()
    {
        $this->setLuft(5);
        $this->setEvolutionMidlRangeMin(100);
        $this->setEvolutionMidlRangeMax(200);
        $this->setEvolutionHightyRangeMin(200);
        $this->setEvolutionHightyRangeMax(600);
        $this->setEvolutionHightyChance(5);
        $this->setEvolutionCriterion1(100);
        $this->setRisePercentShow(55);
        $this->setFallPercentShow(45);
        $this->setRisePercentShowHighty(65);
        $this->setFallPercentShowHighty(35);
        $this->setLuftPercentShow(10);
        $this->setRiseDaysMin(10); // 10 days
        $this->setRiseDaysMax(14); // 2 week
        $this->setRiseDaysMinHighty(14); // 2 week
        $this->setRiseDaysMaxHighty(28); // 3 week
        $this->setFallDaysMin(10); // 1 week
        $this->setFallDaysMax(28); // 3 week
        $this->setFallDaysMinHighty(28); // 3 week
        $this->setFallDaysMaxHighty(42); // 6 week
        $this->setCountLuftPercentMin(50);
        $this->setCountLuftPercentMax(150);
        $this->setCountJustNow(1);
        $this->setUsa(5);
        $this->setEu(7);
        $this->setAsia(9);
        //$this->log = new log();

        $this->log = new log();
    }

    public function defineItemParam()
    {
        $welcome = new NAD();
        $pg = new PostgreSQL();
        //$itemInfo = $welcome->pgItemFullInfo($this->getItemId());
        $itemInfo = $pg->pgOneDataByColumn([
            'table' => $pg->table_items,
            'find_column' => 'item_id',
            'find_value' => $this->getItemId()]);
        $this->setOwnerId($itemInfo['owner_id']);
        $this->setItemCreatedAt($itemInfo['created_at']);
        //echo 'created_at: ' . $this->getItemCreatedAt();
        //$datetime = new DateTime($this->getItemCreatedAt());
        //print_r($datetime);
        //$final = date("Y-m-d", strtotime("+1 month", $this->getItemCreatedAt()));
        //print_r($final);
        //$datetime->modify('+12 day');
        //echo $datetime->format('Y-m-d');
    }

    public function setRange1()
    {
        //$range1Start_l = $this->getRange1start();
        //echo "\n\t--- range1Start_l days: " . $range1Start_l;
        $range1Stop_l = $this->getRange1stop();
        //echo "\n\t--- range1Stop_l days: " . $range1Stop_l;
        $luft_l = $this->getLuft();
        //echo "\n\t---  luft_r: " . $luft_r;
        //$luft_l = (int)$luft_r;
        //$luft_l = -1 * abs($luft_r);
        //echo "\n\t--- luft_l: " . $luft_l;
        $range1Stop_true = rand($range1Stop_l - $luft_l, $range1Stop_l + $luft_l);
        //echo "\n\t--- setRange1 range1Stop_true days: " . $range1Stop_true;
        $this->setRange1stop($range1Stop_true);
    }
    public function setRange0()
    {
        $range1Start_l = $this->getRange1start();
        //echo "\n\t--- range1Start_l days: " . $range1Start_l;
        //$range1Stop_l = $this->getRange1stop();
        //echo "\n\t--- range1Stop_l days: " . $range1Stop_l;
        $luft_l = $this->getLuft();
        //echo "\n\t---  luft_r: " . $luft_r;
        //$luft_l = (int)$luft_r;
        //$luft_l = -1 * abs($luft_r);
        //echo "\n\t--- luft_l: " . $luft_l;
        //$range1Start_true = rand($range1Start_l - $luft_l, $range1Start_l + $luft_l);
        $range1Start_true = rand( - $luft_l,  $luft_l);
        //echo "\n\t--- setRange0 range1Start_true days: " . $range1Start_true;
        $this->setRange1start($range1Start_true);
    }
    /*public function setDate()
    {
        $welcome = new NAD();
        //$this->setDate1start($this->getItemCreatedAt());
        $date1start = $welcome->convTimeForPG_tz($this->getItemCreatedAt());
        //echo "\n\t--- setDate date1start: " . $date1start;
        $this->setDate1start($date1start);
        //echo "\n\t--- setDate date1stop before: " . $this->getDate1stop();
        $date1stop = $welcome->timeShift($this->getDate1start(), $this->getRange1stop());
        //echo "\n\t--- setDate date1stop: " . $date1stop;
        $this->setDate1stop($date1stop);
    }*/
    public function setDate3($date, $range)
    {
        $welcome = new NAD();
        //echo "\n\t--- setDate3 date before: " . $date;
        $date = $welcome->timeShift($date, $range);
        //echo "\n\t--- setDate3 date  after: " . $date;
        return $date;
    }
    public function foreachTM($rangeStart, $rangeStop, $count_view)
    {
        $welcome = new NAD();
        $geoIP = new GeoIP();
        $this->log->setStaffMessage("\n\t--- foreachTM rangeStart: " . $rangeStart . " rangeStop: " . $rangeStop . " count_view: " . $count_view);
        for ($i = 1; $i <= $count_view; $i++) {
            //echo "\n\t--- foreachTM for i: " . $i;
            $continent = $this->randomFrom();
            //===echo "\n\t--- foreachTM for continent: " . $continent;
            //exit;
            //$geoData = $this->composeGeoData($continent);
            $this->defineFakeIP($continent);
            //echo "\n\t--- foreachTM for geoData: ";
            //echo "\n\t--- foreachTM for date: " . $date;
            //$this->defineUserIP();
            $geoIP->setItemId($this->getItemId());
            $geoIP->setOwnerId($this->getOwnerId());
            $geoIP->setUserIp($this->getFakeIP());
            //echo "\n\t--- foreachTM this->getFakeIP(): " . $this->getFakeIP() . "\r\n";
            //echo "\n\t--- foreachTM geoIP->getUserIp(): " . $geoIP->getUserIp() . "\r\n";

            $geoIP->getGeoDataByIPv4();
            $geoData = $geoIP->composeDataForDB();
            $geoData['created_at'] = $welcome->rand_date_between($rangeStart, $rangeStop);
            //echo "\n\t--- foreachTM geoData: \r\n";
            //print_r($geoData);
            //$pg = new PostgreSQL();
            $pgInsight = new PG_demo_insight();
            $pgInsight->pgAddData($pgInsight->table_items_views, $geoData);
        }
    }
    public function randomFrom()
    {
        //$range = $this->getUsa() + $this->getEu() + $this->getAsia();
        $range = 10;
        $rand = rand(1, $range);
        //echo "\n\t--- randomFrom rand: " . $rand . "\n\r";
        if ($rand <= $this->getUsa()) {
            return 'usa';
        }
        if ($rand > $this->getUsa() and $rand <= $this->getEu()) {
            return 'eu';
        }
        if ($rand >= $this->getAsia()) {
            return 'asia';
        }
        return 'usa';
    }
    public function defineFakeIP($defineFakeIP)
    {
        //echo "\n\t--- composeGeoData composeGeoData: " . $composeGeoData . "\n\r";
        //$geoIP = new GeoIP();
        switch ($defineFakeIP)
        {
            case 'usa':
                //$resGeoData = $geoIP->getGeo(['ip' => $this->city_usa[array_rand($this->city_usa)]]);
                $this->setFakeIP($this->city_usa[array_rand($this->city_usa)]);
                break;
            case 'eu':
                //$resGeoData = $geoIP->getGeo(['ip' => $this->city_eu[array_rand($this->city_eu)]]);
                $this->setFakeIP($this->city_eu[array_rand($this->city_eu)]);
                break;
            case 'asia':
                //$resGeoData = $geoIP->getGeo(['ip' => $this->city_asia[array_rand($this->city_asia)]]);
                $this->setFakeIP($this->city_asia[array_rand($this->city_asia)]);
                break;
            default:
                //$resGeoData = $geoIP->getGeo(['ip' => $this->city_eu[array_rand($this->city_eu)]]);
                $this->setFakeIP($this->city_eu[array_rand($this->city_eu)]);
                break;
        }
        //return $resGeoData;
    }

    public function defineEvolutionType()
    {
        if ($this->getCountShow() < $this->getEvolutionCriterion1()) {
            $this->log->setStaffMessage("\n\t--- defineEvolutionType this->getCountShow() < this->getEvolutionCriterion1(): " . $this->getCountShow() . " < " . $this->getEvolutionCriterion1() . "\r\n");
            $evolutionType = $this->randomEvolutionType();
            switch ($evolutionType)
            {
                case 'midl':
                    //$evolutionCountView = rand($this->getEvolutionMidlRangeMin(), $this->getEvolutionMidlRangeMax());
                    $this->setCountShowNew(rand($this->getEvolutionMidlRangeMin(), $this->getEvolutionMidlRangeMax()));
                    $this->defineCountsViews();
                    break;
                case 'highty':
                    //$evolutionCountView = rand($this->getEvolutionHightyRangeMin(), $this->getEvolutionHightyRangeMax());
                    $this->setCountShowNew(rand($this->getEvolutionHightyRangeMin(), $this->getEvolutionHightyRangeMax()));
                    $this->defineCountsViewsHighty();
                    break;
                default:
                    //$evolutionCountView = rand($this->getEvolutionMidlRangeMin(), $this->getEvolutionMidlRangeMax());
                    $this->setCountShowNew(rand($this->getEvolutionMidlRangeMin(), $this->getEvolutionMidlRangeMax()));
                    $this->defineCountsViews();
                    break;
            }
            $this->log->setStaffMessage("\n\t--- defineEvolutionType evolutionCountView: " . $this->getCountShowNew() . "\r\n");
        } else {
            $this->log->setStaffMessage("\n\t--- defineEvolutionType this->getCountShow() > this->getEvolutionCriterion1(): " . $this->getCountShow() . " > " . $this->getEvolutionCriterion1() . "\r\n");
            //$evolutionCountView = $this->getCountShow();
            $this->setCountShowNew($this->getCountShow());
            $this->defineCountsViewsHighty();
        }
        //return $evolutionCountView;
        //$this->setCountShowNew($evolutionCountView);
    }
    public function randomEvolutionType()
    {
        // 1   midl evo      7    highty evo  10
        // <----------------------------------->
        $resRandEvoChance = rand(1, 10);
        $this->log->setStaffMessage("\n\t--- randomEvolutionType resRandEvoChance: " . $resRandEvoChance . "\r\n");
        if ($resRandEvoChance >= $this->getEvolutionHightyChance()) {
            $resRandEvoType = 'highty';
        } else {
            $resRandEvoType = 'midl';
        }
        $this->log->setStaffMessage("\n\t--- randomEvolutionType resRandEvoType: " . $resRandEvoType . "\r\n");
        return $resRandEvoType;

    }
    public function defineCountsViews()
    {
        $this->shuflePercentShow();
        $this->defineRangesRiseFall();
        $percentRise = $this->getRisePercentShow();
        $this->log->setStaffMessage("\n\t--- defineCountsViews percentRise: " . $percentRise . "\r\n");

        $fallPercent = $this->getFallPercentShow();
        $this->log->setStaffMessage("\n\t--- defineCountsViews fallPercent: " . $fallPercent . "\r\n");

        $countShow = $this->getCountShowNew();
        $this->log->setStaffMessage("\n\t--- defineCountsViews countShow: " . $countShow . "\r\n");

        //$perPercent = intdiv($countShow, 100);
        $perPercent = $countShow / 100;
        $this->log->setStaffMessage("\n\t--- defineCountsViews perPercent: " . $perPercent . "\r\n");

        $riseShow = round($perPercent * $percentRise);
        $this->log->setStaffMessage("\n\t--- defineCountsViews RiseShow: " . $riseShow . "\r\n");
        $this->setRiseCountShow($riseShow);

        $fallShow = round($perPercent * $fallPercent);
        $this->log->setStaffMessage("\n\t--- defineCountsViews fallShow: " . $fallShow . "\r\n");
        $this->setFallCountShow($fallShow);

        $sumShow = $riseShow + $fallShow;
        $this->log->setStaffMessage("\n\t--- defineCountsViews sumShow: " . $sumShow . "\r\n");
        $this->setSumCountShow($sumShow);
    }
    public function defineCountsViewsHighty()
    {
        $this->shuflePercentShow();
        $this->defineRangesRiseFallHighty();
        $percentRise = $this->getRisePercentShowHighty();
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty percentRise: " . $percentRise . "\r\n");

        $fallPercent = $this->getFallPercentShowHighty();
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty fallPercent: " . $fallPercent . "\r\n");

        $countShow = $this->getCountShowNew();
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty countShow: " . $countShow . "\r\n");

        //$perPercent = intdiv($countShow, 100);
        $perPercent = $countShow / 100;
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty perPercent: " . $perPercent . "\r\n");

        $riseShow = round($perPercent * $percentRise);
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty RiseShow: " . $riseShow . "\r\n");
        $this->setRiseCountShow($riseShow);

        $fallShow = round($perPercent * $fallPercent);
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty fallShow: " . $fallShow . "\r\n");
        $this->setFallCountShow($fallShow);

        $sumShow = $riseShow + $fallShow;
        $this->log->setStaffMessage("\n\t--- defineCountsViewsHighty sumShow: " . $sumShow . "\r\n");
        $this->setSumCountShow($sumShow);
    }

    public function shuflePercentShow()
    {
        $luftPercentShow = $this->getLuftPercentShow();
        $this->log->setStaffMessage("\n\t--- shuflePercentShow luftPercentShow: " . $luftPercentShow . "\r\n");

        $shiftPercentShow = rand(1, $luftPercentShow);
        $this->log->setStaffMessage("\n\t--- shuflePercentShow shiftPercentShow: " . $shiftPercentShow . "\r\n");

        $risePercentShow = $this->getRisePercentShow();
        $this->log->setStaffMessage("\n\t--- shuflePercentShow RisePercentShow: " . $risePercentShow . "\r\n");

        $fallPercentShow = $this->getFallPercentShow();
        $this->log->setStaffMessage("\n\t--- shuflePercentShow fallPercentShow: " . $fallPercentShow . "\r\n");

        if (rand(0, 1)) {
            $this->setRisePercentShow($risePercentShow - $shiftPercentShow);
            $this->setFallPercentShow($fallPercentShow + $shiftPercentShow);
        } else {
            $this->setRisePercentShow($risePercentShow + $shiftPercentShow);
            $this->setFallPercentShow($fallPercentShow - $shiftPercentShow);
        }
        $this->log->setStaffMessage("\n\t--- shuflePercentShow this->getRisePercentShow(): " . $this->getRisePercentShow() . "\r\n");
        $this->log->setStaffMessage("\n\t--- shuflePercentShow this->getFallPercentShow(): " . $this->getFallPercentShow() . "\r\n");

    }

    public function defineRangesRiseFall()
    {
        $riseDaysStop = rand($this->getRiseDaysMin(), $this->getRiseDaysMax());
        $this->log->setStaffMessage("\n\t--- defineRangesRiseFall riseDaysStop: " . $riseDaysStop . "\r\n");
        $this->setRiseDaysStop($riseDaysStop);

        $fallDaysStop = rand($this->getFallDaysMin(), $this->getFallDaysMax());
        $this->log->setStaffMessage("\n\t--- defineRangesRiseFall fallDaysStop: " . $fallDaysStop . "\r\n");
        $this->setFallDaysStop($fallDaysStop);
    }

    public function defineRangesRiseFallHighty()
    {
        $riseDaysStop = rand($this->getRiseDaysMinHighty(), $this->getRiseDaysMaxHighty());
        $this->log->setStaffMessage("\n\t--- defineRangesRiseFallHighty riseDaysStop: " . $riseDaysStop . "\r\n");
        $this->setRiseDaysStop($riseDaysStop);

        $fallDaysStop = rand($this->getFallDaysMinHighty(), $this->getFallDaysMaxHighty());
        $this->log->setStaffMessage("\n\t--- defineRangesRiseFallHighty fallDaysStop: " . $fallDaysStop . "\r\n");
        $this->setFallDaysStop($fallDaysStop);
    }

    public function defineCountJustNow($start_O, $stop_O, $latest_at_new_O, $countShow)
    {
        $interval = $start_O->diff($stop_O);
        $interval_left = $latest_at_new_O->diff($stop_O);
        //echo "\n\t-----> difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days \n\r";
        // shows the total amount of days (not divided into years, months and days like above)
        //echo "\n\t-----> defineCountJustNow difference period rise " . $interval->days . " days \n\r";
        //echo "\n\t-----> defineCountJustNow difference period rise " . $interval_left->days . " days left\n\r";
        if ($interval->days > 0) {
            $this->setCountJustNow(round($countShow / $interval->days));
        }
        $this->log->setStaffMessage("\n\t-----> defineCountJustNow getCountJustNow before: " . $this->getCountJustNow() . "\n\r");
        //$count_just_now = round($count_just_now / 100 * (rand(50, 150)));
        $this->setCountJustNow(round($this->getCountJustNow() / 100 * (rand($this->getCountLuftPercentMin(), $this->getCountLuftPercentMax()))));
        $this->log->setStaffMessage("\n\t-----> defineCountJustNow getCountJustNow after: " . $this->getCountJustNow() . "\n\r");
    }
}
