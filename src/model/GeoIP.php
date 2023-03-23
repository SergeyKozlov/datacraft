<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PostgreSQL.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_demo_el.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_demo_insight.php');

use GeoIp2\Database\Reader;
use model\PG_demo_insight;

class GeoIP
{
    public function __construct()
    {
        $this->reader = new Reader($_SERVER['DOCUMENT_ROOT'] . '/system/geoip2/GeoLite2-City.mmdb');
        $this->welcome = new NAD();
    }

    public $item_id, $owner_id, $prev_item_id, $user_id, $continent, $country, $state, $city, $area, $lat, $lng, $user_ip;

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
        //echo "\n\r 2 --> class: " . get_class($this) . ' function: ' . debug_backtrace()[1]['function'] . ' ';
        return $this->item_id;
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
     * @param mixed $prev_item_id
     */
    public function setPrevItemId($prev_item_id): void
    {
        $this->prev_item_id = $prev_item_id;
    }

    /**
     * @return mixed
     */
    public function getPrevItemId()
    {
        return $this->prev_item_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $continent
     */
    public function setContinent($continent): void
    {
        $this->continent = $continent;
    }

    /**
     * @return mixed
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area): void
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng): void
    {
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $user_ip
     */
    public function setUserIp($user_ip): void
    {
        $this->user_ip = $user_ip;
    }

    /**
     * @return mixed
     */
    public function getUserIp()
    {
        return $this->user_ip;
    }

    /**
     * @return mixed
     */
    public function getGeoDataByIPv4()
    {
        if ($this->getUserIp()) {
            $record = $this->reader->city($this->getUserIp());
            //$this->setContinent($record->continent->geonameId);
            $this->setContinent(!empty($record->continent->geonameId) ? $record->continent->geonameId : '');
            //print("continent->code: " . $record->continent->code . "\n");
            //$this->setCountry($record->country->geonameId);
            $this->setCountry(!empty($record->country->geonameId) ? $record->country->geonameId : '');
            //print("country->isoCode: " . $record->country->isoCode . "\n");
            //$this->setState($record->subdivisions[0]->geonameId);
            $this->setState(!empty($record->subdivisions[0]->geonameId) ? $record->subdivisions[0]->geonameId : '');
            //print("subdivisions[0]->isoCode: " . $record->subdivisions[0]->isoCode . "\n");
            //$this->setCity($record->city->geonameId);
            $this->setCity(!empty($record->city->geonameId) ? $record->city->geonameId : '');
            //$this->setLat($record->location->latitude);
            $this->setLat(!empty($record->location->latitude) ? $record->location->latitude : '');
            //$this->setLng($record->location->longitude);
            $this->setLng(!empty($record->location->longitude) ? $record->location->longitude : '');
            //print("city->names['en']: " . $record->city->names['en'] . "\n");
            //return $this->reader;
            $this->subdivisionRecordFixation($record);
        } else {
            echo "\n\rgetGeoDataByIPv4 empty getUserIp\n\r";
        }
    }

    public function defineUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }
        //echo "getUserIP ip: \r\n";
        //print_r($ip);
        //return $ip;
        $this->setUserIp($ip);
    }

    public function composeDataForDB()
    {
        //echo "\n\r 1 --> class: " . get_class($this) . ' function: ' . debug_backtrace()[1]['function'] . ' ';
        if ($this->getItemId()) {
            $pgData['iv_id'] = $this->welcome->trueRandom();
            $pgData['item_id'] = $this->getItemId();
            if ($this->getOwnerId()) {
                $pgData['owner_id'] = $this->getOwnerId();
            }
            if ($this->getPrevItemId()) {
                $pgData['prev_item_id'] = $this->getPrevItemId();
            }
            if ($this->getUserId()) {
                $pgData['user_id'] = $this->getUserId();
            }
            if ($this->getContinent()) {
                $pgData['continent'] = $this->getContinent();
            }
            if ($this->getCountry()) {
                $pgData['country'] = $this->getCountry();
            }
            if ($this->getState()) {
                $pgData['state'] = $this->getState();
            }
            if ($this->getCity()) {
                $pgData['city'] = $this->getCity();
            }
            if ($this->getArea()) {
                $pgData['area'] = $this->getArea();
            }
            if ($this->getLat()) {
                $pgData['lat'] = $this->getLat();
            }
            if ($this->getLng()) {
                $pgData['lng'] = $this->getLng();
            }
            //$userId = $this->welcome->getUserId();
            //$bar = new NAD();
            //echo $bar->getUserId();
            //echo "\n\rcomposeDataForDB userId: \r\n";
            //print_r($userId);
            //echo "\n\r";
            //echo "composeDataForDB pgData: \r\n";
            //print_r($pgData);
            return $pgData;
        } else {
            //echo "composeDataForDB empty getItemId";
        }
    }

    public function geoipInit($geoipInit)
    {
        if (!empty($geoipInit['item_id'])) {
            $this->setItemId($geoipInit['item_id']);
            if (!empty($geoipInit['owner_id'])) {
                $this->setOwnerId($geoipInit['owner_id']);
            }
            if (!empty($geoipInit['prev_item_id'])) {
                $this->setPrevItemId($geoipInit['prev_item_id']);
            }
            if (!empty($geoipInit['user_id'])) {
                $this->setUserId($geoipInit['user_id']);
            }
            $this->defineUserIP();
            $this->getGeoDataByIPv4();
            $pgData = $this->composeDataForDB();
            //$pg = new PostgreSQL();
            $pgInsight = new PG_demo_insight();
            $pgInsight->pgAddData($pgInsight->table_items_views, $pgData);
        } else {
            //echo "\n\rfunction geoipInit empty item_id\n\r";
            return false;
        }
    }
    public function subdivisionRecordFixation($record)
    {
        //echo "\n\tsubdivisionRecordFixation subdivisions: \n\t";
        //print_r($record->subdivisions);
        If (!empty($record->subdivisions)) {
            if (!empty($record->subdivisions[0]->geonameId)
                and !empty($record->subdivisions[0]->isoCode)
                and !empty($record->subdivisions[0]->names)) {
                $state['state_id'] = $record->subdivisions[0]->geonameId;
                $state['iso_code'] = $record->subdivisions[0]->isoCode;
                //$state['names'] = json_encode($record->subdivisions[0]->names);
                //$state['names'] = json_encode(pg_escape_bytea($record->subdivisions[0]->names));
                //$state['names'] = pg_escape_bytea(json_encode($record->subdivisions[0]->names));
                //$state['names'] = pg_escape_string(json_encode($record->subdivisions[0]->names));
                $state['names'] = json_encode($record->subdivisions[0]->names,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                //$state['names'] = json_encode($record->subdivisions[0]->names,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                //echo "\n\tsubdivisionRecordFixation state: \n\t";
                //print_r($state);
                //echo "\n\t";
                //$pg = new PostgreSQL();
                $pg = new PG_demo_insight();
                $pg->pgGeo2SetState($state);
            }
        }
    }
}