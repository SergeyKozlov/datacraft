<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/geoip2/Geo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/GeoIP.php');

//require_once 'vendor/autoload.php';
$geo = new GeoIP();

//error_reporting(0); // Turn off error reporting
error_reporting(E_ALL ^ E_DEPRECATED); // Report all e

use GeoIp2\Database\Reader;
use model\GeoIP;

// This creates the Reader object, which should be reused across
// lookups.
//$reader = new Reader('/usr/local/share/GeoIP/GeoIP2-City.mmdb');
$reader = new Reader($_SERVER['DOCUMENT_ROOT'] . '/system/geoip2/GeoLite2-City.mmdb');

// Replace "city" with the appropriate method for your database, e.g.,
// "country".
//$record = $reader->city('128.101.101.101');
if (!empty($_GET['ip'])) {
$record = $reader->city($_GET['ip']);
print("continent->geonameId: " . $record->continent->geonameId . "\n");
print("continent->code: " . $record->continent->code . "\n");
print("country->geonameId: " . $record->country->geonameId . "\n");
print("country->isoCode: " . $record->country->isoCode . "\n");
print("subdivisions[0]->geonameId: " . $record->subdivisions[0]->geonameId . "\n");
print("subdivisions[0]->isoCode: " . $record->subdivisions[0]->isoCode . "\n");
print("city->geonameId: " . $record->city->geonameId . "\n");
print("city->names['en']: " . $record->city->names['en'] . "\n");
print("location->latitude: " . $record->location->latitude . "\n");
print("location->longitude: " . $record->location->longitude . "\n");

//use MaxMind\Db\Reader;
//use GeoIp2\MaxMind\Db\Reader;

/*$ipAddress = '24.24.24.24';
//$databaseFile = 'GeoIP2-City.mmdb';

//$reader2 = new Reader($databaseFile);

// get returns just the record for the IP address
print_r($reader->get($ipAddress));

// getWithPrefixLen returns an array containing the record and the
// associated prefix length for that record.
print_r($reader->getWithPrefixLen($ipAddress));

$reader->close();*/

/*
print($record->country->isoCode . "\n"); // 'US'
print($record->country->name . "\n"); // 'United States'
print($record->country->names['zh-CN'] . "\n"); // '美国'
print($record->country->geonameId . "\n"); // '美国'

print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

print($record->city->name . "\n"); // 'Minneapolis'
print($record->city->geonameId . "\n"); // 'Minneapolis'

print($record->postal->code . "\n"); // '55455'

print($record->location->latitude . "\n"); // 44.9733
print($record->location->longitude . "\n"); // -93.2323

print($record->traits->network . "\n"); // '128.101.101.101/32'

print("continent->code: " . $record->continent->code . "\n");
print("continent->name: " . $record->continent->name . "\n");
print("continent->geonameId: " . $record->continent->geonameId . "\n");
print("continent->names: " . $record->continent->names . "\n");

print("country->name: " . $record->country->name . "\n");
print("country->isoCode: " . $record->country->isoCode . "\n");
print("country->names: " . $record->country->names . "\n");
print("country->geonameId: " . $record->country->geonameId . "\n");
print("country->confidence: " . $record->country->confidence . "\n");
print("country->isInEuropeanUnion: " . $record->country->isInEuropeanUnion . "\n");

print("city->name: " . $record->city->name . "\n");
print("city->confidence: " . $record->city->confidence . "\n");
print("city->names: " . $record->city->names . "\n");
print("city->geonameId: " . $record->city->geonameId . "\n");

print("location->timeZone: " . $record->location->timeZone . "\n");
print("location->accuracyRadius: " . $record->location->accuracyRadius . "\n");
print("location->averageIncome: " . $record->location->averageIncome . "\n");
print("location->latitude: " . $record->location->latitude . "\n");
print("location->longitude: " . $record->location->longitude . "\n");
print("location->metroCode: " . $record->location->metroCode . "\n");
print("location->populationDensity: " . $record->location->populationDensity . "\n");

print("maxmind->queriesRemaining: " . $record->maxmind->queriesRemaining . "\n");

print("mostSpecificSubdivision->name: " . $record->mostSpecificSubdivision->name . "\n");
print("mostSpecificSubdivision->isoCode: " . $record->mostSpecificSubdivision->isoCode . "\n");
print("mostSpecificSubdivision->geonameId: " . $record->mostSpecificSubdivision->geonameId . "\n");
print("mostSpecificSubdivision->names: " . $record->mostSpecificSubdivision->names . "\n");
print("mostSpecificSubdivision->confidence: " . $record->mostSpecificSubdivision->confidence . "\n");

print("postal->code: " . $record->postal->code . "\n");
print("postal->confidence: " . $record->postal->confidence . "\n");

print("raw: " . $record->raw . "\n");

print("registeredCountry->isoCode: " . $record->registeredCountry->isoCode . "\n");
print("registeredCountry->name: " . $record->registeredCountry->name . "\n");
print("registeredCountry->confidence: " . $record->registeredCountry->confidence . "\n");
print("registeredCountry->names: " . $record->registeredCountry->names . "\n");
print("registeredCountry->geonameId: " . $record->registeredCountry->geonameId . "\n");
print("registeredCountry->isInEuropeanUnion: " . $record->registeredCountry->isInEuropeanUnion . "\n");

print("representedCountry->name: " . $record->representedCountry->name . "\n");
print("representedCountry->isoCode: " . $record->representedCountry->isoCode . "\n");
print("representedCountry->type: " . $record->representedCountry->type . "\n");
print("representedCountry->isInEuropeanUnion: " . $record->representedCountry->isInEuropeanUnion . "\n");
print("representedCountry->geonameId: " . $record->representedCountry->geonameId . "\n");
print("representedCountry->names: " . $record->representedCountry->names . "\n");
print("representedCountry->confidence: " . $record->representedCountry->confidence . "\n");

print("mostSpecificSubdivision->isoCode: " . $record->mostSpecificSubdivision->isoCode . "\n");
print("mostSpecificSubdivision->name: " . $record->mostSpecificSubdivision->name . "\n");
print("mostSpecificSubdivision->confidence: " . $record->mostSpecificSubdivision->confidence . "\n");
print("mostSpecificSubdivision->names: " . $record->mostSpecificSubdivision->names . "\n");
print("mostSpecificSubdivision->geonameId: " . $record->mostSpecificSubdivision->geonameId . "\n");

print("maxmind->queriesRemaining: " . $record->maxmind->queriesRemaining . "\n");

print("subdivisions: " . $record->subdivisions . "\n");

print("traits->domain: " . $record->traits->domain . "\n");*/

echo "\n\r";
print_r($record);

} else {
    $geo->defineUserIP();
    $geo->getGeoDataByIPv4();
    $pgData = $geo->composeDataForDB();
    echo "geo data: \n\r";
    print_r($pgData);
    echo "geo class: \n\r";
    print_r($geo);
}