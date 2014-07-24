<?php
echo "<HR>DEBUG START!<BR>";
require_once dirname(__FILE__).'/wechatBase.class.php';

$wechatBaseObj = new wechatBase();
$userID = "o0YLXjtDIFA5ODyViCW_BneVCEIk";
$appCode = "wekefu";
$input = "zixie";
$resultMsg = $wechatBaseObj->__preReplace("12323DDFF");
echo "<BR>1<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$sql ="select * from t_user limit 1";
$resultMsg = $wechatBaseObj->__getInfoFromCacheBySQL($sql);
echo "<BR>2<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatBaseObj->__addLog($userID,"SUBSCRIBE",$appCode);
echo "<BR>3<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatBaseObj->__getTips("HELP","wekefu");
echo "<BR>4<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatBaseObj->__merge_spaces("  12   3ds    23D  D FF  ", $input);
echo "<BR>5<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatBaseObj->__subscribe($userID,$appCode);
echo "<BR>6<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatBaseObj->__unsubscribe($userID,$appCode);
echo "<BR>7<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatBaseObj->__getHelp();
echo "<BR>8<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
echo "<BR>DEBUG FINISHED!<HR>";
?>