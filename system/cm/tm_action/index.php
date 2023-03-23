<?php

use model\PG_demo_el;
use model\PostgreSQL;

include_once($_SERVER['DOCUMENT_ROOT'] . '/system/cm/tm.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_insight.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_elaboration.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/model/PG_demo_el.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$tm = new TM();
//$pg_insight = new PG_insight();
$pg_videme = new PostgreSQL();
$pg_elaboration = new PG_demo_el();
//$pg_elaboration = new PG_elaboration();

$log = new log();

error_reporting(0); // Turn off error reporting
//error_reporting(E_ALL ^ E_DEPRECATED); // Report all errors

$echo = [];

if (!empty($_GET['item_id'])) {
    $tm->setItemId($_GET['item_id']);
} else {
    $res_item_id = $pg_elaboration->pgGetItemForTM_OLD($tm->getFallDaysMaxHighty());
    if (!empty($res_item_id)) {
        $tm->setItemId($res_item_id[0]['item_id']);
    } else {
        exit('no item on base');
    }
}

//$tm->setItemId($_GET['item_id']);

if (isset($_REQUEST['setUsa'])) $tm->setUsa($_REQUEST['setUsa']);
if (isset($_REQUEST['setEu'])) $tm->setEu($_REQUEST['setEu']);
if (isset($_REQUEST['setAsia'])) $tm->setAsia($_REQUEST['setAsia']);
if (isset($_REQUEST['setEvolutionHightyChance'])) $tm->setEvolutionHightyChance($_REQUEST['setEvolutionHightyChance']);

$tm->defineItemParam();
$log->setStaffMessage("\n\rtm->getItemId(): " . $tm->getItemId());
$log->setStaffMessage("\n\rtm->getItemCreatedAt(): " . $tm->getItemCreatedAt());
//exit;

/*$count_show_old = $pg->pgOneDataByColumn([
    'table' => $pg->table_items_counts,
    'find_column' => 'count_item_id',
    'find_value' => $tm->getItemId()]);
//echo "\n\rcount_show_old: ";
//print_r($count_show_old);

$tm->setCountShow($count_show_old['item_count_show']);
echo "\n\rtm->getCountShow(): " . $tm->getCountShow();*/

$count_show_old = [];
$count_show_old = $pg_videme->pgOneDataByColumn([
    'table' => $pg_videme->table_items_counts,
    'find_column' => 'count_item_id',
    'find_value' => $tm->getItemId()]);
//echo "\n\rcount_show_old: ";
//print_r($count_show_old);

if (!empty($count_show_old['item_count_show'])) {
//if (!empty($count_show_old)) {
    $tm->setCountShow($count_show_old['item_count_show']);
} else {
    $countItem['count_item_id'] = $tm->getItemId();
    $countItem['item_count_show'] = 1;
    $pg_videme->pgAddData($pg_videme->table_items_counts, $countItem);

    $count_show_old['item_count_show'] = '1';
    $tm->setCountShow(1);
}
$log->setStaffMessage("\n\rtm->getCountShow(): " . $tm->getCountShow());

$elo_old = $pg_elaboration->pgOneDataByColumn([
    'table' => $pg_elaboration->table_el_trendmaker,
    'find_column' => 'item_id',
    'find_value' => $tm->getItemId()]);
$log->setStaffMessage("\n\rpg->pgOneDataByColumn() elo_old: \n\r");
//===$log->setStaffMessage(var_dump($elo_old));
$log->setStaffMessage("\n\r");

if (empty($elo_old)) {
    /* NEW el item ****************************************************************************************************/
    $log->setStaffMessage("\n\rempty(elo_old) NEW el item: \n\r");

    /*echo "\n\rgetFallDaysStop: \n\r";
    echo $tm->getFallDaysStop();
    echo "\n\rfull tm: \n\r";
    print_r($tm);
    exit;*/

    $today_O = new DateTime("now");
    $log->setStaffMessage("\n\rtoday_O: \n\r");
    //===$log->setStaffMessage(var_dump($today_O));

    //$today = $welcome->getTimeForPG_tz();
    //echo "\n\rtoday: \n\r";
    //var_dump($today);

    //$item_created_at_O = $welcome->convTimeForPG_tz($tm->getItemCreatedAt());
    //$item_created_at_O = new DateTime($tm->getItemCreatedAt());
    //echo "\n\ritem_created_at_O: \n\r";
    //var_dump($item_created_at_O);

    //$max_date = $welcome->timeShift($tm->getItemCreatedAt(), $tm->getFallDaysMaxHighty());
    //echo "\n\rmax_date: \n\r";
    //var_dump($max_date);

    //$max_date_O = $welcome->convTimeForPG_tz($max_date);
    //$max_date_O = new DateTime($max_date);
    $max_date_O = new DateTime($welcome->timeShift($tm->getItemCreatedAt(), $tm->getFallDaysMaxHighty()));
    $log->setStaffMessage("\n\rmax_date_O: \n\r");
    //==$log->setStaffMessage(var_dump($max_date_O));
    //exit;

    if ($max_date_O > $today_O) {
        echo "\n\rlatest_at_O < today_O, period awaiting <-----------\n\r";
        exit;
    } else {

//$resCountNew = $tm->defineEvolutionType();
//echo "\n\rresCountNew: " . $resCountNew;
    $tm->defineEvolutionType();


        $tm->setRange1start(0);
//$tm->setRange1stop(14);
        $tm->setRange1stop($tm->getRiseDaysStop());
//$tm->setLuft(5);
        $tm->setRange1();
//==$tm->setDate();
//echo $welcome->rand_date_between($tm->getDate1start(), $tm->getDate1stop());
//$tm->foreachTM($tm->getDate1start(), $tm->getDate1stop(), $tm->getRiseCountShow());
//echo "\n\rtm->getDate1stop(): " . $tm->getDate1stop();


        $tm->setDate1start($tm->getItemCreatedAt());
        $tm->setDate1stop($tm->setDate3($tm->getDate1start(), $tm->getRange1stop()));
        $log->setStaffMessage("\n\r Rise period start --------------------------------------------------------");
        $log->setStaffMessage("\n\rtm->getDate1start() 1: " . $tm->getDate1start());
        $log->setStaffMessage("\n\rtm->getDate1stop() 1: " . $tm->getDate1stop());
        $tm->foreachTM($tm->getDate1start(), $tm->getDate1stop(), $tm->getRiseCountShow());


        $log->setStaffMessage("\n\r Fall period start --------------------------------------------------------");
        $tm->setRange1start(0); // <--- 0
//$tm->setRange1stop(10);
        $tm->setRange1stop($tm->getFallDaysStop());
        $tm->setLuft(5);
        $tm->setRange1();
        $tm->setRange0();
        $tm->setDate2start($tm->setDate3($tm->getDate1stop(), $tm->getRange1start()));
        $tm->setDate2stop($tm->setDate3($tm->getDate1start(), $tm->getRange1stop()));
        $log->setStaffMessage("\n\r Fall period start --------------------------------------------------------");
        $log->setStaffMessage("\n\rtm->getDate1start() 2: " . $tm->getDate1start());
        $log->setStaffMessage("\n\rtm->getDate1stop() 2: " . $tm->getDate1stop());
        $tm->foreachTM($tm->getDate2start(), $tm->getDate2stop(), $tm->getFallCountShow());

        $pg_videme->pgUpdateData($pg_videme->table_items_counts, 'item_count_show', $tm->getSumCountShow(), 'count_item_id', $tm->getItemId());

        $newdata['item_id'] = $tm->getItemId();
        $newdata['owner_id'] = $tm->getOwnerId();
        $newdata['period_now'] = 'stay';
        $newdata['rise_start'] = $tm->getDate1start();
        $newdata['rise_stop'] = $tm->getDate1stop();
        $newdata['fall_start'] = $tm->getDate2start();
        $newdata['fall_stop'] = $tm->getDate2stop();
        $newdata['rise_count_show'] = $tm->getRiseCountShow();
        $newdata['fall_count_show'] = $tm->getFallCountShow();
        $newdata['sum_count_show'] = $tm->getSumCountShow();
        $newdata['latest_at'] = $tm->getItemCreatedAt();

        $log->setStaffMessage("\n\rnewdata(): \n\r");
        //$log->setStaffMessage(print_r($newdata));
        $log->setStaffMessage("\n\r");
        $pg_elaboration->pgAddData($pg_elaboration->table_el_trendmaker, $newdata);

        //$log->toFile(['service' => 'tm', 'type' => $tm->getItemId(), 'text' => 'item: ' . $tm->getItemId() . 'count: ' . $tm->getSumCountShow() . '; period_now: stay']);
        $log->toFile(['service' => 'tm', 'type' => $tm->getItemId(), 'text' => basename($_SERVER["SCRIPT_FILENAME"], '.php') . ': ' . implode(": ", $newdata)]);

        /* Show next item_id */
        $res_item_id = $pg_elaboration->pgGetItemForTM_OLD($tm->getFallDaysMaxHighty());
        if (!empty($res_item_id)) {
            //$log->setStaffMessage(['next_item_id'] = $res_item_id[0]['item_id']);
            //$log->setStaffMessage($res_item_id[0]['item_id']);
            //$next_item_id = $res_item_id[0]['item_id'];
            $next_item_info = $res_item_id[0];
        } else {
            $next_item_info = '';
        }
    }
    $welcome->outputDDBData(['staff' => $log->getStaffMessage(), 'next_item_info' => $next_item_info]);
    //var_dump($log->getStaffMessage());

} else {
    echo "\n\r" . $tm->getItemId() . " already exist in: $pg_elaboration->table_el_trendmaker \n\r";
    $log->toFile(['service' => 'tm', 'type' => $tm->getItemId(), 'text' => basename($_SERVER["SCRIPT_FILENAME"], '.php') . ': item_id: ' . $tm->getItemId() . ': doble! period_now: stay']);
}