<?php
require_once (dirname(__FILE__).'/../../../basic/wechatTools.do.php');
require_once (dirname(__FILE__).'/../conf/zixie.conf.php');

$wechatToolsDoObj = new wechatToolsDo(APP_ID,APP_KEY);

$menu = array ("button" => array (
		0 => array (
			"name" => urlencode ( "菜单一" ), 
			"sub_button" => array (
				0 => array (
					"type" => "view", 
					"name" => urlencode ( "常见问题" ), 
					"url" => "https://blog.bihe0832.com" ), 
				1 => array (
					"type" => "click", 
					"name" => urlencode ( "使用指南" ), 
					"key" => "V001_HELP" ) ) ), 
		1 => array (
			"type" => "view", 
					"name" => urlencode ( "菜单二" ), 
					"url" => "https://blog.bihe0832.com"), 
		2 => array (
			"name" => urlencode ( "菜单三" ), 
			"sub_button" => array (
				0 => array (
					"type" => "click", 
					"name" => urlencode ( "我要提问" ), 
					"key" => "V003_ASK" ), 
				1 => array (
					"type" => "click", 
					"name" => urlencode ( "意见反馈" ), 
					"key" => "V003_ADVICE" ) ) ) ) );
				
$method = $_GET ["a"];
echo "Help:<BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/app/zixie/tools/zixie.tools.php?a=createMenu<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/app/zixie/tools/zixie.tools.php?a=showMenu<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/app/zixie/tools/zixie.tools.php?a=delMenu<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/app/zixie/tools/zixie.tools.php?a=createQrcode&id=11<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/app/zixie/tools/zixie.tools.php?a=showQrcodeLink&id=XXX<BR><BR>";
echo "XXX:ticket<BR>";

if ("createMenu" == $method) {
	$wechatToolsDoObj->{$method} ( $menu );
} else if ("createQrcode" == $method || "showQrcodeLink" == $method) {
	$id = $_GET ["id"];
	$wechatToolsDoObj->{$method} ( $id );
} else {
	if ($method) {
		$wechatToolsDoObj->{$method} ();
	}

}
exit ();
//$wechatToolsDoObj->showMenu();
//$wechatToolsDoObj->delMenu();
//$wechatToolsDoObj->createMenu($menu);
//$wechatToolsDoObj->createQrcode(1);
//$wechatToolsDoObj->showQrcodeLink("");
?>