<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/system/geoip/SxGeo.php');

class ContentFilter
{
    public $AlJazeeraArr, $clientIP, $clientCountry, $islamCountry, $filterSet, $intFilter, $limitSQL, $notForIslam, $turkeyCountry;
    /*public $badArr = ["8e0d497a17d9",
        "3de7e7f80f8f",
        "945c798c4746",
        "27072ada2991",
        "00bd09b9fe21",
        "b535670bdc75",
        "f6d2d7172dfc",
        "42c1a9ebcea2",
        "917be5d4449e",
        "7c343f3491dd",
        "b6e5befa5dd8",
        "5b3c48ab12bf"];*/

    /**
     * @param array $AlJazeeraArr
     */
    public function setAlJazeeraArr(array $AlJazeeraArr): void
    {
        $this->AlJazeeraArr = $AlJazeeraArr;
    }

    /**
     * @return array
     */
    public function getAlJazeeraArr(): array
    {
        return $this->AlJazeeraArr;
    }

    /**
     * @param mixed $clientIP
     */
    public function setClientIP($clientIP): void
    {
        $this->clientIP = $clientIP;
    }

    /**
     * @return mixed
     */
    public function getClientIP()
    {
        return $this->clientIP;
    }

    /**
     * @param mixed $clientCountry
     */
    public function setClientCountry($clientCountry): void
    {
        $this->clientCountry = $clientCountry;
    }

    /**
     * @return mixed
     */
    public function getClientCountry()
    {
        return $this->clientCountry;
    }

    /**
     * @param mixed $islamCountry
     */
    public function setIslamCountry($islamCountry): void
    {
        $this->islamCountry = $islamCountry;
    }

    /**
     * @return mixed
     */
    public function getIslamCountry()
    {
        return $this->islamCountry;
    }

    /**
     * @param mixed $filterSet
     */
    public function setFilterSet($filterSet): void
    {
        $this->filterSet = $filterSet;
    }

    /**
     * @return mixed
     */
    public function getFilterSet()
    {
        return $this->filterSet;
    }

    /**
     * @param mixed $intFilter
     */
    public function setIntFilter($intFilter): void
    {
        $this->intFilter = $intFilter;
    }

    /**
     * @return mixed
     */
    public function getIntFilter()
    {
        return $this->intFilter;
    }

    /**
     * @param mixed $limitSQL
     */
    public function setLimitSQL($limitSQL): void
    {
        $this->limitSQL = $limitSQL;
    }
    /**
     * @param mixed $limitSQL
     */
    public function resetLimitSQL(): void
    {
        $this->limitSQL = 50;
    }

    /**
     * @return mixed
     */
    public function getLimitSQL()
    {
        return $this->limitSQL;
    }

    /**
     * @param mixed $notForIslam
     */
    public function setNotForIslam($notForIslam): void
    {
        $this->notForIslam = $notForIslam;
    }

    /**
     * @return mixed
     */
    public function getNotForIslam()
    {
        return $this->notForIslam;
    }

    /**
     * @param mixed $turkeyCountry
     */
    public function setTurkeyCountry($turkeyCountry): void
    {
        $this->turkeyCountry = $turkeyCountry;
    }

    /**
     * @return mixed
     */
    public function getTurkeyCountry()
    {
        return $this->turkeyCountry;
    }

    public function __construct()
    {
        $badArr = [
            "8beea6e2ea5b",
            "c8853a72743f",
            "ec07579f5ad7",
            "7b4daf5c631b",
            "ec2616cd8dda",
            "3ef4794eafd1",
            "c163f7ffd6f3",
            "0ee798e4050b",
            "3d9dc53b3bad",
            "49c2e360aff4",
            "461f8e433924",
            "c3493bcfd9ab",
            "cbe316e926bc",
            "d1f7284c7739",
            "53b7e3312f37",
            "f277a489f363",
            "1b74336b03d2",
            "97198d65ea8c",
            "526fae793137",
            "9ccd4c1b4f70",
            "94feeb72db8e",
            "8e0d497a17d9",
            "3de7e7f80f8f",
            "945c798c4746",
            "27072ada2991",
            "00bd09b9fe21",
            "b535670bdc75",
            "f6d2d7172dfc",
            "42c1a9ebcea2",
            "917be5d4449e",
            "7c343f3491dd",
            "b6e5befa5dd8",
            "5b3c48ab12bf"];
        $arrayNotForIslam = ['2a5483b3ac4d'];
        $this->setAlJazeeraArr($badArr);
        $this->setNotForIslam($arrayNotForIslam);
        $this->setClientIP($this->defineClientIP());
        $this->setClientCountry($this->defineClientCountry());
        $this->setIslamCountry($this->defineIslamCountry());
        $this->setTurkeyCountry($this->defineTurkeyCountry());
        $this->setFilterSet('standard');
        $this->defineFilterSet();
        $this->setIntFilter(0);
        $this->setLimitSQL(50);
    }

    public function defineClientIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($ip)) {
            return $ip;
        } else {
            return false;
        }

    }

    public function defineClientCountry()
    {
        //header('Content-type: text/plain; charset=utf8');

// Подключаем SxGeo.php класс
        //include("SxGeo.php");
// Создаем объект
// Первый параметр - имя файла с базой (используется оригинальная бинарная база SxGeo.dat)
// Второй параметр - режим работы:
//     SXGEO_FILE   (работа с файлом базы, режим по умолчанию);
//     SXGEO_BATCH (пакетная обработка, увеличивает скорость при обработке множества IP за раз)
//     SXGEO_MEMORY (кэширование БД в памяти, еще увеличивает скорость пакетной обработки, но требует больше памяти)
//$SxGeo = new SxGeo('SxGeoCity.dat');
        //$SxGeo = new SxGeo('SxGeo.dat');
        //$SxGeo = new SxGeo('SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY); // Самый производительный режим, если нужно обработать много IP за раз

        $SxGeo = new SxGeo($_SERVER['DOCUMENT_ROOT'] . '/system/geoip/SxGeo.dat');
        $country = $SxGeo->get($this->getClientIP());
        if (!empty($country)) {
            return $country;
        } else {
            return false;
        }
    }

    public function defineIslamCountry()
    {
        //return ['TR', 'DZ', 'RU'];
        return ['AF', 'BH', 'AL', 'DZ', 'BD', 'BA', 'BN', 'BF', 'GM', 'GY', 'GW', 'DJ', 'EG', 'ID', 'JO', 'IQ', 'IR', 'YE', 'KZ', 'QA', 'KG', 'KM', 'XK', 'CI', 'KW', 'LB', 'LY', 'MR', 'MY', 'ML', 'MV', 'MA', 'NE', 'NG', 'AE', 'OM', 'PK', 'SA', 'SN', 'SL', 'SY', 'SO', 'SD', 'TJ', 'TN', 'TM', 'TR', 'UZ', 'TD'];
    }

    public function defineTurkeyCountry()
    {
        //return ['TR', 'DZ', 'RU'];
        return ['TR'];
    }

    public function filterAlJazeera($array) // TODO: remove
    {
        //echo 'country: ' . $this->getClientCountry();
        if (in_array($this->getClientCountry(), $this->getIslamCountry())) {
            return $array;
        } else {
            $badArr = $this->getAlJazeeraArr();
            //echo "\n\r badArr-------------------------------------------\n\r";
            //print_r($badArr);
            foreach ($array as $key => $value)
            {
                $curItem = $value['item_id'];
                //print_r($value['item_id'] . " cur item \n\r");
                //print_r($curItem . " cur item \n\r");
                foreach ($badArr as $key2 => $value2)
                {
                    //print_r($key2 . " - " .  $value2 . "\n\r");
                    //print_r($curItem . " - " .  $value2 . "\n\r");
                    if ($curItem == $value2) {
                        //print_r($curItem . " = " .  $value2 . " key - " . $key . "\n\r");
                        unset($array[$key]);
                    }

                }

            }
            return array_values($array);
        }
    }

    public function showNewPosts() {
        //$newArray = [];
        //print_r("\n\rgetFilterSet " . $this->getFilterSet() . "\n\r");
        switch ($this->getFilterSet()) {
            case "standard":
                //$newArray = $this->filterEurope($array);
                return $this->getRedisArray('new_posts_europe');
                break;
            case "islam":
                return $this->getRedisArray('new_posts_islam');
                break;
            case "turkey":
                return $this->getRedisArray('new_posts_turkey');
                break;
            default:
                return $this->getRedisArray('new_posts_genuine');
                //$newArray = $array;
                break;
        }
        //$this->setIntFilter($this->getIntFilter() + 1);
        //return '';
    }

    public function getRedisArray($key) {
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        try {
            //$res = $mc->get($memcachedGetKey["key"]);
            $res = $redis->get($key);
            //echo " memcachedGetKey res ";
            //print_r($res);
            return $res;
        } catch (Exception $e) {
            //echo "Not found. " . $e->getMessage();
            return false;
        }
    }

    public function filterNotForEurope($array)
    {
        //echo 'country: ' . $this->getClientCountry();
        /*if (in_array($this->getClientCountry(), $this->getIslamCountry())) {
            return $array;
        } else {*/
            $badArr = $this->getAlJazeeraArr();
            //echo "\n\r badArr-------------------------------------------\n\r";
            //print_r($badArr);
            foreach ($array as $key => $value)
            {
                $curItem = $value['item_id'];
                //print_r($value['item_id'] . " cur item \n\r");
                //print_r($curItem . " cur item \n\r");
                foreach ($badArr as $key2 => $value2)
                {
                    //print_r($key2 . " - " .  $value2 . "\n\r");
                    //print_r($curItem . " - " .  $value2 . "\n\r");
                    if ($curItem == $value2) {
                        //print_r($curItem . " = " .  $value2 . " key - " . $key . "\n\r");
                        unset($array[$key]);
                    }

                }

            }
        $this->setIntFilter($this->getIntFilter() + 1);

        return array_values($array);
        //}
    }

    public function filterNotForIslam($array)
    {
        //echo 'country: ' . $this->getClientCountry();
        /*if (in_array($this->getClientCountry(), $this->getIslamCountry())) {
            return $array;
        } else {*/
            //$badArr = $this->getAlJazeeraArr();
            $badArr = $this->getNotForIslam();
            //echo "\n\r badArr-------------------------------------------\n\r";
            //print_r($badArr);
            foreach ($array as $key => $value)
            {
                $curItem = $value['item_id'];
                //print_r($value['item_id'] . " cur item \n\r");
                //print_r($curItem . " cur item \n\r");
                foreach ($badArr as $key2 => $value2)
                {
                    //print_r($key2 . " - " .  $value2 . "\n\r");
                    //print_r($curItem . " - " .  $value2 . "\n\r");
                    if ($curItem == $value2) {
                        //print_r($curItem . " = " .  $value2 . " key - " . $key . "\n\r");
                        unset($array[$key]);
                    }

                }

            }
        $this->setIntFilter($this->getIntFilter() + 1);

        return array_values($array);
        //}
    }

    public function filterStdForArray($array) // TODO: remove
    {
        $arrayFiltered = $this->filterAlJazeera($array);
        if (!empty($arrayFiltered)) {
            return $arrayFiltered;
        } else {
            return $array;
        }
    }

    public function filterStdForArrayOld($val)
    {
        //header('Content-type: text/plain; charset=utf8');

// Подключаем SxGeo.php класс
        //include("SxGeo.php");
// Создаем объект
// Первый параметр - имя файла с базой (используется оригинальная бинарная база SxGeo.dat)
// Второй параметр - режим работы:
//     SXGEO_FILE   (работа с файлом базы, режим по умолчанию);
//     SXGEO_BATCH (пакетная обработка, увеличивает скорость при обработке множества IP за раз)
//     SXGEO_MEMORY (кэширование БД в памяти, еще увеличивает скорость пакетной обработки, но требует больше памяти)
//$SxGeo = new SxGeo('SxGeoCity.dat');
        //$SxGeo = new SxGeo('SxGeo.dat');
        $SxGeo = new SxGeo($_SERVER['DOCUMENT_ROOT'] . '/system/geoip/SxGeo.dat');
//$SxGeo = new SxGeo('SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY); // Самый производительный режим, если нужно обработать много IP за раз

        $ip = $_SERVER['REMOTE_ADDR'];

//var_export($SxGeo->getCityFull($ip)); // Вся информация о городе
        //var_export($SxGeo->get($ip));
        //print_r("ddddddddddddddddd " . $SxGeo->get($ip));
        if ($SxGeo->get($ip) == 'TR') return $val;
        /*$badArr = ["8e0d497a17d9",
"3de7e7f80f8f",
"945c798c4746",
"27072ada2991",
"00bd09b9fe21",
"b535670bdc75",
"f6d2d7172dfc"];*/
        $badArr = $this->getAlJazeeraArr();
        //echo "\n\r badArr-------------------------------------------\n\r";
        //print_r($badArr);
        foreach ($val as $key => $value)
        {
            $curItem = $value['item_id'];
            //print_r($value['item_id'] . " cur item \n\r");
            //print_r($curItem . " cur item \n\r");
            $curKey = $key;
            foreach ($badArr as $key2 => $value2)
            {
                //print_r($key2 . " - " .  $value2 . "\n\r");
                //print_r($curItem . " - " .  $value2 . "\n\r");
                if ($curItem == $value2) {
                    //print_r($curItem . " = " .  $value2 . " key - " . $key . "\n\r");
                    unset($val[$key]);
                }

            }

        }
        /*echo "\n\r val-------------------------------------------\n\r";
        print_r($val);
        echo "\n\r val-------------------------------------------\n\r";*/
        return array_values($val);
    }
    public function forItem($forItem) {
        /*$badArr = ["8e0d497a17d9",
            "3de7e7f80f8f",
            "945c798c4746",
            "27072ada2991",
            "00bd09b9fe21",
            "b535670bdc75",
            "f6d2d7172dfc"];*/
        $badArr = $this->getAlJazeeraArr();

        foreach ($badArr as $key => $value)
        {
            //print_r($key2 . " - " .  $value2 . "\n\r");
            //print_r($curItem . " - " .  $value2 . "\n\r");
            if ($forItem == $value) {
                //print_r($curItem . " = " .  $value2 . " key - " . $key . "\n\r");
                return $forItem;
            }

        }
        return false;
    }
    public function defineFilterSet() {
        if (in_array($this->getClientCountry(), $this->getIslamCountry())) {
            $this->setFilterSet('islam');
        }
        if (in_array($this->getClientCountry(), $this->getTurkeyCountry())) {
            $this->setFilterSet('turkey');
        }
        if (!in_array($this->getClientCountry(), $this->getIslamCountry())) {
            $this->setFilterSet('standard');
        }
    }

    public function doFiltration($array) { // TODO: remove NOO
        $newArray = [];
        switch ($this->getFilterSet()) {
            case "standard":
                $newArray = $this->filterNotForEurope($array);
                break;
            case "islam":
                //$newArray = $this->filterNotForIslam($array);
                $newArray = $this->filterNotForEurope($array);
                break;
            case "turkey":
                $newArray = $array;
                break;
            default:
                $newArray = $array;
                break;
        }
        $this->setIntFilter($this->getIntFilter() + 1);
        return $newArray;
    }

    public function compositionNewPosts() { // TODO:remove
        $welcome = new NAD();
        $pgShowNewPosts['offset'] = $welcome->setOffset();
        //$pgShowNewPosts['limit'] = $welcome->setLimit();
        $pgShowNewPosts['limit'] = $this->getLimitSQL();
        $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
        echo "\n\rgetIntFilter: " . $this->getIntFilter() . "\n\r";
        echo "\n\rgetLimitSQL: " . $this->getLimitSQL() . "\n\r";
        $newArray = $this->doFiltration($newPosts);
        echo "\n\rgetIntFilter: " . $this->getIntFilter() . "\n\r";

        //if (count($newArray) < 250 and $this->getIntFilter() < 4)
        while (count($newArray) < 250 and $this->getIntFilter() < 4)
        {
            $this->setLimitSQL($this->getLimitSQL() + (250 - count($newArray)));
            echo "\n\rgetLimitSQL: " . $this->getLimitSQL() . "\n\r";

            $pgShowNewPosts['limit'] = $this->getLimitSQL();
            $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
            $newArray = $this->doFiltration($newPosts);

        }

        return $newArray;
    }
    public function compositionNewPosts_auto() {
        $this->updateRedis('new_posts_genuine', $this->compositionNewPost_ForGenuine());
        $this->updateRedis('new_posts_europe', $this->compositionNewPost_ForEurope());
        $this->updateRedis('new_posts_islam', $this->compositionNewPost_ForIslam());
        $this->updateRedis('new_posts_turkey', $this->compositionNewPost_ForTurkey());
    }

    public function compositionNewPost_ForGenuine() {
        $welcome = new NAD();
        //$this->setFilterSet('standard');
        //$this->resetLimitSQL();

        $pgShowNewPosts['offset'] = $welcome->setOffset();
        //$pgShowNewPosts['limit'] = $welcome->setLimit();
        $pgShowNewPosts['limit'] = $this->getLimitSQL();
        $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
        /*echo "\n\rstandard getIntFilter: " . $this->getIntFilter() . "\n\r";
        echo "\n\rstandard getLimitSQL: " . $this->getLimitSQL() . "\n\r";
        $newArray = $this->doFiltration($newPosts);
        //$newArray = $this->filterEurope($newPosts);

        echo "\n\rstandard getIntFilter: " . $this->getIntFilter() . "\n\r";

        //if (count($newArray) < 250 and $this->getIntFilter() < 4)
        while (count($newArray) < 50 and $this->getIntFilter() < 4)
        {
            $this->setLimitSQL($this->getLimitSQL() + (50 - count($newArray)));
            echo "\n\rstandard getLimitSQL: " . $this->getLimitSQL() . "\n\r";

            $pgShowNewPosts['limit'] = $this->getLimitSQL();
            $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
            $newArray = $this->doFiltration($newPosts);
            //$newArray = $this->filterEurope($newPosts);
        }*/
        return $newPosts;
    }
    public function compositionNewPost_ForEurope() {
        $welcome = new NAD();
        $this->setFilterSet('standard');
        $this->resetLimitSQL();

        $pgShowNewPosts['offset'] = $welcome->setOffset();
        //$pgShowNewPosts['limit'] = $welcome->setLimit();
        $pgShowNewPosts['limit'] = $this->getLimitSQL();
        $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
        echo "\n\rstandard getIntFilter: " . $this->getIntFilter() . "\n\r";
        echo "\n\rstandard getLimitSQL: " . $this->getLimitSQL() . "\n\r";
        $newArray = $this->doFiltration($newPosts);
        //$newArray = $this->filterEurope($newPosts);

        echo "\n\rstandard getIntFilter: " . $this->getIntFilter() . "\n\r";

        //if (count($newArray) < 250 and $this->getIntFilter() < 4)
        while (count($newArray) < 50 and $this->getIntFilter() < 4)
        {
            $this->setLimitSQL($this->getLimitSQL() + (50 - count($newArray)));
            echo "\n\rstandard getLimitSQL: " . $this->getLimitSQL() . "\n\r";

            $pgShowNewPosts['limit'] = $this->getLimitSQL();
            $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
            $newArray = $this->doFiltration($newPosts);
            //$newArray = $this->filterEurope($newPosts);
        }
        return $newArray;
    }
    public function compositionNewPost_ForIslam() {
        $welcome = new NAD();
        $this->setFilterSet('islam');
        $this->resetLimitSQL();

        $pgShowNewPosts['offset'] = $welcome->setOffset();
        //$pgShowNewPosts['limit'] = $welcome->setLimit();
        $pgShowNewPosts['limit'] = $this->getLimitSQL();
        $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
        echo "\n\rislam getIntFilter: " . $this->getIntFilter() . "\n\r";
        echo "\n\rislam getLimitSQL: " . $this->getLimitSQL() . "\n\r";
        $newArray = $this->doFiltration($newPosts);
        //$newArray = $this->filterEurope($newPosts);

        echo "\n\rislam getIntFilter: " . $this->getIntFilter() . "\n\r";

        //if (count($newArray) < 250 and $this->getIntFilter() < 4)
        while (count($newArray) < 50 and $this->getIntFilter() < 4)
        {
            $this->setLimitSQL($this->getLimitSQL() + (50 - count($newArray)));
            echo "\n\rislam getLimitSQL: " . $this->getLimitSQL() . "\n\r";

            $pgShowNewPosts['limit'] = $this->getLimitSQL();
            $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
            $newArray = $this->doFiltration($newPosts);
            //$newArray = $this->filterEurope($newPosts);
        }
        return $newArray;
    }
    public function compositionNewPost_ForTurkey() {
        $welcome = new NAD();
        ////$this->setFilterSet('islam');
        $this->setFilterSet('turkey');
        $this->resetLimitSQL();

        $pgShowNewPosts['offset'] = $welcome->setOffset();
        //$pgShowNewPosts['limit'] = $welcome->setLimit();
        $pgShowNewPosts['limit'] = $this->getLimitSQL();
        $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
        echo "\n\rturkey getIntFilter: " . $this->getIntFilter() . "\n\r";
        echo "\n\rturkey getLimitSQL: " . $this->getLimitSQL() . "\n\r";
        $newArray = $this->doFiltration($newPosts);
        //$newArray = $this->filterEurope($newPosts);

        echo "\n\rturkey getIntFilter: " . $this->getIntFilter() . "\n\r";

        //if (count($newArray) < 250 and $this->getIntFilter() < 4)
        while (count($newArray) < 50 and $this->getIntFilter() < 4)
        {
            $this->setLimitSQL($this->getLimitSQL() + (50 - count($newArray)));
            echo "\n\rturkey getLimitSQL: " . $this->getLimitSQL() . "\n\r";

            $pgShowNewPosts['limit'] = $this->getLimitSQL();
            $newPosts = $welcome->pgShowNewPosts($pgShowNewPosts);
            $newArray = $this->doFiltration($newPosts);
            //$newArray = $this->filterEurope($newPosts);
        }
        return $newArray;
    }
    public function updateRedis($set, $array) {
        $getRredis = new RedisVideme();
        $redis = $getRredis->redisConnect();
        $jsonArray = json_encode($array);
        try {
            $redis->set($set, $jsonArray);
            $redis->expire($set, 1209600);
        } catch (Exception $e) {
            echo "Not found. " . $e->getMessage();
            //return false;
        }
    }
}