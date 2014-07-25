<?php
ini_set('display_errors', true);
require_once (dirname(__FILE__).'/app.test.conf.php');
echo "<HR>DEBUG START!<BR>";
require_once (dirname(__FILE__).'/app.do.php');
$appDoObj = new appDo("wekefu");
echo "<BR>"."-------------------INPUT TEST START-------------------"."<BR>";
$userInput = $GLOBALS["userInput"];
foreach ($userInput as $k=>$v){
	$info = json_decode($v, true);
	echo "<BR>"."*****************INPUT:[".$info["Content"]."] START*****************<BR>";
	$resultMsg = $appDoObj->startApp($v);var_dump($resultMsg) ;
	echo "<BR>"."*****************INPUT:[".$info["Content"]."] FINISH*****************<BR>";
}
echo "<BR>"."-------------------INPUT TEST FINISH-------------------"."<BR>";

echo "<BR>"."-------------------MENU TEST START-------------------"."<BR>";
$userClick = $GLOBALS["userClick"];
foreach ($userClick as $k=>$v){
	echo "<BR>"."*****************MENU:[".$k."] START*****************<BR>";
	$resultMsg = $appDoObj->startApp($v);var_dump($resultMsg) ;
	echo "<BR>"."*****************MENU:[".$k."] FINISH*****************<BR>";
}
echo "<BR>"."-------------------MENU TEST Finished-------------------"."<BR>";
echo "<BR>DEBUG FINISHED!<HR>";
?>