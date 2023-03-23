<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergey
 * Date: 30.03.16
 * Time: 9:10
 */


include_once($_SERVER['DOCUMENT_ROOT'] . '/nad/index.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/pas/html/index.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/article/article.php');

$welcome = new NAD();
$HTML = new HTML();
//$article = new Article();

$welcome->staffOnly();

$HTML->HTML_WrapTop();

echo "<script src=\"https://api.vide.me/system/sys.js\" type=\"text/javascript\"></script>

<div class=\"container-fluid\">
    <div class=\"row\">
        <div class=\"col-md-8\">

	<ol class=\"breadcrumb\">
  <li><a href=\"https://api.vide.me/system/\">System</a></li>
  <li class=\"active\">Function test</li>
</ol>
            <div class=\"jumbotron\">
                <h1>removeCBElementUser</h1>
                <p>removeCBElementUser do</p>
                <form class=\"form-horizontal\" id=\"\" name=\"\" role=\"form\"
                      action=\"https://api.vide.me/system/elaboration/removeCBElementUser.php\" method=\"get\" target='_blank'>
                    <div class=\"form-group\">
                        <label class=\"control-label\" for=\"hash\">document</label>
                        <input type=\"text\" class=\"form-control\" id=\"document\" value=\"\" name=\"document\">
                    </div>
                    <div class=\"form-group\">
                        <label class=\"control-label\" for=\"password\">element</label>
                        <input type=\"text\" class=\"form-control\" id=\"element\" value=\"\" name=\"element\">
                    </div>
                    <div class=\"form-group\">
                        <button type=\"submit\" class=\"btn btn-primary\">Get elaboration</button>
                    </div>
                </form>
            </div>

        </div>
        <div class=\"col-md-4\">
        </div>
    </div>
</div>
";

//$dir    = '/tmp';
$dir    = getcwd();
//$files1 = scandir($dir);
$files2 = scandir($dir);

//print_r($files1);
//print_r($files2);

foreach ($files2 as $key => $value) {
    //echo  "= key " . $key . " = ";
    //echo "---".$outputCBData["$value"];
    //if (isset($value['key'])) unset ($value['key']);
    //$outputConv[$key] = $value;
    if ($entry != "." && $entry != "..") {
        echo $key . " . - " . "<a target='_blank' class=\"\" href=\"https://api.vide.me/system/elaboration/" . $value . "\">" . $value . "</a><br>";
    }
}

$HTML->HTML_WrapDown();