<?php
ini_set('display_errors', false);
require_once (dirname(__FILE__).'/zixie.test.conf.php');
echo "<HR>DEBUG START!<BR>";
require_once (dirname(__FILE__).'/zixie.do.php');
$zixieDoObj = new zixieDo("zixie");

echo "<BR>"."-------------------MENU TEST START-------------------"."<BR>";
$userClick = $GLOBALS["userClick"];
foreach ($userClick as $k=>$v){
	echo "<BR>"."*****************MENU:[".$k."] START*****************<BR>";
	$resultMsg = $zixieDoObj->startApp($v);var_dump($resultMsg) ;
	echo "<BR>"."*****************MENU:[".$k."] FINISH*****************<BR>";
}
echo "<BR>"."-------------------MENU TEST Finished-------------------"."<BR>";
echo "<BR>"."-------------------INPUT TEST START-------------------"."<BR>";
$userInput = $GLOBALS["userInput"];
foreach ($userInput as $k=>$v){
	$info = json_decode($v, true);
	echo "<BR>"."*****************INPUT:[".$info["Content"]."] START*****************<BR>";
	$resultMsg = $zixieDoObj->startApp($v);var_dump($resultMsg) ;
	echo "<BR>"."*****************INPUT:[".$info["Content"]."] FINISH*****************<BR>";
}
echo "<BR>"."-------------------INPUT TEST FINISH-------------------"."<BR>";
$userinput = '{"FromUserName":{"0":"o0YLXjtDIFA5ODyViCW_BneVCEIk"},"MsgType":"text","Content":"植物大战僵尸"}';
$resultMsg = $zixieDoObj->startApp($userinput);var_dump($resultMsg) ;
echo "<BR>DEBUG FINISHED!<HR>";
?>