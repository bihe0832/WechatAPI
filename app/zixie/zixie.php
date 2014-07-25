<?php
require_once (dirname(__FILE__).'/../../wechat/wechatApi.php');
require_once (dirname(__FILE__).'/conf/zixie.conf.php');
require_once (dirname(__FILE__).'/main/zixie.do.php');
//微信验证
wechatApi::_clientValid(TOKEN);
//获取用户输入
$userInput = wechatApi::_clientGetUserInput();
$zixieDoObj = new zixieDo();
$resultMsg = $zixieDoObj->startApp($userInput);
wechatApi::_clientResponseMsg($resultMsg);
exit;
?>
