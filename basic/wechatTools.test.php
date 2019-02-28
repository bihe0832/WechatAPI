<?php
echo "<HR>DEBUG START!<BR>";
require_once (dirname ( __FILE__ ) . '/wechatTools.do.php');
$wechatToolsDoObj = new wechatToolsDo ( "wx00bc545687abefe7", "5856e47ca83f3c1c89847507a261dfe7" );

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
echo "\thttp://microdemo.bihe0832.com/WechatAPI/basic/wechatTools.test.php?a=createMenu<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/basic/wechatTools.test.php?a=showMenu<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/basic/wechatTools.test.php?a=delMenu<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/basic/wechatTools.test.php?a=createQrcode&id=11<BR><BR>";
echo "\thttp://microdemo.bihe0832.com/WechatAPI/basic/wechatTools.test.php?a=showQrcodeLink&id=XXX<BR><BR>";
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
//上面内容对应下面的函数
//$wechatToolsDoObj->createMenu($menu);
//$wechatToolsDoObj->createQrcode(1);
//$wechatToolsDoObj->showQrcodeLink("");
//$wechatToolsDoObj->showMenu();
//$wechatToolsDoObj->delMenu();
?>