<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 30.11.16
 * Time: 22:58
 */

/*
chmod +x /var/www/html/system/cm/go.sh
chmod +x /var/www/html/system/log/scheduler.sh

#0,25 * * * * /var/www/html/system/cm/go.sh
#0,35 * * * * /var/www/html/system/cm/go.sh
#0,25 * * * * /var/www/html/system/cm/sharevideo/goyt.sh

0,35 * * * * curl http://localhost/system/cm/go.sh
0,25 * * * * curl http://localhost/system/cm/sharevideo/goyt.sh
0,5 * * * * curl -i http://localhost/system/log/scheduler.php

#0,25 * * * * /var/www/html/system/sync/sync.sh
#* * * * * /var/www/html/system/sync/purge.sh
#20 6 * * * /var/www/html/system/cm/senddraft.sh
15 7 * * * cd /var/www/html/ && php composer.phar self-update
20 7 * * * cd /var/www/html/ && php composer.phar update

*/
include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$log = new log();

$mode = $log->getSchedulerMode(["type" => "modeScheduler"]);

if ($mode == "work") {
    $log->schedulerWork();
} else {
    echo "Mode not work. Current mode: $mode";
}