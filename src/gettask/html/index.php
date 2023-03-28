<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 30.11.16
 * Time: 18:54
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/pas/html/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$HTML = new HTML();
//$article = new Article();

//$log = new log();

$welcome->staffOnly();
$userId = $welcome->CookieToUserId();

$HTML->HTML_WrapTop();

echo "
<script src=\"//api.vide.me/system/sys.js\" type=\"text/javascript\"></script>

<script type=\"text/javascript\">


$(document).ready(function() {

        $.fn.showSchedulerMode({
            showcaseMode: 'scheduler-mode'
        });

$('#timer').pietimer({
    seconds: 5,
    color: 'rgba(102, 0, 255, 0.8)',
    height: 40,
    width: 40
},
function(){
    console.log(\"pietimer -----> location.reload();\");
    //alert('boom');
    //window.location.assign(\"https://api.vide.me/system/cm/\");
        //location.reload();

});

//showLog(16);

    setInterval(function () {

showTask(16);
        $('#timer').pietimer('start');

    }, 5000);


/*        $.fn.showLimitMode({
            showcaseLimitMode: 'limit-mode'
        });*/

});
</script>
<div class=\"container-fluid\">
	<div class=\"row\">
		<div class=\"col-md-8\">

	<ol class=\"breadcrumb\">
  <li><a href=\"https://api.vide.me/system/\">System</a></li>
  <li class=\"active\">Scheduler</li>
</ol>

<div class=\"page-header\">
  <h1>Log <small>system</small></h1>
</div>


</div>


        <div class=\"col-md-4\">
        	    <div id='timer'></div>

	    </div>

<div class=\"container-fluid\">
	<div class=\"row\">
		<div class=\"col-md-8\">
		
<div id=\"panel-scheduler-mode\" class=\"panel panel-defaults\">
  <div class=\"panel-heading\">
    <h3 id=\"panel-title-scheduler-mode\" class=\"panel-title\">Scheduler mode</h3>
  </div>
  <div class=\"panel-body\">

    <hr>
    <div class=\"btn-group\" role=\"group\" aria-label=\"...\">
        <a type=\"button\" id=\"btn-work-scheduler-mode\" class=\"btn scheduler-mode btn-primary\" href=\"https://api.vide.me/system/log/setschedulermode/?mode=work\">work</a>
        <a type=\"button\" id=\"btn-stop-scheduler-mode\" class=\"btn scheduler-mode btn-warning\" href=\"https://api.vide.me/system/log/setschedulermode/?mode=stop\">stop</a>
    </div>
    <hr>

    <div id=\"scheduler-mode\">
    </div>
    </div>
  <div id=\"panel-footer-scheduler-mode\" class=\"panel-footer\">Panel footer</div>
</div>
</div>
</div>
</div>


  <div id=\"log\" class=\"log\"></div>

	</div>
</div>
";

$HTML->HTML_WrapDown();