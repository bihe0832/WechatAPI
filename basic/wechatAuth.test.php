<?php
echo "<HR>DEBUG START!<BR>";
require_once (dirname(__FILE__).'/wechatAuth.class.php');

$wechatToolsDoObj = new wechatAuth("wx00bc545687abefe7",
								"5856e47ca83f3c1c89847507a261dfe7",
								"http://microdemo.sinaapp.com/WechatAPI/basic/wechatAuth.getData.php");


$accessInfo = array(
		"access_token"=> "OezXcEiiBSKSxW0eoylIeJgfKc962Gi_VpcpjKH_sd9UW4XcGG92quy43M4FN_pn25NNINXc0_5baaHDQYNkodpX-pp838BHqva67b7o5utgMXRzf4AJ7b9482t444o4RdUSOTKIS0ShszUU9NHr7Q", 
		"expires_in"=>7200,
		"refresh_token"=>"OezXcEiiBSKSxW0eoylIeJgfKc962Gi_VpcpjKH_sd9UW4XcGG92quy43M4FN_pn_pdzloL3L02rOiYN5kFwVRgCbWkLbZJUqj4z65DLJDbxEZvlgLLrMCjr9TQTByFLEq9bDQb_vQyq6FX6jtc3dA", 
		"openid"=> "oM6mzjpMHvUXpKsjmoEisRjkIGEQ" ,
		"scope"=> "snsapi_base"
		);
$resultMsg = $wechatToolsDoObj->_getAuthorizeLink(1,1);
echo "<BR>1<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatToolsDoObj->_getAccessTokenByCode("005bec3c87884b26a8cb78ceea226fa2");
echo "<BR>2<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
$resultMsg = $wechatToolsDoObj->_getUserInfoByopenID($accessInfo["openid"]);
echo "<BR>3<BR>";var_dump($resultMsg) ;echo "<BR><BR>";
echo "<BR>DEBUG FINISHED!<HR>";
?>