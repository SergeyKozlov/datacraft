<?php
/**
 * Created by IntelliJ IDEA.
 * User: Пользователь2
 * Date: 24.01.2017
 * Time: 0:27
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/pas/html/index.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/system/log/log.php');

$welcome = new NAD();
$HTML = new HTML();
//$article = new Article();

//$log = new log();

$welcome->staffOnly();
//$userId = $welcome->CookieToUserId();

$HTML->HTML_WrapTop();

echo "
<script src=\"//api.vide.me/system/sys.js\" type=\"text/javascript\"></script>

<script type=\"text/javascript\">


$(document).ready(function() {
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

showInfo(16);
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
  <li class=\"active\">Log</li>
</ol>

<div class=\"page-header\">
  <h1>Log <small>system</small></h1>
</div>


</div>


        <div class=\"col-md-4\">
        	    <div id='timer'></div>

	    </div>

  <div id=\"log\" class=\"log\"></div>

	</div>
</div>
";

$HTML->HTML_WrapDown();